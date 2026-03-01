<?php

if (!function_exists('send_email_contato')) {
    /**
     * Método genérico para enviar e-mail
     * @param string $title 
     * @param string $subject 
     * @param array $data 
     * @return bool 
     */
    function send_email_contato($title, $subject, $data)
    {
        // Em desenvolvimento, usa mailhog. Em produção, usa Gmail
        if (ENVIRONMENT === 'development') {
            // Configurações para MailHog (desenvolvimento)
            // MailHog usa hostname 'mailhog' ou '127.0.0.1' na porta 1025
            $config = [
                'protocol' => 'smtp',
                'SMTPHost' => 'mailhog', // ou '127.0.0.1' se não funcionar
                'SMTPPort' => 1025,
                'SMTPUser' => '',
                'SMTPPass' => '',
                'SMTPCrypto' => '', // MailHog não usa criptografia
                'SMTPAuth' => false, // MailHog não precisa autenticação
                'mailType' => 'html',
                'charset' => 'UTF-8',
                'wordWrap' => true,
                'SMTPTimeout' => 60,
                'newline' => "\r\n",
                'CRLF' => "\r\n"
            ];
        } else {
            // Configurações Gmail (produção)
        $config = [
            'protocol' => 'smtp',
            'SMTPHost' => 'smtp.gmail.com',
            'SMTPPort' => 587,
            'SMTPUser' => 'climatizacaombm@gmail.com',
                'SMTPPass' => 'zzou ofwe jikm jfpo',
            'SMTPCrypto' => 'tls',
            'SMTPAuth' => true,
            'mailType' => 'html',
            'charset' => 'UTF-8',
            'wordWrap' => true,
            'SMTPTimeout' => 60,
            'newline' => "\r\n",
            'CRLF' => "\r\n"
        ];
        }
        
        $email = \Config\Services::email($config);
        
        // Usar configurações do .env
        $fromEmail = env('email.fromEmail', 'nao-responder@mbmclimatizacao.kesug.com');
        $fromName = env('email.fromName', 'MBM Climatizacao');
        
        $email->setFrom($fromEmail, 'Contato via Site - MBM');
        $email->setTo('climatizacaombm@gmail.com');
        $email->setSubject($subject);
        $email->setReplyTo($fromEmail);

        $emailData = [
            'nome' => $data['nome'],
            'email' => $data['email'] ?? '',
            'celular' => $data['celular'] ?? '',
            'cidade' => $data['cidade'] ?? '',
            'observacao' => $data['observacao'] ?? ''
        ];

        try {
            // Email body content
            $email->setMessage(view('parts/email/email_contato', $emailData));

            if (!$email->send()) {
                // Log do erro
                log_message('error', 'Falha no envio de email: ' . $email->printDebugger());
                
                if (ENVIRONMENT == 'development') {
                    echo "Erro no envio de email:<br>";
                    echo $email->printDebugger();
                }
                return false;
            } else {
                log_message('info', 'Email enviado com sucesso para: climatizacaombm@gmail.com');
                return true;
            }
        } catch (Exception $e) {
            log_message('error', 'Exceção no envio de email: ' . $e->getMessage());
            
            if (ENVIRONMENT == 'development') {
                echo "Exceção no email: " . $e->getMessage() . "<br>";
            }
            return false;
        }
    }
}