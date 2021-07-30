<?php

// Get real visitor IP behind CloudFlare network
if (!function_exists('getUserIP'))
{
    function getUserIP()
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
        
        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }
        return $ip;
    }
}

if (!function_exists('getCountryCode'))
{
    function getCountryCode()
    {
        $countryCode = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".getUserIP())) -> {'geoplugin_countryCode'};
        if (empty($countryCode)) {$countryCode = 'XX';}
        return $countryCode;
    }
}

if (!function_exists('getUserAgent'))
{
    function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT']??null;
    }
}

// Alertas
if (!function_exists('sweetAlert'))
{
    function sweetAlert($data, $reload=false)
    {
        setcookie(
			'alert',
			json_encode($data),
            time()+2*24*60*60,
            '/'
        );
        if ($reload) {
            header('Refresh:0');
            exit;
        }
    }
}

function checkAlert()
{
    if (isset($_COOKIE['alert'])){
        $data = json_decode($_COOKIE['alert']);
        echo '
            <script type="text/javascript">
                setTimeout(
                    function() {
                        swal("", "'.$data->msg.'", "'.$data->type.'");
                    },100
                );
            </script>
        '; 
        setcookie('alert', '', time()-3600, '/');
    }
}