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
        // Configurações Gmail que funcionaram no PHPMailer
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
            'email' => $data['email'],
            'celular' => $data['celular'],
            'cidade' => $data['cidade'],
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