<?php

namespace App\Libraries;

class Mc_recaptcha
{
    protected $site_key;
    protected $secret_key;
    
    public function __construct()
    {
        // Usar as constantes definidas
        $this->site_key = SITE_KEY_RECAPTCHA;
        $this->secret_key = SITE_SECRET_RECAPTCHA;
    }

    public function set_secret_key($key)
    {
        $this->secret_key = $key;
    }

    public function get_secret_key()
    {
        return $this->secret_key;
    }

    public function set_site_key($key)
    {
        $this->site_key = $key;
    }

    public function get_site_key()
    {
        return $this->site_key;
    }

    public function validated()
    {
        $request = \Config\Services::request();
        $grr_post = $request->getPost('g-recaptcha-response');
        $user_ip = $request->getIPAddress();
        
        if (empty($grr_post)) {
            return false;
        }
        
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $data = [
            'secret' => $this->get_secret_key(),
            'response' => $grr_post,
            'remoteip' => $user_ip
        ];
        
        $output = $this->http_post($url, $data);
        $output = json_decode($output, true);

        if (isset($output['success']) && $output['success'] === true) {
            return true;
        }
        return false;
    }

    // HTTP Helper function
    public function http_post($url, $data = [])
    {
        $postdata = http_build_query($data);

        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            ]
        ];

        $context = stream_context_create($opts);
        $output = @file_get_contents($url, false, $context);

        if ($output === false) {
            if (function_exists('curl_init')) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $output = curl_exec($ch);
                curl_close($ch);
            }
        }

        return $output;
    }
}