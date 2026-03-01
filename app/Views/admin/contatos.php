<div class="row mb-3">
    <div class="col-12">
        <div class="btn-group" role="group">
            <a href="<?php echo base_url('admin/contatos') ?>" 
               class="btn btn-<?php echo $status_atual == 'todas' ? 'primary' : 'outline-primary' ?>">
                Todas
            </a>
            <a href="<?php echo base_url('admin/contatos?status=pendente') ?>" 
               class="btn btn-<?php echo $status_atual == 'pendente' ? 'warning' : 'outline-warning' ?>">
                Pendentes
            </a>
            <a href="<?php echo base_url('admin/contatos?status=em_atendimento') ?>" 
               class="btn btn-<?php echo $status_atual == 'em_atendimento' ? 'info' : 'outline-info' ?>">
                Em Atendimento
            </a>
            <a href="<?php echo base_url('admin/contatos?status=resolvido') ?>" 
               class="btn btn-<?php echo $status_atual == 'resolvido' ? 'success' : 'outline-success' ?>">
                Resolvidos
            </a>
            <a href="<?php echo base_url('admin/contatos?status=cancelado') ?>" 
               class="btn btn-<?php echo $status_atual == 'cancelado' ? 'danger' : 'outline-danger' ?>">
                Cancelados
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
                        <th width="40" class="text-center" title="Lido/Não Lido"><i class="fas fa-envelope"></i></th>
                        <th width="60">ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Celular</th>
                        <th width="120">Status</th>
                        <th width="140">Data</th>
                        <th width="120" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($contatos)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                Nenhum contato encontrado
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($contatos as $contato): ?>
                            <?php
                            $badge_class = [
                                'pendente' => 'warning',
                                'em_atendimento' => 'info',
                                'resolvido' => 'success',
                                'cancelado' => 'danger'
                            ];
                            $class = $badge_class[$contato['status']] ?? 'secondary';
                            $lido = isset($contato['lido']) ? $contato['lido'] : 0;
                            ?>
                            <tr class="<?php echo $lido ? 'opacity-75' : '' ?>">
                                <td class="text-center">
                                    <i class="fas fa-envelope<?php echo $lido ? '-open text-muted' : ' text-primary' ?> cursor-pointer toggle-lido-contato" 
                                       data-id="<?php echo $contato['id'] ?>"
                                       data-lido="<?php echo $lido ? '1' : '0' ?>"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       title="<?php echo $lido ? 'Marcar como não lido' : 'Marcar como lido' ?>"
                                       style="cursor: pointer;"></i>
                                </td>
                                <td><strong>#<?php echo $contato['id'] ?></strong></td>
                                <td><?php echo esc($contato['nome']) ?></td>
                                <td><small><?php echo esc($contato['email'] ?? '-') ?></small></td>
                                <td><?php echo esc($contato['celular'] ?? '-') ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $class ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $contato['status'])) ?>
                                    </span>
                                </td>
                                <td><small><?php echo date('d/m/Y H:i', strtotime($contato['data_criacao'])) ?></small></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalDetalhes<?php echo $contato['id'] ?>">
                                        <i class="fas fa-eye me-1"></i> Visualizar
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Detalhes -->
                            <div class="modal fade" id="modalDetalhes<?php echo $contato['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-file-alt me-2"></i>
                                                Contato #<?php echo $contato['id'] ?>
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Informações do Contato -->
                                            <div class="card mb-3">
                                                <div class="card-header bg-light">
                                                    <strong><i class="fas fa-user me-2"></i>Informações do Contato</strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="text-muted small">Nome:</label>
                                                            <div><strong><?php echo esc($contato['nome']) ?></strong></div>
                                                        </div>
                                                        <?php if (!empty($contato['email'])): ?>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="text-muted small">E-mail:</label>
                                                            <div>
                                                                <a href="mailto:<?php echo esc($contato['email']) ?>">
                                                                    <?php echo esc($contato['email']) ?>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php if (!empty($contato['celular'])): ?>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="text-muted small">Celular/WhatsApp:</label>
                                                            <div>
                                                                <a href="https://wa.me/55<?php echo preg_replace('/\D/', '', $contato['celular']) ?>" 
                                                                   target="_blank" class="text-success">
                                                                    <i class="fab fa-whatsapp me-1"></i>
                                                                    <?php echo esc($contato['celular']) ?>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detalhes do Contato -->
                                            <div class="card mb-3">
                                                <div class="card-header bg-light">
                                                    <strong><i class="fas fa-clipboard me-2"></i>Detalhes do Contato</strong>
                                                </div>
                                                <div class="card-body">
                                                    <?php if (!empty($contato['observacao'])): ?>
                                                    <div class="mb-3">
                                                        <label class="text-muted small">Observação/Mensagem:</label>
                                                        <div class="bg-light p-3 rounded">
                                                            <?php echo nl2br(esc($contato['observacao'])) ?>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2">
                                                            <label class="text-muted small">Status Atual:</label>
                                                            <div>
                                                                <span class="badge bg-<?php echo $class ?> px-3 py-2">
                                                                    <?php echo ucfirst(str_replace('_', ' ', $contato['status'])) ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <label class="text-muted small">Data de Criação:</label>
                                                            <div><?php echo date('d/m/Y H:i:s', strtotime($contato['data_criacao'])) ?></div>
                                                        </div>
                                                        <?php if (!empty($contato['atualizado_em'])): ?>
                                                        <div class="col-md-4 mb-2">
                                                            <label class="text-muted small">Última Atualização:</label>
                                                            <div><?php echo date('d/m/Y H:i:s', strtotime($contato['atualizado_em'])) ?></div>
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
                                                    <form method="post" action="<?php echo base_url('admin/atualizarStatusContato') ?>" 
                                                          onsubmit="return confirm('Confirma a alteração de status?');">
                                                        <input type="hidden" name="id" value="<?php echo $contato['id'] ?>">
                                                        <div class="row g-2">
                                                            <div class="col-md-8">
                                                                <select name="status" class="form-select" required>
                                                                    <option value="">Selecione o novo status...</option>
                                                                    <option value="pendente" <?php echo $contato['status'] == 'pendente' ? 'selected' : '' ?>>
                                                                        🟡 Pendente
                                                                    </option>
                                                                    <option value="em_atendimento" <?php echo $contato['status'] == 'em_atendimento' ? 'selected' : '' ?>>
                                                                        🔵 Em Atendimento
                                                                    </option>
                                                                    <option value="resolvido" <?php echo $contato['status'] == 'resolvido' ? 'selected' : '' ?>>
                                                                        🟢 Resolvido
                                                                    </option>
                                                                    <option value="cancelado" <?php echo $contato['status'] == 'cancelado' ? 'selected' : '' ?>>
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
                                            <button type="button" class="btn btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalDeletarContato<?php echo $contato['id'] ?>"
                                                    data-bs-dismiss="modal">
                                                <i class="fas fa-trash me-1"></i> Excluir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Confirmar Exclusão Contato -->
                            <div class="modal fade" id="modalDeletarContato<?php echo $contato['id'] ?>" tabindex="-1">
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
                                            <form method="post" action="<?php echo base_url('admin/excluirContato') ?>" class="d-inline">
                                                <input type="hidden" name="<?php echo csrf_token() ?>" value="<?php echo csrf_hash() ?>">
                                                <input type="hidden" name="id" value="<?php echo $contato['id'] ?>">
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

<script>
// CSRF Token
const csrfToken = '<?php echo csrf_hash() ?>';
const csrfName = '<?php echo csrf_token() ?>';

// Inicializa tooltips do Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa todos os tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Toggle lido/não lido ao clicar no ícone da listagem
    document.querySelectorAll('.toggle-lido-contato').forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const id = this.getAttribute('data-id');
            const lidoAtual = this.getAttribute('data-lido') === '1';
            const novoLido = lidoAtual ? 0 : 1;
            
            // Desabilita o ícone durante a requisição
            this.style.opacity = '0.5';
            this.style.pointerEvents = 'none';
            
            const formData = new URLSearchParams();
            formData.append('id', id);
            formData.append('lido', novoLido);
            formData.append(csrfName, csrfToken);

            fetch('<?php echo base_url('admin/marcarContatoLido') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData.toString()
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualiza o ícone na listagem
                    const row = this.closest('tr');
                    
                    // Destrói tooltip antigo
                    const tooltipInstance = bootstrap.Tooltip.getInstance(this);
                    if (tooltipInstance) {
                        tooltipInstance.dispose();
                    }
                    
                    if (novoLido === 1) {
                        // Marca como lido
                        this.className = 'fas fa-envelope-open text-muted cursor-pointer toggle-lido-contato';
                        this.setAttribute('data-lido', '1');
                        this.setAttribute('title', 'Marcar como não lido');
                        this.setAttribute('data-bs-toggle', 'tooltip');
                        row.classList.add('opacity-75');
                    } else {
                        // Marca como não lido
                        this.className = 'fas fa-envelope text-primary cursor-pointer toggle-lido-contato';
                        this.setAttribute('data-lido', '0');
                        this.setAttribute('title', 'Marcar como lido');
                        this.setAttribute('data-bs-toggle', 'tooltip');
                        row.classList.remove('opacity-75');
                    }
                    
                    // Recria tooltip com novo texto
                    new bootstrap.Tooltip(this);
                    
                    // Feedback visual
                    showToast(novoLido === 1 ? 'Marcado como lido!' : 'Marcado como não lido!', 'success');
                    
                    // Atualiza badge na sidebar via AJAX
                    atualizarBadgeContatos();
                } else {
                    showToast('Erro ao atualizar. Tente novamente.', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showToast('Erro ao atualizar. Tente novamente.', 'error');
            })
            .finally(() => {
                // Re-habilita o ícone
                this.style.opacity = '1';
                this.style.pointerEvents = 'auto';
            });
        });
    });
});

// Atualiza badge de contatos na sidebar
function atualizarBadgeContatos() {
    const formData = new URLSearchParams();
    formData.append(csrfName, csrfToken);
    
    fetch('<?php echo base_url('admin/contarContatosNaoLidos') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success !== undefined) {
            const badgeLink = document.querySelector('a[href*="admin/contatos"]');
            if (badgeLink) {
                // Remove badge existente
                const badgeExistente = badgeLink.querySelector('.badge');
                if (badgeExistente) {
                    badgeExistente.remove();
                }
                
                // Adiciona novo badge se houver não lidos
                if (data.count > 0) {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-danger rounded-pill ms-1';
                    badge.textContent = data.count > 99 ? '+99' : data.count;
                    badgeLink.appendChild(badge);
                }
            }
        }
    })
    .catch(error => {
        console.error('Erro ao atualizar badge:', error);
    });
}

// A função showToast está disponível globalmente via layout.php
</script>
