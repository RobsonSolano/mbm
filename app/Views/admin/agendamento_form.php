<?php
$agendamento = $agendamento ?? null;
$isEdicao = !empty($agendamento);
$dataPreenchida = $dataPreenchida ?? date('Y-m-d');
$horaPreenchida = $horaPreenchida ?? null;
$slots = $slots ?? \App\Models\AgendamentoModel::getSlotsHorario();
$clientes = $clientes ?? [];
?>
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-<?php echo $isEdicao ? 'edit' : 'plus' ?> me-2"></i>
                    <?php echo $isEdicao ? 'Editar Agendamento' : 'Novo Agendamento' ?>
                </h5>
            </div>
            <div class="card-body">
                <?php echo form_open(base_url('admin/agendamento/salvar')); ?>
                    <?php if ($isEdicao): ?>
                    <input type="hidden" name="id" value="<?php echo $agendamento['id'] ?>">
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Data *</label>
                            <input type="date" name="data" class="form-control" required
                                   value="<?php echo esc($dataPreenchida) ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Hora Início *</label>
                            <select name="hora_inicio" class="form-select" required id="hora_inicio">
                                <?php foreach ($slots as $s): ?>
                                <?php 
                                    $hi = $isEdicao ? substr(($agendamento ?? [])['hora_inicio'] ?? '', 0, 5) : ($horaPreenchida ? substr($horaPreenchida, 0, 5) : '');
                                    $sel = $hi && substr($s, 0, 5) === $hi;
                                    ?>
                                    <option value="<?php echo $s ?>" <?php echo $sel ? 'selected' : '' ?>><?php echo $s ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Hora Fim</label>
                            <select name="hora_fim" class="form-select" id="hora_fim">
                                <option value="">1h depois</option>
                                <?php foreach ($slots as $s): ?>
                                <option value="<?php echo $s ?>" <?php echo ($agendamento && !empty($agendamento['hora_fim']) && substr($agendamento['hora_fim'], 0, 5) === $s) ? 'selected' : '' ?>><?php echo $s ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 mb-2">
                            <span id="statusSlot" class="small"></span>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Cliente *</label>
                            <select name="cliente_id" class="form-select" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($clientes as $c): ?>
                                <option value="<?php echo $c['id'] ?>" <?php echo ($agendamento && isset($agendamento['cliente_id']) && $agendamento['cliente_id'] == $c['id']) ? 'selected' : '' ?>>
                                    <?php echo esc($c['nome_completo']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Descrição *</label>
                            <textarea name="descricao" class="form-control" rows="2" required><?php echo esc(($agendamento ?? [])['descricao'] ?? '') ?></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="agendado" <?php echo (($agendamento ?? [])['status'] ?? 'agendado') === 'agendado' ? 'selected' : '' ?>>Agendado</option>
                                <option value="concluido" <?php echo (($agendamento ?? [])['status'] ?? '') === 'concluido' ? 'selected' : '' ?>>Concluído</option>
                                <option value="cancelado" <?php echo (($agendamento ?? [])['status'] ?? '') === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Observações</label>
                            <textarea name="observacoes" class="form-control" rows="2"><?php echo esc(($agendamento ?? [])['observacoes'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo base_url('admin/agendamentos') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Salvar
                        </button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const base = '<?php echo base_url('admin/agendamento/verificar-conflito') ?>';
    const id = '<?php echo ($agendamento ?? [])['id'] ?? '' ?>';
    const statusEl = document.getElementById('statusSlot');
    const dataEl = document.querySelector('input[name="data"]');
    const horaInicioEl = document.getElementById('hora_inicio');
    const horaFimEl = document.getElementById('hora_fim');
    function check() {
        const data = dataEl?.value, hi = horaInicioEl?.value, hf = horaFimEl?.value;
        if (!data || !hi) { statusEl.textContent = ''; return; }
        statusEl.textContent = 'Verificando...';
        statusEl.className = 'small text-muted';
        let url = base + '?data=' + encodeURIComponent(data) + '&hora_inicio=' + encodeURIComponent(hi);
        if (hf) url += '&hora_fim=' + encodeURIComponent(hf);
        if (id) url += '&id=' + id;
        fetch(url).then(r => r.json()).then(res => {
            if (!res.success) return;
            if (res.conflito) {
                statusEl.textContent = 'Conflito: este horário já está ocupado.';
                statusEl.className = 'small text-danger fw-bold';
            } else {
                statusEl.textContent = 'Disponível';
                statusEl.className = 'small text-success';
            }
        }).catch(function() { statusEl.textContent = ''; });
    }
    [dataEl, horaInicioEl, horaFimEl].forEach(function(el) { if (el) el.addEventListener('change', check); });
    if (dataEl && dataEl.value && horaInicioEl && horaInicioEl.value) setTimeout(check, 300);
})();
</script>
