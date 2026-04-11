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
                                <td>#<?php echo esc($c['id']) ?></td>
                                <td><?php echo esc($c['nome']) ?></td>
                                <td><?php echo esc($c['email'] ?? '-') ?></td>
                                <td><?php echo esc($c['telefone'] ?? '-') ?></td>
                                <td>
                                    <?php
                                    $nivelBadge = ['dono' => 'danger', 'lider' => 'warning', 'tecnico' => 'info', 'assistente' => 'secondary'];
                                    $cor = $nivelBadge[$c['nivel']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $cor ?>">
                                        <?php echo \App\Models\FuncionarioModel::labelNivel($c['nivel']) ?>
                                    </span>
                                </td>
                                <td><?php echo $c['data_inicio_contrato'] ? date('d/m/Y', strtotime($c['data_inicio_contrato'])) : '-' ?></td>
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
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (!empty($colaboradores)): ?>
    <?php foreach ($colaboradores as $c): ?>
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
                        <?php echo form_open(base_url('admin/colaboradores'), ['class' => 'd-inline']) ?>
                            <input type="hidden" name="acao" value="excluir">
                            <input type="hidden" name="id" value="<?php echo esc($c['id']) ?>">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i> Confirmar
                            </button>
                        <?php echo form_close() ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
