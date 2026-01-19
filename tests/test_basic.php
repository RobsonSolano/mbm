<?php
/**
 * Teste ultra básico - Identificar onde está o problema
 */

// Habilitar TODOS os erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);

echo "<h1>🔬 Teste Ultra Básico</h1>";

// Definir handler de erro personalizado
set_error_handler(function($severity, $message, $file, $line) {
    echo "<div style='color: red; border: 1px solid red; padding: 10px; margin: 5px;'>";
    echo "<strong>PHP Error:</strong> $message<br>";
    echo "<strong>File:</strong> $file<br>";
    echo "<strong>Line:</strong> $line<br>";
    echo "</div>";
    return false; // Continua com o handler padrão
});

// Definir handler de exceção
set_exception_handler(function($exception) {
    echo "<div style='color: red; border: 2px solid red; padding: 15px; margin: 10px;'>";
    echo "<h3>🚨 EXCEÇÃO CAPTURADA:</h3>";
    echo "<strong>Erro:</strong> " . $exception->getMessage() . "<br>";
    echo "<strong>Arquivo:</strong> " . $exception->getFile() . "<br>";
    echo "<strong>Linha:</strong> " . $exception->getLine() . "<br>";
    echo "<h4>Stack Trace:</h4>";
    echo "<pre>" . $exception->getTraceAsString() . "</pre>";
    echo "</div>";
});

echo "<h2>1. ✅ PHP Básico OK</h2>";

// Testar constantes básicas
echo "<h2>2. 📁 Definindo Constantes</h2>";
try {
    define('FCPATH', __DIR__ . '/public/');
    echo "✅ FCPATH: " . FCPATH . "<br>";
    
    define('ROOTPATH', __DIR__ . '/');
    echo "✅ ROOTPATH: " . ROOTPATH . "<br>";
    
    define('APPPATH', __DIR__ . '/app/');
    echo "✅ APPPATH: " . APPPATH . "<br>";
    
    define('SYSTEMPATH', __DIR__ . '/system/');
    echo "✅ SYSTEMPATH: " . SYSTEMPATH . "<br>";
    
    define('WRITEPATH', __DIR__ . '/writable/');
    echo "✅ WRITEPATH: " . WRITEPATH . "<br>";
    
    echo "<div style='color: green;'>✅ Todas as constantes definidas</div>";
} catch (Throwable $e) {
    echo "<div style='color: red;'>❌ Erro ao definir constantes: " . $e->getMessage() . "</div>";
    exit;
}

// Testar carregamento de arquivos um por um
echo "<h2>3. 📄 Carregando Arquivos Básicos</h2>";

$files = [
    'App Common' => APPPATH . 'Common.php',
    'System Common' => SYSTEMPATH . 'Common.php',
    'Constants' => APPPATH . 'Config/Constants.php',
    'AutoloadConfig' => SYSTEMPATH . 'Config/AutoloadConfig.php',
    'App Autoload' => APPPATH . 'Config/Autoload.php',
    'Modules' => SYSTEMPATH . 'Modules/Modules.php',
    'App Modules' => APPPATH . 'Config/Modules.php'
];

foreach ($files as $name => $file) {
    echo "<h3>Carregando: $name</h3>";
    
    if (!file_exists($file)) {
        echo "<div style='color: red;'>❌ Arquivo não existe: $file</div>";
        continue;
    }
    
    try {
        require_once $file;
        echo "<div style='color: green;'>✅ $name carregado com sucesso</div>";
    } catch (Throwable $e) {
        echo "<div style='color: red;'>❌ Erro ao carregar $name:</div>";
        echo "<div style='color: red;'>Erro: " . $e->getMessage() . "</div>";
        echo "<div style='color: red;'>Arquivo: " . $e->getFile() . "</div>";
        echo "<div style='color: red;'>Linha: " . $e->getLine() . "</div>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        break; // Para no primeiro erro
    }
}

echo "<h2>4. 🔧 Testando Autoloader</h2>";

try {
    require_once SYSTEMPATH . 'Autoloader/Autoloader.php';
    echo "<div style='color: green;'>✅ Autoloader.php carregado</div>";
    
    require_once SYSTEMPATH . 'Config/BaseService.php';
    echo "<div style='color: green;'>✅ BaseService.php carregado</div>";
    
    require_once SYSTEMPATH . 'Config/Services.php';
    echo "<div style='color: green;'>✅ Services.php carregado</div>";
    
    require_once APPPATH . 'Config/Services.php';
    echo "<div style='color: green;'>✅ App Services.php carregado</div>";
    
} catch (Throwable $e) {
    echo "<div style='color: red;'>❌ Erro ao carregar services:</div>";
    echo "<div style='color: red;'>Erro: " . $e->getMessage() . "</div>";
    echo "<div style='color: red;'>Arquivo: " . $e->getFile() . "</div>";
    echo "<div style='color: red;'>Linha: " . $e->getLine() . "</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>5. 🌐 Testando Classes Básicas</h2>";

try {
    echo "<h3>Testando Config\\Autoload</h3>";
    $autoload = new Config\Autoload();
    echo "<div style='color: green;'>✅ Config\\Autoload instanciado</div>";
    
    echo "<h3>Testando Config\\Modules</h3>";
    $modules = new Config\Modules();
    echo "<div style='color: green;'>✅ Config\\Modules instanciado</div>";
    
} catch (Throwable $e) {
    echo "<div style='color: red;'>❌ Erro ao instanciar classes:</div>";
    echo "<div style='color: red;'>Erro: " . $e->getMessage() . "</div>";
    echo "<div style='color: red;'>Arquivo: " . $e->getFile() . "</div>";
    echo "<div style='color: red;'>Linha: " . $e->getLine() . "</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>6. 📧 Testando Helper Email</h2>";

try {
    // Verificar se o helper existe
    $helperFile = APPPATH . 'Helpers/email_helper.php';
    if (file_exists($helperFile)) {
        echo "<div style='color: green;'>✅ Helper email_helper.php existe</div>";
        
        require_once $helperFile;
        echo "<div style='color: green;'>✅ Helper email_helper.php carregado</div>";
        
        if (function_exists('send_email_contato')) {
            echo "<div style='color: green;'>✅ Função send_email_contato disponível</div>";
        } else {
            echo "<div style='color: red;'>❌ Função send_email_contato NÃO disponível</div>";
        }
    } else {
        echo "<div style='color: red;'>❌ Helper email_helper.php não existe</div>";
    }
} catch (Throwable $e) {
    echo "<div style='color: red;'>❌ Erro ao testar helper:</div>";
    echo "<div style='color: red;'>Erro: " . $e->getMessage() . "</div>";
    echo "<div style='color: red;'>Arquivo: " . $e->getFile() . "</div>";
    echo "<div style='color: red;'>Linha: " . $e->getLine() . "</div>";
}

echo "<hr>";
echo "<p><strong>Se chegou até aqui sem erros, o problema não é nos arquivos básicos do CodeIgniter.</strong></p>";
echo "<p><small>Teste executado em: " . date('Y-m-d H:i:s') . "</small></p>";
?>