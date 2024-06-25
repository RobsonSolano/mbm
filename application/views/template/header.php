<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!--===== Style JS =====-->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/style.min.css') ?>">

	<link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.png') ?>" type="image/x-icon">
	<!-- =====BOX ICONS===== -->
	<link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />

	<script src="<?php echo base_url('assets/js_fixos/jquery.js') ?>"></script>
	<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

	<title><?php echo APPLICATION_NAME ?></title>
	<meta name="description" content="MBM Climatização - Garanta o melhor desempenho do seu ar condicionado e o conforto da sua familia com a nossa manutenção especializada">
</head>

<body id="pagina-principal" class="pagina-dark">
	<!-- Header -->
	<nav class="header-menu navbar navbar-expand-sm fixed-top navbar-light" style="box-shadow: none !important;">
		<div class="container">
			<a class="navbar-brand" href="<?php echo base_url() ?>">
				<img width="150" height="50" class="logotipo-normal  d-none img-responsive" src="<?php echo base_url('assets/img/logotipo.svg') ?>" alt="<?php echo APPLICATION_NAME ?>Consultoria Financeira" srcset="">
				<img width="150" height="50" class="logotipo-branco img-responsive" src="<?php echo base_url('assets/img/logotipo-white.svg') ?>" alt="<?php echo APPLICATION_NAME ?>Consultoria Financeira" srcset="">
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavMenu" aria-controls="navbarNavMenu" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNavMenu">
				<ul class="navbar-nav ml-auto">

					<li class="nav-item active">
						<a class="nav-link" href="#home">Início</a>
					</li>
					<li class="nav-item">
						<a class="nav-link page-scroll" href="#servicos">Serviços</a>
					</li>

					<li class="nav-item">
						<a class="nav-link page-scroll" href="#contato">Contato</a>
					</li>
					<li class="nav-item">
						<a class="nav-link page-scroll" href="#duvidas-frequentes">FAQ</a>
					</li>
					<!-- <li class="nav-item">
						<div class="nav-link custom-control custom-switch altera-tema" title="Alterar tema da página" data-toggle="tooltip">
							<input type="checkbox" class=" custom-control-input" id="customSwitch1">
							<label class="altera-tema custom-control-label" for="customSwitch1"></label>
						</div>
					</li> -->
				</ul>
			</div>
		</div>
	</nav>
	<!-- /. Header -->
	 
	<?php $flash_message = $this->session->flashdata('flash_message'); ?>
	<?php if (isset($flash_message)) { ?>
		<div class="flash-message d-flex justify-content-center">
			<div class="alert flash alert-<?php echo $flash_message['tipo'] ?>">
				<h5><?php echo $flash_message['mensagem'] ?></h5>
			</div>
		</div>
	<?php } ?>