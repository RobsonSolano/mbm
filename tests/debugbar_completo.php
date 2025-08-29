<?php
/**
 * Debug Completo - Sistema MBM
 * Arquivo para identificar problemas no sistema
 */

// Habilitar todos os erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>🔍 Debug Completo - Sistema MBM</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; }
    .error { color: red; }
    .warning { color: orange; }
    .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
    pre { background: #f5f5f5; padding: 10px; border-radius: 3px; overflow-x: auto; }
    .check { font-weight: bold; }
</style>";

// 1. Verificar PHP básico
echo "<div class='section'>";
echo "<h2>1. ✅ PHP Básico</h2>";
echo "<div class='success check'>✅ PHP Version: " . PHP_VERSION . "</div>";
echo "<div class='success check'>✅ Script executando corretamente</div>";
echo "</div>";

// 2. Verificar caminhos e constantes
echo "<div class='section'>";
echo "<h2>2. 📁 Caminhos e Estrutura</h2>";

$rootPath = __DIR__;
echo "<div class='check'>📂 Root Path: $rootPath</div>";

$paths = [
    'app' => $rootPath . '/app',
    'system' => $rootPath . '/system', 
    'writable' => $rootPath . '/writable',
    'public' => $rootPath . '/public',
    'vendor' => $rootPath . '/vendor'
];

foreach ($paths as $name => $path) {
    if (is_dir($path)) {
        echo "<div class='success check'>✅ $name: $path</div>";
    } else {
        echo "<div class='error check'>❌ $name: $path (NÃO ENCONTRADO)</div>";
    }
}
echo "</div>";

// 3. Verificar arquivos críticos
echo "<div class='section'>";
echo "<h2>3. 📄 Arquivos Críticos</h2>";

$files = [
    '.env' => $rootPath . '/env',
    'index.php' => $rootPath . '/public/index.php',
    'bootstrap.php' => $rootPath . '/system/bootstrap.php',
    'BaseController' => $rootPath . '/app/Controllers/BaseController.php',
    'Home Controller' => $rootPath . '/app/Controllers/Home.php',
    'Routes' => $rootPath . '/app/Config/Routes.php',
    'Email Config' => $rootPath . '/app/Config/Email.php'
];

foreach ($files as $name => $file) {
    if (file_exists($file)) {
        echo "<div class='success check'>✅ $name: existe</div>";
    } else {
        echo "<div class='error check'>❌ $name: NÃO ENCONTRADO ($file)</div>";
    }
}
echo "</div>";

// 4. Verificar .env
echo "<div class='section'>";
echo "<h2>4. ⚙️ Configurações .env</h2>";

$envFile = $rootPath . '/env';
if (file_exists($envFile)) {
    echo "<div class='success check'>✅ Arquivo .env encontrado</div>";
    
    $envContent = file_get_contents($envFile);
    $envLines = explode("\n", $envContent);
    
    echo "<h3>Configurações de Email:</h3>";
    foreach ($envLines as $line) {
        $line = trim($line);
        if (strpos($line, 'email.') === 0 && strpos($line, '#') !== 0) {
            echo "<div class='check'>📧 $line</div>";
        }
    }
    
    echo "<h3>Outras configurações importantes:</h3>";
    foreach ($envLines as $line) {
        $line = trim($line);
        if (strpos($line, 'CI_ENVIRONMENT') === 0 || 
            strpos($line, 'app.baseURL') === 0) {
            echo "<div class='check'>🔧 $line</div>";
        }
    }
} else {
    echo "<div class='error check'>❌ Arquivo .env NÃO ENCONTRADO</div>";
}
echo "</div>";

// 5. Tentar carregar CodeIgniter
echo "<div class='section'>";
echo "<h2>5. 🚀 Carregamento do CodeIgniter</h2>";

try {
    // Definir constantes básicas
    define('ROOTPATH', $rootPath . DIRECTORY_SEPARATOR);
    define('APPPATH', ROOTPATH . 'app' . DIRECTORY_SEPARATOR);
    define('SYSTEMPATH', ROOTPATH . 'system' . DIRECTORY_SEPARATOR);
    define('FCPATH', ROOTPATH . 'public' . DIRECTORY_SEPARATOR);
    define('WRITEPATH', ROOTPATH . 'writable' . DIRECTORY_SEPARATOR);
    
    echo "<div class='success check'>✅ Constantes definidas</div>";
    
    // Verificar bootstrap
    $bootstrapFile = SYSTEMPATH . 'bootstrap.php';
    if (file_exists($bootstrapFile)) {
        echo "<div class='success check'>✅ Bootstrap encontrado</div>";
        
        // Tentar incluir (com cuidado)
        ob_start();
        $bootstrapError = null;
        try {
            require_once $bootstrapFile;
            echo "<div class='success check'>✅ Bootstrap carregado com sucesso</div>";
        } catch (Throwable $e) {
            $bootstrapError = $e;
            echo "<div class='error check'>❌ Erro no Bootstrap: " . $e->getMessage() . "</div>";
            echo "<div class='error'>Arquivo: " . $e->getFile() . " Linha: " . $e->getLine() . "</div>";
        }
        $bootstrapOutput = ob_get_clean();
        
        if (!empty($bootstrapOutput)) {
            echo "<h4>Output do Bootstrap:</h4>";
            echo "<pre>" . htmlspecialchars($bootstrapOutput) . "</pre>";
        }
        
    } else {
        echo "<div class='error check'>❌ Bootstrap NÃO ENCONTRADO: $bootstrapFile</div>";
    }
    
} catch (Throwable $e) {
    echo "<div class='error check'>❌ Erro fatal ao carregar CodeIgniter:</div>";
    echo "<div class='error'>Erro: " . $e->getMessage() . "</div>";
    echo "<div class='error'>Arquivo: " . $e->getFile() . "</div>";
    echo "<div class='error'>Linha: " . $e->getLine() . "</div>";
    echo "<h4>Stack Trace:</h4>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
echo "</div>";

// 6. Verificar logs
echo "<div class='section'>";
echo "<h2>6. 📋 Logs de Erro</h2>";

$logDir = $rootPath . '/writable/logs';
if (is_dir($logDir)) {
    echo "<div class='success check'>✅ Diretório de logs existe</div>";
    
    $logFiles = glob($logDir . '/log-*.log');
    if (!empty($logFiles)) {
        $latestLog = end($logFiles);
        echo "<div class='check'>📄 Log mais recente: " . basename($latestLog) . "</div>";
        
        if (file_exists($latestLog)) {
            $logContent = file_get_contents($latestLog);
            $recentLines = array_slice(explode("\n", $logContent), -20); // Últimas 20 linhas
            
            echo "<h4>Últimas entradas do log:</h4>";
            echo "<pre>" . htmlspecialchars(implode("\n", $recentLines)) . "</pre>";
        }
    } else {
        echo "<div class='warning check'>⚠️ Nenhum arquivo de log encontrado</div>";
    }
} else {
    echo "<div class='error check'>❌ Diretório de logs NÃO ENCONTRADO</div>";
}
echo "</div>";

// 7. Teste de permissões
echo "<div class='section'>";
echo "<h2>7. 🔐 Permissões</h2>";

$checkDirs = [
    'writable' => $rootPath . '/writable',
    'writable/cache' => $rootPath . '/writable/cache',
    'writable/logs' => $rootPath . '/writable/logs',
    'writable/session' => $rootPath . '/writable/session'
];

foreach ($checkDirs as $name => $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "<div class='success check'>✅ $name: escrevível</div>";
        } else {
            echo "<div class='error check'>❌ $name: SEM PERMISSÃO DE ESCRITA</div>";
        }
    } else {
        echo "<div class='error check'>❌ $name: diretório não existe</div>";
    }
}
echo "</div>";

// 8. Informações do servidor
echo "<div class='section'>";
echo "<h2>8. 🖥️ Informações do Servidor</h2>";

echo "<div class='check'>🐘 PHP SAPI: " . php_sapi_name() . "</div>";
echo "<div class='check'>💾 Memory Limit: " . ini_get('memory_limit') . "</div>";
echo "<div class='check'>⏱️ Max Execution Time: " . ini_get('max_execution_time') . "s</div>";
echo "<div class='check'>📁 Upload Max Filesize: " . ini_get('upload_max_filesize') . "</div>";

if (isset($_SERVER['SERVER_SOFTWARE'])) {
    echo "<div class='check'>🌐 Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</div>";
}

echo "</div>";

echo "<div style='margin-top: 30px; padding: 15px; background: #e7f3ff; border-radius: 5px;'>";
echo "<h3>🎯 Próximos Passos:</h3>";
echo "<ol>";
echo "<li>Verifique se todos os itens acima estão ✅ (verdes)</li>";
echo "<li>Se há erros ❌ (vermelhos), esses são os problemas a resolver</li>";
echo "<li>Preste atenção especial nos logs de erro</li>";
echo "<li>Se o Bootstrap falhou, o problema está na configuração básica do CodeIgniter</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<p><small>Debug executado em: " . date('Y-m-d H:i:s') . "</small></p>";
?>