<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/novo', 'Novo::index');
$routes->post('/novo/solicitar', 'Novo::solicitar');
$routes->post('/home/solicitar', 'Home::solicitar');

// Admin routes
$routes->get('/admin', 'Admin::index');
$routes->get('/admin/login', 'Admin::login');
$routes->post('/admin/login', 'Admin::login');
$routes->get('/admin/logout', 'Admin::logout');
$routes->get('/admin/solicitacoes', 'Admin::solicitacoes');
$routes->post('/admin/atualizarStatus', 'Admin::atualizarStatus');
$routes->get('/admin/marcas', 'Admin::marcas');
$routes->post('/admin/marcas', 'Admin::marcas');
$routes->get('/admin/parceiros', 'Admin::parceiros');
$routes->post('/admin/parceiros', 'Admin::parceiros');

if (ENVIRONMENT == 'development') {
    $routes->get('/view_email_contato', 'Home::view_email_contato');
}

// Rota para teste de envio de email
$routes->get('/teste_envio_email', 'Home::teste_envio_email');

$routes->post('/enviar', 'Home::enviar');
$routes->get('/404', 'NaoEncontrado::index');

// Capturar todas as rotas não definidas
$routes->get('(:any)', 'NaoEncontrado::index');

// Set 404 override
$routes->set404Override('NaoEncontrado::index');