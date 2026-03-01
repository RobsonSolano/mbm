<?php

namespace App\Controllers;

use App\Models\AcessoModel;
use App\Models\MarcaModel;
use App\Models\ParceiroModel;
use App\Models\SolicitacaoModel;
use App\Models\ContatoModel;

class Novo extends BaseController
{
    protected $acessoModel;
    protected $marcaModel;
    protected $parceiroModel;
    protected $solicitacaoModel;
    protected $contatoModel;

    public function __construct()
    {
        $this->acessoModel = new AcessoModel();
        $this->marcaModel = new MarcaModel();
        $this->parceiroModel = new ParceiroModel();
        $this->solicitacaoModel = new SolicitacaoModel();
        $this->contatoModel = new ContatoModel();
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
        // Verificar reCAPTCHA primeiro
        $recaptcha = new \App\Libraries\Mc_recaptcha();
        $recaptcha_valid = $recaptcha->validated();
        $recaptcha_not_checked = !$recaptcha_valid;

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

        if (!$validation->withRequest($this->request)->run() || !$recaptcha_valid) {
            $session = session();
            if (!$recaptcha_valid) {
                $session->setFlashdata('flash_message', [
                    'mensagem' => '<strong>Erro ao enviar solicitação.</strong><br>Por favor, marque a caixa de validação do reCAPTCHA.', 
                    'tipo' => 'danger'
                ]);
                // Passa o erro do reCAPTCHA para a view
                $data['recaptcha_not_checked'] = true;
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
            } else {
                $session->setFlashdata('flash_message', [
                    'mensagem' => '<strong>Erro ao enviar solicitação.</strong><br>Por favor, preencha todos os campos corretamente.', 
                    'tipo' => 'danger'
                ]);
            }
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

    public function contato()
    {
        try {
            // Carrega o helper de email se não estiver carregado
            helper('email');
            
            // Verificar reCAPTCHA primeiro
            $recaptcha_valid = false;
            try {
                $recaptcha = new \App\Libraries\Mc_recaptcha();
                $recaptcha_valid = $recaptcha->validated();
            } catch (\Exception $e) {
                log_message('error', 'Erro ao validar reCAPTCHA: ' . $e->getMessage());
                // Em caso de erro no reCAPTCHA, continua sem bloquear (pode ser problema de configuração)
            }

            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'nome' => [
                    'label' => 'Nome Completo',
                    'rules' => 'required|trim|min_length[3]',
                    'errors' => ['required' => 'O campo {field} é obrigatório.']
                ],
                'email' => [
                    'label' => 'E-mail',
                    'rules' => 'permit_empty|trim|valid_email',
                    'errors' => [
                        'valid_email' => 'O e-mail informado não é válido'
                    ]
                ],
                'celular' => [
                    'label' => 'Celular',
                    'rules' => 'permit_empty|trim|max_length[15]',
                    'errors' => [
                        'max_length' => 'O {field} deve ter no máximo 11 dígitos.'
                    ]
                ],
                'observacao' => [
                    'label' => 'Observação',
                    'rules' => 'permit_empty|trim',
                ]
            ]);

            if (!$validation->withRequest($this->request)->run() || !$recaptcha_valid) {
                $session = session();
                if (!$recaptcha_valid) {
                    $session->setFlashdata('flash_message', [
                        'mensagem' => '<strong>Erro ao enviar contato.</strong><br>Por favor, marque a caixa de validação do reCAPTCHA.', 
                        'tipo' => 'danger'
                    ]);
                    // Passa o erro do reCAPTCHA para a view
                    $data['recaptcha_not_checked'] = true;
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
                } else {
                    $session->setFlashdata('flash_message', [
                        'mensagem' => '<strong>Erro ao enviar contato.</strong><br>Por favor, preencha todos os campos corretamente.', 
                        'tipo' => 'danger'
                    ]);
                }
                return redirect()->to(base_url('novo#contato'));
            }

            // Valida e normaliza o celular (remove caracteres não numéricos e limita a 11 dígitos)
            $celular = '';
            $celularPost = $this->request->getPost('celular');
            if (!empty($celularPost)) {
                $celular = preg_replace('/\D/', '', $celularPost);
                if (strlen($celular) > 11) {
                    $celular = substr($celular, 0, 11);
                }
            }

            // Salva contato no banco
            $dados = [
                'nome' => $this->request->getPost('nome'),
                'email' => $this->request->getPost('email') ?? null,
                'celular' => !empty($celular) ? $celular : null,
                'observacao' => $this->request->getPost('observacao') ?? null,
                'status' => 'pendente',
                'lido' => 0
            ];

            // Tenta inserir no banco
            try {
                $this->contatoModel->skipValidation(true); // Pula validação do model pois já validamos acima
                $insertId = $this->contatoModel->insert($dados);
                
                if (!$insertId) {
                    throw new \Exception('Falha ao inserir contato no banco de dados');
                }
                
                log_message('info', 'Contato inserido com sucesso. ID: ' . $insertId);
            } catch (\Exception $e) {
                log_message('error', 'Erro ao inserir contato: ' . $e->getMessage());
                log_message('error', 'Trace: ' . $e->getTraceAsString());
                
                $session = session();
                $session->setFlashdata('flash_message', [
                    'mensagem' => '<strong>Erro ao salvar contato.</strong><br>Por favor, tente novamente mais tarde.', 
                    'tipo' => 'danger'
                ]);
                return redirect()->to(base_url('novo#contato'));
            }

            // Envia e-mail (não bloqueia se falhar)
            try {
                if (function_exists('send_email_contato')) {
                    send_email_contato('Novo Contato pelo Site', 'Novo contato pelo site', $dados);
                } else {
                    // Tenta carregar o helper manualmente
                    $helperPath = APPPATH . 'Helpers/email_helper.php';
                    if (file_exists($helperPath)) {
                        require_once $helperPath;
                        if (function_exists('send_email_contato')) {
                            send_email_contato('Novo Contato pelo Site', 'Novo contato pelo site', $dados);
                        }
                    } else {
                        log_message('warning', 'Helper email_helper.php não encontrado');
                    }
                }
            } catch (\Exception $e) {
                // Log do erro mas não bloqueia o sucesso do contato
                log_message('error', 'Erro ao enviar email de contato: ' . $e->getMessage());
            }

            $session = session();
            $session->setFlashdata('flash_message', [
                'mensagem' => '<strong>Contato enviado com sucesso!</strong><br>Em breve nossa equipe entrará em contato.<br><small>Nós agradecemos o contato</small>.', 
                'tipo' => 'success'
            ]);

            return redirect()->to(base_url('novo#contato'));
            
        } catch (\Exception $e) {
            // Log completo do erro
            log_message('error', 'Erro no método contato(): ' . $e->getMessage());
            log_message('error', 'Arquivo: ' . $e->getFile());
            log_message('error', 'Linha: ' . $e->getLine());
            log_message('error', 'Trace: ' . $e->getTraceAsString());
            
            $session = session();
            $session->setFlashdata('flash_message', [
                'mensagem' => '<strong>Erro ao processar contato.</strong><br>Por favor, tente novamente mais tarde.', 
                'tipo' => 'danger'
            ]);
            
            return redirect()->to(base_url('novo#contato'));
        }
    }
}
