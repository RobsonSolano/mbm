<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Uploads extends Controller
{
    /**
     * Serve imagens de uploads (marcas, parceiros, etc.)
     * 
     * @param string $tipo Tipo do upload (marcas, parceiros, etc.)
     * @param string $arquivo Nome do arquivo
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function index($tipo = '', $arquivo = '')
    {
        // Valida tipo permitido
        $tiposPermitidos = ['marcas', 'parceiros'];
        if (!in_array($tipo, $tiposPermitidos) || empty($arquivo)) {
            return $this->response->setStatusCode(404)->setBody('Arquivo não encontrado');
        }
        
        // Caminho do arquivo
        $caminhoArquivo = WRITEPATH . 'uploads/' . $tipo . '/' . $arquivo;
        
        // Verifica se arquivo existe
        if (!file_exists($caminhoArquivo) || !is_file($caminhoArquivo)) {
            return $this->response->setStatusCode(404)->setBody('Arquivo não encontrado');
        }
        
        // Valida se é uma imagem
        $mimeType = mime_content_type($caminhoArquivo);
        if (strpos($mimeType, 'image/') !== 0) {
            return $this->response->setStatusCode(403)->setBody('Acesso negado');
        }
        
        // Lê o arquivo
        $conteudo = file_get_contents($caminhoArquivo);
        
        // Define headers apropriados
        $this->response->setHeader('Content-Type', $mimeType);
        $this->response->setHeader('Content-Length', (string)filesize($caminhoArquivo));
        $this->response->setHeader('Cache-Control', 'public, max-age=31536000'); // Cache de 1 ano
        
        return $this->response->setBody($conteudo);
    }
}
