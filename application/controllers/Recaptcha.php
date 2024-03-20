<?php
defined('BASEPATH') or exit('URL inválida.');

class Recaptcha extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->library('mc_recaptcha');
    $this->load->library('form_validation');
  }

  public function salvar()
  {

    if (empty($this->input->post('nome'))) {

      $data = [
        'site_key' => '6Lfg_usgAAAAAJ85VI3s8j5bHdHV2i3CMsfVIe6j',
        'secret_key' => '6Lf-9esgAAAAAH9ty_O6kbfWRHc_hpVYiA0q_0cn'
      ];

      $this->load->view('teste/view_form', $data);
    } else {

      $this->form_validation->set_rules('nome', 'Nome', 'required');
      $this->form_validation->set_rules('recaptcha', 'Recaptcha', 'callback__valid_recaptcha');

      if ($this->form_validation->run() == false) {
        return true;
      } else {
        return false;
      }

      exit;
    }
  }

  public function _valid_recaptcha($g_recaptcha_response)
  {

    if ($this->mc_recaptcha->validated() == TRUE) {

      return TRUE;
    } else {

      $this->form_validation->set_message('_valid_recaptcha', "Recaptcha diz que você não é humano!");
    }

    return FALSE;
  }
}
