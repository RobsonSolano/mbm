<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : '' ?>Admin - MBM Climatização</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <h5 class="text-white px-3 mb-3">MBM Admin</h5>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo (uri_string() == 'admin' || uri_string() == 'admin/index') ? 'active' : '' ?>" href="<?php echo base_url('admin') ?>">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (uri_string() == 'admin/solicitacoes') ? 'active' : '' ?>" href="<?php echo base_url('admin/solicitacoes') ?>">
                                <i class="fas fa-envelope me-2"></i> Solicitações
                                <?php 
                                try {
                                    $solicitacaoModel = new \App\Models\SolicitacaoModel();
                                    $naoLidas = $solicitacaoModel->where('lido', 0)->countAllResults();
                                    if ($naoLidas > 0): 
                                        $badgeText = $naoLidas > 99 ? '+99' : $naoLidas;
                                ?>
                                        <span class="badge bg-danger rounded-pill ms-1"><?php echo $badgeText ?></span>
                                <?php 
                                    endif;
                                } catch (\Exception $e) {
                                    // Ignora se a coluna 'lido' ainda não existe
                                }
                                ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos(uri_string(), 'admin/cliente') !== false) ? 'active' : '' ?>" href="<?php echo base_url('admin/clientes') ?>">
                                <i class="fas fa-users me-2"></i> Clientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (uri_string() == 'admin/marcas') ? 'active' : '' ?>" href="<?php echo base_url('admin/marcas') ?>">
                                <i class="fas fa-tags me-2"></i> Marcas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo (uri_string() == 'admin/parceiros') ? 'active' : '' ?>" href="<?php echo base_url('admin/parceiros') ?>">
                                <i class="fas fa-handshake me-2"></i> Parceiros
                            </a>
                        </li>
                        <li class="nav-item mt-3 border-top pt-3">
                            <a class="nav-link <?php echo (uri_string() == 'admin/perfil') ? 'active' : '' ?>" href="<?php echo base_url('admin/perfil') ?>">
                                <i class="fas fa-user-cog me-2"></i> Dados Cadastrais
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url('admin/logout') ?>">
                                <i class="fas fa-sign-out-alt me-2"></i> Sair
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?php echo isset($title) ? $title : 'Dashboard' ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="text-muted">Olá, <?php echo session()->get('admin_nome') ?></span>
                    </div>
                </div>

                <?php 
                // Flash messages serão exibidas via toast
                $flash_message = session()->getFlashdata('sucesso');
                $flash_erro = session()->getFlashdata('erro');
                ?>

                <?php if (isset($content)): ?>
                    <?php echo $content; ?>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>
    
    <script>
    // Função global para exibir toasts
    function showToast(message, type = 'success') {
        const toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) return;
        
        const toastId = 'toast-' + Date.now();
        const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : type === 'warning' ? 'bg-warning' : 'bg-info';
        const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle';
        
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-${icon} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 4000
        });
        
        toast.show();
        
        // Remove o elemento após ser escondido
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }
    
    // Exibe toasts de flash messages ao carregar a página
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($flash_message): ?>
            showToast('<?php echo addslashes($flash_message) ?>', 'success');
        <?php endif; ?>
        
        <?php if ($flash_erro): ?>
            showToast('<?php echo addslashes($flash_erro) ?>', 'error');
        <?php endif; ?>
    });
    </script>
</body>
</html>
