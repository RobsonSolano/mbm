<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!--===== Style JS =====-->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/style.min.css') ?>">
	<?php if (uri_string() == 'novo' || strpos(uri_string(), 'novo') === 0): ?>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/novo-home.css') ?>">
	<?php endif; ?>

	<link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.png') ?>" type="image/x-icon">
	<!-- =====BOX ICONS===== -->
	<link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />

	<script src="<?php echo base_url('assets/js_fixos/jquery.js') ?>"></script>
	<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

	<title><?php echo isset($title) ? $title . ' - ' . APPLICATION_NAME : APPLICATION_NAME ?></title>
	<meta name="description" content="MBM Climatização - Garanta o melhor desempenho do seu ar condicionado e o conforto da sua familia com a nossa manutenção especializada">
</head>

<body>

    <div class="container-fluid p-0 m-0" id="page-not-found">
        <div class="row d-flex justify-content-center align-items-center vh-100">
            <div class="col-12">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-7 justify-content-center d-flex align-items-center flex-column gap-3">
                            <div class="texts">
                                <h1 class="titulo-not-found">404</h1>
                                <h3 class="h2 text-white font-weight-bold">Ooops.. Página não encontrada</h3>
                                <p class="descricao mb-4 text-white" >Parece que a página que tentou acessar não existe.</p>
                                <a href="<?php echo base_url() ?>" class="btn btn btn-light text-dark d-flex gap-2 justify-content-center rounded-pill w-auto px-5 py-2 align-items-center page-not-found-btn-voltar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                                    </svg> <span>Página inicial</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-5 d-none d-lg-flex justify-content-center align-items-center">
                            <img src="<?php echo base_url('assets/img/pagina_nao_encontrada.png') ?>" alt="Página não encontrada" class="img-fluid page-not-found-img" srcset="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>