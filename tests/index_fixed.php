<?php

// Check PHP version.
$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION
    );
    exit($message);
}

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// Load our paths config file
require FCPATH . '../app/Config/Paths.php';

$paths = new Config\Paths();

// Definir constantes manualmente (bypass do bootstrap problemático)
define('APPPATH', realpath(rtrim($paths->appDirectory, '\\/ ')) . DIRECTORY_SEPARATOR);
define('ROOTPATH', realpath(APPPATH . '../') . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', realpath(rtrim($paths->systemDirectory, '\\/ ')) . DIRECTORY_SEPARATOR);
define('WRITEPATH', realpath(rtrim($paths->writableDirectory, '\\/ ')) . DIRECTORY_SEPARATOR);
define('TESTPATH', realpath(rtrim($paths->testsDirectory, '\\/ ')) . DIRECTORY_SEPARATOR);

// Carregar Constants.php ANTES de qualquer coisa
require_once APPPATH . 'Config/Constants.php';

// Carregar Common.php
if (is_file(APPPATH . 'Common.php')) {
    require_once APPPATH . 'Common.php';
}
require_once SYSTEMPATH . 'Common.php';

// Carregar configurações
require_once SYSTEMPATH . 'Config/AutoloadConfig.php';
require_once APPPATH . 'Config/Autoload.php';
require_once SYSTEMPATH . 'Modules/Modules.php';
require_once APPPATH . 'Config/Modules.php';

require_once SYSTEMPATH . 'Autoloader/Autoloader.php';
require_once SYSTEMPATH . 'Config/BaseService.php';
require_once SYSTEMPATH . 'Config/Services.php';
require_once APPPATH . 'Config/Services.php';

// Initialize and register the loader with the SPL autoloader stack.
use Config\Autoload;
use Config\Modules;
use Config\Services;

Services::autoloader()->initialize(new Autoload(), new Modules())->register();
Services::autoloader()->loadHelpers();

// Load environment settings from .env files into $_SERVER and $_ENV
require_once SYSTEMPATH . 'Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv(ROOTPATH))->load();

// Define ENVIRONMENT
if (! defined('ENVIRONMENT')) {
    define('ENVIRONMENT', env('CI_ENVIRONMENT', 'production'));
}

/*
 * GRAB OUR CODEIGNITER INSTANCE
 */
$app = Services::codeigniter();
$app->initialize();
$context = is_cli() ? 'php-cli' : 'web';
$app->setContext($context);

/*
 * LAUNCH THE APPLICATION
 */
$app->run();

// Exits the application
exit(EXIT_SUCCESS);
?>