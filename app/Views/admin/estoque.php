<div class="row mb-3">
    <div class="col-12">
        <a href="<?php echo base_url('admin/estoque/peca/novo') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Nova Peça
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Fornecedor</th>
                        <th>Quantidade (atual)</th>
                        <th>Atualizado em</th>
                        <th class="text-center">Quantidade</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pecas)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Nenhuma peça cadastrada</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pecas as $peca): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo base_url('admin/estoque/peca/' . $peca['id']) ?>" data-bs-toggle="tooltip" title="Visualizar peça">
                                        <?php echo esc($peca['nome']) ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if (!empty($peca['fornecedor_nome'])){ ?>
                                        <a href="<?php echo base_url('admin/fornecedor/' . $peca['fornecedor_id']) ?>" data-bs-toggle="tooltip" title="Visualizar fornecedor">
                                            <?php echo esc($peca['fornecedor_nome']) ?>
                                        </a>
                                    <?php }else{ ?>
                                        <span class="text-muted">-</span>
                                    <?php } ?>
                                </td>
                                <td class="text-center" width="150px"><strong><?php echo (int) $peca['quantidade'] ?></strong></td>
                                <td>
                                    <?php echo !empty($peca['atualizado_em']) ? date('d/m/Y H:i', strtotime($peca['atualizado_em'])) : '-' ?>
                                </td>
                                <td class="text-center" width="150px">
                                    <button type="button" class="btn btn-sm btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalAjustar<?php echo $peca['id'] ?>Aumento"
                                        title="Aumentar">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalAjustar<?php echo $peca['id'] ?>Diminuicao"
                                        title="Diminuir">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </td>
                                <td class="text-center" width="200px">
                                    <a href="<?php echo base_url('admin/estoque/peca/' . $peca['id']) ?>"
                                        class="btn btn-sm btn-info" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="<?php echo base_url('admin/estoque/peca/' . $peca['id'] . '/editar') ?>"
                                        class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDeletarPeca<?php echo $peca['id'] ?>"
                                        title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Aumentar -->
                            <div class="modal fade" id="modalAjustar<?php echo $peca['id'] ?>Aumento" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-plus me-2"></i>Aumentar estoque: <?php echo esc($peca['nome']) ?>
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <?php echo form_open('admin/estoque/ajustar'); ?>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?php echo $peca['id'] ?>">
                                            <input type="hidden" name="tipo" value="aumento">
                                            <div class="mb-3">
                                                <label for="quantidade_aumento_<?php echo $peca['id'] ?>" class="form-label">Informe a quantidade que irá Aumentar *</label>
                                                <input type="number" class="form-control" id="quantidade_aumento_<?php echo $peca['id'] ?>"
                                                    name="quantidade" min="1" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="descricao_aumento_<?php echo $peca['id'] ?>" class="form-label">Descrição (opcional)</label>
                                                <textarea class="form-control" id="descricao_aumento_<?php echo $peca['id'] ?>"
                                                    name="descricao" rows="2"></textarea>
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
                            <div class="modal fade" id="modalAjustar<?php echo $peca['id'] ?>Diminuicao" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title">
                                                <i class="fas fa-minus me-2"></i>Diminuir estoque: <?php echo esc($peca['nome']) ?>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <?php echo form_open('admin/estoque/ajustar'); ?>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?php echo $peca['id'] ?>">
                                            <input type="hidden" name="tipo" value="diminuicao">
                                            <p class="text-muted small">Estoque atual: <strong><?php echo (int) $peca['quantidade'] ?></strong></p>
                                            <div class="mb-3">
                                                <label for="quantidade_diminuicao_<?php echo $peca['id'] ?>" class="form-label">Informe a quantidade que irá Diminuir *</label>
                                                <input type="number" class="form-control" id="quantidade_diminuicao_<?php echo $peca['id'] ?>"
                                                    name="quantidade" min="1" max="<?php echo (int) $peca['quantidade'] ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="descricao_diminuicao_<?php echo $peca['id'] ?>" class="form-label">Descrição (opcional)</label>
                                                <textarea class="form-control" id="descricao_diminuicao_<?php echo $peca['id'] ?>"
                                                    name="descricao" rows="2"></textarea>
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

                            <!-- Modal Confirmar Exclusão -->
                            <div class="modal fade" id="modalDeletarPeca<?php echo $peca['id'] ?>" tabindex="-1">
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
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>