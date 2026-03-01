<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('admin/clientes') ?>">Clientes</a></li>
                <li class="breadcrumb-item active"><?php echo esc($cliente['nome_completo']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Cliente:</h6>
                <h5 class="card-title mb-3">
                    <i class="fas fa-user me-2"></i>
                    <?php echo esc($cliente['nome_completo']) ?>
                </h5>
                <div class="d-flex gap-3">
                    <?php if (!empty($cliente['celular'])): ?>
                        <a href="https://wa.me/55<?php echo preg_replace('/\D/', '', $cliente['celular']) ?>" 
                           target="_blank" class="text-success">
                            <i class="fab fa-whatsapp me-1"></i>
                            <?php echo esc($cliente['celular']) ?>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($cliente['email'])): ?>
                        <a href="mailto:<?php echo esc($cliente['email']) ?>">
                            <i class="fas fa-envelope me-1"></i>
                            <?php echo esc($cliente['email']) ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?php echo base_url('admin/cliente/' . $cliente['id'] . '/editar') ?>" 
           class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Editar Cliente
        </a>
        <a href="<?php echo base_url('admin/cliente/' . $cliente['id'] . '/servico/novo') ?>" 
           class="btn btn-success">
            <i class="fas fa-plus me-1"></i> Novo Serviço
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-tools me-2"></i>
            Histórico de Serviços
            <span class="badge bg-primary ms-2"><?php echo count($servicos) ?></span>
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($servicos)): ?>
            <div class="text-center text-muted py-5">
                <i class="fas fa-tools fa-3x mb-3 d-block"></i>
                <p>Nenhum serviço registrado para este cliente.</p>
                <a href="<?php echo base_url('admin/cliente/' . $cliente['id'] . '/servico/novo') ?>" 
                   class="btn btn-success mt-3">
                    <i class="fas fa-plus me-1"></i> Adicionar Primeiro Serviço
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th>Título</th>
                            <th width="180">Data Início</th>
                            <th width="180">Data Finalização</th>
                            <th width="150" class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($servicos as $servico): ?>
                            <tr>
                                <td><strong>#<?php echo $servico['id'] ?></strong></td>
                                <td>
                                    <strong><?php echo esc($servico['titulo']) ?></strong>
                                    <?php if (!empty($servico['descricao'])): ?>
                                        <br>
                                        <small class="text-muted">
                                            <?php echo mb_substr(strip_tags($servico['descricao']), 0, 100) ?>...
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    <?php echo date('d/m/Y H:i', strtotime($servico['data_inicio'])) ?>
                                </td>
                                <td>
                                    <?php if (!empty($servico['data_finalizacao'])): ?>
                                        <i class="fas fa-calendar-check me-1 text-success"></i>
                                        <?php echo date('d/m/Y H:i', strtotime($servico['data_finalizacao'])) ?>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Em andamento</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalServico<?php echo $servico['id'] ?>"
                                            title="Ver detalhes">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="<?php echo base_url('admin/cliente/' . $cliente['id'] . '/servico/' . $servico['id'] . '/editar') ?>" 
                                       class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            title="Excluir"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalDeletarServico<?php echo $servico['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Detalhes do Serviço -->
                            <div class="modal fade" id="modalServico<?php echo $servico['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-tools me-2"></i>
                                                <?php echo esc($servico['titulo']) ?>
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="text-muted small">Título do Serviço:</label>
                                                <div><strong><?php echo esc($servico['titulo']) ?></strong></div>
                                            </div>

                                            <?php if (!empty($servico['descricao'])): ?>
                                            <div class="mb-3">
                                                <label class="text-muted small">Descrição:</label>
                                                <div class="bg-light p-3 rounded">
                                                    <?php echo $servico['descricao'] ?>
                                                </div>
                                            </div>
                                            <?php endif; ?>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Data/Hora de Início:</label>
                                                    <div>
                                                        <i class="fas fa-calendar-alt text-primary me-1"></i>
                                                        <?php echo date('d/m/Y H:i', strtotime($servico['data_inicio'])) ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Data/Hora de Finalização:</label>
                                                    <div>
                                                        <?php if (!empty($servico['data_finalizacao'])): ?>
                                                            <i class="fas fa-calendar-check text-success me-1"></i>
                                                            <?php echo date('d/m/Y H:i', strtotime($servico['data_finalizacao'])) ?>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning">Em andamento</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Registrado em:</label>
                                                    <div><?php echo date('d/m/Y H:i:s', strtotime($servico['data_criacao'])) ?></div>
                                                </div>

                                                <?php if ($servico['data_edicao']): ?>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Última edição:</label>
                                                    <div><?php echo date('d/m/Y H:i:s', strtotime($servico['data_edicao'])) ?></div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="<?php echo base_url('admin/cliente/' . $cliente['id'] . '/servico/' . $servico['id'] . '/editar') ?>" 
                                               class="btn btn-warning">
                                                <i class="fas fa-edit me-1"></i> Editar
                                            </a>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1"></i> Fechar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Confirmar Exclusão Serviço -->
                            <div class="modal fade" id="modalDeletarServico<?php echo $servico['id'] ?>" tabindex="-1">
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
                                            <a href="<?php echo base_url('admin/cliente/' . $cliente['id'] . '/servico/' . $servico['id'] . '/deletar') ?>" 
                                               class="btn btn-danger">
                                                <i class="fas fa-trash me-1"></i> Confirmar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
