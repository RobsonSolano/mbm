<?php
$vista = $vista ?? 'mes';
$novo = $novo ?? false;
$nomesDias = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
$hoje = date('Y-m-d');
$slotsHora = [8,9,10,11,12,13,14,15,16,17,18];
$diasSemana = $diasSemana ?? [];
$agendamentosPorDia = $agendamentosPorDia ?? [];
$clientes = $clientes ?? [];
$slots = $slots ?? \App\Models\AgendamentoModel::getSlotsHorario();
$dataInicial = $dataInicial ?? null;
$qsNovo = $novo ? '&novo=1' : '';
?>
<style>
.ag-cal-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 12px 12px 0 0; padding: 1rem 1.25rem; }
.ag-cal-body { background: #fff; border-radius: 0 0 12px 12px; box-shadow: 0 4px 20px rgba(0,0,0,.08); overflow: hidden; }
.ag-cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); border-collapse: separate; }
.ag-cal-weekday { padding: 0.75rem; text-align: center; font-weight: 600; font-size: 0.8rem; color: #64748b; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
.ag-cal-weekday.weekend { background: #fef3c7; color: #92400e; }
.ag-cal-day { min-height: 100px; padding: 0.5rem; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; transition: all .15s; }
.ag-cal-day:hover { background: #f1f5f9; }
.ag-cal-day.weekend { background: #fffbeb; }
.ag-cal-day.weekend:hover { background: #fef3c7; }
.ag-cal-day.today { background: #dbeafe; border-color: #3b82f6; box-shadow: inset 0 0 0 2px #3b82f6; }
.ag-cal-day.today:hover { background: #bfdbfe; }
.ag-cal-day-num { font-size: 0.95rem; font-weight: 600; color: #334155; margin-bottom: 0.35rem; }
.ag-cal-day.today .ag-cal-day-num { color: #1d4ed8; }
.ag-cal-badge { display: inline-flex; align-items: center; justify-content: center; min-width: 22px; height: 22px; padding: 0 6px; font-size: 0.75rem; font-weight: 600; background: #6366f1; color: #fff; border-radius: 999px; }
.ag-cal-badge.zero { display: none; }
.ag-tabs .nav-link { border: none; font-weight: 500; color: #64748b; }
.ag-tabs .nav-link.active { color: #6366f1; background: transparent; border-bottom: 2px solid #6366f1; }
.ag-tabs .nav-link:hover { color: #4f46e5; }
.ag-semana-grid { display: grid; grid-template-columns: 60px repeat(7, 1fr); }
.ag-semana-header { padding: 0.6rem; text-align: center; font-weight: 600; font-size: 0.8rem; background: #f8fafc; border: 1px solid #e2e8f0; }
.ag-semana-header.weekend { background: #fef3c7; }
.ag-semana-hour { padding: 0.4rem; font-size: 0.75rem; color: #64748b; background: #f8fafc; border: 1px solid #e2e8f0; }
.ag-semana-cell { min-height: 48px; padding: 0.35rem; border: 1px solid #e2e8f0; cursor: pointer; background: #fff; transition: background .15s; }
.ag-semana-cell:hover { background: #f1f5f9; }
.ag-semana-cell.weekend { background: #fffbeb; }
.ag-semana-event { font-size: 0.75rem; padding: 0.25rem 0.4rem; margin-bottom: 0.2rem; border-radius: 6px; border-left: 3px solid #6366f1; background: #eef2ff; }
.ag-semana-event.concluido { border-left-color: #22c55e; background: #dcfce7; }
/* Mobile: calendário mensal mais compacto */
@media (max-width: 767.98px) {
    .ag-cal-header { padding: 0.75rem 0.5rem; font-size: 0.9rem; }
    .ag-cal-grid { grid-template-columns: repeat(7, 1fr); }
    .ag-cal-weekday { padding: 0.35rem 0.2rem; font-size: 0.65rem; }
    .ag-cal-day { min-height: 65px; padding: 0.3rem; }
    .ag-cal-day-num { font-size: 0.8rem; margin-bottom: 0.2rem; }
    .ag-cal-badge { min-width: 18px; height: 18px; padding: 0 4px; font-size: 0.65rem; }
    .ag-cal-body { overflow-x: auto; -webkit-overflow-scrolling: touch; border-radius: 8px; }
    .ag-cal-body .ag-cal-grid { min-width: 280px; }
    /* Tabs e controles */
    .ag-tabs .nav-link { font-size: 0.85rem; padding: 0.4rem 0.6rem; }
    .ag-tabs .nav { flex-wrap: nowrap; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .d-flex.justify-content-between.mb-4 { flex-direction: column; gap: 0.75rem; align-items: stretch !important; }
    .d-flex.justify-content-between.mb-4 .d-flex.align-items-center { flex-direction: row; flex-wrap: wrap; }
    .d-flex.justify-content-between.mb-4 h5 { font-size: 0.95rem; min-width: auto !important; }
    .d-flex.justify-content-between.mb-4 .btn { font-size: 0.8rem; }
    .d-flex.justify-content-between.mb-4 .vr { display: none !important; }
    /* Grade semanal: scroll horizontal suave, células maiores para touch */
    .ag-semana-grid { min-width: 560px; touch-action: pan-x; }
    .ag-semana-header { padding: 0.4rem 0.25rem; font-size: 0.65rem; }
    .ag-semana-header small { font-size: 0.6rem; }
    .ag-semana-hour { padding: 0.3rem; font-size: 0.65rem; }
    .ag-semana-cell { min-height: 52px; padding: 0.25rem; }
    .ag-semana-event { font-size: 0.7rem; padding: 0.2rem 0.3rem; }
    .ag-semana-event small { display: none; }
    .ag-semana-event .btn-link { font-size: 0.65rem; padding: 0.1rem 0; }
}
/* Modais e cards no modal */
@media (max-width: 575.98px) {
    .modal-footer { flex-wrap: wrap; gap: 0.5rem; }
    .modal-footer .btn { flex: 1 1 auto; min-width: 120px; }
    #modalDiaLista .card .d-flex { flex-direction: column; align-items: flex-start !important; gap: 0.5rem; }
    #modalDiaLista .btn-group { align-self: stretch; justify-content: flex-start; }
}
</style>

<?php if ($novo): ?>
<div class="alert alert-info d-flex align-items-center mb-3 flex-wrap" role="alert">
    <i class="fas fa-info-circle me-2 fa-lg"></i>
    <span><strong>Clique no dia</strong> para inserir o agendamento.</span>
</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <ul class="nav nav-tabs ag-tabs mb-0">
            <li class="nav-item">
                <a class="nav-link <?php echo $vista === 'mes' ? 'active' : '' ?>" href="<?php 
                    $anoMes = ($vista === 'semana') ? [date('Y'), date('m')] : [($ano ?? date('Y')), ($mes ?? date('m'))];
                    echo base_url('admin/agendamentos?vista=mes&ano=' . $anoMes[0] . '&mes=' . $anoMes[1] . $qsNovo);
                ?>"><i class="fas fa-calendar-alt me-1"></i> Mês</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $vista === 'semana' ? 'active' : '' ?>" href="<?php 
                    $hojeTs = strtotime(date('Y-m-d'));
                    $dow = (int) date('w', $hojeTs); // 0=Dom, 1=Seg, ..., 6=Sab
                    $dom = strtotime("-{$dow} days", $hojeTs);
                    echo base_url('admin/agendamentos?vista=semana&ano=' . date('Y', $dom) . '&mes=' . date('m', $dom) . '&dia=' . date('d', $dom) . $qsNovo);
                ?>"><i class="fas fa-calendar-week me-1"></i> Semana</a>
            </li>
        </ul>
        <div class="vr d-none d-md-block"></div>
        <?php if ($vista === 'mes'): ?>
        <?php $mesAnt = ($mes ?? 1) == 1 ? 12 : ($mes ?? 1) - 1; $anoAnt = ($mes ?? 1) == 1 ? ($ano ?? date('Y')) - 1 : ($ano ?? date('Y')); ?>
        <?php $mesProx = ($mes ?? 1) == 12 ? 1 : ($mes ?? 1) + 1; $anoProx = ($mes ?? 1) == 12 ? ($ano ?? date('Y')) + 1 : ($ano ?? date('Y')); ?>
        <a href="<?php echo base_url('admin/agendamentos?ano=' . $anoAnt . '&mes=' . $mesAnt . $qsNovo) ?>" class="btn btn-light btn-sm"><i class="fas fa-chevron-left"></i></a>
        <h5 class="mb-0 px-2" style="min-width:180px; text-align:center;"><?php echo esc($tituloMes ?? '') ?></h5>
        <a href="<?php echo base_url('admin/agendamentos?ano=' . $anoProx . '&mes=' . $mesProx . $qsNovo) ?>" class="btn btn-light btn-sm"><i class="fas fa-chevron-right"></i></a>
        <a href="<?php echo base_url('admin/agendamentos?ano=' . date('Y') . '&mes=' . date('m') . $qsNovo) ?>" class="btn btn-outline-primary btn-sm">Hoje</a>
        <?php elseif ($vista === 'semana'): ?>
        <?php $dom = strtotime($semanaInicio ?? $hoje); $domAnt = strtotime('-7 days', $dom); $domProx = strtotime('+7 days', $dom); ?>
        <a href="<?php echo base_url('admin/agendamentos?vista=semana&ano=' . date('Y', $domAnt) . '&mes=' . date('m', $domAnt) . '&dia=' . date('d', $domAnt) . $qsNovo) ?>" class="btn btn-light btn-sm"><i class="fas fa-chevron-left"></i></a>
        <h5 class="mb-0 px-2" style="min-width:220px; text-align:center;"><?php echo esc($tituloSemana ?? $tituloMes ?? '') ?></h5>
        <a href="<?php echo base_url('admin/agendamentos?vista=semana&ano=' . date('Y', $domProx) . '&mes=' . date('m', $domProx) . '&dia=' . date('d', $domProx) . $qsNovo) ?>" class="btn btn-light btn-sm"><i class="fas fa-chevron-right"></i></a>
        <a href="<?php echo base_url('admin/agendamentos?vista=semana&ano=' . date('Y') . '&mes=' . date('m') . '&dia=' . date('d') . $qsNovo) ?>" class="btn btn-outline-primary btn-sm">Hoje</a>
        <?php endif; ?>
    </div>
    <?php if ($novo): ?>
    <a href="<?php echo base_url('admin/agendamentos?ano=' . ($ano ?? date('Y')) . '&mes=' . ($mes ?? date('m')) . '&dia=' . ($dia ?? date('d')) . '&vista=' . $vista) ?>" class="btn btn-outline-secondary btn-sm">Sair do modo novo</a>
    <?php else: ?>
    <a href="<?php echo base_url('admin/agendamentos?novo=1&ano=' . ($ano ?? date('Y')) . '&mes=' . ($mes ?? date('m')) . '&dia=' . ($dia ?? date('d')) . '&vista=' . $vista) ?>" class="btn btn-primary shadow-sm"><i class="fas fa-plus me-1"></i> Novo Agendamento</a>
    <?php endif; ?>
</div>

<?php if ($vista === 'mes'): ?>
<?php
$primeiroDia = strtotime(($ano ?? date('Y')) . '-' . ($mes ?? date('m')) . '-01');
$numDias = date('t', $primeiroDia);
$diaSemana1 = (int) date('w', $primeiroDia); // 0=Dom, 6=Sáb
$celulasVazias = $diaSemana1;
$contagemPorDia = $contagemPorDia ?? [];
?>
<div class="ag-cal-body">
    <div class="ag-cal-grid">
        <?php foreach ($nomesDias as $i => $nome): ?>
        <div class="ag-cal-weekday <?php echo $i === 0 || $i === 6 ? 'weekend' : '' ?>"><?php echo $nome ?></div>
        <?php endforeach; ?>
        <?php for ($i = 0; $i < $celulasVazias; $i++): ?>
        <div class="ag-cal-day" style="background:#f8fafc; cursor:default;"></div>
        <?php endfor;
        for ($d = 1; $d <= $numDias; $d++):
            $dataStr = sprintf('%04d-%02d-%02d', $ano ?? date('Y'), $mes ?? date('m'), $d);
            $dw = (int) date('N', strtotime($dataStr));
            $isWeekend = in_array($dw, [6, 7]);
            $isHoje = ($dataStr === $hoje);
            $total = $contagemPorDia[$dataStr] ?? 0;
        ?>
        <div class="ag-cal-day cal-cell <?php echo $isWeekend ? 'weekend' : '' ?> <?php echo $isHoje ? 'today' : '' ?>" data-data="<?php echo $dataStr ?>">
            <div class="ag-cal-day-num"><?php echo $d ?></div>
            <?php if ($total > 0): ?>
            <span class="ag-cal-badge"><?php echo $total ?></span>
            <?php endif; ?>
        </div>
        <?php endfor;
        $restantes = (7 - (($celulasVazias + $numDias) % 7)) % 7;
        for ($i = 0; $i < $restantes; $i++): ?>
        <div class="ag-cal-day" style="background:#f8fafc; cursor:default;"></div>
        <?php endfor; ?>
    </div>
</div>

<?php elseif ($vista === 'semana'): ?>
<div class="ag-cal-body">
    <div class="ag-semana-grid">
        <div class="ag-semana-header"></div>
        <?php foreach ($diasSemana as $ds): ?>
        <div class="ag-semana-header <?php echo in_array($ds['nome'] ?? '', ['Sáb','Dom']) ? 'weekend' : '' ?>">
            <?php echo esc($ds['nome'] ?? '') ?><br>
            <small class="text-muted"><?php echo (int)($ds['dia'] ?? 0) ?>/<?php echo (int)($ds['mes'] ?? 0) ?></small>
        </div>
        <?php endforeach; ?>
        <?php foreach ($slotsHora as $h): ?>
        <div class="ag-semana-hour"><?php echo sprintf('%02d:00', $h) ?></div>
        <?php foreach ($diasSemana as $ds): 
            $dataStr = trim($ds['data'] ?? '');
            $list = $agendamentosPorDia[$dataStr] ?? [];
            $naHora = array_filter($list, function($a) use ($h) {
                if (empty($a['hora_inicio'])) return false;
                $p = explode(':', trim((string)$a['hora_inicio']));
                return ((int)($p[0] ?? 0)) === $h;
            });
        ?>
        <div class="ag-semana-cell semana-cell <?php echo in_array($ds['nome'] ?? '', ['Sáb','Dom']) ? 'weekend' : '' ?>" 
             data-data="<?php echo esc($dataStr) ?>" data-hora="<?php echo sprintf('%02d:00', $h) ?>">
            <?php foreach ($naHora as $ag): 
                $hi = isset($ag['hora_inicio']) ? substr((string)$ag['hora_inicio'], 0, 5) : '';
                $hf = !empty($ag['hora_fim']) ? substr((string)$ag['hora_fim'], 0, 5) : '';
            ?>
            <div class="ag-semana-event <?php echo ($ag['status'] ?? '') === 'concluido' ? 'concluido' : '' ?>" data-agendamento-id="<?php echo (int)($ag['id'] ?? 0) ?>">
                <strong><?php echo $hi ?><?php echo $hf ? " – {$hf}" : '' ?></strong>
                <?php echo esc($ag['cliente_nome'] ?? '-') ?>
                <small class="d-block text-muted text-truncate"><?php echo esc(mb_substr($ag['descricao'] ?? '', 0, 35)) ?><?php echo mb_strlen($ag['descricao'] ?? '') > 35 ? '…' : '' ?></small>
                <a href="<?php echo base_url('admin/agendamento/' . ($ag['id'] ?? '') . '/editar') ?>" class="btn btn-xs btn-link p-0 mt-1" onclick="event.stopPropagation()">Editar</a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="modal fade" id="modalDia" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Agendamentos – <span id="modalDiaTitulo"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modalDiaLista"></div>
                <p class="text-muted small mt-2" id="modalDiaVazio" style="display:none;">Nenhum agendamento neste dia.</p>
            </div>
            <div class="modal-footer">
                <a href="#" id="btnNovoNesseDia" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Novo agendamento</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNovoAgendamento" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-sm-down">
        <div class="modal-content shadow-lg">
            <?php echo form_open(base_url('admin/agendamento/salvar'), ['id' => 'formNovoAgendamento']); ?>
            <input type="hidden" name="data" id="formNovoData">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Novo Agendamento – <span id="formNovoDiaTitulo"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Horário *</label>
                    <select name="hora_inicio" id="formNovoHora" class="form-select" required>
                        <option value="">Carregando...</option>
                    </select>
                    <small id="formNovoHoraOcupado" class="text-danger d-none">Este horário está ocupado.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cliente *</label>
                    <select name="cliente_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($clientes as $c): ?>
                        <option value="<?php echo $c['id'] ?>"><?php echo esc($c['nome_completo']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descrição *</label>
                    <textarea name="descricao" class="form-control" rows="2" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="formNovoSubmit"><i class="fas fa-save me-1"></i> Salvar</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
var modoNovo = <?php echo $novo ? 'true' : 'false' ?>;
var slotsAll = <?php echo json_encode($slots) ?>;
var baseSlotsOcupados = '<?php echo base_url('admin/agendamentos/slots-ocupados') ?>';

document.querySelectorAll('.cal-cell, .semana-cell').forEach(function(cell) {
    cell.addEventListener('click', function(e) {
        if (e.target.tagName === 'A' || e.target.closest('a')) return;
        var data = cell.getAttribute('data-data');
        if (!data) return;

        if (modoNovo) {
            abrirModalNovo(data, cell.getAttribute('data-hora'));
            return;
        }

        var clickedEvent = e.target.closest('.ag-semana-event');
        var onlyId = clickedEvent ? clickedEvent.getAttribute('data-agendamento-id') : null;

        var modal = new bootstrap.Modal(document.getElementById('modalDia'));
        document.getElementById('modalDiaTitulo').textContent = formatarDataBR(data);
        document.getElementById('btnNovoNesseDia').href = '<?php echo base_url('admin/agendamentos') ?>?novo=1&ano=<?php echo $ano ?? date('Y') ?>&mes=<?php echo $mes ?? date('m') ?>&dia=<?php echo $dia ?? date('d') ?>&vista=<?php echo $vista ?>&data=' + data;

        fetch('<?php echo base_url('admin/agendamentos/dia') ?>/' + data)
            .then(function(r) { return r.json(); })
            .then(function(res) {
                var ags = res.agendamentos || [];
                if (onlyId) ags = ags.filter(function(a) { return String(a.id) === String(onlyId); });
                var lista = document.getElementById('modalDiaLista');
                var vazio = document.getElementById('modalDiaVazio');
                lista.innerHTML = '';
                vazio.style.display = (ags.length) ? 'none' : 'block';
                if (ags.length) {
                    ags.forEach(function(a) {
                        var statusBadge = a.status === 'concluido' ? 'success' : a.status === 'cancelado' ? 'secondary' : 'primary';
                        var hi = (a.hora_inicio || '').substring(0, 5);
                        var hf = (a.hora_fim || '') ? ' – ' + a.hora_fim.substring(0, 5) : '';
                        lista.innerHTML += '<div class="card mb-2 border-0 shadow-sm"><div class="card-body py-2"><div class="d-flex justify-content-between align-items-start"><div><strong>' + hi + hf + '</strong> <span class="badge bg-' + statusBadge + '">' + a.status + '</span><br><small>' + (a.cliente_nome || '') + '</small><br><small class="text-muted">' + (a.descricao || '') + '</small></div><div class="btn-group btn-group-sm">' + (a.status === 'agendado' ? '<a href="<?php echo base_url('admin/agendamento') ?>/' + a.id + '/editar" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a><form action="<?php echo base_url('admin/agendamento/concluir') ?>" method="post" class="d-inline"><input type="hidden" name="id" value="' + a.id + '"><button type="submit" class="btn btn-outline-success btn-sm"><i class="fas fa-check"></i></button></form><form action="<?php echo base_url('admin/agendamento/cancelar') ?>" method="post" class="d-inline"><input type="hidden" name="id" value="' + a.id + '"><button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-times"></i></button></form>' : '') + '</div></div></div></div>';
                    });
                }
                modal.show();
            })
            .catch(function() {
                document.getElementById('modalDiaLista').innerHTML = '<p class="text-danger">Erro ao carregar.</p>';
                modal.show();
            });
    });
});

function abrirModalNovo(data, horaPref) {
    document.getElementById('formNovoData').value = data;
    document.getElementById('formNovoDiaTitulo').textContent = formatarDataBR(data);
    document.getElementById('formNovoHora').innerHTML = '<option value="">Carregando...</option>';
    document.getElementById('formNovoHoraOcupado').classList.add('d-none');
    document.getElementById('formNovoSubmit').disabled = true;

    var modal = new bootstrap.Modal(document.getElementById('modalNovoAgendamento'));
    modal.show();

    fetch(baseSlotsOcupados + '/' + data)
        .then(function(r) { return r.json(); })
        .then(function(res) {
            var ocupados = (res.ocupados || []);
            var sel = document.getElementById('formNovoHora');
            sel.innerHTML = '<option value="">Selecione o horário</option>';
            slotsAll.forEach(function(s) {
                var opt = document.createElement('option');
                opt.value = s;
                opt.textContent = s;
                if (ocupados.indexOf(s) >= 0) {
                    opt.disabled = true;
                    opt.textContent = s + ' (ocupado)';
                } else if (horaPref && s.substring(0, 5) === horaPref.substring(0, 5)) {
                    opt.selected = true;
                }
                sel.appendChild(opt);
            });
            document.getElementById('formNovoSubmit').disabled = false;
        })
        .catch(function() {
            document.getElementById('formNovoHora').innerHTML = '<option value="">Erro ao carregar</option>';
        });
}

function formatarDataBR(str) { var p = str.split('-'); return p[2] + '/' + p[1] + '/' + p[0]; }

<?php if ($novo && $dataInicial): ?>
document.addEventListener('DOMContentLoaded', function() { abrirModalNovo('<?php echo esc($dataInicial) ?>', null); });
<?php endif; ?>
</script>
