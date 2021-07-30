<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;

class Auth extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('security');
        $this->load->helper('session');
        
        $this->load->model('Users_model');
    }

	public function login()
	{
        if(isLoggedIn()){
            echo('already loged in!');
            exit();
        }

        if($this->input->post())
        {
            if($this->check_recaptcha_v3($_POST["recaptcha"]))
            {
                // Check empty fields
                if(empty($_POST['username']) || empty($_POST['password'])) {
                    $data['error'] = "Completa todos los campos!";
                } else {
                    // Check username in db
                    if($this->Users_model->count_user($this->input->post('username')) == 0) {
                        $data['error'] = 'Usuario o contraseña invalidos!';
                    } else {
                        // Check password
                        $hash = $this->Users_model->get_password($this->input->post('username'));
                        if (!password_verify($this->input->post('password'), $hash)) {
                            $data['error'] = 'Usuario o contraseña invalidos!';
                        }
                    }
                }

                // Check banned
                /*if($this->Users_model->getUserRankByUsername($this->input->post('user')) == -1) {
                    $data['error'] = 'Estás baneado';
                }*/
            } else {
                $data['error'] = 'Necesitas completar el captcha';
            }
        } else {
            echo('Bad request');
            exit;
        }

        if (empty($data['error'])) {
            $user_data = $this->Users_model->get_user_by_username($this->input->post('username'));
            $token = $this->gen_token($user_data['id']);
            $this->Users_model->set_token($user_data['id'], $token);

            setcookie("token", $token, time() + 720000);
            $data = [
                'status' => 'success',
                'msg' => 'Has iniciado sesión!'
            ];
            echo(json_encode($data));
            exit;
        } else {
            $data = [
                'status' => 'error',
                'msg' => $data['error']
            ];
            echo(json_encode($data));
        }
    }

    public function register()
	{
        if(isLoggedIn()){
            header('Location: /');
            exit;
        }

        $data = [
            'seo' => [
				'title' => 'Registro',
				'desc' => 'Registrateasdasd'
            ],
            'logged_in' => isLoggedIn(),
			'user_data' => []
        ];

        if($this->input->post())
        {
            if($this->check_recaptcha_v2($_POST["g-recaptcha-response"]))
            {
                // Check empty fields
                if(empty($_POST['username']) || empty($_POST['email']) || empty($_POST['pass']) || empty($_POST['rpass'])) {
                    $data['error'] = "Completa todos los campos";
                } else {
                    //Compare first to second password
                    if ($_POST['pass'] != $_POST['rpass']){
                        $data['error'] = 'Las contraseñas no coinciden';
                    } else {
                        // Check username
                        if (!ctype_alnum($_POST['username']) || strlen($_POST['username']) < 3 || strlen($_POST['username']) > 25){
                            $data['error'] = 'El nombre de usuario solo puede contener números y letras con una longitud de entre 3-25 caracteres';
                        } else {
                            if (strlen($_POST['pass']) < 6) {
                                $data['error'] = 'La contraseña debe de tener como mínimo 6 caracteres';
                            } else {
                                // Validate email
                                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                                    $data['error'] = 'Ingresa un email válido';
                                } else {
                                    // Check username in db
                                    if ($this->Users_model->count_user($_POST['username']) > 0){
                                        $data['error'] = 'Este nombre de usuario ya está registrado';
                                    } else {
                                        // Check email in db
                                        if ($this->Users_model->count_email($_POST['email']) > 0){
                                            $data['error'] = 'Este email ya está registrado';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                // Register new user on db
                if (empty($data['error'])) {
                    $hash = password_hash($this->input->post('pass'), PASSWORD_BCRYPT);
                    $user_id = $this->Users_model->insert_user($this->input->post('username'), $hash, '', $this->input->post('email'), getUserIP());
                    $token = $this->gen_token($user_id);
                    $this->Users_model->set_token($user_id, $token);
                    setcookie("token", $token, time() + 720000);
                    header('Location: /');
                    exit;
                }
            } else {
                $data['error'] = 'Captcha Incorrecto!';
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('auth/register', $data);
        $this->load->view('templates/footer', $data);
    }

    public function logout()
    {
        unset($_COOKIE['token']);
        setcookie("token", "", time() + 720000);
        header('location: /');
    }

    private function gen_token($user_id)
    {
        $payload = array(
            "id" => $user_id
        );

        return JWT::encode($payload, config_item('jwt_key'));
    }

    private function check_recaptcha_v2($res)
    {
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".config_item('rcapv2_secret')."&response=".$res."&remoteip=".getUserIP());
        $rcaptcha = json_decode($response);
        return $rcaptcha->success;
    }

    private function check_recaptcha_v3($res)
    {
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".config_item('rcapv3_secret')."&response=".$res."&remoteip=".getUserIP());
        $rcaptcha = json_decode($response);
        return $rcaptcha->success;
    }
}