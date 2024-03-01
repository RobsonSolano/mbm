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

	public function index($error = '', $name_error = "", $cpf_error = "")
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

		if (!empty($name_error)) {
			$data['name_error'] = $name_error;
			$data['form_error'] = 'form_error';
		}

		if (!empty($cpf_error)) {
			$data['cpf_error'] = 'cpf_error';
			$data['form_error'] = 'form_error';
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
			'cpf',
			'CPF',
			'required|trim',
			array('required' => 'O campo %s é obrigatório.')
		);
		$this->form_validation->set_rules(
			'renda',
			'Renda',
			'required|trim',
			array('required' => 'O campo %s é obrigatório.')
		);
		$this->form_validation->set_rules(
			'email',
			'E-mail',
			'trim'
		);

		if ($this->form_validation->run() == FALSE) {
			$this->index('error');
		} else {

			if ($this->_verify_full_name($item['nome']) == false) {
				$this->index('error', 'name_error');
			} elseif (validaCPF($item['cpf']) == false) {
				$this->index('error', null, 'cpf_error');
			} else {

				$nome_completo = explode(' ', trim($item['nome']));

				$nome = ucfirst($nome_completo[0]);
				$sobrenome = ucfirst($nome_completo[1]);

				$mensagem = "*Nome:*%20" . $nome . '%20' . $sobrenome .
					"%0A*CPF:*%20" . $item['cpf'];

				if (!empty($item['email'])) {
					$mensagem .= "%0A*E-mail:*%20" . $item['email'];
				}

				$mensagem .= "%0A*Renda:*%20" . $item['renda'] .
					"%0A*Acessei%20o%20site%20" . base_url() . "*" .
					"%0AGostaria%20de%20saber%20mais.";

				header("location: http://api.whatsapp.com/send?phone=+5511958183686&text=$mensagem");
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
