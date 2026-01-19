<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('admin/marcas') ?>">Marcas</a></li>
                <li class="breadcrumb-item active"><?php echo isset($marca) ? 'Editar Marca' : 'Nova Marca' ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-tag<?php echo isset($marca) ? '-edit' : '-plus' ?> me-2"></i>
                    <?php echo isset($marca) ? 'Editar Marca' : 'Nova Marca' ?>
                </h5>
            </div>
            <div class="card-body">
                <?php echo form_open_multipart(base_url('admin/marcas/salvar')); ?>
                    <?php if (isset($marca)): ?>
                        <input type="hidden" name="id" value="<?php echo $marca['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome *</label>
                        <input type="text" class="form-control" id="nome" name="nome" required
                               value="<?php echo esc($marca['nome'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="logo_file" class="form-label">Logo (upload opcional)</label>
                        <?php if (isset($marca) && !empty($marca['logo'])): ?>
                            <div class="mb-2">
                                <img src="<?php echo base_url('writable/uploads/marcas/' . $marca['logo']) ?>" 
                                     alt="<?php echo esc($marca['nome']) ?>" 
                                     class="img-thumbnail" style="max-height: 100px;">
                                <p class="text-muted small mt-1">Logo atual</p>
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="logo_file" name="logo_file" 
                               accept="image/*">
                        <small class="text-muted">Formatos: JPG, PNG, GIF, WEBP</small>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo esc($marca['descricao'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="ordem" class="form-label">Ordem</label>
                        <input type="number" class="form-control" id="ordem" name="ordem" 
                               value="<?php echo $marca['ordem'] ?? 0 ?>">
                        <small class="text-muted">Usado para ordenar a exibição (menor número aparece primeiro)</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="ativo" name="ativo" 
                                   <?php echo (!isset($marca) || $marca['ativo']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="ativo">Ativo</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo base_url('admin/marcas') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Salvar Marca
                        </button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
