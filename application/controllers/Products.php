<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

	function __construct()
    {
		parent::__construct();

		$this->load->helper('session');

		$this->load->model('Users_model');
		$this->load->model('Products_model');
		$this->load->model('Auctioneers_model');
		$this->load->model('Prices_model');
		$this->load->model('Categories_model');
    }

	public function get($n_product = null)
	{
		if (isLoggedIn()) {
			$user_data = $this->Users_model->get_user($_COOKIE['token']);
		} else {
			$user_data = [];
		}

		$n_product = str_replace('-', ' ', $n_product);

		if (!$this->Products_model->count_product_by_name($n_product)) {
			header('Location: /error/404');
		}

		$product = $this->Products_model->get_product_by_name($n_product);
		$auctions = $this->get_auctions($product);

		$data = [
			'seo' => [
				'title' => $product->name,
				'desc' => 'descripcion'
			],
			'logged_in' => isLoggedIn(),
			'user_data' => $user_data,
			'product' => $product,
			'auctions' => $auctions,
			'auctioneers' => $this->Auctioneers_model->get_auctioneers(),
			'categories' => $this->Categories_model->get_categories(),
			'products' => $this->Products_model->get_products(),
			'date' => date_format(date_create($auctions[0]->created_at), 'Y-m-d')
		];

		$this->load->view('templates/header', $data);
        $this->load->view('precios/product', $data);
		$this->load->view('templates/footer');
	}

	public function post($n_product = null)
	{
		if (isLoggedIn()) {
			$user_data = $this->Users_model->get_user($_COOKIE['token']);
		} else {
			$user_data = [];
		}

		$n_product = str_replace('-', ' ', $n_product);

		if (!$this->Products_model->count_product_by_name($n_product)) {
			header('Location: /error/404');
			exit();
		}

		if (!$this->check_recaptcha_v3($this->input->post('recaptcha'))) {
			header('Location: /error/403');
			exit();
		}

		$product = $this->Products_model->get_product_by_name($n_product);
		
		$data = [
			'seo' => [
				'title' => $product->name,
				'desc' => 'descripcion'
			],
			'logged_in' => isLoggedIn(),
			'user_data' => $user_data,
			'product' => $product,
			'auctions' => $this->get_auctions($product, $this->input->post('date')),
			'auctioneers' => $this->Auctioneers_model->get_auctioneers(),
			'categories' => $this->Categories_model->get_categories(),
			'products' => $this->Products_model->get_products(),
			'date' => $this->input->post('date')
		];

		$this->load->view('templates/header', $data);
        $this->load->view('precios/product', $data);
		$this->load->view('templates/footer');
	}

	private function get_auctions($product, $date = null) {
		$auctions = $this->Prices_model->get_prices_by_product_id($product->id, $date);
		if (empty($auctions)) {
			return [];
		}
		$prev_prices_avg = $this->get_previous_prices_avg($product->id, $auctions[0]->created_at);

		foreach ($auctions as $key => $auction) {
			$auctions[$key]->prices = json_decode($auction->prices);
			if (count($auctions[$key]->prices) > 10) {
				$auctions[$key]->prices = array_slice($auctions[$key]->prices,  0 , 10); // truncamos array a los 10 primeros elementos
			}

			$prices_avg = array_slice($auctions[$key]->prices, 0, 3, true);
			$prices_avg = round(array_sum($prices_avg) / count($prices_avg));
			
			$auctions[$key]->prices_avg = $prices_avg;

			if (!empty($prev_prices_avg[$auction->auctioneer_id])) {
				$auctions[$key]->last_prices_avg = $prev_prices_avg[$auction->auctioneer_id];
			} else {
				$auctions[$key]->last_prices_avg = null;
			}
		}

		return $auctions;
	}

	private function max_length($array) {
		$max = 0;
		foreach($array as $child) {
			if(count($child) > $max) {
				$max = count($child);
			}
		}
		return $max;
	}

	private function get_previous_prices_avg($auctioneer_id, $date) {
		$prev_date = new DateTime($date);
		$prev_date->modify('-1 day');
		$prev_date = $prev_date->format('Y-m-d H:i:s');

		$auctions = $this->Prices_model->get_prices_by_product_id($auctioneer_id, $prev_date);

		$prev_prices_avg = [];

		foreach ($auctions as $key => $auction) {
			$auctions[$key]->prices = json_decode($auction->prices);
			if (count($auctions[$key]->prices) > 10) {
				$auctions[$key]->prices = array_slice($auctions[$key]->prices,  0 , 10); // truncamos array a los 10 primeros elementos
			}

			$prices_avg = array_slice($auctions[$key]->prices, 0, 3, true);
			$prices_avg = round(array_sum($prices_avg) / count($prices_avg));
			
			$prev_prices_avg[$auction->auctioneer_id] = $prices_avg;
		}

		return $prev_prices_avg;
	}

	private function check_recaptcha_v3($res)
    {
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".config_item('rcapv3_secret')."&response=".$res."&remoteip=".getUserIP());
        $rcaptcha = json_decode($response);
        return $rcaptcha->success;
    }
}