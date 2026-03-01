<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-cog me-2"></i>
                    Meus Dados Cadastrais
                </h5>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nome *</label>
                            <input type="text" name="nome" class="form-control" required
                                   value="<?php echo esc($admin['nome'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">E-mail *</label>
                            <input type="email" name="email" class="form-control" required
                                   value="<?php echo esc($admin['email'] ?? '') ?>">
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3">
                        <i class="fas fa-lock me-2"></i>
                        Alterar Senha
                    </h6>
                    <p class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Preencha os campos abaixo apenas se desejar alterar sua senha. 
                        Ao alterar a senha, você será desconectado e precisará fazer login novamente.
                    </p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nova Senha</label>
                            <input type="password" name="nova_senha" class="form-control" 
                                   id="nova_senha" placeholder="Deixe em branco para não alterar">
                            <small class="text-muted">Mínimo 8 caracteres</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirmar Nova Senha</label>
                            <input type="password" name="confirmar_senha" class="form-control" 
                                   id="confirmar_senha" placeholder="Confirme a nova senha">
                        </div>
                    </div>


                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo base_url('admin') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Voltar ao Dashboard
                        </a>
                        <button type="submit" class="btn btn-success" id="btnSalvar">
                            <i class="fas fa-save me-1"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Informações da Conta</h6>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Último acesso:</small>
                        <div><?php echo $admin['ultimo_acesso'] ? date('d/m/Y H:i:s', strtotime($admin['ultimo_acesso'])) : 'Nunca' ?></div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Conta criada em:</small>
                        <div><?php echo date('d/m/Y H:i:s', strtotime($admin['criado_em'])) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const novaSenha = document.getElementById('nova_senha');
    const confirmarSenha = document.getElementById('confirmar_senha');
    const btnSalvar = document.getElementById('btnSalvar');
    const form = document.querySelector('form');
    let timeoutValidacao = null;

    function validarSenhas() {
        if (novaSenha.value || confirmarSenha.value) {
            if (novaSenha.value.length > 0 && novaSenha.value.length < 8) {
                showToast('A senha deve ter no mínimo 8 caracteres.', 'warning');
                return false;
            }
            
            if (novaSenha.value && confirmarSenha.value && novaSenha.value !== confirmarSenha.value) {
                showToast('As senhas não conferem.', 'warning');
                return false;
            }
        }
        
        return true;
    }

    // Validação com debounce para não poluir com muitos toasts
    function validarSenhasDebounce() {
        if (timeoutValidacao) {
            clearTimeout(timeoutValidacao);
        }
        timeoutValidacao = setTimeout(validarSenhas, 500);
    }

    novaSenha.addEventListener('input', function() {
        if (confirmarSenha.value) {
            validarSenhasDebounce();
        }
    });
    
    confirmarSenha.addEventListener('input', function() {
        if (novaSenha.value) {
            validarSenhasDebounce();
        }
    });

    form.addEventListener('submit', function(e) {
        if (!validarSenhas()) {
            e.preventDefault();
            return false;
        }

        if (novaSenha.value) {
            if (!confirm('Ao alterar a senha, você será desconectado e precisará fazer login novamente. Deseja continuar?')) {
                e.preventDefault();
                return false;
            }
        }
    });
});
</script>
