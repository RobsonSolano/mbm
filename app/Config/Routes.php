<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

if (ENVIRONMENT == 'development') {
    $routes->get('/view_email_contato', 'Home::view_email_contato');
}

$routes->post('/enviar', 'Home::enviar');
$routes->get('/404', 'NaoEncontrado::index');

// Capturar todas as rotas não definidas
$routes->get('(:any)', 'NaoEncontrado::index');

// Set 404 override
$routes->set404Override('NaoEncontrado::index');