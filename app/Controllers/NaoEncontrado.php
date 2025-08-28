<?php

namespace App\Controllers;

class NaoEncontrado extends BaseController
{
    public function index(): string
    {
        $data['title'] = 'Página não encontrada - MBM Climatização';
        
        return view('template/header', $data) . 
               view('errors/404') . 
               view('template/footer');
    }
}