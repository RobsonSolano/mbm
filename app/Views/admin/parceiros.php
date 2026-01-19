<div class="row mb-3">
    <div class="col-12">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalParceiro" onclick="limparForm()">
            <i class="fas fa-plus me-2"></i> Novo Parceiro
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Logo</th>
                        <th>Link</th>
                        <th>Descrição</th>
                        <th>Ordem</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($parceiros)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">Nenhum parceiro cadastrado</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($parceiros as $parceiro): ?>
                            <tr>
                                <td>#<?php echo $parceiro['id'] ?></td>
                                <td><?php echo esc($parceiro['nome']) ?></td>
                                <td>
                                    <?php if (!empty($parceiro['logo'])): ?>
                                        <img src="<?php echo base_url('writable/uploads/parceiros/' . $parceiro['logo']) ?>" 
                                             alt="<?php echo esc($parceiro['nome']) ?>" 
                                             style="max-height: 50px;">
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($parceiro['link'])): ?>
                                        <a href="<?php echo esc($parceiro['link']) ?>" target="_blank" class="text-decoration-none">
                                            <i class="fas fa-external-link-alt"></i> Link
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo esc(substr($parceiro['descricao'] ?? '', 0, 50)) ?><?php echo strlen($parceiro['descricao'] ?? '') > 50 ? '...' : '' ?></td>
                                <td><?php echo $parceiro['ordem'] ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $parceiro['ativo'] ? 'success' : 'secondary' ?>">
                                        <?php echo $parceiro['ativo'] ? 'Ativo' : 'Inativo' ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            onclick="editarParceiro(<?php echo htmlspecialchars(json_encode($parceiro)) ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="post" action="<?php echo base_url('admin/parceiros') ?>" 
                                          class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                        <input type="hidden" name="acao" value="excluir">
                                        <input type="hidden" name="id" value="<?php echo $parceiro['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Parceiro -->
<div class="modal fade" id="modalParceiro" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalParceiroTitle">Novo Parceiro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <?php echo form_open_multipart(base_url('admin/parceiros'), ['id' => 'formParceiro']); ?>
            <div class="modal-body">
                <input type="hidden" name="acao" id="acaoParceiro" value="criar">
                <input type="hidden" name="id" id="idParceiro">
                
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome *</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
                <div class="mb-3">
                    <label for="logo_file" class="form-label">Logo (upload opcional)</label>
                    <input type="file" class="form-control" id="logo_file" name="logo_file" 
                           accept="image/*">
                    <small class="text-muted">Formatos: JPG, PNG, GIF, WEBP</small>
                    <div id="logoPreview" class="mt-2"></div>
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">Link (URL)</label>
                    <input type="url" class="form-control" id="link" name="link" 
                           placeholder="https://exemplo.com.br">
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="ordem" class="form-label">Ordem</label>
                    <input type="number" class="form-control" id="ordem" name="ordem" value="0">
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="ativo" name="ativo" checked>
                        <label class="form-check-label" for="ativo">Ativo</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
function limparForm() {
    document.getElementById('formParceiro').reset();
    document.getElementById('acaoParceiro').value = 'criar';
    document.getElementById('idParceiro').value = '';
    document.getElementById('modalParceiroTitle').textContent = 'Novo Parceiro';
    document.getElementById('ativo').checked = true;
}

function editarParceiro(parceiro) {
    document.getElementById('acaoParceiro').value = 'editar';
    document.getElementById('idParceiro').value = parceiro.id;
    document.getElementById('nome').value = parceiro.nome;
    document.getElementById('logo').value = parceiro.logo || '';
    document.getElementById('link').value = parceiro.link || '';
    document.getElementById('descricao').value = parceiro.descricao || '';
    document.getElementById('ordem').value = parceiro.ordem || 0;
    document.getElementById('ativo').checked = parceiro.ativo == 1;
    document.getElementById('modalParceiroTitle').textContent = 'Editar Parceiro';
    
    var modal = new bootstrap.Modal(document.getElementById('modalParceiro'));
    modal.show();
}
</script>
