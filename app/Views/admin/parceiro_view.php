<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('admin/parceiros') ?>">Parceiros</a></li>
                <li class="breadcrumb-item active"><?php echo esc($parceiro['nome']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-handshake me-2"></i>
                    <?php echo esc($parceiro['nome']) ?>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">ID:</label>
                        <div><strong>#<?php echo $parceiro['id'] ?></strong></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Status:</label>
                        <div>
                            <span class="badge bg-<?php echo $parceiro['ativo'] ? 'success' : 'secondary' ?>">
                                <?php echo $parceiro['ativo'] ? 'Ativo' : 'Inativo' ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Ordem:</label>
                        <div><?php echo $parceiro['ordem'] ?></div>
                    </div>
                    <?php if (!empty($parceiro['link'])): ?>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Link:</label>
                        <div>
                            <a href="<?php echo esc($parceiro['link']) ?>" target="_blank" class="text-decoration-none">
                                <i class="fas fa-external-link-alt me-1"></i>
                                <?php echo esc($parceiro['link']) ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($parceiro['logo'])): ?>
                    <div class="col-12 mb-3">
                        <label class="text-muted small">Logo:</label>
                        <div>
                            <img src="<?php echo base_url('uploads/parceiros/' . $parceiro['logo']) ?>" 
                                 alt="<?php echo esc($parceiro['nome']) ?>" 
                                 class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($parceiro['descricao'])): ?>
                    <div class="col-12 mb-3">
                        <label class="text-muted small">Descrição:</label>
                        <div class="bg-light p-3 rounded">
                            <?php echo nl2br(esc($parceiro['descricao'])) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="<?php echo base_url('admin/parceiros') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Voltar
                    </a>
                    <div>
                        <a href="<?php echo base_url('admin/parceiro/' . $parceiro['id'] . '/editar') ?>" 
                           class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Editar
                        </a>
                        <button type="button" class="btn btn-danger"
                                data-bs-toggle="modal" 
                                data-bs-target="#modalDeletarParceiro">
                            <i class="fas fa-trash me-1"></i> Excluir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirmar Exclusão -->
<div class="modal fade" id="modalDeletarParceiro" tabindex="-1">
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
                <form method="post" action="<?php echo base_url('admin/parceiros') ?>" class="d-inline">
                    <input type="hidden" name="acao" value="excluir">
                    <input type="hidden" name="id" value="<?php echo $parceiro['id'] ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Confirmar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
