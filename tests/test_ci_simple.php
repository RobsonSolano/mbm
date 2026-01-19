<?php
/**
 * Teste simples do CodeIgniter - Bypass do bootstrap problemático
 */

// Definir constantes manualmente
define('FCPATH', __DIR__ . '/public/');
define('ROOTPATH', __DIR__ . '/');
define('APPPATH', __DIR__ . '/app/');
define('SYSTEMPATH', __DIR__ . '/system/');
define('WRITEPATH', __DIR__ . '/writable/');
define('TESTPATH', __DIR__ . '/tests/');

// Definir environment
define('ENVIRONMENT', 'development');

// Habilitar erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🧪 Teste CodeIgniter Simples</h1>";

try {
    // Carregar Common.php
    if (file_exists(APPPATH . 'Common.php')) {
        require_once APPPATH . 'Common.php';
        echo "<div style='color: green;'>✅ App Common.php carregado</div>";
    }
    
    require_once SYSTEMPATH . 'Common.php';
    echo "<div style='color: green;'>✅ System Common.php carregado</div>";
    
    // Carregar configurações básicas
    require_once SYSTEMPATH . 'Config/AutoloadConfig.php';
    require_once APPPATH . 'Config/Autoload.php';
    require_once SYSTEMPATH . 'Modules/Modules.php';
    require_once APPPATH . 'Config/Modules.php';
    echo "<div style='color: green;'>✅ Configurações carregadas</div>";
    
    // Carregar autoloader
    require_once SYSTEMPATH . 'Autoloader/Autoloader.php';
    require_once SYSTEMPATH . 'Config/BaseService.php';
    require_once SYSTEMPATH . 'Config/Services.php';
    require_once APPPATH . 'Config/Services.php';
    echo "<div style='color: green;'>✅ Services carregados</div>";
    
    // Inicializar autoloader
    use Config\Autoload;
    use Config\Modules;
    use Config\Services;
    
    Services::autoloader()->initialize(new Autoload(), new Modules())->register();
    Services::autoloader()->loadHelpers();
    echo "<div style='color: green;'>✅ Autoloader inicializado</div>";
    
    // Carregar DotEnv
    require_once SYSTEMPATH . 'Config/DotEnv.php';
    $env = new CodeIgniter\Config\DotEnv(ROOTPATH);
    $env->load();
    echo "<div style='color: green;'>✅ .env carregado</div>";
    
    // Testar controller Home
    echo "<h2>🏠 Testando Controller Home</h2>";
    
    $homeController = new App\Controllers\Home();
    echo "<div style='color: green;'>✅ Controller Home instanciado</div>";
    
    // Testar método index
    ob_start();
    $result = $homeController->index();
    $output = ob_get_clean();
    
    if (!empty($result)) {
        echo "<div style='color: green;'>✅ Método index() executado com sucesso</div>";
        echo "<h3>📄 Output do método index():</h3>";
        echo "<div style='border: 1px solid #ccc; padding: 10px; max-height: 300px; overflow-y: auto;'>";
        echo $result;
        echo "</div>";
    } else {
        echo "<div style='color: red;'>❌ Método index() não retornou conteúdo</div>";
    }
    
    if (!empty($output)) {
        echo "<h3>📄 Output buffer capturado:</h3>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
    }
    
} catch (Throwable $e) {
    echo "<div style='color: red;'>❌ Erro: " . $e->getMessage() . "</div>";
    echo "<div style='color: red;'>Arquivo: " . $e->getFile() . "</div>";
    echo "<div style='color: red;'>Linha: " . $e->getLine() . "</div>";
    echo "<h4>Stack Trace:</h4>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "<hr>";
echo "<p><small>Teste executado em: " . date('Y-m-d H:i:s') . "</small></p>";
?>