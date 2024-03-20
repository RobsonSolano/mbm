<footer class="rodape">
	<div class="container">
		<div class="row">

			<div class="col-12 col-sm-12 col-md-4 mb-3 text-center">
				<img width="200" height="70" class="logotipo-normal d-none img-responsive" src="<?php echo base_url('assets/img/logotipo.svg') ?>" alt="<?php echo APPLICATION_NAME ?>" srcset="">
				<img width="200" height="70" class="logotipo-branco img-responsive" src="<?php echo base_url('assets/img/logotipo.svg') ?>" alt="<?php echo APPLICATION_NAME ?>" srcset="">
			</div>

			<div class="col-6 col-sm-6 col-md-4 p-0 text-center mb-3">
				<h5 class="mb-4 font-weight-bold text-uppercase">Links úteis</h5>
				<hr>
				<ul class="list-group">
				<li class="list-group-item bg-transparent border-0 p-0 mb-2">
						<a href="#home" class="page-scroll">Início</a>
					</li>
					<li class="list-group-item bg-transparent border-0 p-0 mb-2">
						<a href="#servicos" class="page-scroll">Serviços</a>
					</li>
					<li class="list-group-item bg-transparent border-0 p-0 mb-2">
						<a href="#contato" class="page-scroll">Contatos</a>
					</li>
					<li class="list-group-item bg-transparent border-0 p-0 mb-2">
						<a href="#duvidas-frequentes" class="page-scroll">FAQ</a>
					</li>
				</ul>
			</div>

			<div class="col-6 col-sm-6 col-md-4 mb-3 p-0 text-center">
				<h5 class="mb-4 font-weight-bold text-uppercase">Redes Sociais</h5>
				<hr>
				<a href="https://www.instagram.com/mbm.climatizacao/?utm_source=qr&igsh=MWR3OW5rYTZ5aXpvaQ%3D%3D" data-toggle="tooltip" class="link-social" target="_blank" title="Visite nossa página no Instagram">
					<i class="fa fa-instagram mr-1"></i>
				</a>
				<a href="http://api.whatsapp.com/send?phone=+5511949676793&text=Ol%C3%A1%2C%20venho%20atrav%C3%A9s%20do%20site%20e%20gostaria%20de%20fazer%20um%20or%C3%A7amento" data-toggle="tooltip" class="link-social ml-3" target="_blank" title="Entre em contato pelo Whatsapp">
					<i class="fa fa-whatsapp mr-1"></i>
				</a>

			</div>

		</div>
	</div>
</footer>

<div class="rodape-inferior pt-5 pb-2">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 col-md-12">
				<div class="py-4 text-center">
					<p>
						<strong>Horario de atendimento:
						</strong> 9:00 ás 18 horas (dias úteis).
					</p>
					<p>E-mail de contato: mdmsolucoesfinanceiras@gmail.com</p>
					<p class="mr-4">&copy; Todos os direitos reservados - <?php echo date('Y') ?></p>
				</div>
			</div>
		</div>
	</div>
</div>

<span id="whatsapp-flutuante" class="<?php echo isset($hide_whatsapp) && $hide_whatsapp == true ? 'd-none' :'' ?>">
	<a href="https://web.whatsapp.com/send?phone=5511949676793&text=Ol%C3%A1%2C%20venho%20atrav%C3%A9s%20do%20site%20e%20gostaria%20de%20fazer%20um%20or%C3%A7amento" target="_blank">
		<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="#198754" class="bi bi-whatsapp" viewBox="0 0 16 16">
			<path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
		</svg>
		</i>
	</a></i>
</span>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>

<!--===== MAIN JS =====-->
<script src="<?php echo base_url('assets/js/main.min.js') ?>"></script>
</body>

</html>