<?php
if (!function_exists('send_codigo_to_email')) {
  /**
   * Método genérico para enviar e-mail
   * @param string $title 
   * @param string $subject 
   * @param string $to 
   * @param string $view 
   * @param array $data 
   * @return bool 
   */
  function send_email_contato($title, $subject, $data)
  {

    $ci = &get_instance();

    $ci->email->from($ci->email->smtp_user, $title);

    $ci->email->to($ci->email->smtp_user);

    $ci->email->subject($subject);

    $data = [
      'nome' => $data['nome'],
      'email' => $data['email'],
      'celular' => $data['celular'],
      'observacao' => $data['observacao']
    ];

    // Email body content
    $ci->email->message($ci->load->view('parts/email/email_contato', $data, TRUE));

    $ci->email->reply_to('naoresponder@mbmclimacizacao.com.br');

    if (!$ci->email->send()) {

      if (ENVIRONMENT == 'development') {
        var_dump($ci->email->print_debugger());
        exit;
      }
      return false;
    } else {
      return true;
    }
  }
}
