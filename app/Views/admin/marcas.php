<div class="row mb-3">
    <div class="col-12">
        <a href="<?php echo base_url('admin/marca/novo') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Nova Marca
        </a>
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
                        <th>Logo</th>
                        <th>Ordem</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($marcas)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Nenhuma marca cadastrada</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($marcas as $marca): ?>
                            <tr>
                                <td>#<?php echo $marca['id'] ?></td>
                                <td><?php echo esc($marca['nome']) ?></td>
                                <td>
                                    <?php if (!empty($marca['logo'])): ?>
                                        <img src="<?php echo base_url('uploads/marcas/' . $marca['logo']) ?>" 
                                             alt="<?php echo esc($marca['nome']) ?>" 
                                             style="max-height: 50px;">
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $marca['ordem'] ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $marca['ativo'] ? 'success' : 'secondary' ?>">
                                        <?php echo $marca['ativo'] ? 'Ativo' : 'Inativo' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('admin/marca/' . $marca['id']) ?>" 
                                       class="btn btn-sm btn-info" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo base_url('admin/marca/' . $marca['id'] . '/editar') ?>" 
                                       class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalDeletarMarca<?php echo $marca['id'] ?>"
                                            title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Confirmar Exclusão Marca -->
                            <div class="modal fade" id="modalDeletarMarca<?php echo $marca['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h4 class="modal-title">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Deseja deletar esse registro?
                                            </h4>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-danger mb-0">
                                                <strong>Esta ação não poderá ser desfeita.</strong>
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1"></i> Cancelar
                                            </button>
                                            <form method="post" action="<?php echo base_url('admin/marcas') ?>" class="d-inline">
                                                <input type="hidden" name="acao" value="excluir">
                                                <input type="hidden" name="id" value="<?php echo $marca['id'] ?>">
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash me-1"></i> Confirmar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
            </table>
        </div>
    </div>
</div>
