<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auctions extends CI_Controller {

	function __construct()
    {
		parent::__construct();

		$this->load->helper('session');

		$this->load->model('Users_model');
		$this->load->model('Auctioneers_model');
		$this->load->model('Prices_model');
		$this->load->model('Categories_model');
    }

	public function get($n_auctioneer = null, $sub_name = null)
	{
		if (isLoggedIn()) {
			$user_data = $this->Users_model->get_user($_COOKIE['token']);
		} else {
			$user_data = [];
		}

		$n_auctioneer = str_replace('-', ' ', $n_auctioneer);
		$sub_name = str_replace('-', ' ', $sub_name);

		if (!$this->Auctioneers_model->count_auctioneer_sub_name($n_auctioneer, $sub_name)) {
			header('Location: /error/404');
		}

		$auctioneer = $this->Auctioneers_model->get_auctioneer_by_sub_name($n_auctioneer, $sub_name);
		$products = $this->get_products($auctioneer);

		$data = [
			'seo' => [
				'title' => $auctioneer->name,
				'desc' => 'Pizarra de precios: '.$auctioneer->name.' - '.$auctioneer->sub_name
			],
			'logged_in' => isLoggedIn(),
			'user_data' => $user_data,
			'auctioneer' => $auctioneer,
			'products' => $products,
			'auctioneers' => $this->Auctioneers_model->get_auctioneers(),
			'categories' => $this->Categories_model->get_categories(),
			'date' => date_format(date_create($products[0]->created_at), 'Y-m-d')
		];

		$this->load->view('templates/header', $data);
        $this->load->view('precios/auction', $data);
		$this->load->view('templates/footer');
	}

	public function post($n_auctioneer = null, $sub_name = null)
	{
		if (isLoggedIn()) {
			$user_data = $this->Users_model->get_user($_COOKIE['token']);
		} else {
			$user_data = [];
		}

		$n_auctioneer = str_replace('-', ' ', $n_auctioneer);
		$sub_name = str_replace('-', ' ', $sub_name);

		if (!$this->Auctioneers_model->count_auctioneer_sub_name($n_auctioneer, $sub_name)) {
			header('Location: /error/404');
			exit();
		}

		if (!$this->check_recaptcha_v3($this->input->post('recaptcha'))) {
			header('Location: /error/robot');
			exit();
		}

		$auctioneer = $this->Auctioneers_model->get_auctioneer_by_sub_name($n_auctioneer, $sub_name);

		$data = [
			'seo' => [
				'title' => $auctioneer->name,
				'desc' => 'Pizarra de precios: '.$auctioneer->name.' - '.$auctioneer->sub_name
			],
			'logged_in' => isLoggedIn(),
			'user_data' => $user_data,
			'auctioneer' => $auctioneer,
			'products' => $this->get_products($auctioneer, $this->input->post('date')),
			'auctioneers' => $this->Auctioneers_model->get_auctioneers(),
			'categories' => $this->Categories_model->get_categories(),
			'date' => $this->input->post('date')
		];

		$this->load->view('templates/header', $data);
        $this->load->view('precios/auction', $data);
		$this->load->view('templates/footer');
	}

	private function get_products($auctioneer, $date = null) {
		$products = $this->Prices_model->get_prices_by_auctioneer_id($auctioneer->id, $date);
		if (empty($products)) {
			return [];
		}
		$prev_prices_avg = $this->get_previous_prices_avg($auctioneer->id, $products[0]->created_at);

		foreach ($products as $key => $product) {
			$products[$key]->prices = json_decode($product->prices);
			if (count($products[$key]->prices) > 10) {
				$products[$key]->prices = array_slice($products[$key]->prices,  0 , 10); // truncamos array a los 10 primeros elementos
			}

			$prices_avg = array_slice($products[$key]->prices, 0, 3, true);
			$prices_avg = round(array_sum($prices_avg) / count($prices_avg));
			
			$products[$key]->prices_avg = $prices_avg;
			
			if (!empty($prev_prices_avg[$product->product_id])) {
				$products[$key]->last_prices_avg = $prev_prices_avg[$product->product_id];
			} else {
				$products[$key]->last_prices_avg = null;
			}
		}

		return $products;
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

		$products = $this->Prices_model->get_prices_by_auctioneer_id($auctioneer_id, $prev_date);

		$prev_prices_avg = [];

		foreach ($products as $key => $product) {
			$products[$key]->prices = json_decode($product->prices);
			if (count($products[$key]->prices) > 10) {
				$products[$key]->prices = array_slice($products[$key]->prices,  0 , 10); // truncamos array a los 10 primeros elementos
			}

			$prices_avg = array_slice($products[$key]->prices, 0, 3, true);
			$prices_avg = round(array_sum($prices_avg) / count($prices_avg));
			
			$prev_prices_avg[$product->product_id] = $prices_avg;
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