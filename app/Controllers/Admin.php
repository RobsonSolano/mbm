<?php

namespace App\Controllers;

use App\Models\AuthAdminModel;
use App\Models\SolicitacaoModel;
use App\Models\ContatoModel;
use App\Models\MarcaModel;
use App\Models\ParceiroModel;
use App\Models\AcessoModel;
use App\Models\ClienteModel;
use App\Models\ServicoClienteModel;

class Admin extends BaseController
{
    protected $authAdminModel;
    protected $solicitacaoModel;
    protected $contatoModel;
    protected $marcaModel;
    protected $parceiroModel;
    protected $acessoModel;
    protected $clienteModel;
    protected $servicoClienteModel;

    public function __construct()
    {
        $this->authAdminModel = new AuthAdminModel();
        $this->solicitacaoModel = new SolicitacaoModel();
        $this->contatoModel = new ContatoModel();
        $this->marcaModel = new MarcaModel();
        $this->parceiroModel = new ParceiroModel();
        $this->acessoModel = new AcessoModel();
        $this->clienteModel = new ClienteModel();
        $this->servicoClienteModel = new ServicoClienteModel();
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
        
        // Estatísticas de clientes (com try-catch caso as tabelas ainda não existam)
        try {
            $data['total_clientes'] = $this->clienteModel->contarAtivos();
            $data['clientes_bloqueados'] = $this->clienteModel->contarBloqueados();
            $data['clientes_nao_lidos'] = $this->clienteModel->contarNaoLidos();
        } catch (\Exception $e) {
            $data['total_clientes'] = 0;
            $data['clientes_bloqueados'] = 0;
            $data['clientes_nao_lidos'] = 0;
        }
        
        // Solicitações não lidas (com try-catch caso a coluna ainda não exista)
        try {
            $data['solicitacoes_nao_lidas'] = $this->solicitacaoModel->where('lido', 0)->countAllResults();
        } catch (\Exception $e) {
            $data['solicitacoes_nao_lidas'] = 0;
        }

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
     * Listagem de Marcas
     */
    public function marcas()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        // Handle delete via POST
        if ($this->request->getMethod() === 'post' && $this->request->getPost('acao') === 'excluir') {
            $id = $this->request->getPost('id');
            $this->marcaModel->delete($id);
            $session = session();
            $session->setFlashdata('sucesso', 'Marca excluída com sucesso!');
            return redirect()->to(base_url('admin/marcas'));
        }

        $data['marcas'] = $this->marcaModel->orderBy('ordem', 'ASC')->findAll();
        $data['title'] = 'Gestão de Marcas';
        $data['content'] = view('admin/marcas', $data);
        return view('admin/layout', $data);
    }

    /**
     * Visualizar Marca
     */
    public function marcaView($id)
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $data['marca'] = $this->marcaModel->find($id);
        if (!$data['marca']) {
            $session = session();
            $session->setFlashdata('erro', 'Marca não encontrada.');
            return redirect()->to(base_url('admin/marcas'));
        }

        $data['title'] = 'Marca - ' . $data['marca']['nome'];
        $data['content'] = view('admin/marca_view', $data);
        return view('admin/layout', $data);
    }

    /**
     * Formulário de Marca (criar/editar)
     */
    public function marcaForm($id = null)
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $data['marca'] = null;
        if ($id) {
            $data['marca'] = $this->marcaModel->find($id);
            if (!$data['marca']) {
                $session = session();
                $session->setFlashdata('erro', 'Marca não encontrada.');
                return redirect()->to(base_url('admin/marcas'));
            }
        }

        $data['title'] = $id ? 'Editar Marca' : 'Nova Marca';
        $data['content'] = view('admin/marca_form', $data);
        return view('admin/layout', $data);
    }

    /**
     * Salvar Marca
     */
    public function marcaSalvar()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $id = $this->request->getPost('id');
        $marcaAntiga = $id ? $this->marcaModel->find($id) : null;
        
        $logo = $marcaAntiga['logo'] ?? '';
        $file = $this->request->getFile('logo_file');
        if ($file && $file->isValid()) {
            $logo = $this->uploadImagem($file, 'marcas', $logo);
        }
        
        $dados = [
            'nome' => $this->request->getPost('nome'),
            'logo' => $logo,
            'descricao' => $this->request->getPost('descricao'),
            'ordem' => (int)($this->request->getPost('ordem') ?? 0),
            'ativo' => $this->request->getPost('ativo') ? 1 : 0
        ];

        $session = session();
        if ($id) {
            $this->marcaModel->update($id, $dados);
            $session->setFlashdata('sucesso', 'Marca atualizada com sucesso!');
        } else {
            $this->marcaModel->insert($dados);
            $session->setFlashdata('sucesso', 'Marca criada com sucesso!');
        }

        return redirect()->to(base_url('admin/marcas'));
    }

    /**
     * Listagem de Parceiros
     */
    public function parceiros()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        // Handle delete via POST
        if ($this->request->getMethod() === 'post' && $this->request->getPost('acao') === 'excluir') {
            $id = $this->request->getPost('id');
            $this->parceiroModel->delete($id);
            $session = session();
            $session->setFlashdata('sucesso', 'Parceiro excluído com sucesso!');
            return redirect()->to(base_url('admin/parceiros'));
        }

        $data['parceiros'] = $this->parceiroModel->orderBy('ordem', 'ASC')->findAll();
        $data['title'] = 'Gestão de Parceiros';
        $data['content'] = view('admin/parceiros', $data);
        return view('admin/layout', $data);
    }

    /**
     * Visualizar Parceiro
     */
    public function parceiroView($id)
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $data['parceiro'] = $this->parceiroModel->find($id);
        if (!$data['parceiro']) {
            $session = session();
            $session->setFlashdata('erro', 'Parceiro não encontrado.');
            return redirect()->to(base_url('admin/parceiros'));
        }

        $data['title'] = 'Parceiro - ' . $data['parceiro']['nome'];
        $data['content'] = view('admin/parceiro_view', $data);
        return view('admin/layout', $data);
    }

    /**
     * Formulário de Parceiro (criar/editar)
     */
    public function parceiroForm($id = null)
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $data['parceiro'] = null;
        if ($id) {
            $data['parceiro'] = $this->parceiroModel->find($id);
            if (!$data['parceiro']) {
                $session = session();
                $session->setFlashdata('erro', 'Parceiro não encontrado.');
                return redirect()->to(base_url('admin/parceiros'));
            }
        }

        $data['title'] = $id ? 'Editar Parceiro' : 'Novo Parceiro';
        $data['content'] = view('admin/parceiro_form', $data);
        return view('admin/layout', $data);
    }

    /**
     * Salvar Parceiro
     */
    public function parceiroSalvar()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $id = $this->request->getPost('id');
        $parceiroAntigo = $id ? $this->parceiroModel->find($id) : null;
        
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
            'ordem' => (int)($this->request->getPost('ordem') ?? 0),
            'ativo' => $this->request->getPost('ativo') ? 1 : 0
        ];

        $session = session();
        if ($id) {
            $this->parceiroModel->update($id, $dados);
            $session->setFlashdata('sucesso', 'Parceiro atualizado com sucesso!');
        } else {
            $this->parceiroModel->insert($dados);
            $session->setFlashdata('sucesso', 'Parceiro criado com sucesso!');
        }

        return redirect()->to(base_url('admin/parceiros'));
    }

    /**
     * Contar solicitações não lidas (para atualizar badge)
     */
    public function contarSolicitacoesNaoLidas()
    {
        if ($this->verificarLogin()) {
            return $this->response->setJSON(['success' => false, 'count' => 0]);
        }

        try {
            $count = $this->solicitacaoModel->where('lido', 0)->countAllResults();
            return $this->response->setJSON(['success' => true, 'count' => $count]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'count' => 0]);
        }
    }

    /**
     * Marcar solicitação como lida/não lida
     */
    public function marcarSolicitacaoLida()
    {
        if ($this->verificarLogin()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Não autorizado']);
        }

        $id = $this->request->getPost('id');
        $lido = $this->request->getPost('lido') == '1' ? 1 : 0;

        if ($id) {
            try {
                $this->solicitacaoModel->update($id, ['lido' => $lido]);
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => $lido ? 'Marcado como lido' : 'Marcado como não lido'
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Erro ao marcar solicitação como lida: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Erro ao atualizar'
                ]);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'ID inválido']);
    }

    /**
     * Adicionar observação do admin na solicitação
     */
    public function adicionarObservacaoSolicitacao()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $id = $this->request->getPost('id');
        $observacao = $this->request->getPost('observacao_admin');

        if ($id && $observacao) {
            $this->solicitacaoModel->update($id, ['observacao_admin' => $observacao]);
            $session = session();
            $session->setFlashdata('sucesso', 'Observação adicionada com sucesso!');
        }

        return redirect()->to(base_url('admin/solicitacoes'));
    }

    /**
     * Gestão de Contatos
     */
    public function contatos()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $status = $this->request->getGet('status');
        
        if ($status && $status !== 'todas') {
            $data['contatos'] = $this->contatoModel->buscarPorStatus($status);
        } else {
            $data['contatos'] = $this->contatoModel->buscarPorStatus();
        }

        $data['status_atual'] = $status ?? 'todas';
        $data['title'] = 'Gestão de Contatos';
        $data['content'] = view('admin/contatos', $data);
        return view('admin/layout', $data);
    }

    /**
     * Atualizar status de contato
     */
    public function atualizarStatusContato()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        if ($id && $status) {
            $this->contatoModel->update($id, ['status' => $status]);
            $session = session();
            $session->setFlashdata('sucesso', 'Status atualizado com sucesso!');
        }

        return redirect()->to(base_url('admin/contatos'));
    }

    /**
     * Contar contatos não lidos (para atualizar badge)
     */
    public function contarContatosNaoLidos()
    {
        if ($this->verificarLogin()) {
            return $this->response->setJSON(['success' => false, 'count' => 0]);
        }

        try {
            $count = $this->contatoModel->where('lido', 0)->countAllResults();
            return $this->response->setJSON(['success' => true, 'count' => $count]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'count' => 0]);
        }
    }

    /**
     * Marcar contato como lido/não lido
     */
    public function marcarContatoLido()
    {
        if ($this->verificarLogin()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Não autorizado']);
        }

        $id = $this->request->getPost('id');
        $lido = $this->request->getPost('lido') == '1' ? 1 : 0;

        if ($id) {
            try {
                $this->contatoModel->update($id, ['lido' => $lido]);
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => $lido ? 'Marcado como lido' : 'Marcado como não lido'
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Erro ao marcar contato como lido: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Erro ao atualizar'
                ]);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'ID inválido']);
    }

    /**
     * Excluir contato
     */
    public function excluirContato()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $id = $this->request->getPost('id');
        if ($id) {
            try {
                $this->contatoModel->delete($id);
                $session = session();
                $session->setFlashdata('sucesso', 'Contato excluído com sucesso!');
            } catch (\Exception $e) {
                log_message('error', 'Erro ao excluir contato: ' . $e->getMessage());
                $session = session();
                $session->setFlashdata('erro', 'Erro ao excluir contato.');
            }
        }

        return redirect()->to(base_url('admin/contatos'));
    }

    /**
     * Excluir solicitação
     */
    public function excluirSolicitacao()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $id = $this->request->getPost('id');
        if ($id) {
            try {
                $this->solicitacaoModel->delete($id);
                $session = session();
                $session->setFlashdata('sucesso', 'Solicitação excluída com sucesso!');
            } catch (\Exception $e) {
                log_message('error', 'Erro ao excluir solicitação: ' . $e->getMessage());
                $session = session();
                $session->setFlashdata('erro', 'Erro ao excluir solicitação.');
            }
        }

        return redirect()->to(base_url('admin/solicitacoes'));
    }

    /**
     * Gestão de Clientes
     */
    public function clientes()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $ordenacao = $this->request->getGet('ordenacao') ?? 'id';
        $direcao = $this->request->getGet('direcao') ?? 'DESC';
        $filtroNome = $this->request->getGet('filtro_nome') ?? '';
        $filtroEmail = $this->request->getGet('filtro_email') ?? '';
        $filtroCelular = $this->request->getGet('filtro_celular') ?? '';
        $filtroCidade = $this->request->getGet('filtro_cidade') ?? '';

        $query = $this->clienteModel->where('deletado', 0);

        if ($filtroNome) {
            $query->like('nome_completo', $filtroNome);
        }
        if ($filtroEmail) {
            $query->like('email', $filtroEmail);
        }
        if ($filtroCelular) {
            $query->like('celular', $filtroCelular);
        }
        if ($filtroCidade) {
            $query->like('cidade', $filtroCidade);
        }

        $totalClientes = $this->clienteModel->where('deletado', 0)->countAllResults(false);
        $data['clientes'] = $query->orderBy($ordenacao, $direcao)->findAll();
        $data['totalClientes'] = $totalClientes;
        $data['ordenacao'] = $ordenacao;
        $data['direcao'] = $direcao;
        $data['filtroNome'] = $filtroNome;
        $data['filtroEmail'] = $filtroEmail;
        $data['filtroCelular'] = $filtroCelular;
        $data['filtroCidade'] = $filtroCidade;
        $data['title'] = 'Gestão de Clientes (' . number_format($totalClientes, 0, ',', '.') . ')';
        $data['content'] = view('admin/clientes', $data);
        return view('admin/layout', $data);
    }

    /**
     * Criar/Editar Cliente
     */
    public function clienteForm($id = null)
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $data['cliente'] = null;
        if ($id) {
            $data['cliente'] = $this->clienteModel->buscarPorId($id);
            if (!$data['cliente']) {
                $session = session();
                $session->setFlashdata('erro', 'Cliente não encontrado.');
                return redirect()->to(base_url('admin/clientes'));
            }
        }

        if ($this->request->getMethod() === 'post') {
            // Valida e normaliza o celular (remove caracteres não numéricos e limita a 11 dígitos)
            $celular = preg_replace('/\D/', '', $this->request->getPost('celular'));
            if (strlen($celular) > 11) {
                $celular = substr($celular, 0, 11);
            }
            if (strlen($celular) < 10) {
                $session = session();
                $session->setFlashdata('erro', 'Por favor, informe um número de celular válido (mínimo 10 dígitos).');
                return redirect()->to(base_url($id ? "admin/cliente/{$id}/editar" : 'admin/cliente/novo'));
            }
            
            $dados = [
                'nome_completo' => $this->request->getPost('nome_completo'),
                'celular' => $celular,
                'email' => $this->request->getPost('email'),
                'endereco' => $this->request->getPost('endereco'),
                'cidade' => $this->request->getPost('cidade'),
                'observacoes' => $this->request->getPost('observacoes'),
                'bloqueado' => $this->request->getPost('bloqueado') ? 1 : 0
            ];

            $session = session();
            if ($id) {
                $this->clienteModel->update($id, $dados);
                $session->setFlashdata('sucesso', 'Cliente atualizado com sucesso!');
            } else {
                $this->clienteModel->insert($dados);
                $session->setFlashdata('sucesso', 'Cliente criado com sucesso!');
            }

            return redirect()->to(base_url('admin/clientes'));
        }

        $data['title'] = $id ? 'Editar Cliente' : 'Novo Cliente';
        $data['content'] = view('admin/cliente_form', $data);
        return view('admin/layout', $data);
    }

    /**
     * Visualizar Cliente (Modal/Ajax)
     */
    public function clienteDetalhes($id)
    {
        if ($this->verificarLogin()) {
            return $this->response->setJSON(['error' => 'Não autorizado']);
        }

        $cliente = $this->clienteModel->buscarPorId($id);
        
        if ($cliente) {
            // Marca como lido ao visualizar
            $this->clienteModel->marcarComoLido($id);
            return $this->response->setJSON(['success' => true, 'cliente' => $cliente]);
        }

        return $this->response->setJSON(['success' => false]);
    }

    /**
     * Contar clientes não lidos (para atualizar badge)
     */
    public function contarClientesNaoLidos()
    {
        if ($this->verificarLogin()) {
            return $this->response->setJSON(['success' => false, 'count' => 0]);
        }

        try {
            $count = $this->clienteModel->contarNaoLidos();
            return $this->response->setJSON(['success' => true, 'count' => $count]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'count' => 0]);
        }
    }

    /**
     * Marcar cliente como lido/não lido
     */
    public function marcarClienteLido()
    {
        if ($this->verificarLogin()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Não autorizado']);
        }

        $id = $this->request->getPost('id');
        $lido = $this->request->getPost('lido') == '1' ? 1 : 0;

        if ($id) {
            try {
                if ($lido) {
                    $this->clienteModel->marcarComoLido($id);
                } else {
                    $this->clienteModel->marcarComoNaoLido($id);
                }
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => $lido ? 'Cliente marcado como lido' : 'Cliente marcado como não lido'
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Erro ao marcar cliente como lido: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Erro ao atualizar'
                ]);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'ID inválido']);
    }

    /**
     * Deletar Cliente (soft delete)
     */
    public function clienteDeletar($id)
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $this->clienteModel->deletar($id);
        $session = session();
        $session->setFlashdata('sucesso', 'Cliente excluído com sucesso!');
        return redirect()->to(base_url('admin/clientes'));
    }

    /**
     * Histórico de Serviços do Cliente
     */
    public function clienteServicos($clienteId)
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $data['cliente'] = $this->clienteModel->buscarPorId($clienteId);
        if (!$data['cliente']) {
            $session = session();
            $session->setFlashdata('erro', 'Cliente não encontrado.');
            return redirect()->to(base_url('admin/clientes'));
        }

        $data['servicos'] = $this->servicoClienteModel->buscarPorCliente($clienteId);
        $data['title'] = 'Serviços - ' . $data['cliente']['nome_completo'];
        $data['content'] = view('admin/cliente_servicos', $data);
        return view('admin/layout', $data);
    }

    /**
     * Adicionar/Editar Serviço do Cliente
     */
    public function clienteServicoForm($clienteId, $servicoId = null)
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $data['cliente'] = $this->clienteModel->buscarPorId($clienteId);
        if (!$data['cliente']) {
            $session = session();
            $session->setFlashdata('erro', 'Cliente não encontrado.');
            return redirect()->to(base_url('admin/clientes'));
        }

        $data['servico'] = null;
        if ($servicoId) {
            $data['servico'] = $this->servicoClienteModel->find($servicoId);
        }

        if ($this->request->getMethod() === 'post') {
            $dados = [
                'cliente_id' => $clienteId,
                'titulo' => $this->request->getPost('titulo'),
                'descricao' => $this->request->getPost('descricao'),
                'data_inicio' => $this->request->getPost('data_inicio'),
                'data_finalizacao' => $this->request->getPost('data_finalizacao')
            ];

            $session = session();
            if ($servicoId) {
                $this->servicoClienteModel->update($servicoId, $dados);
                $session->setFlashdata('sucesso', 'Serviço atualizado com sucesso!');
            } else {
                $this->servicoClienteModel->insert($dados);
                $session->setFlashdata('sucesso', 'Serviço adicionado com sucesso!');
            }

            return redirect()->to(base_url("admin/cliente/{$clienteId}/servicos"));
        }

        $data['title'] = $servicoId ? 'Editar Serviço' : 'Novo Serviço';
        $data['content'] = view('admin/cliente_servico_form', $data);
        return view('admin/layout', $data);
    }

    /**
     * Excluir Serviço do Cliente
     */
    public function clienteServicoDeletar($clienteId, $servicoId)
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $this->servicoClienteModel->delete($servicoId);
        $session = session();
        $session->setFlashdata('sucesso', 'Serviço excluído com sucesso!');
        return redirect()->to(base_url("admin/cliente/{$clienteId}/servicos"));
    }

    /**
     * Perfil do Admin
     */
    public function perfil()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $session = session();
        $adminId = $session->get('admin_id');
        $data['admin'] = $this->authAdminModel->find($adminId);

        if ($this->request->getMethod() === 'post') {
            $dados = [
                'nome' => $this->request->getPost('nome'),
                'email' => $this->request->getPost('email')
            ];

            $novaSenha = $this->request->getPost('nova_senha');
            $confirmarSenha = $this->request->getPost('confirmar_senha');

            if ($novaSenha) {
                if ($novaSenha === $confirmarSenha) {
                    $dados['senha'] = $novaSenha; // O model já faz o hash
                    $this->authAdminModel->update($adminId, $dados);
                    
                    $session->setFlashdata('sucesso', 'Dados atualizados! Faça login novamente.');
                    // Faz logout
                    $session->remove('admin_logado');
                    $session->remove('admin_id');
                    $session->remove('admin_email');
                    $session->remove('admin_nome');
                    $session->destroy();
                    return redirect()->to(base_url('admin/login'));
                } else {
                    $session->setFlashdata('erro', 'As senhas não conferem.');
                    return redirect()->to(base_url('admin/perfil'));
                }
            } else {
                $this->authAdminModel->update($adminId, $dados);
                $session->setFlashdata('sucesso', 'Dados atualizados com sucesso!');
                // Atualiza nome na sessão
                $session->set('admin_nome', $dados['nome']);
                $session->set('admin_email', $dados['email']);
                return redirect()->to(base_url('admin/perfil'));
            }
        }

        $data['title'] = 'Meu Perfil';
        $data['content'] = view('admin/perfil', $data);
        return view('admin/layout', $data);
    }
}
