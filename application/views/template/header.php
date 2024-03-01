<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!--===== Style JS =====-->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/style.min.css') ?>">

	<link rel="shortcut icon" href="<?php echo base_url('assets/img/logotipo.png') ?>" type="image/x-icon">
	<!-- =====BOX ICONS===== -->
	<link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />

	<script src="<?php echo base_url('assets/js_fixos/jquery.js') ?>"></script>
	<script src="https://code.jquery.com/jquery-3.5.0.js"></script>

	<title>MDM Soluções financeiras</title>
</head>

<body id="pagina-principal">
	<!-- Header -->
	<nav class="header-menu navbar navbar-expand-sm fixed-top navbar-light">
		<div class="container">
			<a class="navbar-brand" href="<?php echo base_url() ?>">
				<img width="120" height="50" class="logotipo-normal img-responsive" src="<?php echo base_url('assets/img/logotipo.png') ?>" alt="MDM Consultoria Financeira" srcset="">
				<img width="120" height="50" class="logotipo-branco d-none img-responsive" src="<?php echo base_url('assets/img/logotipo-white.png') ?>" alt="MDM Consultoria Financeira" srcset="">
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
						<a class="nav-link page-scroll" href="#vantagens">Vantagens</a>
					</li>
					<li class="nav-item">
						<a class="nav-link page-scroll" href="#sobre">Sobre</a>
					</li>
					<li class="nav-item mr-5">
						<a class="nav-link page-scroll" href="#contato">Contato</a>
					</li>
					<li class="nav-item">
						<div class="nav-link custom-control custom-switch altera-tema" title="Alterar tema da página" data-toggle="tooltip">
							<input type="checkbox" class=" custom-control-input" id="customSwitch1">
							<label class="altera-tema custom-control-label" for="customSwitch1"></label>
						</div>
					</li>

				</ul>

			</div>
		</div>
	</nav>
	<!-- /. Header -->