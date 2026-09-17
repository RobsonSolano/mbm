<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('admin/estoque') ?>">Estoque</a></li>
                <li class="breadcrumb-item active"><?php echo isset($peca) ? 'Editar Peça' : 'Nova Peça' ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-box<?php echo isset($peca) ? '-open' : '' ?> me-2"></i>
                    <?php echo isset($peca) ? 'Editar Peça' : 'Nova Peça' ?>
                </h5>
            </div>
            <div class="card-body">
                <?php echo form_open(base_url('admin/estoque/salvar')); ?>
                    <?php if (isset($peca)): ?>
                        <input type="hidden" name="id" value="<?php echo $peca['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome *</label>
                        <input type="text" class="form-control" id="nome" name="nome" required
                               value="<?php echo esc($peca['nome'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="quantidade" class="form-label">Quantidade *</label>
                        <input type="number" class="form-control" id="quantidade" name="quantidade" 
                               min="0" required value="<?php echo (int)($peca['quantidade'] ?? 0) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="fornecedor_id" class="form-label">Fornecedor</label>
                        <select class="form-select" id="fornecedor_id" name="fornecedor_id">
                            <option value="">-- Nenhum --</option>
                            <?php if (!empty($fornecedores)): ?>
                                <?php foreach ($fornecedores as $f): ?>
                                    <option value="<?php echo $f['id'] ?>" <?php echo (isset($peca) && (int)($peca['fornecedor_id'] ?? 0) === (int)$f['id']) ? 'selected' : '' ?>>
                                        <?php echo esc($f['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="4"><?php echo esc($peca['descricao'] ?? '') ?></textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo base_url('admin/estoque') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Salvar Peça
                        </button>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
