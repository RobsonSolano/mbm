<div class="row mb-3">
    <div class="col-12">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMarca" onclick="limparForm()">
            <i class="fas fa-plus me-2"></i> Nova Marca
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
                        <th>Descrição</th>
                        <th>Ordem</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($marcas)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Nenhuma marca cadastrada</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($marcas as $marca): ?>
                            <tr>
                                <td>#<?php echo $marca['id'] ?></td>
                                <td><?php echo esc($marca['nome']) ?></td>
                                <td>
                                    <?php if (!empty($marca['logo'])): ?>
                                        <img src="<?php echo base_url('writable/uploads/marcas/' . $marca['logo']) ?>" 
                                             alt="<?php echo esc($marca['nome']) ?>" 
                                             style="max-height: 50px;">
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo esc(substr($marca['descricao'] ?? '', 0, 50)) ?><?php echo strlen($marca['descricao'] ?? '') > 50 ? '...' : '' ?></td>
                                <td><?php echo $marca['ordem'] ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $marca['ativo'] ? 'success' : 'secondary' ?>">
                                        <?php echo $marca['ativo'] ? 'Ativo' : 'Inativo' ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            onclick="editarMarca(<?php echo htmlspecialchars(json_encode($marca)) ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="post" action="<?php echo base_url('admin/marcas') ?>" 
                                          class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                        <input type="hidden" name="acao" value="excluir">
                                        <input type="hidden" name="id" value="<?php echo $marca['id'] ?>">
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

<!-- Modal Marca -->
<div class="modal fade" id="modalMarca" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMarcaTitle">Nova Marca</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <?php echo form_open_multipart(base_url('admin/marcas'), ['id' => 'formMarca']); ?>
            <div class="modal-body">
                <input type="hidden" name="acao" id="acaoMarca" value="criar">
                <input type="hidden" name="id" id="idMarca">
                
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
    document.getElementById('formMarca').reset();
    document.getElementById('acaoMarca').value = 'criar';
    document.getElementById('idMarca').value = '';
    document.getElementById('modalMarcaTitle').textContent = 'Nova Marca';
    document.getElementById('ativo').checked = true;
}

function editarMarca(marca) {
    document.getElementById('acaoMarca').value = 'editar';
    document.getElementById('idMarca').value = marca.id;
    document.getElementById('nome').value = marca.nome;
    document.getElementById('logo').value = marca.logo || '';
    document.getElementById('descricao').value = marca.descricao || '';
    document.getElementById('ordem').value = marca.ordem || 0;
    document.getElementById('ativo').checked = marca.ativo == 1;
    document.getElementById('modalMarcaTitle').textContent = 'Editar Marca';
    
    var modal = new bootstrap.Modal(document.getElementById('modalMarca'));
    modal.show();
}
</script>
