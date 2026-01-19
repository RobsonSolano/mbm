<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url('admin/clientes') ?>">Clientes</a></li>
                <li class="breadcrumb-item"><a href="<?php echo base_url('admin/cliente/' . $cliente['id'] . '/servicos') ?>"><?php echo esc($cliente['nome_completo']) ?></a></li>
                <li class="breadcrumb-item active"><?php echo isset($servico) ? 'Editar Serviço' : 'Novo Serviço' ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-tools me-2"></i>
                    <?php echo isset($servico) ? 'Editar Serviço' : 'Novo Serviço' ?>
                </h5>
                <small>Cliente: <?php echo esc($cliente['nome_completo']) ?></small>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">Título do Serviço *</label>
                            <input type="text" name="titulo" class="form-control" required
                                   placeholder="Ex: Instalação de Ar Condicionado Split 12000 BTUs"
                                   value="<?php echo esc($servico['titulo'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data/Hora de Início *</label>
                            <input type="datetime-local" name="data_inicio" class="form-control" required
                                   value="<?php echo isset($servico['data_inicio']) ? date('Y-m-d\TH:i', strtotime($servico['data_inicio'])) : '' ?>">
                            <small class="text-muted">Início do serviço</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data/Hora de Finalização</label>
                            <input type="datetime-local" name="data_finalizacao" class="form-control"
                                   value="<?php echo isset($servico['data_finalizacao']) && $servico['data_finalizacao'] ? date('Y-m-d\TH:i', strtotime($servico['data_finalizacao'])) : '' ?>">
                            <small class="text-muted">Deixe em branco se ainda está em andamento</small>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Descrição do Serviço</label>
                            <textarea name="descricao" id="descricao" class="form-control"><?php echo $servico['descricao'] ?? '' ?></textarea>
                            <small class="text-muted">Descreva detalhes do serviço prestado, materiais utilizados, observações, etc.</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo base_url('admin/cliente/' . $cliente['id'] . '/servicos') ?>" 
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Salvar Serviço
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tinymce@5.10.9/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#descricao',
    height: 400,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | removeformat | code | help',
    content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }',
    language: 'pt_BR',
    branding: false
});
</script>
