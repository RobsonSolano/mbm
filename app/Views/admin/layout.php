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
                                $solicitacaoModel = new \App\Models\SolicitacaoModel();
                                $pendentes = $solicitacaoModel->where('status', 'pendente')->countAllResults();
                                if ($pendentes > 0): 
                                ?>
                                    <span class="badge bg-warning text-dark rounded-pill ms-1"><?php echo $pendentes ?></span>
                                <?php endif; ?>
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
                $flash_message = session()->getFlashdata('sucesso');
                if ($flash_message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $flash_message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php 
                $flash_erro = session()->getFlashdata('erro');
                if ($flash_erro): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $flash_erro ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($content)): ?>
                    <?php echo $content; ?>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
