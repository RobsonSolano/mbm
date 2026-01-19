<?php
/**
 * Teste direto com PHPMailer para Gmail
 */

// Habilitar erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>📧 Teste PHPMailer Gmail</h1>";

// Carregar autoloader do Composer
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

try {
    // Criar instância PHPMailer
    $mail = new PHPMailer(true);
    
    echo "<h2>1. ⚙️ Configurando PHPMailer</h2>";
    
    // Configurações do servidor
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'climatizacaombm@gmail.com';
    $mail->Password   = 'zzou ofwe jikm jfpo'; // ⚠️ Substitua pela senha real
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    
    // Habilitar debug SMTP
    $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
    $mail->Debugoutput = function($str, $level) {
        echo "<div style='background: #f0f0f0; padding: 5px; margin: 2px; font-family: monospace; font-size: 12px;'>";
        echo htmlspecialchars($str);
        echo "</div>";
    };
    
    echo "<div style='color: green;'>✅ Configurações aplicadas</div>";
    
    echo "<h2>2. 📨 Configurando Email</h2>";
    
    // Remetente e destinatário
    $mail->setFrom('nao-responder@mbmclimatizacao.kesug.com', 'MBM Climatizacao');
    $mail->addAddress('climatizacaombm@gmail.com', 'MBM Climatizacao');
    $mail->addReplyTo('nao-responder@mbmclimatizacao.kesug.com', 'MBM Climatizacao');
    
    // Conteúdo
    $mail->isHTML(true);
    $mail->Subject = 'Teste de Email - Gmail via PHPMailer';
    $mail->Body    = '
    <html>
    <body>
        <h2>🎉 Teste de Email Gmail</h2>
        <p>Este é um teste de envio de email usando <strong>PHPMailer</strong> diretamente.</p>
        <p>Se você recebeu este email, a configuração Gmail está funcionando!</p>
        <hr>
        <p><small>Enviado em: ' . date('d/m/Y H:i:s') . '</small></p>
    </body>
    </html>';
    
    $mail->AltBody = 'Teste de Email Gmail - Se você recebeu este email, a configuração está funcionando!';
    
    echo "<div style='color: green;'>✅ Email configurado</div>";
    
    echo "<h2>3. 📤 Enviando Email</h2>";
    echo "<div style='background: #f9f9f9; padding: 10px; border: 1px solid #ddd; margin: 10px 0;'>";
    
    // Tentar enviar
    $mail->send();
    
    echo "</div>";
    echo "<div style='color: green; font-size: 18px; font-weight: bold; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;'>";
    echo "🎉 EMAIL ENVIADO COM SUCESSO!";
    echo "</div>";
    
} catch (Exception $e) {
    echo "</div>";
    echo "<div style='color: red; font-size: 16px; font-weight: bold; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px;'>";
    echo "❌ ERRO AO ENVIAR EMAIL:<br>";
    echo "Erro: {$mail->ErrorInfo}";
    echo "</div>";
}

echo "<hr>";
echo "<h3>📋 Instruções:</h3>";
echo "<ol>";
echo "<li>Substitua <code>COLOQUE_SUA_SENHA_DE_APP_AQUI</code> pela sua senha de app real do Gmail</li>";
echo "<li>Se funcionar, o problema é específico do CodeIgniter</li>";
echo "<li>Se não funcionar, o problema é na configuração Gmail ou rede</li>";
echo "</ol>";

echo "<p><small>Teste executado em: " . date('Y-m-d H:i:s') . "</small></p>";
?>