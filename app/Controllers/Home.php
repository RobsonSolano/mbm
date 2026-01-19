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

        // Verificar reCAPTCHA
        $recaptcha = new \App\Libraries\Mc_recaptcha();
        $recaptcha_valid = $recaptcha->validated();
        
        if (!$validation->withRequest($this->request)->run() || !$recaptcha_valid) {
            $recaptcha_not_checked = !$recaptcha_valid;
            return $this->index('error', $recaptcha_not_checked);
        } else {
            
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


    /**
     *
     */
    public function view_email_contato()
    {
        $data['nome'] = 'João da Silva';
        $data['email'] = 'joao@silva.com';
        $data['celular'] = '(11) 99999-9999';
        $data['cidade'] = 'São Paulo';
        $data['observacao'] = 'Gostaria de saber mais sobre os serviços de climatização';

        return view('parts/email/email_contato', $data);
    }

    public function teste_envio_email()
    {
        echo "<h2>Debug do Sistema de Email</h2>";
        
        // 1. Verificar se o helper está carregado
        echo "<h3>1. Verificando Helper</h3>";
        if (function_exists('send_email_contato')) {
            echo "✅ Função send_email_contato encontrada<br>";
        } else {
            echo "❌ Função send_email_contato NÃO encontrada<br>";
            return;
        }
        
        // 2. Verificar configurações de email (usando as mesmas do helper)
        echo "<h3>2. Verificando Configurações (Gmail)</h3>";
        echo "Protocol: smtp<br>";
        echo "SMTP Host: smtp.gmail.com<br>";
        echo "SMTP Port: 587<br>";
        echo "SMTP User: climatizacaombm@gmail.com<br>";
        echo "SMTP Crypto: tls<br>";
        echo "SMTP Auth: true<br>";
        echo "From Email: nao-responder@mbmclimatizacao.kesug.com<br>";
        echo "From Name: MBM Climatizacao<br>";
        
        // 3. Testar serviço de email (com configurações Gmail)
        echo "<h3>3. Testando Serviço de Email</h3>";
        try {
            // Usar as mesmas configurações do helper
            $config = [
                'protocol' => 'smtp',
                'SMTPHost' => 'smtp.gmail.com',
                'SMTPPort' => 587,
                'SMTPUser' => 'climatizacaombm@gmail.com',
                'SMTPPass' => 'zzou ofwe jikm jfpo', // Mesma senha que funcionou no PHPMailer
                'SMTPCrypto' => 'tls',
                'SMTPAuth' => true,
                'mailType' => 'html',
                'charset' => 'UTF-8',
                'wordWrap' => true,
                'SMTPTimeout' => 60,
                'newline' => "\r\n",
                'CRLF' => "\r\n"
            ];
            $emailService = \Config\Services::email($config);
            echo "✅ Serviço de email criado com configurações Gmail<br>";
        } catch (\Throwable $e) {
            echo "❌ Erro ao criar serviço de email: " . $e->getMessage() . "<br>";
            return;
        }
        
        // 4. Verificar view do email
        echo "<h3>4. Verificando View do Email</h3>";
        $data = [
            'nome' => 'João da Silva',
            'email' => 'joao@silva.com',
            'celular' => '(11) 99999-9999',
            'cidade' => 'São Paulo',
            'observacao' => 'Teste de email'
        ];
        
        try {
            $emailContent = view('parts/email/email_contato', $data);
            echo "✅ View do email carregada com sucesso<br>";
            echo "Tamanho do conteúdo: " . strlen($emailContent) . " caracteres<br>";
        } catch (\Throwable $e) {
            echo "❌ Erro na view do email: " . $e->getMessage() . "<br>";
            echo "Arquivo: " . $e->getFile() . " Linha: " . $e->getLine() . "<br>";
            return;
        }
        
        // 5. Tentar enviar email
        echo "<h3>5. Tentando Enviar Email</h3>";
        try {
            $result = send_email_contato('Nova Solicitação de orçamento', 'Novo contato pelo site', $data);
            
            if ($result) {
                echo "✅ E-mail enviado com sucesso!<br>";
            } else {
                echo "❌ Falha no envio do e-mail. Verifique os logs.<br>";
            }
        } catch (\Throwable $e) {
            echo "❌ Erro no envio de e-mail: " . $e->getMessage() . "<br>";
            echo "Arquivo: " . $e->getFile() . " Linha: " . $e->getLine() . "<br>";
            echo "<details><summary>Stack Trace</summary><pre>" . $e->getTraceAsString() . "</pre></details>";
        }
        
        // 6. Verificar logs recentes
        echo "<h3>6. Logs Recentes</h3>";
        $logPath = WRITEPATH . 'logs/log-' . date('Y-m-d') . '.php';
        if (file_exists($logPath)) {
            $logs = file_get_contents($logPath);
            $recentLogs = substr($logs, -2000); // Últimos 2000 caracteres
            echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 300px; overflow-y: auto;'>" . htmlspecialchars($recentLogs) . "</pre>";
        } else {
            echo "Arquivo de log não encontrado: " . $logPath . "<br>";
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