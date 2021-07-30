<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;

class Dashboard extends CI_Controller {

	function __construct()
    {
		parent::__construct();

		$this->load->helper('session');

		$this->load->model('Users_model');
    }

	public function settings()
	{
		if (isLoggedIn()) {
			$user_data = $this->Users_model->get_user($_COOKIE['token']);
		} else {
			header('Location: /');
			exit();
		}

		$data = [
			'seo' => [
				'title' => 'Ajustes',
				'desc' => 'Ajustes de usuario.'
			],
			'logged_in' => isLoggedIn(),
			'user_data' => $user_data
		];

		if ($this->input->post()) {
			if (empty($this->input->post('old_password')) || empty($this->input->post('new_password'))) {
				$data['error'] = 'Completa todos los campos!';
			} else {
				if (password_verify($this->input->post('old_password'), $user_data->password)) {
					$hash = password_hash($this->input->post('new_password'), PASSWORD_BCRYPT);
					$this->Users_model->update_password($user_data->id, $hash);
					$data['success'] = 'Contraseña cambiada!';
				} else {
					$data['error'] = 'Contraseña antigua incorrecta!';
				}
			}
		}

		$this->load->view('templates/header', $data);
        $this->load->view('dashboard/settings', $data);
		$this->load->view('templates/footer');
	}

	public function api()
	{
		if (isLoggedIn()) {
			$user_data = $this->Users_model->get_user($_COOKIE['token']);
		} else {
			header('Location: /');
			exit();
		}

		$this->load->model('Api_model');

		if ($this->Api_model->count_api_key($user_data->api_key, date('Y-m-d'))) {
			$n_requests = $this->Api_model->get_n_requests_today($user_data->api_key);
		} else {
			$n_requests = 0;
		}

		$data = [
			'seo' => [
				'title' => 'API',
				'desc' => 'Acceso api usuarios.'
			],
			'logged_in' => isLoggedIn(),
			'user_data' => $user_data,
			'api' => [
				'n_requests' => $n_requests
			]
		];

		$this->load->view('templates/header', $data);
        $this->load->view('dashboard/api', $data);
		$this->load->view('templates/footer');
	}
}