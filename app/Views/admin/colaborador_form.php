<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('admin/colaboradores') ?>">Colaboradores</a></li>
                <li class="breadcrumb-item active"><?php echo isset($colaborador) ? 'Editar' : 'Novo Colaborador' ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-tie me-2"></i>
                    <?php echo isset($colaborador) ? 'Editar Colaborador' : 'Novo Colaborador' ?>
                </h5>
            </div>
            <div class="card-body">
                <?php echo form_open(base_url('admin/colaboradores/salvar')) ?>
                    <?php if (isset($colaborador)): ?>
                        <input type="hidden" name="id" value="<?php echo esc($colaborador['id']) ?>">
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="nome" class="form-label">Nome *</label>
                            <input type="text" class="form-control" id="nome" name="nome" required
                                   value="<?php echo esc($colaborador['nome'] ?? '') ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nivel" class="form-label">Nível *</label>
                            <select class="form-select" id="nivel" name="nivel" required>
                                <?php foreach (['dono' => 'Dono', 'lider' => 'Líder', 'tecnico' => 'Técnico', 'assistente' => 'Assistente'] as $val => $label): ?>
                                    <option value="<?php echo $val ?>"
                                        <?php echo ($colaborador['nivel'] ?? '') === $val ? 'selected' : '' ?>>
                                        <?php echo $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">E-mail <small class="text-muted">(necessário para notificações)</small></label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?php echo esc($colaborador['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone"
                                   placeholder="(11) 99999-9999"
                                   maxlength="15"
                                   value="<?php echo esc($colaborador['telefone'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="data_inicio_contrato" class="form-label">Início do Contrato</label>
                            <input type="date" class="form-control" id="data_inicio_contrato" name="data_inicio_contrato"
                                   value="<?php echo esc($colaborador['data_inicio_contrato'] ?? date('Y-m-d')) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="data_fim_contrato" class="form-label">Fim do Contrato <small class="text-muted">(se desligado)</small></label>
                            <input type="date" class="form-control" id="data_fim_contrato" name="data_fim_contrato"
                                   value="<?php echo esc($colaborador['data_fim_contrato'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="bloqueado" name="bloqueado"
                                   <?php echo (!empty($colaborador['bloqueado'])) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="bloqueado">Bloqueado (não recebe notificações nem aparece em seleções)</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo base_url('admin/colaboradores') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Salvar Colaborador
                        </button>
                    </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var tel = document.getElementById('telefone');
    if (!tel) return;
    function mascara(v) {
        v = v.replace(/\D/g, '');
        if (v.length > 11) v = v.substring(0, 11);
        if (v.length <= 10) {
            v = v.replace(/^(\d{2})(\d{4})(\d{0,4})$/, function(m, ddd, p1, p2) {
                return p2 ? '(' + ddd + ') ' + p1 + '-' + p2 : '(' + ddd + ') ' + p1;
            });
        } else {
            v = v.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
        }
        return v;
    }
    tel.addEventListener('input', function() { this.value = mascara(this.value); });
    if (tel.value) tel.value = mascara(tel.value);
});
</script>
