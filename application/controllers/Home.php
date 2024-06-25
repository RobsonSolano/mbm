<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Home_model $home_model
 */
class Home extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('home_model');
		date_default_timezone_set('America/Sao_Paulo');
		$this->load->library('form_validation');
	}

	public function index($error = '', $recaptcha_not_checked = false)
	{

		$cumprimento = "";

		if (date('h') < 12) {
			$cumprimento = "Bom dia,%20";
		} elseif (date('h') >= 12 && date('h') < 18) {
			$cumprimento = "Boa tarde,%20";
		} else {
			$cumprimento = "Bom noite,%20";
		}

		$data['mensagem_whatsapp'] = $cumprimento . "%0Aconheci%20o%20site%20" . base_url() . "%0Ae%20gostaria%20de%20saber%20mais.";

		if (!empty($error)) {
			$data['form_error'] = 'form_error';
		}

		if($recaptcha_not_checked){
			$data['recaptcha_not_checked'] = true;
		}

		$this->load->view('template/header', $data);
		$this->load->view('home/view_index');
		$this->load->view('template/footer');
	}


	public function enviar()
	{
		$item = $this->input->post();

		$this->form_validation->set_rules(
			'nome',
			'Nome Completo',
			'required|trim',
			array('required' => 'O campo %s é obrigatório.')
		);
		$this->form_validation->set_rules(
			'email',
			'E-mail',
			'required|trim|valid_email',
			array('required' => 'O campo %s é obrigatório.', 'valid_email' => 'O e-mail informado não é válido')
		);

		$this->form_validation->set_rules(
			'celular',
			'Celular',
			'required|trim',
			array('required' => 'O campo %s é obrigatório.')
		);

		$this->form_validation->set_rules(
			'cidade',
			'Cidade',
			'required|trim',
			array('required' => 'O campo %s é obrigatório.')
		);

		if ($this->form_validation->run() == FALSE || empty($item['g-recaptcha-response'])) {
			$recaptcha_not_checked = false;
			if(empty($item['g-recaptcha-response'])){
				$recaptcha_not_checked = true;
			}
			$this->index('error', $recaptcha_not_checked);
		} else {

			if(empty($item['g-recaptcha-response'])){
				$recaptcha_not_checked = true;
				$this->index('error', true);
				exit;
			}
			if ($this->_verify_full_name($item['nome']) == false) {
				$this->index('error', 'name_error');
				exit;
			} else {
				// Enviar e-mail
				$send_email = send_email_contato('Nova Solicitação de orçamento', 'Novo contato pelo site', $item);

				if (!$send_email) {
					$this->session->set_flashdata('flash_message', ['mensagem' => '<strong>Falha ao enviar e-mail.</strong><br>Por favor tente novamente', 'tipo' => 'warning']);
					
				} else {
                    $this->session->set_flashdata('flash_message', ['mensagem' => '<strong>E-mail enviado com sucesso</strong><br>Em breve nossa equipe entrará em contato<br><small>Nós agradecemos o contato</small>.', 'tipo' => 'success']);
				}

				redirect(base_url());
			}
		}
	}

	public function _verify_full_name($name)
	{

		$nome_completo = explode(' ', trim($name));

		if (!empty($nome_completo[0]) && !empty($nome_completo[1])) {
			return true;
		} else {
			return false;
		}
	}
}
