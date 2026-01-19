<div class="row mb-3">
    <div class="col-12">
        <div class="btn-group" role="group">
            <a href="<?php echo base_url('admin/solicitacoes') ?>" 
               class="btn btn-<?php echo $status_atual == 'todas' ? 'primary' : 'outline-primary' ?>">
                Todas
            </a>
            <a href="<?php echo base_url('admin/solicitacoes?status=pendente') ?>" 
               class="btn btn-<?php echo $status_atual == 'pendente' ? 'warning' : 'outline-warning' ?>">
                Pendentes
            </a>
            <a href="<?php echo base_url('admin/solicitacoes?status=em_atendimento') ?>" 
               class="btn btn-<?php echo $status_atual == 'em_atendimento' ? 'info' : 'outline-info' ?>">
                Em Atendimento
            </a>
            <a href="<?php echo base_url('admin/solicitacoes?status=resolvido') ?>" 
               class="btn btn-<?php echo $status_atual == 'resolvido' ? 'success' : 'outline-success' ?>">
                Resolvidas
            </a>
            <a href="<?php echo base_url('admin/solicitacoes?status=cancelado') ?>" 
               class="btn btn-<?php echo $status_atual == 'cancelado' ? 'danger' : 'outline-danger' ?>">
                Canceladas
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="60">ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Celular</th>
                        <th>Cidade</th>
                        <th width="120">Status</th>
                        <th width="140">Data</th>
                        <th width="120" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($solicitacoes)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                Nenhuma solicitação encontrada
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($solicitacoes as $solicitacao): ?>
                            <?php
                            $badge_class = [
                                'pendente' => 'warning',
                                'em_atendimento' => 'info',
                                'resolvido' => 'success',
                                'cancelado' => 'danger'
                            ];
                            $class = $badge_class[$solicitacao['status']] ?? 'secondary';
                            ?>
                            <tr>
                                <td><strong>#<?php echo $solicitacao['id'] ?></strong></td>
                                <td><?php echo esc($solicitacao['nome']) ?></td>
                                <td><small><?php echo esc($solicitacao['email']) ?></small></td>
                                <td><?php echo esc($solicitacao['celular'] ?? '-') ?></td>
                                <td><?php echo esc($solicitacao['cidade'] ?? '-') ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $class ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $solicitacao['status'])) ?>
                                    </span>
                                </td>
                                <td><small><?php echo date('d/m/Y H:i', strtotime($solicitacao['criado_em'])) ?></small></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalDetalhes<?php echo $solicitacao['id'] ?>">
                                        <i class="fas fa-eye me-1"></i> Visualizar
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Detalhes -->
                            <div class="modal fade" id="modalDetalhes<?php echo $solicitacao['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-file-alt me-2"></i>
                                                Solicitação #<?php echo $solicitacao['id'] ?>
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Informações do Cliente -->
                                            <div class="card mb-3">
                                                <div class="card-header bg-light">
                                                    <strong><i class="fas fa-user me-2"></i>Informações do Cliente</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="text-muted small">Nome Completo:</label>
                                                            <div><strong><?php echo esc($solicitacao['nome']) ?></strong></div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="text-muted small">E-mail:</label>
                                                            <div>
                                                                <a href="mailto:<?php echo esc($solicitacao['email']) ?>">
                                                                    <?php echo esc($solicitacao['email']) ?>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="text-muted small">Celular/WhatsApp:</label>
                                                            <div>
                                                                <a href="https://wa.me/55<?php echo preg_replace('/\D/', '', $solicitacao['celular'] ?? '') ?>" 
                                                                   target="_blank" class="text-success">
                                                                    <i class="fab fa-whatsapp me-1"></i>
                                                                    <?php echo esc($solicitacao['celular'] ?? '-') ?>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="text-muted small">Cidade:</label>
                                                            <div><?php echo esc($solicitacao['cidade'] ?? '-') ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detalhes da Solicitação -->
                                            <div class="card mb-3">
                                                <div class="card-header bg-light">
                                                    <strong><i class="fas fa-clipboard me-2"></i>Detalhes da Solicitação</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label class="text-muted small">Observação/Descrição:</label>
                                                        <div class="bg-light p-3 rounded">
                                                            <?php echo nl2br(esc($solicitacao['observacao'] ?? 'Nenhuma observação')) ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2">
                                                            <label class="text-muted small">Status Atual:</label>
                                                            <div>
                                                                <span class="badge bg-<?php echo $class ?> px-3 py-2">
                                                                    <?php echo ucfirst(str_replace('_', ' ', $solicitacao['status'])) ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <label class="text-muted small">Data de Criação:</label>
                                                            <div><?php echo date('d/m/Y H:i:s', strtotime($solicitacao['criado_em'])) ?></div>
                                                        </div>
                                                        <?php if ($solicitacao['atualizado_em']): ?>
                                                        <div class="col-md-4 mb-2">
                                                            <label class="text-muted small">Última Atualização:</label>
                                                            <div><?php echo date('d/m/Y H:i:s', strtotime($solicitacao['atualizado_em'])) ?></div>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Ações -->
                                            <div class="card">
                                                <div class="card-header bg-light">
                                                    <strong><i class="fas fa-tasks me-2"></i>Alterar Status</strong>
                                                </div>
                                                <div class="card-body">
                                                    <form method="post" action="<?php echo base_url('admin/atualizarStatus') ?>" 
                                                          onsubmit="return confirm('Confirma a alteração de status?');">
                                                        <input type="hidden" name="id" value="<?php echo $solicitacao['id'] ?>">
                                                        <div class="row g-2">
                                                            <div class="col-md-8">
                                                                <select name="status" class="form-select" required>
                                                                    <option value="">Selecione o novo status...</option>
                                                                    <option value="pendente" <?php echo $solicitacao['status'] == 'pendente' ? 'selected' : '' ?>>
                                                                        🟡 Pendente
                                                                    </option>
                                                                    <option value="em_atendimento" <?php echo $solicitacao['status'] == 'em_atendimento' ? 'selected' : '' ?>>
                                                                        🔵 Em Atendimento
                                                                    </option>
                                                                    <option value="resolvido" <?php echo $solicitacao['status'] == 'resolvido' ? 'selected' : '' ?>>
                                                                        🟢 Resolvido
                                                                    </option>
                                                                    <option value="cancelado" <?php echo $solicitacao['status'] == 'cancelado' ? 'selected' : '' ?>>
                                                                        🔴 Cancelado
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button type="submit" class="btn btn-success w-100">
                                                                    <i class="fas fa-check me-1"></i> Atualizar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1"></i> Fechar
                                            </button>
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
