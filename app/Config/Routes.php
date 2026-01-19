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
// Marcas
$routes->get('/admin/marcas', 'Admin::marcas');
$routes->post('/admin/marcas', 'Admin::marcas');
$routes->get('/admin/marca/novo', 'Admin::marcaForm');
$routes->get('/admin/marca/(:num)', 'Admin::marcaView/$1');
$routes->get('/admin/marca/(:num)/editar', 'Admin::marcaForm/$1');
$routes->post('/admin/marcas/salvar', 'Admin::marcaSalvar');

// Parceiros
$routes->get('/admin/parceiros', 'Admin::parceiros');
$routes->post('/admin/parceiros', 'Admin::parceiros');
$routes->get('/admin/parceiro/novo', 'Admin::parceiroForm');
$routes->get('/admin/parceiro/(:num)', 'Admin::parceiroView/$1');
$routes->get('/admin/parceiro/(:num)/editar', 'Admin::parceiroForm/$1');
$routes->post('/admin/parceiros/salvar', 'Admin::parceiroSalvar');

// Solicitações - marcar lido e observação
$routes->post('/admin/marcarSolicitacaoLida', 'Admin::marcarSolicitacaoLida');
$routes->post('/admin/contarSolicitacoesNaoLidas', 'Admin::contarSolicitacoesNaoLidas');
$routes->post('/admin/adicionarObservacaoSolicitacao', 'Admin::adicionarObservacaoSolicitacao');

// Clientes
$routes->get('/admin/clientes', 'Admin::clientes');
$routes->get('/admin/cliente/novo', 'Admin::clienteForm');
$routes->post('/admin/cliente/novo', 'Admin::clienteForm');
$routes->get('/admin/cliente/(:num)/editar', 'Admin::clienteForm/$1');
$routes->post('/admin/cliente/(:num)/editar', 'Admin::clienteForm/$1');
$routes->get('/admin/cliente/(:num)/deletar', 'Admin::clienteDeletar/$1');
$routes->get('/admin/cliente/(:num)/detalhes', 'Admin::clienteDetalhes/$1');
$routes->post('/admin/marcarClienteLido', 'Admin::marcarClienteLido');
$routes->post('/admin/contarClientesNaoLidos', 'Admin::contarClientesNaoLidos');

// Serviços dos Clientes
$routes->get('/admin/cliente/(:num)/servicos', 'Admin::clienteServicos/$1');
$routes->get('/admin/cliente/(:num)/servico/novo', 'Admin::clienteServicoForm/$1');
$routes->post('/admin/cliente/(:num)/servico/novo', 'Admin::clienteServicoForm/$1');
$routes->get('/admin/cliente/(:num)/servico/(:num)/editar', 'Admin::clienteServicoForm/$1/$2');
$routes->post('/admin/cliente/(:num)/servico/(:num)/editar', 'Admin::clienteServicoForm/$1/$2');
$routes->get('/admin/cliente/(:num)/servico/(:num)/deletar', 'Admin::clienteServicoDeletar/$1/$2');

// Perfil do Admin
$routes->get('/admin/perfil', 'Admin::perfil');
$routes->post('/admin/perfil', 'Admin::perfil');

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