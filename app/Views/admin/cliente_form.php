<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user<?php echo isset($cliente) ? '-edit' : '-plus' ?> me-2"></i>
                    <?php echo isset($cliente) ? 'Editar Cliente' : 'Novo Cliente' ?>
                </h5>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nome Completo *</label>
                            <input type="text" name="nome_completo" class="form-control" required
                                   value="<?php echo esc($cliente['nome_completo'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Celular *</label>
                            <input type="text" name="celular" id="celular" class="form-control" required
                                   value="<?php echo esc($cliente['celular'] ?? '') ?>"
                                   placeholder="(11) 99999-9999"
                                   maxlength="15">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">E-mail (opcional)</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?php echo esc($cliente['email'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cidade</label>
                            <input type="text" name="cidade" id="cidade" class="form-control" 
                                   list="cidades-sp" autocomplete="off"
                                   value="<?php echo esc($cliente['cidade'] ?? '') ?>"
                                   placeholder="Digite ou selecione uma cidade">
                            <datalist id="cidades-sp">
                                <?php 
                                $cidadesSP = \App\Config\CidadesSP::listar();
                                foreach ($cidadesSP as $cidade): 
                                ?>
                                    <option value="<?php echo esc($cidade) ?>">
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Endereço</label>
                            <textarea name="endereco" class="form-control" rows="2"><?php echo esc($cliente['endereco'] ?? '') ?></textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Observações</label>
                            <textarea name="observacoes" id="observacoes" class="form-control" rows="4"><?php echo esc($cliente['observacoes'] ?? '') ?></textarea>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="bloqueado" 
                                       id="bloqueado" value="1"
                                       <?php echo isset($cliente) && $cliente['bloqueado'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="bloqueado">
                                    <i class="fas fa-lock me-1"></i> Cliente Bloqueado
                                    <small class="text-muted d-block">Marque esta opção para bloquear o acesso/ações deste cliente</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <a href="<?php echo base_url('admin/clientes') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Voltar
                            </a>
                            <?php if (isset($cliente)): ?>
                                <a href="<?php echo base_url('admin/cliente/' . $cliente['id'] . '/servicos') ?>" 
                                   class="btn btn-info">
                                    <i class="fas fa-tools me-1"></i> Histórico de Serviços
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex gap-2">
                            <?php if (isset($cliente)): ?>
                                <button type="button" class="btn btn-danger"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalDeletarCliente">
                                    <i class="fas fa-trash me-1"></i> Excluir
                                </button>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Salvar Cliente
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (isset($cliente)): ?>
<!-- Modal Confirmar Exclusão Cliente -->
<div class="modal fade" id="modalDeletarCliente" tabindex="-1">
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
                <a href="<?php echo base_url('admin/cliente/' . $cliente['id'] . '/deletar') ?>" 
                   class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i> Confirmar
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tinymce@5.10.9/tinymce.min.js"></script>
<script>
$(document).ready(function(){
    $('#celular').mask('(00) 90000-0000', {
        completed: function() {
            // Garante que não exceda 11 dígitos (2 DDD + 9 números)
            let valor = $(this).val().replace(/\D/g, '');
            if (valor.length > 11) {
                valor = valor.substring(0, 11);
                $(this).val(valor.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3'));
            }
        }
    });
    
    // Limita a 11 dígitos numéricos
    $('#celular').on('input', function() {
        let valor = $(this).val().replace(/\D/g, '');
        if (valor.length > 11) {
            valor = valor.substring(0, 11);
            $(this).mask('(00) 90000-0000').val(valor.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3'));
        }
    });
});

tinymce.init({
    selector: '#observacoes',
    height: 300,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
        'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | removeformat | help',
    language: 'pt_BR',
    branding: false
});
</script>
