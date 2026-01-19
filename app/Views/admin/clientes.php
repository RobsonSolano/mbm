<div class="row mb-3">
    <div class="col-md-3">
        <a href="<?php echo base_url('admin/cliente/novo') ?>" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> Novo Cliente
        </a>
    </div>
    <div class="col-md-9">
        <form method="get" class="row g-2">
            <div class="col-md-3">
                <input type="text" name="filtro_nome" class="form-control" 
                       placeholder="Nome..." 
                       value="<?php echo esc($filtroNome ?? '') ?>">
            </div>
            <div class="col-md-3">
                <input type="text" name="filtro_email" class="form-control" 
                       placeholder="E-mail..." 
                       value="<?php echo esc($filtroEmail ?? '') ?>">
            </div>
            <div class="col-md-2">
                <input type="text" name="filtro_celular" class="form-control" 
                       placeholder="Celular..." 
                       value="<?php echo esc($filtroCelular ?? '') ?>">
            </div>
            <div class="col-md-3">
                <input type="text" name="filtro_cidade" id="filtro_cidade" class="form-control" 
                       list="cidades-sp-filtro" autocomplete="off"
                       value="<?php echo esc($filtroCidade ?? '') ?>"
                       placeholder="Digite ou selecione uma cidade">
                <datalist id="cidades-sp-filtro">
                    <?php 
                    $cidadesSP = \App\Config\CidadesSP::listar();
                    foreach ($cidadesSP as $cidade): 
                    ?>
                        <option value="<?php echo esc($cidade) ?>">
                    <?php endforeach; ?>
                </datalist>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100" title="Buscar">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <?php if (!empty($filtroNome) || !empty($filtroEmail) || !empty($filtroCelular) || !empty($filtroCidade)): ?>
            <div class="col-md-12 mt-2">
                <a href="<?php echo base_url('admin/clientes') ?>" class="btn btn-sm btn-secondary">
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
                        <th width="40" class="text-center" title="Lido/Não Lido"><i class="fas fa-envelope"></i></th>
                        <th width="60">
                            <?php 
                            $params = http_build_query(array_filter([
                                'ordenacao' => 'id',
                                'direcao' => ($ordenacao == 'id' && $direcao == 'DESC') ? 'ASC' : 'DESC',
                                'filtro_nome' => $filtroNome ?? '',
                                'filtro_email' => $filtroEmail ?? '',
                                'filtro_celular' => $filtroCelular ?? '',
                                'filtro_cidade' => $filtroCidade ?? ''
                            ]));
                            ?>
                            <a href="?<?php echo $params ?>" 
                               class="text-decoration-none text-dark" title="Ordenar por ID">
                                ID <?php if ($ordenacao == 'id'): ?>
                                    <i class="fas fa-sort-<?php echo $direcao == 'DESC' ? 'down' : 'up' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <?php 
                            $params = http_build_query(array_filter([
                                'ordenacao' => 'nome_completo',
                                'direcao' => ($ordenacao == 'nome_completo' && $direcao == 'ASC') ? 'DESC' : 'ASC',
                                'filtro_nome' => $filtroNome ?? '',
                                'filtro_email' => $filtroEmail ?? '',
                                'filtro_celular' => $filtroCelular ?? '',
                                'filtro_cidade' => $filtroCidade ?? ''
                            ]));
                            ?>
                            <a href="?<?php echo $params ?>" 
                               class="text-decoration-none text-dark" title="Ordenar por Nome">
                                Nome <?php if ($ordenacao == 'nome_completo'): ?>
                                    <i class="fas fa-sort-<?php echo $direcao == 'DESC' ? 'down' : 'up' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>Contato</th>
                        <th>
                            <?php 
                            $params = http_build_query(array_filter([
                                'ordenacao' => 'cidade',
                                'direcao' => ($ordenacao == 'cidade' && $direcao == 'ASC') ? 'DESC' : 'ASC',
                                'filtro_nome' => $filtroNome ?? '',
                                'filtro_email' => $filtroEmail ?? '',
                                'filtro_celular' => $filtroCelular ?? '',
                                'filtro_cidade' => $filtroCidade ?? ''
                            ]));
                            ?>
                            <a href="?<?php echo $params ?>" 
                               class="text-decoration-none text-dark" title="Ordenar por Cidade">
                                Cidade <?php if ($ordenacao == 'cidade'): ?>
                                    <i class="fas fa-sort-<?php echo $direcao == 'DESC' ? 'down' : 'up' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <?php 
                            $params = http_build_query(array_filter([
                                'ordenacao' => 'data_criacao',
                                'direcao' => ($ordenacao == 'data_criacao' && $direcao == 'ASC') ? 'DESC' : 'ASC',
                                'filtro_nome' => $filtroNome ?? '',
                                'filtro_email' => $filtroEmail ?? '',
                                'filtro_celular' => $filtroCelular ?? '',
                                'filtro_cidade' => $filtroCidade ?? ''
                            ]));
                            ?>
                            <a href="?<?php echo $params ?>" 
                               class="text-decoration-none text-dark" title="Ordenar por Data de Cadastro">
                                Cadastrado em <?php if ($ordenacao == 'data_criacao'): ?>
                                    <i class="fas fa-sort-<?php echo $direcao == 'DESC' ? 'down' : 'up' ?>"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th width="180" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-users fa-3x mb-3 d-block"></i>
                                Nenhum cliente encontrado
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $cliente): ?>
                            <?php
                            $lido = $cliente['lido'] ?? 0;
                            $bloqueado = $cliente['bloqueado'] ?? 0;
                            ?>
                            <tr class="<?php echo $lido ? 'opacity-75' : '' ?>">
                                <td class="text-center">
                                    <i class="fas fa-envelope<?php echo $lido ? '-open text-muted' : ' text-primary' ?> cursor-pointer toggle-lido-cliente" 
                                       data-id="<?php echo $cliente['id'] ?>"
                                       data-lido="<?php echo $lido ? '1' : '0' ?>"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       title="<?php echo $lido ? 'Marcar como não lido' : 'Marcar como lido' ?>"
                                       style="cursor: pointer;"></i>
                                </td>
                                <td><strong>#<?php echo $cliente['id'] ?></strong></td>
                                <td>
                                    <?php echo esc($cliente['nome_completo']) ?>
                                    <?php if ($bloqueado): ?>
                                        <i class="fas fa-lock text-danger ms-2" title="Cliente bloqueado"></i>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div>
                                        <?php if (!empty($cliente['email'])): ?>
                                            <a href="mailto:<?php echo esc($cliente['email']) ?>" title="Enviar e-mail">
                                                <i class="fas fa-envelope me-1"></i>
                                                <small><?php echo esc($cliente['email']) ?></small>
                                            </a>
                                        <?php else: ?>
                                            <small class="text-muted">-</small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mt-1">
                                        <a href="https://wa.me/55<?php echo preg_replace('/\D/', '', $cliente['celular']) ?>" 
                                           target="_blank" class="text-success" title="WhatsApp">
                                            <i class="fab fa-whatsapp me-1"></i>
                                            <small><?php echo esc($cliente['celular']) ?></small>
                                        </a>
                                    </div>
                                </td>
                                <td><?php echo esc($cliente['cidade'] ?? '-') ?></td>
                                <td><small><?php echo date('d/m/Y H:i', strtotime($cliente['data_criacao'])) ?></small></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalDetalhes<?php echo $cliente['id'] ?>"
                                            title="Visualizar detalhes">
                                        <i class="fas fa-eye me-1"></i> Visualizar
                                    </button>
                                    <a href="<?php echo base_url('admin/cliente/' . $cliente['id'] . '/editar') ?>" 
                                       class="btn btn-sm btn-warning" title="Editar cliente">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal Detalhes -->
                            <div class="modal fade" id="modalDetalhes<?php echo $cliente['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-user me-2"></i>
                                                <?php echo esc($cliente['nome_completo']) ?>
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Nome Completo:</label>
                                                    <div><strong><?php echo esc($cliente['nome_completo']) ?></strong></div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Celular:</label>
                                                    <div>
                                                        <a href="https://wa.me/55<?php echo preg_replace('/\D/', '', $cliente['celular']) ?>" 
                                                           target="_blank" class="text-success">
                                                            <i class="fab fa-whatsapp me-1"></i>
                                                            <?php echo esc($cliente['celular']) ?>
                                                        </a>
                                                    </div>
                                                </div>
                                                <?php if (!empty($cliente['email'])): ?>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">E-mail:</label>
                                                    <div>
                                                        <a href="mailto:<?php echo esc($cliente['email']) ?>">
                                                            <?php echo esc($cliente['email']) ?>
                                                        </a>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (!empty($cliente['cidade'])): ?>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Cidade:</label>
                                                    <div><?php echo esc($cliente['cidade']) ?></div>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (!empty($cliente['endereco'])): ?>
                                                <div class="col-12 mb-3">
                                                    <label class="text-muted small">Endereço:</label>
                                                    <div><?php echo nl2br(esc($cliente['endereco'])) ?></div>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (!empty($cliente['observacoes'])): ?>
                                                <div class="col-12 mb-3">
                                                    <label class="text-muted small">Observações:</label>
                                                    <div class="bg-light p-3 rounded">
                                                        <?php echo nl2br(esc($cliente['observacoes'])) ?>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Cadastrado em:</label>
                                                    <div><?php echo date('d/m/Y H:i:s', strtotime($cliente['data_criacao'])) ?></div>
                                                </div>
                                                <?php if ($cliente['data_edicao']): ?>
                                                <div class="col-md-6 mb-3">
                                                    <label class="text-muted small">Última edição:</label>
                                                    <div><?php echo date('d/m/Y H:i:s', strtotime($cliente['data_edicao'])) ?></div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="<?php echo base_url('admin/cliente/' . $cliente['id'] . '/servicos') ?>" 
                                               class="btn btn-info">
                                                <i class="fas fa-tools me-1"></i> Ver Serviços
                                            </a>
                                            <a href="<?php echo base_url('admin/cliente/' . $cliente['id'] . '/editar') ?>" 
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
    document.querySelectorAll('.toggle-lido-cliente').forEach(icon => {
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

            fetch('<?php echo base_url('admin/marcarClienteLido') ?>', {
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
                        this.className = 'fas fa-envelope-open text-muted cursor-pointer toggle-lido-cliente';
                        this.setAttribute('data-lido', '1');
                        this.setAttribute('title', 'Marcar como não lido');
                        this.setAttribute('data-bs-toggle', 'tooltip');
                        row.classList.add('opacity-75');
                    } else {
                        // Marca como não lido
                        this.className = 'fas fa-envelope text-primary cursor-pointer toggle-lido-cliente';
                        this.setAttribute('data-lido', '0');
                        this.setAttribute('title', 'Marcar como lido');
                        this.setAttribute('data-bs-toggle', 'tooltip');
                        row.classList.remove('opacity-75');
                    }
                    
                    // Recria tooltip com novo texto
                    new bootstrap.Tooltip(this);
                    
                    // Feedback visual
                    showToast(novoLido === 1 ? 'Cliente marcado como lido!' : 'Cliente marcado como não lido!', 'success');
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

// Atualiza badge de clientes na sidebar
function atualizarBadgeClientes() {
    const formData = new URLSearchParams();
    formData.append(csrfName, csrfToken);
    
    fetch('<?php echo base_url('admin/contarClientesNaoLidos') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success !== undefined) {
            const badgeLink = document.querySelector('a[href*="admin/clientes"]');
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
