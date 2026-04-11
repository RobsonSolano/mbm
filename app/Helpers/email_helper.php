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

if (!function_exists('send_email_agendamento')) {
    /**
     * Envia e-mail de notificação de agendamento ao responsável.
     *
     * @param string $tipo        'criado' | 'cancelado' | 'concluido'
     * @param string $destinatario E-mail do responsável
     * @param array  $agendamento  Campos: data, hora_inicio, hora_fim, descricao, observacoes
     * @param string $clienteNome  Nome do cliente
     */
    function send_email_agendamento(string $tipo, string $destinatario, array $agendamento, string $clienteNome): bool
    {
        if (ENVIRONMENT === 'development') {
            $config = [
                'protocol'    => 'smtp',
                'SMTPHost'    => 'mailhog',
                'SMTPPort'    => 1025,
                'SMTPUser'    => '',
                'SMTPPass'    => '',
                'SMTPCrypto'  => '',
                'SMTPAuth'    => false,
                'mailType'    => 'html',
                'charset'     => 'UTF-8',
                'wordWrap'    => true,
                'SMTPTimeout' => 60,
                'newline'     => "\r\n",
                'CRLF'        => "\r\n",
            ];
        } else {
            $config = [
                'protocol'    => 'smtp',
                'SMTPHost'    => 'smtp.gmail.com',
                'SMTPPort'    => 587,
                'SMTPUser'    => 'climatizacaombm@gmail.com',
                'SMTPPass'    => 'zzou ofwe jikm jfpo',
                'SMTPCrypto'  => 'tls',
                'SMTPAuth'    => true,
                'mailType'    => 'html',
                'charset'     => 'UTF-8',
                'wordWrap'    => true,
                'SMTPTimeout' => 60,
                'newline'     => "\r\n",
                'CRLF'        => "\r\n",
            ];
        }

        $assuntos = [
            'criado'    => 'Novo agendamento: ' . date('d/m/Y', strtotime($agendamento['data'])),
            'cancelado' => 'Agendamento cancelado: ' . date('d/m/Y', strtotime($agendamento['data'])),
            'concluido' => 'Atendimento concluído: ' . date('d/m/Y', strtotime($agendamento['data'])),
        ];

        $fromEmail = env('email.fromEmail', 'nao-responder@mbmclimatizacao.kesug.com');

        $email = \Config\Services::email($config);
        $email->setFrom($fromEmail, 'MBM Climatização');
        $email->setTo($destinatario);
        $email->setSubject($assuntos[$tipo] ?? 'Notificação de agendamento');
        $email->setReplyTo($fromEmail);

        try {
            $email->setMessage(view('parts/email/email_agendamento', [
                'tipo'         => $tipo,
                'cliente_nome' => $clienteNome,
                'data'         => $agendamento['data'],
                'hora_inicio'  => $agendamento['hora_inicio'],
                'hora_fim'     => $agendamento['hora_fim'] ?? '',
                'descricao'    => $agendamento['descricao'],
                'observacoes'  => $agendamento['observacoes'] ?? '',
            ]));
            if (!$email->send()) {
                log_message('error', '[agendamento] Falha no envio: ' . $email->printDebugger(['headers']));
                return false;
            }
            log_message('info', '[agendamento] E-mail ' . $tipo . ' enviado para: ' . $destinatario);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[agendamento] Exceção: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('send_email_lembrete_diario')) {
    /**
     * Envia resumo diário de agendamentos ao responsável.
     *
     * @param string $destinatario    E-mail do responsável
     * @param string $responsavelNome Nome do responsável
     * @param array  $itens           Lista de agendamentos com: hora_inicio, hora_fim, descricao,
     *                                observacoes, cliente_nome, endereco, cidade
     * @param string $data            Data no formato Y-m-d
     */
    function send_email_lembrete_diario(string $destinatario, string $responsavelNome, array $itens, string $data): bool
    {
        if (ENVIRONMENT === 'development') {
            $config = [
                'protocol'    => 'smtp',
                'SMTPHost'    => 'mailhog',
                'SMTPPort'    => 1025,
                'SMTPUser'    => '',
                'SMTPPass'    => '',
                'SMTPCrypto'  => '',
                'SMTPAuth'    => false,
                'mailType'    => 'html',
                'charset'     => 'UTF-8',
                'wordWrap'    => true,
                'SMTPTimeout' => 60,
                'newline'     => "\r\n",
                'CRLF'        => "\r\n",
            ];
        } else {
            $config = [
                'protocol'    => 'smtp',
                'SMTPHost'    => 'smtp.gmail.com',
                'SMTPPort'    => 587,
                'SMTPUser'    => 'climatizacaombm@gmail.com',
                'SMTPPass'    => 'zzou ofwe jikm jfpo',
                'SMTPCrypto'  => 'tls',
                'SMTPAuth'    => true,
                'mailType'    => 'html',
                'charset'     => 'UTF-8',
                'wordWrap'    => true,
                'SMTPTimeout' => 60,
                'newline'     => "\r\n",
                'CRLF'        => "\r\n",
            ];
        }

        $total     = count($itens);
        $dataFmt   = date('d/m/Y', strtotime($data));
        $fromEmail = env('email.fromEmail', 'nao-responder@mbmclimatizacao.kesug.com');

        $email = \Config\Services::email($config);
        $email->setFrom($fromEmail, 'MBM Climatização');
        $email->setTo($destinatario);
        $email->setSubject("Agendamentos de hoje ({$dataFmt}) — {$total} " . ($total === 1 ? 'atendimento' : 'atendimentos'));
        $email->setReplyTo($fromEmail);

        try {
            $email->setMessage(view('parts/email/email_lembrete_diario', [
                'responsavel_nome' => $responsavelNome,
                'itens'            => $itens,
                'data'             => $data,
            ]));
            if (!$email->send()) {
                log_message('error', '[lembrete] Falha ao enviar para ' . $destinatario . ': ' . $email->printDebugger(['headers']));
                return false;
            }
            log_message('info', '[lembrete] Resumo diário enviado para: ' . $destinatario);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[lembrete] Exceção: ' . $e->getMessage());
            return false;
        }
    }
}