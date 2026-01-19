<?php

use Config\Constants;

// Define global constants from the Constants class
if (!defined('APPLICATION_NAME')) {
    define('APPLICATION_NAME', Constants::APPLICATION_NAME);
}

if (!defined('QUANTIDADE_PAGINACAO')) {
    define('QUANTIDADE_PAGINACAO', Constants::QUANTIDADE_PAGINACAO);
}

if (!defined('NOME_PROJETO')) {
    define('NOME_PROJETO', Constants::NOME_PROJETO);
}

if (!defined('ABS_PATH_UPLOAD_PRODUTOS')) {
    define('ABS_PATH_UPLOAD_PRODUTOS', Constants::ABS_PATH_UPLOAD_PRODUTOS);
}

if (!defined('ABS_PATH_UPLOAD_CATEGORIA')) {
    define('ABS_PATH_UPLOAD_CATEGORIA', Constants::ABS_PATH_UPLOAD_CATEGORIA);
}

if (!defined('SITE_KEY_RECAPTCHA')) {
    define('SITE_KEY_RECAPTCHA', Constants::SITE_KEY_RECAPTCHA);
}

if (!defined('SITE_SECRET_RECAPTCHA')) {
    define('SITE_SECRET_RECAPTCHA', Constants::SITE_SECRET_RECAPTCHA);
}