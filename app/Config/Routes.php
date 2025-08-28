<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/enviar', 'Home::enviar');
$routes->get('/404', 'NaoEncontrado::index');

// Set 404 override
$routes->set404Override('NaoEncontrado::index');
