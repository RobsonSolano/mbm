<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class NaoEncontrado extends BaseController
{
    public function index(): string
    {
        // Definir status HTTP 404
        $this->response->setStatusCode(404);
        
        $data['title'] = 'Página não encontrada - MBM Climatização';
        
        return view('template/header', $data) . 
               view('view_pagina_nao_encontrada') . 
               view('template/footer');
    }
}