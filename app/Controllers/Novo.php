<?php

namespace App\Controllers;

use App\Models\AcessoModel;
use App\Models\MarcaModel;
use App\Models\ParceiroModel;
use App\Models\SolicitacaoModel;

class Novo extends BaseController
{
    protected $acessoModel;
    protected $marcaModel;
    protected $parceiroModel;
    protected $solicitacaoModel;

    public function __construct()
    {
        $this->acessoModel = new AcessoModel();
        $this->marcaModel = new MarcaModel();
        $this->parceiroModel = new ParceiroModel();
        $this->solicitacaoModel = new SolicitacaoModel();
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function index(): string
    {
        // Registra acesso
        $ip = $this->request->getIPAddress();
        $navegador = $this->request->getUserAgent()->getAgentString();
        $this->acessoModel->registrarAcesso($ip, $navegador);

        // Busca marcas e parceiros ativos
        $data['marcas'] = $this->marcaModel->buscarAtivas();
        $data['parceiros'] = $this->parceiroModel->buscarAtivos();

        $cumprimento = "";
        if (date('H') < 12) {
            $cumprimento = "Bom dia,%20";
        } elseif (date('H') >= 12 && date('H') < 18) {
            $cumprimento = "Boa tarde,%20";
        } else {
            $cumprimento = "Boa noite,%20";
        }

        $data['mensagem_whatsapp'] = $cumprimento . "%0Aconheci%20o%20site%20" . base_url() . "%0Ae%20gostaria%20de%20saber%20mais.";
        $data['title'] = 'MBM Climatização';

        return view('novo/landing', $data);
    }

    public function solicitar()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nome' => [
                'label' => 'Nome Completo',
                'rules' => 'required|trim|min_length[3]',
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
                'rules' => 'required|trim|max_length[15]',
                'errors' => [
                    'required' => 'O campo {field} é obrigatório.',
                    'max_length' => 'O {field} deve ter no máximo 11 dígitos.'
                ]
            ],
            'cidade' => [
                'label' => 'Cidade',
                'rules' => 'required|trim',
                'errors' => ['required' => 'O campo {field} é obrigatório.']
            ],
            'observacao' => [
                'label' => 'Observação',
                'rules' => 'required|trim',
                'errors' => ['required' => 'O campo {field} é obrigatório.']
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $session = session();
            $session->setFlashdata('flash_message', [
                'mensagem' => '<strong>Erro ao enviar solicitação.</strong><br>Por favor, preencha todos os campos corretamente.', 
                'tipo' => 'danger'
            ]);
            return redirect()->to(base_url('novo'));
        }

        // Verifica se é nome completo
        $nome = $this->request->getPost('nome');
        $nome_completo = explode(' ', trim($nome));
        if (empty($nome_completo[0]) || empty($nome_completo[1])) {
            $session = session();
            $session->setFlashdata('flash_message', [
                'mensagem' => '<strong>Erro ao enviar solicitação.</strong><br>Por favor, informe o nome completo.', 
                'tipo' => 'danger'
            ]);
            return redirect()->to(base_url('novo'));
        }

        // Valida e normaliza o celular (remove caracteres não numéricos e limita a 11 dígitos)
        $celular = preg_replace('/\D/', '', $this->request->getPost('celular'));
        if (strlen($celular) > 11) {
            $celular = substr($celular, 0, 11);
        }
        if (strlen($celular) < 10) {
            $session = session();
            $session->setFlashdata('flash_message', [
                'mensagem' => '<strong>Erro ao enviar solicitação.</strong><br>Por favor, informe um número de celular válido (11 dígitos).', 
                'tipo' => 'danger'
            ]);
            return redirect()->to(base_url('novo'));
        }

        // Salva solicitação no banco
        $ip = $this->request->getIPAddress();
        $navegador = $this->request->getUserAgent()->getAgentString();

        $dados = [
            'nome' => $nome,
            'email' => $this->request->getPost('email'),
            'celular' => $celular,
            'cidade' => $this->request->getPost('cidade'),
            'observacao' => $this->request->getPost('observacao'),
            'ip' => $ip,
            'navegador' => $navegador,
            'status' => 'pendente'
        ];

        $this->solicitacaoModel->insert($dados);

        // Envia e-mail (mantém compatibilidade com sistema antigo)
        if (function_exists('send_email_contato')) {
            send_email_contato('Nova Solicitação de atendimento', 'Novo contato pelo site', $dados);
        }

        $session = session();
        $session->setFlashdata('flash_message', [
            'mensagem' => '<strong>Solicitação enviada com sucesso!</strong><br>Em breve nossa equipe entrará em contato.<br><small>Nós agradecemos o contato</small>.', 
            'tipo' => 'success'
        ]);

        return redirect()->to(base_url('novo'));
    }
}
