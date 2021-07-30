<?php

// Check if user is logged in
if (!function_exists('isLoggedIn'))
{
    function isLoggedIn()
    {
        $CI = get_instance();
        $CI->load->model('Users_model');
        if (isset($_COOKIE['token'])) {
            if ($CI->Users_model->count_token($_COOKIE['token']) < 1) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('check_token'))
{
    function check_token()
    {
        $CI = get_instance();
        $CI->load->model('Users_model');

        if (!isLoggedIn()) {
            header('Location: /');
            exit();
            
        } elseif ($CI->Users_model->count_banned($_COOKIE['token']) > 0) {
            header("Location: /banned");
            exit();
        }
    }
}