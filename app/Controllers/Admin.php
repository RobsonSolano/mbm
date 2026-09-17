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
use App\Models\EstoqueModel;
use App\Models\EstoqueHistoricoModel;
use App\Models\FornecedorModel;
use App\Models\AgendamentoModel;
use App\Models\FuncionarioModel;

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
    protected $estoqueModel;
    protected $estoqueHistoricoModel;
    protected $fornecedorModel;
    protected $agendamentoModel;
    protected $funcionarioModel;

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
        $this->estoqueModel = new EstoqueModel();
        $this->estoqueHistoricoModel = new EstoqueHistoricoModel();
        $this->fornecedorModel = new FornecedorModel();
        $this->agendamentoModel = new AgendamentoModel();
        $this->funcionarioModel = new FuncionarioModel();
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
            $recaptcha = new \App\Libraries\Mc_recaptcha();
            $recaptchaValid = $recaptcha->validated();

            if (!$recaptchaValid) {
                $session->setFlashdata('erro', 'Por favor, marque a opção "Não sou um robô" (reCAPTCHA).');
                return view('admin/login', ['recaptcha_not_checked' => true]);
            }

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
     * Listagem de Fornecedores
     */
    public function fornecedores()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        if ($this->request->getMethod() === 'post' && $this->request->getPost('acao') === 'excluir') {
            $id = $this->request->getPost('id');
            $this->fornecedorModel->delete($id);
            $session = session();
            $session->setFlashdata('sucesso', 'Fornecedor excluído com sucesso!');
            return redirect()->to(base_url('admin/fornecedores'));
        }

        $filtroNome = $this->request->getGet('filtro_nome') ?? '';
        $query = $this->fornecedorModel;
        if ($filtroNome) {
            $query = $query->like('nome', $filtroNome);
        }
        $data['fornecedores'] = $query->orderBy('nome', 'ASC')->findAll();
        $data['filtroNome'] = $filtroNome;
        $data['title'] = 'Gestão de Fornecedores';
        $data['content'] = view('admin/fornecedores', $data);
        return view('admin/layout', $data);
    }

    /**
     * Visualizar Fornecedor
     */
    public function fornecedorView($id)
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $data['fornecedor'] = $this->fornecedorModel->find($id);
        if (!$data['fornecedor']) {
            $session = session();
            $session->setFlashdata('erro', 'Fornecedor não encontrado.');
            return redirect()->to(base_url('admin/fornecedores'));
        }

        $data['title'] = 'Fornecedor - ' . $data['fornecedor']['nome'];
        $data['content'] = view('admin/fornecedor_view', $data);
        return view('admin/layout', $data);
    }

    /**
     * Formulário de Fornecedor (criar/editar)
     */
    public function fornecedorForm($id = null)
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $data['fornecedor'] = null;
        if ($id) {
            $data['fornecedor'] = $this->fornecedorModel->find($id);
            if (!$data['fornecedor']) {
                $session = session();
                $session->setFlashdata('erro', 'Fornecedor não encontrado.');
                return redirect()->to(base_url('admin/fornecedores'));
            }
        }

        $data['title'] = $id ? 'Editar Fornecedor' : 'Novo Fornecedor';
        $data['content'] = view('admin/fornecedor_form', $data);
        return view('admin/layout', $data);
    }

    /**
     * Salvar Fornecedor
     */
    public function fornecedorSalvar()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $id = $this->request->getPost('id');
        $dados = [
            'nome' => $this->request->getPost('nome'),
            'contato' => $this->request->getPost('contato'),
            'email' => $this->request->getPost('email'),
            'observacoes' => $this->request->getPost('observacoes'),
            'ativo' => $this->request->getPost('ativo') ? 1 : 0
        ];

        $session = session();
        if ($id) {
            $this->fornecedorModel->update($id, $dados);
            $session->setFlashdata('sucesso', 'Fornecedor atualizado com sucesso!');
        } else {
            $this->fornecedorModel->insert($dados);
            $session->setFlashdata('sucesso', 'Fornecedor criado com sucesso!');
        }

        return redirect()->to(base_url('admin/fornecedores'));
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
     * Gestão de Estoque
     */
    public function estoque()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        if ($this->request->getMethod() === 'post' && $this->request->getPost('acao') === 'excluir') {
            $id = $this->request->getPost('id');
            $this->estoqueHistoricoModel->where('peca_id', $id)->delete();
            $this->estoqueModel->delete($id);
            $session = session();
            $session->setFlashdata('sucesso', 'Peça excluída com sucesso!');
            return redirect()->to(base_url('admin/estoque'));
        }

        $filtroNome = $this->request->getGet('filtro_nome') ?? '';
        $db = \Config\Database::connect();
        try {
            $builder = $db->table('estoque_pecas')
                ->select('estoque_pecas.*, fornecedores.nome as fornecedor_nome')
                ->join('fornecedores', 'fornecedores.id = estoque_pecas.fornecedor_id', 'left');
            if ($filtroNome) {
                $builder->like('estoque_pecas.nome', $filtroNome);
            }
            $data['pecas'] = $builder->orderBy('estoque_pecas.nome', 'ASC')->get()->getResultArray();
        } catch (\Throwable $e) {
            $query = $this->estoqueModel;
            if ($filtroNome) {
                $query = $query->like('nome', $filtroNome);
            }
            $data['pecas'] = $query->orderBy('nome', 'ASC')->findAll();
            foreach ($data['pecas'] as &$p) {
                $p['fornecedor_nome'] = null;
            }
        }
        $data['filtroNome'] = $filtroNome;
        $data['title'] = 'Gestão de Estoque';
        $data['content'] = view('admin/estoque', $data);
        return view('admin/layout', $data);
    }

    /**
     * Salvar Peça
     */
    public function estoqueSalvar()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $id = $this->request->getPost('id');
        $fornecedorId = $this->request->getPost('fornecedor_id');
        $dados = [
            'nome' => $this->request->getPost('nome'),
            'quantidade' => (int) $this->request->getPost('quantidade'),
            'descricao' => $this->request->getPost('descricao'),
            'fornecedor_id' => $fornecedorId ? (int) $fornecedorId : null,
        ];

        $session = session();
        if ($id) {
            $this->estoqueModel->update($id, $dados);
            $session->setFlashdata('sucesso', 'Peça atualizada com sucesso!');
        } else {
            $this->estoqueModel->insert($dados);
            $session->setFlashdata('sucesso', 'Peça cadastrada com sucesso!');
        }
        return redirect()->to(base_url('admin/estoque'));
    }

    /**
     * Formulário de Peça (criar/editar)
     */
    public function estoqueForm($id = null)
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $data['peca'] = null;
        if ($id) {
            $data['peca'] = $this->estoqueModel->find($id);
            if (!$data['peca']) {
                $session = session();
                $session->setFlashdata('erro', 'Peça não encontrada.');
                return redirect()->to(base_url('admin/estoque'));
            }
        }

        $data['fornecedores'] = $this->fornecedorModel->orderBy('nome', 'ASC')->findAll();
        $data['title'] = $id ? 'Editar Peça' : 'Nova Peça';
        $data['content'] = view('admin/estoque_form', $data);
        return view('admin/layout', $data);
    }

    /**
     * Visualizar Peça (detalhe + histórico)
     */
    public function estoqueView($id)
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $peca = $this->estoqueModel->find($id);
        if (!$peca) {
            $session = session();
            $session->setFlashdata('erro', 'Peça não encontrada.');
            return redirect()->to(base_url('admin/estoque'));
        }
        $data['peca'] = $peca;
        if (!empty($peca['fornecedor_id'])) {
            $data['fornecedor'] = $this->fornecedorModel->find($peca['fornecedor_id']);
        } else {
            $data['fornecedor'] = null;
        }

        $data['historico'] = $this->estoqueHistoricoModel->buscarPorPeca($id);
        $data['title'] = 'Peça - ' . $data['peca']['nome'];
        $data['content'] = view('admin/estoque_view', $data);
        return view('admin/layout', $data);
    }

    /**
     * Aumentar ou diminuir quantidade (POST)
     */
    public function estoqueAjustar()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $id = $this->request->getPost('id');
        $tipo = $this->request->getPost('tipo'); // 'aumento' ou 'diminuicao'
        $quantidade = (int) $this->request->getPost('quantidade');
        $descricao = $this->request->getPost('descricao');

        if (!$id || !in_array($tipo, ['aumento', 'diminuicao']) || $quantidade <= 0) {
            $session = session();
            $session->setFlashdata('erro', 'Dados inválidos.');
            return redirect()->to(base_url('admin/estoque'));
        }

        $peca = $this->estoqueModel->find($id);
        if (!$peca) {
            $session = session();
            $session->setFlashdata('erro', 'Peça não encontrada.');
            return redirect()->to(base_url('admin/estoque'));
        }

        $qtdAtual = (int) $peca['quantidade'];
        $qtdAnterior = $qtdAtual;

        if ($tipo === 'diminuicao') {
            if ($quantidade > $qtdAtual) {
                $session = session();
                $session->setFlashdata('erro', 'Quantidade a diminuir não pode ser maior que o estoque atual (' . $qtdAtual . ').');
                return redirect()->to(base_url('admin/estoque'));
            }
            $qtdNova = $qtdAtual - $quantidade;
        } else {
            $qtdNova = $qtdAtual + $quantidade;
        }

        $this->estoqueModel->update($id, ['quantidade' => $qtdNova]);

        $this->estoqueHistoricoModel->insert([
            'peca_id' => $id,
            'tipo' => $tipo,
            'quantidade' => $quantidade,
            'quantidade_anterior' => $qtdAnterior,
            'quantidade_nova' => $qtdNova,
            'descricao' => $descricao ?: null,
            'criado_em' => date('Y-m-d H:i:s'),
        ]);

        $session = session();
        $session->setFlashdata('sucesso', $tipo === 'aumento' ? 'Estoque aumentado com sucesso!' : 'Estoque diminuído com sucesso!');
        return redirect()->to(base_url('admin/estoque'));
    }

    /**
     * Agendamentos - Calendário principal (mês ou semana)
     */
    public function agendamentos()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $vista = $this->request->getGet('vista') ?? 'mes';
        $ano = (int) ($this->request->getGet('ano') ?? date('Y'));
        $mes = (int) ($this->request->getGet('mes') ?? date('m'));
        $dia = (int) ($this->request->getGet('dia') ?? date('d'));
        $mes = max(1, min(12, $mes));
        if ($ano < 2020 || $ano > 2030) $ano = date('Y');

        $data['vista'] = $vista;
        $data['ano'] = $ano;
        $data['mes'] = $mes;
        $data['dia'] = $dia;

        if ($vista === 'semana') {
            $dataRef = sprintf('%04d-%02d-%02d', $ano, $mes, min($dia, date('t', strtotime("{$ano}-{$mes}-01"))));
            $ts = strtotime($dataRef);
            $dow = (int) date('w', $ts); // 0=Dom..6=Sab
            $domingo = strtotime("-{$dow} days", $ts);
            $sabado = strtotime('+6 days', $domingo);
            $data['semanaInicio'] = date('Y-m-d', $domingo);
            $data['semanaFim'] = date('Y-m-d', $sabado);
            $data['diasSemana'] = [];
            for ($i = 0; $i < 7; $i++) {
                $d = strtotime("+{$i} days", $domingo);
                $data['diasSemana'][] = [
                    'data' => date('Y-m-d', $d),
                    'dia' => (int) date('d', $d),
                    'mes' => (int) date('m', $d),
                    'nome' => ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'][$i],
                ];
            }
            $db = \Config\Database::connect();
            $agendamentos = $db->table('agendamentos')
                ->select('agendamentos.*')
                ->where('data >=', $data['semanaInicio'])
                ->where('data <=', $data['semanaFim'])
                ->whereIn('status', ['agendado', 'concluido'])
                ->orderBy('data', 'ASC')
                ->orderBy('hora_inicio', 'ASC')
                ->get()
                ->getResultArray();
            $porDataHora = [];
            foreach ($agendamentos as $a) {
                $cliente = $db->table('clientes')->select('nome_completo')->where('id', $a['cliente_id'])->get()->getRowArray();
                $a['cliente_nome'] = $cliente['nome_completo'] ?? '-';
                $raw = $a['data'] ?? '';
                $ts = strtotime((string)$raw);
                $dataKey = $ts ? date('Y-m-d', $ts) : substr((string)$raw, 0, 10);
                if (!isset($porDataHora[$dataKey])) $porDataHora[$dataKey] = [];
                $porDataHora[$dataKey][] = $a;
            }
            $data['agendamentosPorDia'] = $porDataHora;
            $data['tituloSemana'] = date('d/m', $domingo) . ' – ' . date('d/m/Y', $sabado);
            $mesesPT = ['', 'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
            $data['tituloMes'] = 'Semana de ' . date('d', $domingo) . ' a ' . date('d', $sabado) . ' de ' . $mesesPT[(int) date('m', $domingo)] . ' ' . date('Y', $domingo);
        } else {
            $data['contagemPorDia'] = $this->agendamentoModel->contarPorDiaNoMes($ano, $mes);
            $mesesPT = ['', 'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
            $data['tituloMes'] = ucfirst($mesesPT[$mes]) . ' ' . $ano;
        }

        $data['novo'] = (bool) $this->request->getGet('novo');
        $data['dataInicial'] = $this->request->getGet('data'); // para auto-abrir modal ao vir da listagem
        if ($data['novo']) {
            $data['clientes'] = $this->clienteModel->where('deletado', 0)->where('bloqueado', 0)->orderBy('nome_completo', 'ASC')->findAll();
            $data['slots'] = AgendamentoModel::getSlotsHorario();
        } else {
            $data['clientes'] = [];
            $data['slots'] = AgendamentoModel::getSlotsHorario();
        }

        $data['title'] = 'Agendamentos';
        $data['content'] = view('admin/agendamentos', $data);
        return view('admin/layout', $data);
    }

    /**
     * API: slots ocupados no dia (JSON)
     */
    public function agendamentosSlotsOcupados($dataStr)
    {
        if ($this->verificarLogin()) {
            return $this->response->setJSON(['success' => false]);
        }
        $ocupados = $this->agendamentoModel->getSlotsOcupados($dataStr);
        return $this->response->setJSON(['success' => true, 'ocupados' => $ocupados]);
    }

    /**
     * Agendamentos do dia (JSON para modal)
     */
    public function agendamentosDia($dataStr)
    {
        if ($this->verificarLogin()) {
            return $this->response->setJSON(['success' => false]);
        }

        $agendamentos = $this->agendamentoModel->buscarPorDia($dataStr);
        $db = \Config\Database::connect();
        $comCliente = [];
        foreach ($agendamentos as $a) {
            $cliente = $db->table('clientes')->select('nome_completo, celular')->where('id', $a['cliente_id'])->get()->getRowArray();
            $a['cliente_nome'] = $cliente['nome_completo'] ?? '-';
            $a['cliente_celular'] = $cliente['celular'] ?? '';
            $comCliente[] = $a;
        }
        return $this->response->setJSON(['success' => true, 'agendamentos' => $comCliente, 'data' => $dataStr]);
    }

    /**
     * Formulário novo/editar agendamento
     */
    public function agendamentoForm($id = null)
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $data['agendamento'] = null;
        $data['dataPreenchida'] = $this->request->getGet('data') ?? date('Y-m-d');
        $data['horaPreenchida'] = $this->request->getGet('hora') ?? null;

        if ($id) {
            $data['agendamento'] = $this->agendamentoModel->find($id);
            if (!$data['agendamento']) {
                session()->setFlashdata('erro', 'Agendamento não encontrado.');
                return redirect()->to(base_url('admin/agendamentos'));
            }
            $data['dataPreenchida'] = $data['agendamento']['data'];
        }

        $data['clientes'] = $this->clienteModel->where('deletado', 0)->where('bloqueado', 0)->orderBy('nome_completo', 'ASC')->findAll();
        $data['slots'] = AgendamentoModel::getSlotsHorario();
        $data['title'] = $id ? 'Editar Agendamento' : 'Novo Agendamento';
        $data['content'] = view('admin/agendamento_form', $data);
        return view('admin/layout', $data);
    }

    /**
     * Salvar agendamento
     */
    public function agendamentoSalvar()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $id = $this->request->getPost('id');
        $dataAgend = $this->request->getPost('data');
        $horaInicio = $this->request->getPost('hora_inicio');
        $horaFim = $this->request->getPost('hora_fim') ?: date('H:i', strtotime($horaInicio . ' +1 hour'));

        if ($this->agendamentoModel->temConflito($dataAgend, $horaInicio, $horaFim, $id ? (int) $id : null)) {
            session()->setFlashdata('erro', 'Este horário já está ocupado. Escolha outro.');
            return redirect()->back()->withInput();
        }

        $dados = [
            'cliente_id'     => (int) $this->request->getPost('cliente_id'),
            'responsavel_id' => (int) ($this->request->getPost('responsavel_id') ?: 1),
            'data'           => $dataAgend,
            'hora_inicio'    => $horaInicio,
            'hora_fim'       => $horaFim,
            'descricao'      => $this->request->getPost('descricao'),
            'status'         => $this->request->getPost('status') ?: 'agendado',
            'observacoes'    => $this->request->getPost('observacoes') ?: null,
        ];

        $session = session();
        if ($id) {
            $this->agendamentoModel->update($id, $dados);
            $session->setFlashdata('sucesso', 'Agendamento atualizado!');
        } else {
            $this->agendamentoModel->insert($dados);
            $session->setFlashdata('sucesso', 'Agendamento criado!');
        }

        // Notificação ao responsável apenas na criação
        if (!$id) {
            helper('email_helper');
            $responsavel = $this->funcionarioModel->find($dados['responsavel_id']);
            if ($responsavel && !empty($responsavel['email'])) {
                $cliente = $this->clienteModel->find($dados['cliente_id']);
                $clienteNome = $cliente['nome_completo'] ?? $cliente['nome'] ?? '';
                send_email_agendamento('criado', $responsavel['email'], $dados, $clienteNome);
            }
        }

        return redirect()->to(base_url('admin/agendamentos'));
    }

    /**
     * API: verificar conflito de horário (JSON)
     */
    public function agendamentoVerificarConflito()
    {
        if ($this->verificarLogin()) {
            return $this->response->setJSON(['success' => false]);
        }

        $data = $this->request->getGet('data');
        $horaInicio = $this->request->getGet('hora_inicio');
        $horaFim = $this->request->getGet('hora_fim');
        $id = $this->request->getGet('id') ? (int) $this->request->getGet('id') : null;

        if (!$data || !$horaInicio) {
            return $this->response->setJSON(['success' => true, 'conflito' => false]);
        }

        $conflito = $this->agendamentoModel->temConflito($data, $horaInicio, $horaFim ?? '', $id);
        return $this->response->setJSON(['success' => true, 'conflito' => $conflito]);
    }

    /**
     * Cancelar agendamento (status)
     */
    public function agendamentoCancelar()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $id = $this->request->getPost('id');
        if ($id) {
            $this->agendamentoModel->update($id, ['status' => 'cancelado']);
            session()->setFlashdata('sucesso', 'Agendamento cancelado.');

            // Notificação ao responsável
            helper('email_helper');
            $agendamento = $this->agendamentoModel->find($id);
            if ($agendamento) {
                $responsavel = $this->funcionarioModel->find($agendamento['responsavel_id'] ?? 1);
                if ($responsavel && !empty($responsavel['email'])) {
                    $cliente = $this->clienteModel->find($agendamento['cliente_id']);
                    $clienteNome = $cliente['nome_completo'] ?? $cliente['nome'] ?? '';
                    send_email_agendamento('cancelado', $responsavel['email'], $agendamento, $clienteNome);
                }
            }
        }
        return redirect()->back();
    }

    /**
     * Marcar como concluído
     */
    public function agendamentoConcluir()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $id = $this->request->getPost('id');
        if ($id) {
            $this->agendamentoModel->update($id, ['status' => 'concluido']);
            session()->setFlashdata('sucesso', 'Agendamento marcado como concluído.');

            // Notificação ao responsável
            helper('email_helper');
            $agendamento = $this->agendamentoModel->find($id);
            if ($agendamento) {
                $responsavel = $this->funcionarioModel->find($agendamento['responsavel_id'] ?? 1);
                if ($responsavel && !empty($responsavel['email'])) {
                    $cliente = $this->clienteModel->find($agendamento['cliente_id']);
                    $clienteNome = $cliente['nome_completo'] ?? $cliente['nome'] ?? '';
                    send_email_agendamento('concluido', $responsavel['email'], $agendamento, $clienteNome);
                }
            }
        }
        return redirect()->back();
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

    public function colaboradores()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        if ($this->request->getMethod() === 'post' && $this->request->getPost('acao') === 'excluir') {
            $id = (int) $this->request->getPost('id');
            $this->funcionarioModel->update($id, ['deletado' => 1]);
            session()->setFlashdata('sucesso', 'Colaborador removido com sucesso!');
            return redirect()->to(base_url('admin/colaboradores'));
        }

        $filtroNome  = $this->request->getGet('filtro_nome') ?? '';
        $filtroNivel = $this->request->getGet('filtro_nivel') ?? '';

        $query = $this->funcionarioModel->where('deletado', 0);

        if ($filtroNome) {
            $query->like('nome', $filtroNome);
        }
        if ($filtroNivel) {
            $query->where('nivel', $filtroNivel);
        }

        $data['colaboradores'] = $query->orderBy('nome', 'ASC')->findAll();
        $data['filtroNome']    = $filtroNome;
        $data['filtroNivel']   = $filtroNivel;
        $data['title']         = 'Gestão de Colaboradores';
        $data['content']       = view('admin/colaboradores', $data);
        return view('admin/layout', $data);
    }

    public function colaboradorForm($id = null)
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $data['colaborador'] = null;
        if ($id) {
            $data['colaborador'] = $this->funcionarioModel->find($id);
            if (!$data['colaborador'] || $data['colaborador']['deletado']) {
                session()->setFlashdata('erro', 'Colaborador não encontrado.');
                return redirect()->to(base_url('admin/colaboradores'));
            }
        }

        $data['title']   = $id ? 'Editar Colaborador' : 'Novo Colaborador';
        $data['content'] = view('admin/colaborador_form', $data);
        return view('admin/layout', $data);
    }

    public function colaboradorSalvar()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        $id = (int) $this->request->getPost('id');

        $dados = [
            'nome'                 => $this->request->getPost('nome'),
            'email'                => $this->request->getPost('email') ?: null,
            'telefone'             => $this->request->getPost('telefone') ?: null,
            'nivel'                => $this->request->getPost('nivel'),
            'data_inicio_contrato' => $this->request->getPost('data_inicio_contrato') ?: null,
            'data_fim_contrato'    => $this->request->getPost('data_fim_contrato') ?: null,
            'bloqueado'            => $this->request->getPost('bloqueado') ? 1 : 0,
        ];

        $session = session();
        if ($id) {
            $this->funcionarioModel->update($id, $dados);
            $session->setFlashdata('sucesso', 'Colaborador atualizado com sucesso!');
        } else {
            $this->funcionarioModel->insert($dados);
            $session->setFlashdata('sucesso', 'Colaborador criado com sucesso!');
        }

        return redirect()->to(base_url('admin/colaboradores'));
    }

    /**
     * Teste do lembrete diário — dispara os e-mails de agendamentos de hoje.
     * Acessível apenas para admin logado.
     */
    public function testarLembretes()
    {
        if ($this->verificarLogin()) {
            return redirect()->to(base_url('admin/login'));
        }

        helper('email_helper');

        $clienteModel = new \App\Models\ClienteModel();
        $hoje         = date('Y-m-d');

        $agendamentos = $this->agendamentoModel
            ->where('data', $hoje)
            ->where('status', 'agendado')
            ->orderBy('hora_inicio', 'ASC')
            ->findAll();

        $sep = str_repeat('=', 53);

        if (empty($agendamentos)) {
            $corpo = implode("\n", [
                $sep,
                '[' . date('Y-m-d H:i:s') . '] Nenhum agendamento para hoje (' . date('d/m/Y') . ')',
                $sep,
            ]);
            $this->salvarLogLembrete($hoje, $corpo);
            return $this->response
                ->setHeader('Content-Type', 'text/plain; charset=utf-8')
                ->setBody($corpo);
        }

        // Agrupar por responsável
        $porResponsavel = [];
        foreach ($agendamentos as $ag) {
            $rid = $ag['responsavel_id'] ?? 1;
            $porResponsavel[$rid][] = $ag;
        }

        $linhas = [
            $sep,
            '[' . date('Y-m-d H:i:s') . '] Data: ' . date('d/m/Y') . ' | ' . count($agendamentos) . ' agendamento(s)',
            '',
        ];

        foreach ($porResponsavel as $responsavelId => $lista) {
            $responsavel = $this->funcionarioModel->find($responsavelId);

            if (!$responsavel || empty($responsavel['email'])) {
                $linhas[] = "Responsável ID {$responsavelId}: sem e-mail, ignorado.";
                continue;
            }

            $itens = [];
            foreach ($lista as $ag) {
                $cliente = $clienteModel->find($ag['cliente_id']);
                $itens[] = [
                    'hora_inicio'  => $ag['hora_inicio'],
                    'hora_fim'     => $ag['hora_fim'] ?? '',
                    'descricao'    => $ag['descricao'],
                    'observacoes'  => $ag['observacoes'] ?? '',
                    'cliente_nome' => $cliente['nome_completo'] ?? 'N/A',
                    'endereco'     => $cliente['endereco'] ?? '',
                    'cidade'       => $cliente['cidade'] ?? '',
                ];
            }

            $enviado = send_email_lembrete_diario(
                $responsavel['email'],
                $responsavel['nome'],
                $itens,
                $hoje
            );

            $status   = $enviado ? '✓ ENVIADO' : '✗ FALHOU';
            $linhas[] = "{$status} — {$responsavel['nome']} <{$responsavel['email']}> (" . count($itens) . " agendamento(s))";
        }

        $linhas[] = $sep;

        $corpo = implode("\n", $linhas);
        $this->salvarLogLembrete($hoje, $corpo);

        return $this->response
            ->setHeader('Content-Type', 'text/plain; charset=utf-8')
            ->setBody($corpo);
    }

    private function salvarLogLembrete(string $data, string $conteudo): void
    {
        $dir = WRITEPATH . 'logs/agendamentos/';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        file_put_contents(
            $dir . 'lembrete_' . $data . '.log',
            $conteudo . PHP_EOL . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }
}
