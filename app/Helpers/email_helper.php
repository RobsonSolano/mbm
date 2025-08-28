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
        $email = \Config\Services::email();
        
        $email->setFrom('naoresponder@mbmclimacizacao.com.br', $title);
        $email->setTo('climatizacaombm@gmail.com');
        $email->setSubject($subject);
        $email->setReplyTo('naoresponder@mbmclimacizacao.com.br');

        $emailData = [
            'nome' => $data['nome'],
            'email' => $data['email'],
            'celular' => $data['celular'],
            'cidade' => $data['cidade'],
            'observacao' => $data['observacao'] ?? ''
        ];

        // Email body content
        $email->setMessage(view('parts/email/email_contato', $emailData));

        if (!$email->send()) {
            if (ENVIRONMENT == 'development') {
                var_dump($email->printDebugger());
                exit;
            }
            return false;
        } else {
            return true;
        }
    }
}