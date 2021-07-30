<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class Cron extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->client = new Client([
            'base_uri' => 'https://asdasd.com',
            'timeout'  => 5,
        ]);
        
        $this->load->model('Prices_model');
    }

    private function get_eur_exchange_rate($currency)
    {
        $client = new Client([
            'base_uri' => 'https://api.ratesapi.io/api',
            'timeout'  => 5,
        ]);

        $res = $client->request('GET', "/latest?base=EUR&symbols=".$currency);

        $data = json_decode($res->getBody(), true);
        return $data['rates'][$currency];
    }
}