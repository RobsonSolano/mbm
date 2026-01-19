<?php

namespace App\Controllers;

use App\Models\AuthAdminModel;
use App\Models\SolicitacaoModel;
use App\Models\MarcaModel;
use App\Models\ParceiroModel;
use App\Models\AcessoModel;

class Admin extends BaseController
{
    protected $authAdminModel;
    protected $solicitacaoModel;
    protected $marcaModel;
    protected $parceiroModel;
    protected $acessoModel;

    public function __construct()
    {
        $this->authAdminModel = new AuthAdminModel();
        $this->solicitacaoModel = new SolicitacaoModel();
        $this->marcaModel = new MarcaModel();
        $this->parceiroModel = new ParceiroModel();
        $this->acessoModel = new AcessoModel();
        date_default_timezone_set('America/Sao_Paulo');
    }

    /**
     * Verifica se o usuário está logado
     */
    private function verificarLogin()
    {
        $session = session();
        if (!$session->has('admin_logado') || !$session->get('admin_logado')) {
            return true; // Retorna true se NÃO estiver logado
        }
        return false; // Retorna false se estiver logado
    }
    
    /**
     * Upload de imagem
     */
    private function uploadImagem($file, $pasta, $nomeAntigo = null)
    {
        if (!$file || !$file->isValid()) {
            return $nomeAntigo; // Retorna o nome antigo se não houver novo arquivo
        }
        
        // Remove arquivo antigo se existir
        if ($nomeAntigo) {
            $caminhoAntigo = WRITEPATH . 'uploads/' . $pasta . '/' . $nomeAntigo;
            if (file_exists($caminhoAntigo)) {
                unlink($caminhoAntigo);
            }
        }
        
        // Valida extensões permitidas
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extensao = $file->getClientExtension();
        
        if (!in_array(strtolower($extensao), $extensoesPermitidas)) {
            return $nomeAntigo;
        }
        
        // Gera nome único
        $novoNome = uniqid() . '_' . time() . '.' . $extensao;
        
        // Cria pasta se não existir
        $pastaUpload = WRITEPATH . 'uploads/' . $pasta . '/';
        if (!is_dir($pastaUpload)) {
            mkdir($pastaUpload, 0777, true);
        }
        
        // Move arquivo
        $file->move($pastaUpload, $novoNome);
        
        return $novoNome;
    }

    /**
     * Página de login
     */
    public function login()
    {
        // DEBUG
        log_message('debug', '=== LOGIN ATTEMPT ===');
        
        // Se já estiver logado, redireciona para dashboard
        $session = session();
        if ($session->has('admin_logado') && $session->get('admin_logado')) {
            log_message('debug', 'User already logged in, redirecting to admin');
            return redirect()->to(base_url('admin'));
        }

        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $senha = $this->request->getPost('senha');
            
            // DEBUG
            log_message('debug', 'POST received - Email: ' . $email);
            log_message('debug', 'POST received - Senha: ' . str_repeat('*', strlen($senha)));

            $admin = $this->authAdminModel->verificarLogin($email, $senha);
            
            // DEBUG
            log_message('debug', 'verificarLogin result: ' . ($admin ? 'SUCCESS' : 'FAILED'));
            if ($admin) {
                log_message('debug', 'Admin data: ' . json_encode($admin));
            }

            if ($admin) {
                $session->set([
                    'admin_logado' => true,
                    'admin_id' => $admin['id'],
                    'admin_email' => $admin['email'],
                    'admin_nome' => $admin['nome'] ?? 'Administrador'
                ]);
                
                // DEBUG
                log_message('debug', 'Session data set: ' . json_encode([
                    'admin_logado' => $session->get('admin_logado'),
                    'admin_id' => $session->get('admin_id'),
                    'admin_email' => $session->get('admin_email'),
                ]));
                
                return redirect()->to(base_url('admin'));
            } else {
                log_message('debug', 'Login failed, setting flash error');
                $session->setFlashdata('erro', 'E-mail ou senha inválidos.');
            }
        }

        return view('admin/login');
    }

    /**
     * Logout
     */
    public function logout()
    {
        $session = session();
        $session->remove('admin_logado');
        $session->remove('admin_id');
        $session->remove('admin_email');
        $session->remove('admin_nome');
        $session->destroy();
        return redirect()->to(base_url('admin/login'));
    }

    /**
     * Dashboard
     */
    public function index()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $db = \Config\Database::connect();

        // Estatísticas básicas
        $data['total_solicitacoes'] = $this->solicitacaoModel->countAllResults();
        $data['solicitacoes_pendentes'] = $this->solicitacaoModel->contarPorStatus('pendente');
        $data['solicitacoes_em_atendimento'] = $this->solicitacaoModel->contarPorStatus('em_atendimento');
        $data['solicitacoes_resolvidas'] = $this->solicitacaoModel->contarPorStatus('resolvido');
        $data['solicitacoes_canceladas'] = $this->solicitacaoModel->contarPorStatus('cancelado');
        $data['total_marcas'] = $this->marcaModel->countAllResults();
        $data['total_parceiros'] = $this->parceiroModel->countAllResults();
        $data['total_acessos'] = $this->acessoModel->countAllResults();

        $db = \Config\Database::connect();

        // Acessos por período
        $builder_acessos = $db->table('acessos');
        $data['acessos_hoje'] = $builder_acessos
            ->where('DATE(data_acesso)', date('Y-m-d'))
            ->countAllResults(false);
        
        $builder_acessos = $db->table('acessos');
        $data['acessos_semana'] = $builder_acessos
            ->where('data_acesso >=', date('Y-m-d 00:00:00', strtotime('-7 days')))
            ->countAllResults(false);
        
        $builder_acessos = $db->table('acessos');
        $data['acessos_mes'] = $builder_acessos
            ->where('MONTH(data_acesso)', date('m'))
            ->where('YEAR(data_acesso)', date('Y'))
            ->countAllResults(false);

        // Solicitações por período
        $builder_solicitacoes = $db->table('solicitacoes');
        $data['solicitacoes_hoje'] = $builder_solicitacoes
            ->where('DATE(criado_em)', date('Y-m-d'))
            ->countAllResults(false);
        
        $builder_solicitacoes = $db->table('solicitacoes');
        $data['solicitacoes_semana'] = $builder_solicitacoes
            ->where('criado_em >=', date('Y-m-d 00:00:00', strtotime('-7 days')))
            ->countAllResults(false);
        
        $builder_solicitacoes = $db->table('solicitacoes');
        $data['solicitacoes_mes'] = $builder_solicitacoes
            ->where('MONTH(criado_em)', date('m'))
            ->where('YEAR(criado_em)', date('Y'))
            ->countAllResults(false);

        // Acessos últimos 7 dias (para gráfico)
        $data['acessos_ultimos_7_dias'] = [];
        for ($i = 6; $i >= 0; $i--) {
            $data_consulta = date('Y-m-d', strtotime("-{$i} days"));
            $builder = $db->table('acessos');
            $count = $builder
                ->where('DATE(data_acesso)', $data_consulta)
                ->countAllResults(false);
            $data['acessos_ultimos_7_dias'][] = [
                'data' => date('d/m', strtotime($data_consulta)),
                'total' => $count
            ];
        }

        // Solicitações últimos 7 dias (para gráfico)
        $data['solicitacoes_ultimos_7_dias'] = [];
        for ($i = 6; $i >= 0; $i--) {
            $data_consulta = date('Y-m-d', strtotime("-{$i} days"));
            $builder = $db->table('solicitacoes');
            $count = $builder
                ->where('DATE(criado_em)', $data_consulta)
                ->countAllResults(false);
            $data['solicitacoes_ultimos_7_dias'][] = [
                'data' => date('d/m', strtotime($data_consulta)),
                'total' => $count
            ];
        }

        // Top 5 cidades com mais solicitações
        $builder = $db->table('solicitacoes');
        $builder->select('cidade, COUNT(*) as total');
        $builder->where('cidade IS NOT NULL');
        $builder->where('cidade !=', '');
        $builder->groupBy('cidade');
        $builder->orderBy('total', 'DESC');
        $builder->limit(5);
        $data['top_cidades'] = $builder->get()->getResultArray();

        // Últimas solicitações
        $data['ultimas_solicitacoes'] = $this->solicitacaoModel
            ->orderBy('criado_em', 'DESC')
            ->limit(10)
            ->findAll();

        // Últimos acessos
        $data['ultimos_acessos'] = $this->acessoModel
            ->orderBy('data_acesso', 'DESC')
            ->limit(10)
            ->findAll();

        // Taxa de conversão (solicitações / acessos)
        if ($data['total_acessos'] > 0) {
            $data['taxa_conversao'] = round(($data['total_solicitacoes'] / $data['total_acessos']) * 100, 2);
        } else {
            $data['taxa_conversao'] = 0;
        }

        $data['title'] = 'Dashboard';
        $data['content'] = view('admin/dashboard', $data);
        return view('admin/layout', $data);
    }

    /**
     * Gestão de Solicitações
     */
    public function solicitacoes()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $status = $this->request->getGet('status');
        
        if ($status) {
            $data['solicitacoes'] = $this->solicitacaoModel->buscarPorStatus($status);
        } else {
            $data['solicitacoes'] = $this->solicitacaoModel->buscarPorStatus();
        }

        $data['status_atual'] = $status ?? 'todas';
        $data['title'] = 'Gestão de Solicitações';
        $data['content'] = view('admin/solicitacoes', $data);
        return view('admin/layout', $data);
    }

    /**
     * Atualizar status de solicitação
     */
    public function atualizarStatus()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        if ($id && $status) {
            $this->solicitacaoModel->update($id, ['status' => $status]);
            $session = session();
            $session->setFlashdata('sucesso', 'Status atualizado com sucesso!');
        }

        return redirect()->to(base_url('admin/solicitacoes'));
    }

    /**
     * Gestão de Marcas
     */
    public function marcas()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        if ($this->request->getMethod() === 'post') {
            $acao = $this->request->getPost('acao');

            if ($acao === 'criar') {
                $logo = '';
                $file = $this->request->getFile('logo_file');
                if ($file && $file->isValid()) {
                    $logo = $this->uploadImagem($file, 'marcas');
                }
                
                $dados = [
                    'nome' => $this->request->getPost('nome'),
                    'logo' => $logo,
                    'descricao' => $this->request->getPost('descricao'),
                    'ordem' => (int)$this->request->getPost('ordem') ?? 0,
                    'ativo' => $this->request->getPost('ativo') ? 1 : 0
                ];
                $this->marcaModel->insert($dados);
                $session = session();
                $session->setFlashdata('sucesso', 'Marca criada com sucesso!');
            } elseif ($acao === 'editar') {
                $id = $this->request->getPost('id');
                $marcaAntiga = $this->marcaModel->find($id);
                
                $logo = $marcaAntiga['logo'] ?? '';
                $file = $this->request->getFile('logo_file');
                if ($file && $file->isValid()) {
                    $logo = $this->uploadImagem($file, 'marcas', $logo);
                }
                
                $dados = [
                    'nome' => $this->request->getPost('nome'),
                    'logo' => $logo,
                    'descricao' => $this->request->getPost('descricao'),
                    'ordem' => (int)$this->request->getPost('ordem') ?? 0,
                    'ativo' => $this->request->getPost('ativo') ? 1 : 0
                ];
                $this->marcaModel->update($id, $dados);
                $session = session();
                $session->setFlashdata('sucesso', 'Marca atualizada com sucesso!');
            } elseif ($acao === 'excluir') {
                $id = $this->request->getPost('id');
                $this->marcaModel->delete($id);
                $session = session();
                $session->setFlashdata('sucesso', 'Marca excluída com sucesso!');
            }
        }

        $data['marcas'] = $this->marcaModel->orderBy('ordem', 'ASC')->findAll();
        $data['title'] = 'Gestão de Marcas';
        $data['content'] = view('admin/marcas', $data);
        return view('admin/layout', $data);
    }

    /**
     * Gestão de Parceiros
     */
    public function parceiros()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        if ($this->request->getMethod() === 'post') {
            $acao = $this->request->getPost('acao');

            if ($acao === 'criar') {
                $logo = '';
                $file = $this->request->getFile('logo_file');
                if ($file && $file->isValid()) {
                    $logo = $this->uploadImagem($file, 'parceiros');
                }
                
                $dados = [
                    'nome' => $this->request->getPost('nome'),
                    'logo' => $logo,
                    'link' => $this->request->getPost('link'),
                    'descricao' => $this->request->getPost('descricao'),
                    'ordem' => (int)$this->request->getPost('ordem') ?? 0,
                    'ativo' => $this->request->getPost('ativo') ? 1 : 0
                ];
                $this->parceiroModel->insert($dados);
                $session = session();
                $session->setFlashdata('sucesso', 'Parceiro criado com sucesso!');
            } elseif ($acao === 'editar') {
                $id = $this->request->getPost('id');
                $parceiroAntigo = $this->parceiroModel->find($id);
                
                $logo = $parceiroAntigo['logo'] ?? '';
                $file = $this->request->getFile('logo_file');
                if ($file && $file->isValid()) {
                    $logo = $this->uploadImagem($file, 'parceiros', $logo);
                }
                
                $dados = [
                    'nome' => $this->request->getPost('nome'),
                    'logo' => $logo,
                    'link' => $this->request->getPost('link'),
                    'descricao' => $this->request->getPost('descricao'),
                    'ordem' => (int)$this->request->getPost('ordem') ?? 0,
                    'ativo' => $this->request->getPost('ativo') ? 1 : 0
                ];
                $this->parceiroModel->update($id, $dados);
                $session = session();
                $session->setFlashdata('sucesso', 'Parceiro atualizado com sucesso!');
            } elseif ($acao === 'excluir') {
                $id = $this->request->getPost('id');
                $this->parceiroModel->delete($id);
                $session = session();
                $session->setFlashdata('sucesso', 'Parceiro excluído com sucesso!');
            }
        }

        $data['parceiros'] = $this->parceiroModel->orderBy('ordem', 'ASC')->findAll();
        $data['title'] = 'Gestão de Parceiros';
        $data['content'] = view('admin/parceiros', $data);
        return view('admin/layout', $data);
    }
}
