<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;

class Api extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->driver('cache', ['adapter' => 'apc', 'backup' => 'file']);
        $this->load->helper('session');

        $this->load->model('Products_model');
		$this->load->model('Auctioneers_model');
		$this->load->model('Prices_model');
		$this->load->model('Categories_model');
    }

	public function get_products()
	{
		$this->check_api_key();

		$data = [
            'success' => true,
			'products' => $this->Products_model->get_products()
		];

		header('Content-Type: application/json');
		echo(json_encode($data, JSON_UNESCAPED_UNICODE));
	}

	public function get_auctioneers()
	{
		$this->check_api_key();

		$data = [
            'success' => true,
			'auctions' => $this->Auctioneers_model->get_auctioneers()
		];

		header('Content-Type: application/json');
		echo(json_encode($data, JSON_UNESCAPED_UNICODE));
	}

    public function get_product($product_id = null, $date = null)
	{
		$this->check_api_key();

		if (!$this->Products_model->count_product($product_id)) {
			header('Content-Type: application/json');
			echo '{"success": false, "message": "Invalid product_id"}';
            exit();
		}

		$product = $this->Products_model->get_product($product_id);
		$auctions = $this->Prices_model->get_prices_by_product_id($product->id, $date);

		foreach ($auctions as $key => $auction) {
			$auctions[$key]->prices = json_decode($auction->prices);
			if (count($auctions[$key]->prices) > 10) {
				$auctions[$key]->prices = array_slice($auctions[$key]->prices,  0 , 10); // truncamos array a los 10 primeros elementos
			}
		}

		$data = [
            'success' => true,
			'product' => $product,
			'auctions' => $auctions
		];

		header('Content-Type: application/json');
		echo(json_encode($data, JSON_UNESCAPED_UNICODE));
	}

    public function get_auction($auctioneer_id = null, $date = null)
	{
		$this->check_api_key();

		if (!$this->Auctioneers_model->count_auctioneer($auctioneer_id)) {
			header('Content-Type: application/json');
			echo '{"success": false, "message": "Invalid auctioneer_id"}';
            exit();
		}

		$auctioneer = $this->Auctioneers_model->get_auctioneer($auctioneer_id);
		$products = $this->Prices_model->get_prices_by_auctioneer_id($auctioneer->id, $date);

		foreach ($products as $key => $product) {
			$products[$key]->prices = json_decode($product->prices);
			if (count($products[$key]->prices) > 10) {
				$products[$key]->prices = array_slice($products[$key]->prices,  0 , 10); // truncamos array a los 10 primeros elementos
			}
		}

		$data = [
            'success' => true,
			'auctioneer' => $auctioneer,
			'products' => $products
		];

		header('Content-Type: application/json');
		echo(json_encode($data, JSON_UNESCAPED_UNICODE));
	}

	public function renew_api_key()
	{
		$this->load->model('Users_model');
		$this->load->model('Api_model');

		if (isLoggedIn()) {
			$user_data = $this->Users_model->get_user($_COOKIE['token']);
		} else {
			header('Content-Type: application/json');
			echo '{"success": false, "message": "Not logged in."}';
			exit();
		}

		$api_key = $this->gen_api_key();

		$this->Users_model->set_api_key($user_data->id, $api_key);
		$this->Api_model->update_api_key($api_key, $user_data->api_key);

		header('Content-Type: application/json');
		echo '{"success": true, "message": "API key renewed.", "api_key": "'.$api_key.'"}';
		exit();
	}

	private function check_api_key()
    {
		$api_key = $this->input->request_headers()['X-API-KEY'];

		if (!isset($api_key)) {
			header('Content-Type: application/json');
			echo '{"success": false, "message": "Missing X-API-KEY header."}';
            exit();
		}

		$this->load->model('Users_model');

		if (!$this->Users_model->count_api_key($api_key)) {
			header('Content-Type: application/json');
			echo '{"success": false, "message": "Invalid X-API-KEY header."}';
            exit();
		}

		$this->load->model('Api_model');

		if (!$this->Api_model->count_api_key($api_key, date('Y-m-d'))) {
			$this->Api_model->insert_log($api_key, getUserIP(), date('Y-m-d'));
		}

		$n_requests = $this->Api_model->get_n_requests_today($api_key);
		if ($n_requests >= 100) {
			header('Content-Type: application/json');
			echo '{"success": false, "message": "X-Rate-Limit-Requests-Quota exceeded."}';
            exit();
		}

		$this->Api_model->add_request($api_key);

		$requests_left = 100 - ($n_requests + 1);
		header('X-Rate-Limit-Requests-Quota: 100');
		header('X-Rate-Limit-Requests-Left: '.$requests_left);
		header('X-Rate-Limit-Time-Reset: '.(new DateTime('tomorrow'))->format('Y-m-d H:i:s'));

        return true;
    }

	private function gen_api_key() {
		return implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
	}
}