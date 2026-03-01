<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('admin/fornecedores') ?>">Fornecedores</a></li>
                <li class="breadcrumb-item active"><?php echo isset($fornecedor) ? 'Editar Fornecedor' : 'Novo Fornecedor' ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-truck<?php echo isset($fornecedor) ? '-loading' : '' ?> me-2"></i>
                    <?php echo isset($fornecedor) ? 'Editar Fornecedor' : 'Novo Fornecedor' ?>
                </h5>
            </div>
            <div class="card-body">
                <?php echo form_open(base_url('admin/fornecedores/salvar')); ?>
                    <?php if (isset($fornecedor)): ?>
                        <input type="hidden" name="id" value="<?php echo $fornecedor['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome *</label>
                        <input type="text" class="form-control" id="nome" name="nome" required
                               value="<?php echo esc($fornecedor['nome'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="contato" class="form-label">Contato (telefone)</label>
                        <input type="text" class="form-control" id="contato" name="contato" 
                               placeholder="(11) 99999-9999 ou (11) 1234-5678"
                               maxlength="15"
                               value="<?php echo esc($fornecedor['contato'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo esc($fornecedor['email'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="observacoes" class="form-label">Observações</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3"><?php echo esc($fornecedor['observacoes'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="ativo" name="ativo" 
                                   <?php echo (!isset($fornecedor) || $fornecedor['ativo']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="ativo">Ativo</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo base_url('admin/fornecedores') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Salvar Fornecedor
                        </button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var contato = document.getElementById('contato');
    if (!contato) return;
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
    contato.addEventListener('input', function() { this.value = mascara(this.value); });
    if (contato.value) contato.value = mascara(contato.value);
});
</script>
