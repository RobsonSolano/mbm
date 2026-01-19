<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h4 class="mb-0">Visão Geral</h4>
                <p class="text-muted mb-0">Estatísticas gerais do sistema</p>
            </div>
        </div>
    </div>
</div>

<!-- Cards Principais -->
<div class="row mb-4">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Total Solicitações</h6>
                        <h2 class="mb-0 fw-bold"><?php echo number_format($total_solicitacoes) ?></h2>
                        <small class="opacity-75"><?php echo $solicitacoes_mes ?> este mês</small>
                    </div>
                    <i class="fas fa-envelope fa-3x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Pendentes</h6>
                        <h2 class="mb-0 fw-bold"><?php echo number_format($solicitacoes_pendentes) ?></h2>
                        <small class="opacity-75"><?php echo $solicitacoes_hoje ?> hoje</small>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Em Atendimento</h6>
                        <h2 class="mb-0 fw-bold"><?php echo number_format($solicitacoes_em_atendimento) ?></h2>
                        <small class="opacity-75"><?php echo $solicitacoes_semana ?> esta semana</small>
                    </div>
                    <i class="fas fa-user-cog fa-3x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 opacity-75">Resolvidas</h6>
                        <h2 class="mb-0 fw-bold"><?php echo number_format($solicitacoes_resolvidas) ?></h2>
                        <small class="opacity-75">Taxa: <?php echo $total_solicitacoes > 0 ? round(($solicitacoes_resolvidas / $total_solicitacoes) * 100, 1) : 0 ?>%</small>
                    </div>
                    <i class="fas fa-check-circle fa-3x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas de Acessos -->
<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 text-muted">Acessos Hoje</h6>
                    <i class="fas fa-calendar-day fa-2x text-primary opacity-25"></i>
                </div>
                <h2 class="mb-0 fw-bold text-primary"><?php echo number_format($acessos_hoje) ?></h2>
                <small class="text-muted">Total: <?php echo number_format($total_acessos) ?> acessos</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 text-muted">Acessos Esta Semana</h6>
                    <i class="fas fa-calendar-week fa-2x text-info opacity-25"></i>
                </div>
                <h2 class="mb-0 fw-bold text-info"><?php echo number_format($acessos_semana) ?></h2>
                <small class="text-muted">Últimos 7 dias</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 text-muted">Acessos Este Mês</h6>
                    <i class="fas fa-calendar-alt fa-2x text-success opacity-25"></i>
                </div>
                <h2 class="mb-0 fw-bold text-success"><?php echo number_format($acessos_mes) ?></h2>
                <small class="text-muted">Mês atual</small>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos e Estatísticas -->
<div class="row mb-4">
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Acessos - Últimos 7 Dias</h5>
            </div>
            <div class="card-body">
                <canvas id="chartAcessos" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Solicitações - Últimos 7 Dias</h5>
            </div>
            <div class="card-body">
                <canvas id="chartSolicitacoes" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Informações Adicionais -->
<div class="row mb-4">
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-tags fa-3x text-primary mb-3"></i>
                <h3 class="fw-bold"><?php echo number_format($total_marcas) ?></h3>
                <p class="text-muted mb-0">Marcas Cadastradas</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-handshake fa-3x text-success mb-3"></i>
                <h3 class="fw-bold"><?php echo number_format($total_parceiros) ?></h3>
                <p class="text-muted mb-0">Parceiros Cadastrados</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-percentage fa-3x text-info mb-3"></i>
                <h3 class="fw-bold"><?php echo number_format($taxa_conversao, 2) ?>%</h3>
                <p class="text-muted mb-0">Taxa de Conversão</p>
                <small class="text-muted">(Solicitações / Acessos)</small>
            </div>
        </div>
    </div>
</div>

<!-- Top Cidades -->
<?php if (!empty($top_cidades)): ?>
<div class="row mb-4">
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Top 5 Cidades com Mais Solicitações</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <?php $posicao = 1; ?>
                    <?php foreach ($top_cidades as $cidade): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <span class="badge bg-primary rounded-pill me-2"><?php echo $posicao++ ?></span>
                                <strong><?php echo esc($cidade['cidade']) ?></strong>
                            </div>
                            <span class="badge bg-secondary rounded-pill"><?php echo $cidade['total'] ?> solicitações</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">Últimos Acessos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th>IP</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ultimos_acessos)): ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted">Nenhum acesso registrado</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($ultimos_acessos as $acesso): ?>
                                    <tr>
                                        <td><code><?php echo esc($acesso['ip']) ?></code></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($acesso['data_acesso'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Últimas Solicitações -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Últimas Solicitações</h5>
                <a href="<?php echo base_url('admin/solicitacoes') ?>" class="btn btn-sm btn-primary">
                    Ver Todas
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Cidade</th>
                                <th>Status</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($ultimas_solicitacoes)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Nenhuma solicitação encontrada</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($ultimas_solicitacoes as $solicitacao): ?>
                                    <tr>
                                        <td>#<?php echo $solicitacao['id'] ?></td>
                                        <td><?php echo esc($solicitacao['nome']) ?></td>
                                        <td><?php echo esc($solicitacao['email']) ?></td>
                                        <td><?php echo esc($solicitacao['cidade'] ?? '-') ?></td>
                                        <td>
                                            <?php
                                            $badge_class = [
                                                'pendente' => 'warning',
                                                'em_atendimento' => 'info',
                                                'resolvido' => 'success',
                                                'cancelado' => 'danger'
                                            ];
                                            $class = $badge_class[$solicitacao['status']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?php echo $class ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $solicitacao['status'])) ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($solicitacao['criado_em'])) ?></td>
                                        <td>
                                            <a href="<?php echo base_url('admin/solicitacoes') ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Gráfico de Acessos
const ctxAcessos = document.getElementById('chartAcessos');
if (ctxAcessos) {
    new Chart(ctxAcessos, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($acessos_ultimos_7_dias, 'data')) ?>,
            datasets: [{
                label: 'Acessos',
                data: <?php echo json_encode(array_column($acessos_ultimos_7_dias, 'total')) ?>,
                borderColor: 'rgb(13, 110, 253)',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// Gráfico de Solicitações
const ctxSolicitacoes = document.getElementById('chartSolicitacoes');
if (ctxSolicitacoes) {
    new Chart(ctxSolicitacoes, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_column($solicitacoes_ultimos_7_dias, 'data')) ?>,
            datasets: [{
                label: 'Solicitações',
                data: <?php echo json_encode(array_column($solicitacoes_ultimos_7_dias, 'total')) ?>,
                backgroundColor: 'rgba(255, 193, 7, 0.8)',
                borderColor: 'rgb(255, 193, 7)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}
</script>
