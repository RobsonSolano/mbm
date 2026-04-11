# Colaboradores + Notificações de Agendamento — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Criar gestão completa de colaboradores (CRUD com sidebar), vincular `responsavel_id` aos agendamentos e enviar e-mails de notificação ao responsável ao criar, cancelar e concluir agendamentos.

**Architecture:** FuncionarioModel + CRUD no Admin controller seguindo o padrão existente (Fornecedores). O `responsavel_id` é adicionado à tabela `agendamentos` sem campo no formulário por enquanto (fixo em 1). Notificações usam o `email_helper.php` existente com nova função `send_email_agendamento()`.

**Tech Stack:** CodeIgniter 4, PHP 8, Bootstrap 5, PHPMailer (via email_helper existente), MySQL

---

## Files

**Criar:**
- `database/colaboradores_responsavel.sql` — script SQL para execução manual
- `app/Database/Migrations/2026-04-11-100000_Create_funcionarios_add_responsavel.php` — migration CI4
- `app/Models/FuncionarioModel.php` — model de colaboradores
- `app/Views/admin/colaboradores.php` — listagem com filtros
- `app/Views/admin/colaborador_form.php` — formulário criar/editar
- `app/Views/parts/email/email_agendamento.php` — template HTML do e-mail

**Modificar:**
- `app/Config/Routes.php` — adicionar rotas de colaboradores
- `app/Controllers/Admin.php` — property $funcionarioModel + 3 métodos CRUD + atualizar agendamentoSalvar/Cancelar/Concluir
- `app/Models/AgendamentoModel.php` — adicionar `responsavel_id` em allowedFields
- `app/Helpers/email_helper.php` — adicionar `send_email_agendamento()`
- `app/Views/admin/layout.php` — adicionar link Colaboradores na sidebar

---

## Task 1: SQL File + Migration

**Files:**
- Create: `database/colaboradores_responsavel.sql`
- Create: `app/Database/Migrations/2026-04-11-100000_Create_funcionarios_add_responsavel.php`

- [ ] **Step 1: Criar arquivo SQL para execução manual**

Criar `database/colaboradores_responsavel.sql`:

```sql
-- ============================================================
-- MBM - Gestão de Colaboradores + Responsável no Agendamento
-- Executar manualmente via phpMyAdmin ou MySQL client
-- ============================================================

-- 1. Tabela funcionarios
CREATE TABLE IF NOT EXISTS `funcionarios` (
  `id`                   INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome`                 VARCHAR(255) NOT NULL,
  `email`                VARCHAR(255) NULL DEFAULT NULL,
  `telefone`             VARCHAR(20)  NULL DEFAULT NULL,
  `nivel`                ENUM('dono','lider','tecnico','assistente') NOT NULL DEFAULT 'assistente',
  `data_inicio_contrato` DATE NOT NULL DEFAULT (CURRENT_DATE),
  `data_fim_contrato`    DATE NULL DEFAULT NULL,
  `bloqueado`            TINYINT(1) NOT NULL DEFAULT 0,
  `deletado`             TINYINT(1) NOT NULL DEFAULT 0,
  `criado_em`            DATETIME NULL DEFAULT NULL,
  `atualizado_em`        DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Primeiro colaborador (dono)
INSERT INTO `funcionarios` (`id`, `nome`, `email`, `nivel`, `data_inicio_contrato`, `criado_em`, `atualizado_em`)
VALUES (1, 'Maicon José', 'climatizacaombm@gmail.com', 'dono', '2024-01-01', NOW(), NOW())
ON DUPLICATE KEY UPDATE `nome` = VALUES(`nome`);

-- 3. Adicionar responsavel_id na tabela agendamentos
ALTER TABLE `agendamentos`
  ADD COLUMN `responsavel_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `cliente_id`,
  ADD CONSTRAINT `agendamentos_responsavel_fk`
    FOREIGN KEY (`responsavel_id`) REFERENCES `funcionarios` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE;

-- 4. Vincular agendamentos existentes ao responsavel_id = 1
UPDATE `agendamentos` SET `responsavel_id` = 1 WHERE `responsavel_id` IS NULL;
```

- [ ] **Step 2: Criar migration CI4**

Criar `app/Database/Migrations/2026-04-11-100000_Create_funcionarios_add_responsavel.php`:

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Create_funcionarios_add_responsavel extends Migration
{
    public function up(): void
    {
        // Tabela funcionarios
        $this->forge->addField([
            'id'                   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nome'                 => ['type' => 'VARCHAR', 'constraint' => 255],
            'email'                => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'telefone'             => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'nivel'                => ['type' => 'ENUM', 'constraint' => ['dono','lider','tecnico','assistente'], 'default' => 'assistente'],
            'data_inicio_contrato' => ['type' => 'DATE', 'default' => date('Y-m-d')],
            'data_fim_contrato'    => ['type' => 'DATE', 'null' => true],
            'bloqueado'            => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'deletado'             => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'criado_em'            => ['type' => 'DATETIME', 'null' => true],
            'atualizado_em'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('funcionarios', true);

        // Seed: primeiro colaborador
        $this->db->table('funcionarios')->insert([
            'id'                   => 1,
            'nome'                 => 'Maicon José',
            'email'                => 'climatizacaombm@gmail.com',
            'nivel'                => 'dono',
            'data_inicio_contrato' => '2024-01-01',
            'criado_em'            => date('Y-m-d H:i:s'),
            'atualizado_em'        => date('Y-m-d H:i:s'),
        ]);

        // Adicionar responsavel_id em agendamentos
        $this->forge->addColumn('agendamentos', [
            'responsavel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'cliente_id',
            ],
        ]);

        $prefix = $this->db->getPrefix() ?: '';
        $this->db->query(
            "ALTER TABLE `{$prefix}agendamentos`
             ADD CONSTRAINT `agendamentos_responsavel_fk`
             FOREIGN KEY (`responsavel_id`) REFERENCES `{$prefix}funcionarios` (`id`)
             ON DELETE SET NULL ON UPDATE CASCADE"
        );

        // Vincular existentes ao id 1
        $this->db->query("UPDATE `{$prefix}agendamentos` SET `responsavel_id` = 1 WHERE `responsavel_id` IS NULL");
    }

    public function down(): void
    {
        $prefix = $this->db->getPrefix() ?: '';
        $this->db->query("ALTER TABLE `{$prefix}agendamentos` DROP FOREIGN KEY `agendamentos_responsavel_fk`");
        $this->forge->dropColumn('agendamentos', 'responsavel_id');
        $this->forge->dropTable('funcionarios', true);
    }
}
```

---

## Task 2: FuncionarioModel

**Files:**
- Create: `app/Models/FuncionarioModel.php`

- [ ] **Step 1: Criar o model**

```php
<?php

namespace App\Models;

use CodeIgniter\Model;

class FuncionarioModel extends Model
{
    protected $table            = 'funcionarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nome', 'email', 'telefone', 'nivel',
        'data_inicio_contrato', 'data_fim_contrato',
        'bloqueado', 'deletado',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';

    protected $validationRules = [
        'nome'  => 'required|min_length[2]',
        'nivel' => 'required|in_list[dono,lider,tecnico,assistente]',
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Retorna colaboradores ativos (não bloqueados e não deletados).
     */
    public function buscarAtivos(): array
    {
        return $this->where('deletado', 0)
                    ->where('bloqueado', 0)
                    ->where('data_fim_contrato IS NULL OR data_fim_contrato >=', date('Y-m-d'))
                    ->orderBy('nome', 'ASC')
                    ->findAll();
    }

    /**
     * Rótulo legível para o nível.
     */
    public static function labelNivel(string $nivel): string
    {
        return match($nivel) {
            'dono'       => 'Dono',
            'lider'      => 'Líder',
            'tecnico'    => 'Técnico',
            'assistente' => 'Assistente',
            default      => $nivel,
        };
    }
}
```

---

## Task 3: Rotas de Colaboradores

**Files:**
- Modify: `app/Config/Routes.php`

- [ ] **Step 1: Adicionar rotas após o bloco de Estoque**

Localizar o comentário `// Perfil do Admin` e inserir antes dele:

```php
// Colaboradores
$routes->get('/admin/colaboradores', 'Admin::colaboradores');
$routes->post('/admin/colaboradores', 'Admin::colaboradores');
$routes->get('/admin/colaborador/novo', 'Admin::colaboradorForm');
$routes->get('/admin/colaborador/(:num)/editar', 'Admin::colaboradorForm/$1');
$routes->post('/admin/colaboradores/salvar', 'Admin::colaboradorSalvar');
```

---

## Task 4: Controller — Property + Métodos CRUD de Colaboradores

**Files:**
- Modify: `app/Controllers/Admin.php`

- [ ] **Step 1: Adicionar property $funcionarioModel**

No topo da classe Admin, junto às outras properties de model (onde estão `$agendamentoModel`, `$fornecedorModel`, etc.), adicionar:

```php
protected $funcionarioModel;
```

- [ ] **Step 2: Instanciar no __construct ou initController**

Localizar onde os outros models são instanciados (ex: `$this->fornecedorModel = new \App\Models\FornecedorModel();`) e adicionar:

```php
$this->funcionarioModel = new \App\Models\FuncionarioModel();
```

- [ ] **Step 3: Adicionar método colaboradores() (listagem + soft-delete)**

```php
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
```

- [ ] **Step 4: Adicionar método colaboradorForm()**

```php
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
```

- [ ] **Step 5: Adicionar método colaboradorSalvar()**

```php
public function colaboradorSalvar()
{
    if ($this->verificarLogin()) {
        return redirect()->to(base_url('admin/login'));
    }

    $id = $this->request->getPost('id');

    $dados = [
        'nome'                 => $this->request->getPost('nome'),
        'email'                => $this->request->getPost('email') ?: null,
        'telefone'             => $this->request->getPost('telefone') ?: null,
        'nivel'                => $this->request->getPost('nivel'),
        'data_inicio_contrato' => $this->request->getPost('data_inicio_contrato') ?: date('Y-m-d'),
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
```

---

## Task 5: Views — Listagem e Formulário

**Files:**
- Create: `app/Views/admin/colaboradores.php`
- Create: `app/Views/admin/colaborador_form.php`

- [ ] **Step 1: Criar app/Views/admin/colaboradores.php**

```php
<div class="row mb-3">
    <div class="col-md-4">
        <a href="<?php echo base_url('admin/colaborador/novo') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Novo Colaborador
        </a>
    </div>
    <div class="col-md-8">
        <form method="get" class="row g-2">
            <div class="col-md-7">
                <input type="text" name="filtro_nome" class="form-control"
                       placeholder="Filtrar por nome..."
                       value="<?php echo esc($filtroNome ?? '') ?>">
            </div>
            <div class="col-md-3">
                <select name="filtro_nivel" class="form-select">
                    <option value="">Todos os níveis</option>
                    <?php foreach (['dono' => 'Dono', 'lider' => 'Líder', 'tecnico' => 'Técnico', 'assistente' => 'Assistente'] as $val => $label): ?>
                        <option value="<?php echo $val ?>" <?php echo ($filtroNivel ?? '') === $val ? 'selected' : '' ?>>
                            <?php echo $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100" title="Buscar">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <?php if (!empty($filtroNome) || !empty($filtroNivel)): ?>
            <div class="col-12 mt-1">
                <a href="<?php echo base_url('admin/colaboradores') ?>" class="btn btn-sm btn-secondary">
                    <i class="fas fa-times me-1"></i> Limpar Filtros
                </a>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Nível</th>
                        <th>Início</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colaboradores)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">Nenhum colaborador cadastrado</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($colaboradores as $c): ?>
                            <tr>
                                <td>#<?php echo $c['id'] ?></td>
                                <td><?php echo esc($c['nome']) ?></td>
                                <td><?php echo esc($c['email'] ?? '-') ?></td>
                                <td><?php echo esc($c['telefone'] ?? '-') ?></td>
                                <td>
                                    <?php
                                    $nivelBadge = [
                                        'dono'       => 'danger',
                                        'lider'      => 'warning',
                                        'tecnico'    => 'info',
                                        'assistente' => 'secondary',
                                    ];
                                    $cor = $nivelBadge[$c['nivel']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $cor ?>">
                                        <?php echo \App\Models\FuncionarioModel::labelNivel($c['nivel']) ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($c['data_inicio_contrato'])) ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $c['bloqueado'] ? 'secondary' : 'success' ?>">
                                        <?php echo $c['bloqueado'] ? 'Bloqueado' : 'Ativo' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('admin/colaborador/' . $c['id'] . '/editar') ?>"
                                       class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($c['id'] != 1): ?>
                                    <button type="button" class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDeletar<?php echo $c['id'] ?>"
                                            title="Remover">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <?php if ($c['id'] != 1): ?>
                            <div class="modal fade" id="modalDeletar<?php echo $c['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h4 class="modal-title">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Remover colaborador?
                                            </h4>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mb-0">
                                                Deseja remover <strong><?php echo esc($c['nome']) ?></strong>?
                                                Esta ação não poderá ser desfeita.
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1"></i> Cancelar
                                            </button>
                                            <form method="post" action="<?php echo base_url('admin/colaboradores') ?>" class="d-inline">
                                                <input type="hidden" name="acao" value="excluir">
                                                <input type="hidden" name="id" value="<?php echo $c['id'] ?>">
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash me-1"></i> Confirmar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
```

- [ ] **Step 2: Criar app/Views/admin/colaborador_form.php**

```php
<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('admin/colaboradores') ?>">Colaboradores</a></li>
                <li class="breadcrumb-item active"><?php echo isset($colaborador) ? 'Editar' : 'Novo Colaborador' ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-tie me-2"></i>
                    <?php echo isset($colaborador) ? 'Editar Colaborador' : 'Novo Colaborador' ?>
                </h5>
            </div>
            <div class="card-body">
                <?php echo form_open(base_url('admin/colaboradores/salvar')) ?>
                    <?php if (isset($colaborador)): ?>
                        <input type="hidden" name="id" value="<?php echo $colaborador['id'] ?>">
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="nome" class="form-label">Nome *</label>
                            <input type="text" class="form-control" id="nome" name="nome" required
                                   value="<?php echo esc($colaborador['nome'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nivel" class="form-label">Nível *</label>
                            <select class="form-select" id="nivel" name="nivel" required>
                                <?php foreach (['dono' => 'Dono', 'lider' => 'Líder', 'tecnico' => 'Técnico', 'assistente' => 'Assistente'] as $val => $label): ?>
                                    <option value="<?php echo $val ?>"
                                        <?php echo ($colaborador['nivel'] ?? '') === $val ? 'selected' : '' ?>>
                                        <?php echo $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">E-mail <small class="text-muted">(necessário para notificações)</small></label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?php echo esc($colaborador['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone"
                                   placeholder="(11) 99999-9999"
                                   maxlength="15"
                                   value="<?php echo esc($colaborador['telefone'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="data_inicio_contrato" class="form-label">Início do Contrato</label>
                            <input type="date" class="form-control" id="data_inicio_contrato" name="data_inicio_contrato"
                                   value="<?php echo esc($colaborador['data_inicio_contrato'] ?? date('Y-m-d')) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="data_fim_contrato" class="form-label">Fim do Contrato <small class="text-muted">(se desligado)</small></label>
                            <input type="date" class="form-control" id="data_fim_contrato" name="data_fim_contrato"
                                   value="<?php echo esc($colaborador['data_fim_contrato'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="bloqueado" name="bloqueado"
                                   <?php echo (!empty($colaborador['bloqueado'])) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="bloqueado">Bloqueado (não recebe notificações nem aparece em seleções)</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo base_url('admin/colaboradores') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Salvar Colaborador
                        </button>
                    </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var tel = document.getElementById('telefone');
    if (!tel) return;
    function mascara(v) {
        v = v.replace(/\D/g, '');
        if (v.length > 11) v = v.substring(0, 11);
        if (v.length <= 10) {
            v = v.replace(/^(\d{2})(\d{4})(\d{0,4})$/, function(m, ddd, p1, p2) {
                return p2 ? '(' + ddd + ') ' + p1 + '-' + p2 : '(' + ddd + ') ' + p1;
            });
        } else {
            v = v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
        }
        return v;
    }
    tel.addEventListener('input', function() { this.value = mascara(this.value); });
    if (tel.value) tel.value = mascara(tel.value);
});
</script>
```

---

## Task 6: Sidebar — Link Colaboradores

**Files:**
- Modify: `app/Views/admin/layout.php`

- [ ] **Step 1: Adicionar link Colaboradores na seção Cadastros**

Localizar o bloco `<!-- Cadastros -->` na sidebar e adicionar após o link de Fornecedores (antes de Marcas):

```php
<li class="nav-item">
    <a class="nav-link <?php echo (strpos(uri_string(), 'admin/colaborador') !== false) ? 'active' : '' ?>" href="<?php echo base_url('admin/colaboradores') ?>">
        <i class="fas fa-user-tie me-2"></i> Colaboradores
    </a>
</li>
```

---

## Task 7: AgendamentoModel — responsavel_id

**Files:**
- Modify: `app/Models/AgendamentoModel.php`

- [ ] **Step 1: Adicionar responsavel_id em allowedFields**

Localizar:
```php
protected $allowedFields    = [
    'cliente_id', 'data', 'hora_inicio', 'hora_fim',
    'descricao', 'status', 'observacoes'
];
```

Substituir por:
```php
protected $allowedFields    = [
    'cliente_id', 'responsavel_id', 'data', 'hora_inicio', 'hora_fim',
    'descricao', 'status', 'observacoes'
];
```

---

## Task 8: agendamentoSalvar — vincular responsavel_id = 1

**Files:**
- Modify: `app/Controllers/Admin.php`

- [ ] **Step 1: Adicionar responsavel_id ao array $dados em agendamentoSalvar()**

Localizar em `agendamentoSalvar()`:
```php
$dados = [
    'cliente_id'   => (int) $this->request->getPost('cliente_id'),
    'data'         => $dataAgend,
    'hora_inicio'  => $horaInicio,
    'hora_fim'     => $horaFim,
    'descricao'    => $this->request->getPost('descricao'),
    'status'       => $this->request->getPost('status') ?: 'agendado',
    'observacoes'  => $this->request->getPost('observacoes') ?: null,
];
```

Substituir por:
```php
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
```

---

## Task 9: Email — Template + Função Helper

**Files:**
- Create: `app/Views/parts/email/email_agendamento.php`
- Modify: `app/Helpers/email_helper.php`

- [ ] **Step 1: Criar template app/Views/parts/email/email_agendamento.php**

```php
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
  .wrapper { max-width: 560px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
  .header { padding: 20px 30px; color: #fff; }
  .header.criado    { background: #0d6efd; }
  .header.cancelado { background: #dc3545; }
  .header.concluido { background: #198754; }
  .header h2 { margin: 0; font-size: 1.2rem; }
  .body { padding: 24px 30px; }
  .row { display: flex; margin-bottom: 12px; }
  .label { color: #888; font-size: .8rem; width: 130px; flex-shrink: 0; padding-top: 2px; }
  .value { font-weight: bold; color: #333; }
  .obs  { background: #f8f9fa; border-left: 3px solid #dee2e6; padding: 10px 14px; border-radius: 4px; margin-top: 8px; font-size: .9rem; color: #555; }
  .footer { background: #f8f9fa; padding: 14px 30px; font-size: .75rem; color: #999; text-align: center; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header <?php echo $tipo ?>">
    <h2>
      <?php if ($tipo === 'criado'):    ?>Novo agendamento criado
      <?php elseif ($tipo === 'cancelado'): ?>Agendamento cancelado
      <?php else: ?>Atendimento concluído
      <?php endif; ?>
    </h2>
  </div>
  <div class="body">
    <div class="row">
      <span class="label">Cliente</span>
      <span class="value"><?php echo esc($cliente_nome) ?></span>
    </div>
    <div class="row">
      <span class="label">Data</span>
      <span class="value"><?php echo date('d/m/Y', strtotime($data)) ?></span>
    </div>
    <div class="row">
      <span class="label">Horário</span>
      <span class="value"><?php echo date('H:i', strtotime($hora_inicio)) ?>
        <?php if (!empty($hora_fim)): ?> – <?php echo date('H:i', strtotime($hora_fim)) ?><?php endif; ?>
      </span>
    </div>
    <div class="row">
      <span class="label">Serviço</span>
      <span class="value"><?php echo esc($descricao) ?></span>
    </div>
    <?php if (!empty($observacoes)): ?>
    <div class="obs"><?php echo nl2br(esc($observacoes)) ?></div>
    <?php endif; ?>
  </div>
  <div class="footer">MBM Climatização — notificação automática, não responda este e-mail.</div>
</div>
</body>
</html>
```

- [ ] **Step 2: Adicionar send_email_agendamento() em app/Helpers/email_helper.php**

Ao final do arquivo, após o fechamento do `if (!function_exists('send_email_contato'))`, adicionar:

```php
if (!function_exists('send_email_agendamento')) {
    /**
     * Envia e-mail de notificação de agendamento ao responsável.
     *
     * @param string $tipo       'criado' | 'cancelado' | 'concluido'
     * @param string $destinatario E-mail do responsável
     * @param array  $agendamento  Campos: data, hora_inicio, hora_fim, descricao, observacoes
     * @param string $clienteNome  Nome do cliente
     */
    function send_email_agendamento(string $tipo, string $destinatario, array $agendamento, string $clienteNome): bool
    {
        if (ENVIRONMENT === 'development') {
            $config = [
                'protocol'   => 'smtp',
                'SMTPHost'   => 'mailhog',
                'SMTPPort'   => 1025,
                'SMTPUser'   => '',
                'SMTPPass'   => '',
                'SMTPCrypto' => '',
                'SMTPAuth'   => false,
                'mailType'   => 'html',
                'charset'    => 'UTF-8',
                'wordWrap'   => true,
                'SMTPTimeout' => 60,
                'newline'    => "\r\n",
                'CRLF'       => "\r\n",
            ];
        } else {
            $config = [
                'protocol'   => 'smtp',
                'SMTPHost'   => 'smtp.gmail.com',
                'SMTPPort'   => 587,
                'SMTPUser'   => 'climatizacaombm@gmail.com',
                'SMTPPass'   => 'zzou ofwe jikm jfpo',
                'SMTPCrypto' => 'tls',
                'SMTPAuth'   => true,
                'mailType'   => 'html',
                'charset'    => 'UTF-8',
                'wordWrap'   => true,
                'SMTPTimeout' => 60,
                'newline'    => "\r\n",
                'CRLF'       => "\r\n",
            ];
        }

        $assuntos = [
            'criado'    => 'Novo agendamento: ' . date('d/m/Y', strtotime($agendamento['data'])),
            'cancelado' => 'Agendamento cancelado: ' . date('d/m/Y', strtotime($agendamento['data'])),
            'concluido' => 'Atendimento concluído: ' . date('d/m/Y', strtotime($agendamento['data'])),
        ];

        $fromEmail = env('email.fromEmail', 'nao-responder@mbmclimatizacao.kesug.com');

        $email = \Config\Services::email($config);
        $email->setFrom($fromEmail, 'MBM Climatização');
        $email->setTo($destinatario);
        $email->setSubject($assuntos[$tipo] ?? 'Notificação de agendamento');
        $email->setReplyTo($fromEmail);
        $email->setMessage(view('parts/email/email_agendamento', [
            'tipo'        => $tipo,
            'cliente_nome' => $clienteNome,
            'data'        => $agendamento['data'],
            'hora_inicio' => $agendamento['hora_inicio'],
            'hora_fim'    => $agendamento['hora_fim'] ?? '',
            'descricao'   => $agendamento['descricao'],
            'observacoes' => $agendamento['observacoes'] ?? '',
        ]));

        try {
            if (!$email->send()) {
                log_message('error', '[agendamento] Falha no envio: ' . $email->printDebugger(['headers']));
                return false;
            }
            log_message('info', '[agendamento] E-mail ' . $tipo . ' enviado para: ' . $destinatario);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[agendamento] Exceção: ' . $e->getMessage());
            return false;
        }
    }
}
```

---

## Task 10: Disparar E-mails nos Métodos de Agendamento

**Files:**
- Modify: `app/Controllers/Admin.php`

**Pré-requisito:** o `email_helper` deve estar carregado. Verificar se há um `helper(['email_helper'])` ou `helper('email')` no `__construct` ou `initController`. Se não houver, adicionar no topo de cada método afetado: `helper('email_helper');`

- [ ] **Step 1: Atualizar agendamentoSalvar() — enviar e-mail só ao CRIAR**

Após o bloco `if ($id) { ... } else { ... }`, antes do `return redirect(...)`, inserir:

```php
// Notificação ao responsável apenas na criação
if (!$id) {
    helper('email_helper');
    $responsavel = $this->funcionarioModel->find($dados['responsavel_id'] ?? 1);
    if ($responsavel && !empty($responsavel['email'])) {
        $clienteNome = '';
        $cliente = $this->clienteModel->find($dados['cliente_id']);
        if ($cliente) {
            $clienteNome = $cliente['nome_completo'] ?? $cliente['nome'] ?? '';
        }
        send_email_agendamento('criado', $responsavel['email'], $dados, $clienteNome);
    }
}
```

- [ ] **Step 2: Atualizar agendamentoCancelar() — enviar e-mail ao CANCELAR**

Localizar o método `agendamentoCancelar()` e, após atualizar o status no banco, inserir:

```php
// Notificação ao responsável
helper('email_helper');
$agendamento = $this->agendamentoModel->find($id);
if ($agendamento) {
    $responsavel = $this->funcionarioModel->find($agendamento['responsavel_id'] ?? 1);
    if ($responsavel && !empty($responsavel['email'])) {
        $clienteNome = '';
        $cliente = $this->clienteModel->find($agendamento['cliente_id']);
        if ($cliente) {
            $clienteNome = $cliente['nome_completo'] ?? $cliente['nome'] ?? '';
        }
        send_email_agendamento('cancelado', $responsavel['email'], $agendamento, $clienteNome);
    }
}
```

- [ ] **Step 3: Atualizar agendamentoConcluir() — enviar e-mail ao CONCLUIR**

Localizar o método `agendamentoConcluir()` e, após atualizar o status no banco, inserir:

```php
// Notificação ao responsável
helper('email_helper');
$agendamento = $this->agendamentoModel->find($id);
if ($agendamento) {
    $responsavel = $this->funcionarioModel->find($agendamento['responsavel_id'] ?? 1);
    if ($responsavel && !empty($responsavel['email'])) {
        $clienteNome = '';
        $cliente = $this->clienteModel->find($agendamento['cliente_id']);
        if ($cliente) {
            $clienteNome = $cliente['nome_completo'] ?? $cliente['nome'] ?? '';
        }
        send_email_agendamento('concluido', $responsavel['email'], $agendamento, $clienteNome);
    }
}
```

---

## Checklist Final

- [ ] SQL executado manualmente na base de dados
- [ ] Tabela `funcionarios` criada com registro do Maicon José (id=1)
- [ ] Coluna `responsavel_id` adicionada em `agendamentos`
- [ ] `FuncionarioModel` criado
- [ ] Rotas de colaboradores adicionadas
- [ ] 3 métodos CRUD adicionados ao Admin controller
- [ ] Views `colaboradores.php` e `colaborador_form.php` criadas
- [ ] Link Colaboradores na sidebar (seção Cadastros)
- [ ] `allowedFields` do `AgendamentoModel` atualizado
- [ ] `agendamentoSalvar` salva `responsavel_id = 1`
- [ ] Template `email_agendamento.php` criado
- [ ] `send_email_agendamento()` adicionada ao helper
- [ ] E-mails disparados em salvar, cancelar e concluir
