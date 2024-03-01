<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mc_recaptcha
{
  protected $CI = NULL;

  protected $site_key = '6Lfg_usgAAAAAJ85VI3s8j5bHdHV2i3CMsfVIe6j';
  protected $secret_key = '6Lf-9esgAAAAAH9ty_O6kbfWRHc_hpVYiA0q_0cn';
  
  function __construct()
  {
    $this->CI = &get_instance();
  }

  function set_secret_key($key)
  {
    $this->secret_key = $key;
  }

  function get_secret_key()
  {
    return $this->secret_key;
  }

  function set_site_key($key)
  {
    $this->site_key = $key;
  }

  function get_site_key()
  {
    return $this->site_key;
  }

  public function validated()
  {
    $grr_post = $this->CI->input->post('g-recaptcha-response');
    $user_ip = $this->CI->input->ip_address();
    $output = $this->http_post("https://www.google.com/recaptcha/api/siteverify?secret=" . $this->get_secret_key() . "&response={$grr_post}&remoteip=" . $user_ip);
    $output = json_decode($output, TRUE);

    if (isset($output['success']) and $output['success'] === true) {
      return TRUE;
    }
    return FALSE;
  }

  // HTTP Helper function
  public function http_post($url)
  {
    $postdata = array();

    if (strpos($url, "?") !== FALSE) {
      $query_string_array = array();
      $url_array = explode("?", $url);
      $url = $url_array[0];
      parse_str($url_array[1], $query_string_array);
      $postdata = http_build_query($query_string_array);
    }

    $opts = array(
      'http' =>
      array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
      )
    );

    $context  = stream_context_create($opts);
    $output = @file_get_contents($url, false, $context);

    if ($output === FALSE) {
      if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
      }
    }

    return $output;
  }
}
