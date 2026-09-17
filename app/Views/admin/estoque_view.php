<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('admin/estoque') ?>">Estoque</a></li>
                <li class="breadcrumb-item active"><?php echo esc($peca['nome']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-box me-2"></i>
                    <?php echo esc($peca['nome']) ?>
                </h5>
                <div>
                    <button type="button" class="btn btn-sm btn-light me-1"
                            data-bs-toggle="modal" data-bs-target="#modalAumentar">
                        <i class="fas fa-plus me-1"></i> Aumentar
                    </button>
                    <button type="button" class="btn btn-sm btn-light"
                            data-bs-toggle="modal" data-bs-target="#modalDiminuir">
                        <i class="fas fa-minus me-1"></i> Diminuir
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Quantidade atual</label>
                        <div><strong class="fs-4"><?php echo (int) $peca['quantidade'] ?></strong></div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Atualizado em</label>
                        <div>
                            <?php echo !empty($peca['atualizado_em']) ? date('d/m/Y H:i', strtotime($peca['atualizado_em'])) : '-' ?>
                        </div>
                    </div>
                    <?php if (!empty($fornecedor)): ?>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Fornecedor</label>
                        <div>
                            <a href="<?php echo base_url('admin/fornecedor/' . $fornecedor['id']) ?>"><?php echo esc($fornecedor['nome']) ?></a>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($peca['descricao'])): ?>
                    <div class="col-12 mb-3">
                        <label class="text-muted small">Descrição</label>
                        <div class="bg-light p-3 rounded">
                            <?php echo nl2br(esc($peca['descricao'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo base_url('admin/estoque') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Voltar
                </a>
                <a href="<?php echo base_url('admin/estoque/peca/' . $peca['id'] . '/editar') ?>" class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i> Editar
                </a>
                <button type="button" class="btn btn-danger"
                        data-bs-toggle="modal" data-bs-target="#modalDeletarPeca">
                    <i class="fas fa-trash me-1"></i> Excluir
                </button>
            </div>
        </div>

        <!-- Histórico de interações -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i> Histórico de interações
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($historico)): ?>
                    <p class="text-muted mb-0">Nenhuma movimentação registrada ainda.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Tipo</th>
                                    <th>Quantidade</th>
                                    <th>Anterior</th>
                                    <th>Nova</th>
                                    <th>Descrição</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historico as $h): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y H:i', strtotime($h['criado_em'])) ?></td>
                                        <td>
                                            <?php if ($h['tipo'] === 'aumento'): ?>
                                                <span class="badge bg-success">Aumento</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Diminuição</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo (int) $h['quantidade'] ?></td>
                                        <td><?php echo (int) $h['quantidade_anterior'] ?></td>
                                        <td><strong><?php echo (int) $h['quantidade_nova'] ?></strong></td>
                                        <td><?php echo !empty($h['descricao']) ? esc($h['descricao']) : '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Aumentar -->
<div class="modal fade" id="modalAumentar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Aumentar estoque</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <?php echo form_open('admin/estoque/ajustar'); ?>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $peca['id'] ?>">
                    <input type="hidden" name="tipo" value="aumento">
                    <div class="mb-3">
                        <label for="quantidade_aumentar" class="form-label">Informe a quantidade que irá Aumentar *</label>
                        <input type="number" class="form-control" id="quantidade_aumentar" name="quantidade" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao_aumentar" class="form-label">Descrição (opcional)</label>
                        <textarea class="form-control" id="descricao_aumentar" name="descricao" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Confirmar</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Diminuir -->
<div class="modal fade" id="modalDiminuir" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-minus me-2"></i>Diminuir estoque</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <?php echo form_open('admin/estoque/ajustar'); ?>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $peca['id'] ?>">
                    <input type="hidden" name="tipo" value="diminuicao">
                    <p class="text-muted small">Estoque atual: <strong><?php echo (int) $peca['quantidade'] ?></strong></p>
                    <div class="mb-3">
                        <label for="quantidade_diminuir" class="form-label">Informe a quantidade que irá Diminuir *</label>
                        <input type="number" class="form-control" id="quantidade_diminuir" name="quantidade" 
                               min="1" max="<?php echo (int) $peca['quantidade'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao_diminuir" class="form-label">Descrição (opcional)</label>
                        <textarea class="form-control" id="descricao_diminuir" name="descricao" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Confirmar</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Excluir -->
<div class="modal fade" id="modalDeletarPeca" tabindex="-1">
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
                <?php echo form_open('admin/estoque', ['class' => 'd-inline']); ?>
                    <input type="hidden" name="acao" value="excluir">
                    <input type="hidden" name="id" value="<?php echo $peca['id'] ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Confirmar
                    </button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
