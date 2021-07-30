<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	function __construct()
    {
		parent::__construct();

		$this->load->helper('session');

		$this->load->model('Users_model');
    }

	public function home()
	{
		if (isLoggedIn()) {
			$user_data = $this->Users_model->get_user($_COOKIE['token']);
		} else {
			$user_data = [];
		}

		header('Location: /subasta/la-union/la-redonda');
		exit();
	}

	public function weather()
	{
		if (isLoggedIn()) {
			$user_data = $this->Users_model->get_user($_COOKIE['token']);
		} else {
			$user_data = [];
		}

		$data = [
			'seo' => [
				'title' => 'El Tiempo en Almería',
				'desc' => 'Previsiones de el tiempo agrícola en Almería.'
			],
			'logged_in' => isLoggedIn(),
			'user_data' => $user_data,
		];

		$this->load->view('templates/header', $data);
        $this->load->view('weather', $data);
		$this->load->view('templates/footer');
	}

	public function contact()
	{
		if (isLoggedIn()) {
			$user_data = $this->Users_model->get_user($_COOKIE['token']);
		} else {
			$user_data = [];
		}

		$data = [
			'seo' => [
				'title' => 'Contacto',
				'desc' => 'Contacta con nosotros.'
			],
			'logged_in' => isLoggedIn(),
			'user_data' => $user_data,
		];

		if($this->input->post()) {
            if($this->check_recaptcha_v2($_POST["g-recaptcha-response"])) {
                if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
                    $data['error'] = "Necesitas completar todos los campos!";
                } else {
                    mail(config_item('email_contact'), 'Formulario contacto AgroData - '.$_POST['name'],
					'Nombre: '.$_POST['name'].'<br>Email: '.$_POST['email'].'<br>Mensaje:<br>'.$_POST['message']);
					$data['success'] = "Mensaje enviado correctamente!";
				}
            } else {
                $data['error'] = 'Captcha Incorrecto!';
            }
        }

		$this->load->view('templates/header', $data);
        $this->load->view('contact', $data);
		$this->load->view('templates/footer');
	}

	private function check_recaptcha_v2($res)
    {
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".config_item('rcapv2_secret')."&response=".$res."&remoteip=".getUserIP());
        $rcaptcha = json_decode($response);
        return $rcaptcha->success;
    }
}