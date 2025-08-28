<?php

namespace App\Controllers;

use App\Models\HomeModel;

class Home extends BaseController
{
    protected $homeModel;

    public function __construct()
    {
        $this->homeModel = new HomeModel();
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function index($error = '', $recaptcha_not_checked = false): string
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

        if ($recaptcha_not_checked) {
            $data['recaptcha_not_checked'] = true;
        }

        $data['title'] = 'MBM Climatização';

        return view('template/header', $data) . 
               view('home/view_index') . 
               view('template/footer');
    }

    public function enviar()
    {
        $item = $this->request->getPost();

        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nome' => [
                'label' => 'Nome Completo',
                'rules' => 'required|trim',
                'errors' => ['required' => 'O campo {field} é obrigatório.']
            ],
            'email' => [
                'label' => 'E-mail',
                'rules' => 'required|trim|valid_email',
                'errors' => [
                    'required' => 'O campo {field} é obrigatório.',
                    'valid_email' => 'O e-mail informado não é válido'
                ]
            ],
            'celular' => [
                'label' => 'Celular',
                'rules' => 'required|trim',
                'errors' => ['required' => 'O campo {field} é obrigatório.']
            ],
            'cidade' => [
                'label' => 'Cidade',
                'rules' => 'required|trim',
                'errors' => ['required' => 'O campo {field} é obrigatório.']
            ]
        ]);

        if (!$validation->withRequest($this->request)->run() || empty($item['g-recaptcha-response'])) {
            $recaptcha_not_checked = false;
            if (empty($item['g-recaptcha-response'])) {
                $recaptcha_not_checked = true;
            }
            return $this->index('error', $recaptcha_not_checked);
        } else {
            if (empty($item['g-recaptcha-response'])) {
                return $this->index('error', true);
            }
            
            if ($this->_verify_full_name($item['nome']) == false) {
                return $this->index('error', 'name_error');
            } else {
                // Enviar e-mail
                $send_email = send_email_contato('Nova Solicitação de orçamento', 'Novo contato pelo site', $item);

                $session = session();
                
                if (!$send_email) {
                    $session->setFlashdata('flash_message', [
                        'mensagem' => '<strong>Falha ao enviar e-mail.</strong><br>Por favor tente novamente', 
                        'tipo' => 'warning'
                    ]);
                } else {
                    $session->setFlashdata('flash_message', [
                        'mensagem' => '<strong>E-mail enviado com sucesso</strong><br>Em breve nossa equipe entrará em contato<br><small>Nós agradecemos o contato</small>.', 
                        'tipo' => 'success'
                    ]);
                }

                return redirect()->to(base_url());
            }
        }
    }

    private function _verify_full_name($name)
    {
        $nome_completo = explode(' ', trim($name));

        if (!empty($nome_completo[0]) && !empty($nome_completo[1])) {
            return true;
        } else {
            return false;
        }
    }
}