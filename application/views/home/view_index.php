<style>
	.btn-solicitacao:hover {
		background-color: #343a40 !important;
		color: #fff;
		box-shadow: 0px 0px 10px #fff;
		transition: 0.3s;
	}
</style>
<!-- Home -->
<section class="sessao home" id="home">
	<div class="container">
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-7 d-flex mb-lg-0 mb-5 mb-lg-0">
				<div class="align-self-center">

					<h1 class="h1 titulo mb-4">MBM Climatização</h1>

					<p class="descricao">
						Garanta o melhor desempenho do seu ar condicionado e o conforto da sua familia com a nossa manutenção especializada.
					</p>
					<p class="descricao">
						Oferecemos uma ampla gama de serviços elétricos para atender as suas necessidades
					</p>

					<a href="http://api.whatsapp.com/send?phone=+5511949676793&text=<?php echo $mensagem_whatsapp ?>" class="btn btn-primary text-white rounded-pill mt-3 px-4 py-2" target="_blank" title="Entre em contato pelo Whatsapp">
						<span class="h4">
							<i class="fa fa-whatsapp mr-1"></i> Saiba mais
						</span>
					</a>

				</div>
			</div>
			<div class="col-12 col-md-12 col-lg-5 d-flex mt-5 mt-md-0 mt-5 mt-0">
				<div class="area-ima-topo align-self-center d-flex justify-content-center justify-content-lg-end w-100">
					<ul class="list-unstyled d-flex justify-content-start flex-column">
						<li class="d-flex justify-content-start justify-content-lg-end align-items-center gap-4 mb-3 topo-li">
							<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler rounded-circle border border-white circle-icon p-3 icons-tabler-outline icon-tabler-tool">
								<path stroke="none" d="M0 0h24v24H0z" fill="none" />
								<path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" />
							</svg>
							<span class="hard-title ">Excelência técnica</span>
						</li>
						<li class="d-flex justify-content-start  justify-content-lg-end align-items-center gap-4 mb-3 topo-li">
							<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler rounded-circle border border-white circle-icon p-3 icons-tabler-outline icon-tabler-shield-lock">
								<path stroke="none" d="M0 0h24v24H0z" fill="none" />
								<path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" />
								<path d="M12 11m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
								<path d="M12 12l0 2.5" />
							</svg>
							<span class="hard-title ">Suporte e segurança</span>
						</li>
						<li class="d-flex justify-content-start  justify-content-lg-end align-items-center gap-4 mb-3 topo-li">
							<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler rounded-circle border border-white circle-icon p-3 icons-tabler-outline icon-tabler-brand-whatsapp">
								<path stroke="none" d="M0 0h24v24H0z" fill="none" />
								<path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" />
								<path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" />
							</svg>
							<span class="hard-title ">Atendimento rápido</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Sessão de Serviços -->
<section class="servicos sessao" id="servicos">
	<div class="container">
		<div class="row">
			<div class="col-12 mb-4">
				<h2 class="subtitulo h1">Conheça nossos serviços</h2>
				<p class="descricao">Saiba quais são os segmentos que nós atendemos.</p>
			</div>
		</div>

		<div class="row">
			<div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4">
				<div class="card px-3 pt-4 card-servicos">
					<div class="card-body text-start">
						<div class="w-100 text-center mb-3">
							<img loading="lazy" decoding="async" width="100" height="100" src="<?php echo base_url('assets/img/servicos/ar-condicionado.png') ?>" class="lazy" alt="" data-ll-status="loaded">
						</div>
						<h4 class="text-dark">
							Sistemas climatizados
						</h4>
						<p class="text-dark">A Climatização é a disciplina responsável pelo condicionamento do ar ambiente, de forma a gerar conforto, qualidade e segurança no ar consumido pelos ocupantes dos espaços sejam corporativos ou comerciais</p>
						<a href="<?php echo base_url('contato') ?>" target="_self" rel="" class="mb-1 btn btn-dark text-white rounded-pill px-4 btn-saiba-mais">
							Saiba mais
						</a>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4">
				<div class="card px-3 pt-4 card-servicos">
					<div class="card-body text-start">
						<div class="w-100 text-center mb-3">
							<img loading="lazy" decoding="async" width="100" height="100" src="<?php echo base_url('assets/img/servicos/ar-condicionado-refrigerator.png') ?>" class="lazy" alt="">

						</div>
						<h4 class="text-dark">
							Refrigeração Comercial e Industrial
						</h4>
						<p class="text-dark">Planejamos, implementamos e oferecemos manutenção em sistemas de refrigeração comercial e industrial que precisam manter produtos congelados ou resfriados.</p>
						<a href="<?php echo base_url('contato') ?>" target="_self" rel="" class="btn btn-dark text-white rounded-pill px-4 btn-saiba-mais">
							Saiba mais
						</a>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4">
				<div class="card px-3 pt-4 card-servicos">
					<div class="card-body text-start">
						<div class="w-100 text-center mb-3">
							<img loading="lazy" decoding="async" width="100" height="100" src="<?php echo base_url('assets/img/servicos/suporte-tecnico.png') ?>" class="lazy" alt="">
						</div>
						<h4 class="text-dark">
							Assistência Técnica
						</h4>
						<p class="text-dark">Garanta um ambiente livre de contaminação, evitando transmissão de algumas doenças relacionadas à má qualidade do ar, mas também faz com que a vida útil dos equipamentos aumente.</p>
						<a href="<?php echo base_url('contato') ?>" target="_self" rel="" class="mb-1 btn btn-dark text-white rounded-pill px-4 btn-saiba-mais">
							Saiba mais
						</a>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4">
				<div class="card px-3 pt-4 card-servicos">
					<div class="card-body text-start">
						<div class="w-100 text-center mb-3">
							<img loading="lazy" decoding="async" width="100" height="100" src="<?php echo base_url('assets/img/servicos/manutencao-consultoria.png') ?>" class="lazy" alt="">
						</div>
						<h4 class="text-dark">
							Manutenção e Consultoria
						</h4>
						<p class="text-dark">A manutenção é extremamente importante para manter o funcionamento, qualidade e previnir possíveis defeitos, mas também prestamos consultoria para análise de prevenções e implantações de equipamentos</p>
						<a href="<?php echo base_url('contato') ?>" target="_self" rel="" class="mb-1 btn btn-dark text-white rounded-pill px-4 btn-saiba-mais">
							Saiba mais
						</a>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4">
				<div class="card px-3 pt-4 card-servicos">
					<div class="card-body text-start">
						<div class="w-100 text-center mb-3">
							<img loading="lazy" decoding="async" width="100" height="100" src="<?php echo base_url('assets/img/servicos/cuidado.png') ?>" class="lazy" alt="">
						</div>
						<h4 class="text-dark">
							Soluções Sustentáveis
						</h4>
						<p class="text-dark">Com foco no uso de tecnologias sustentáveis, ajudamos nossos clientes a operar com economia, eficiência e zero impacto ambiental.</p>
						<a href="<?php echo base_url('contato') ?>" target="_self" rel="" class="mb-1 btn btn-dark text-white rounded-pill px-4 btn-saiba-mais">
							Saiba mais
						</a>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4">
				<div class="card px-3 pt-4 card-servicos">
					<div class="card-body text-start">
						<div class="w-100 text-center mb-3">
							<img loading="lazy" decoding="async" width="100" height="100" src="<?php echo base_url('assets/img/servicos/classe-energetica.png') ?>" class="lazy" alt="">
						</div>
						<h3 class="text-dark">
							Eficiência Energética
						</h3>
						<p class="text-dark">A eficiência energética consiste em obter o melhor desempenho dos sistemas e dispositivos atrelados ao menor consumo de energia (de qualquer espécie) possível.</p>
						<a href="<?php echo base_url('contato') ?>" target="_self" rel="" class="mb-1 btn btn-dark text-white rounded-pill px-4 btn-saiba-mais">
							Saiba mais
						</a>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>
<!-- /. Sessão de beneficios -->

<section class="contato sessao" id="contato">
	<div class="container">
		<div class="row mb-5">
			<div class="col-12 text-center">
				<h2 class="mb-2">
					Solicite um orçamento
				</h2>
				<p>
					Em breve nossa equipe entrará em contato com você!
				</p>
			</div>
		</div>

		<div id="form-simulacao" class="row d-flex justify-content-center" data-error="<?php echo !empty($form_error) ? 'error' : '' ?>">

			<div class="col-12 col-sm-12 col-md-10 col-lg-9 align-self-center">

				<?php echo form_open('home/enviar') ?>

				<div class="row">

					<div class="col-12 col-sm-12">
						<div class="form-group">
							<?php $data = [
								'type' => 'text',
								'name' => 'nome',
								'id' => 'nome',
								'required' => 'required',
								'placeholder' => 'Digite seu nome completo',
								'value' => set_value('nome', null, false)
							]; ?>

							<?php $data_error = !empty(form_error($data['id'])) || !empty($name_error) ? 'border border-danger' : '' ?>
							<?php $data['class'] = 'form-control input-simulacao ' . $data_error; ?>

							<label for="<?php echo $data['id'] ?>">Nome completo</label>
							<?php echo form_input($data) ?>
							<small class="text-danger">
								<?php echo form_error($data['id']) ?>
								<?php echo !empty($name_error) ? 'Informe o Nome e Sobrenome' : '' ?>

							</small>
						</div>
					</div>

					<div class="col-12 col-sm-12 col-md-6">
						<div class="form-group">
							<?php $data = [
								'type' => 'email',
								'name' => 'email',
								'id' => 'email',
								'placeholder' => 'Digite seu e-mail',
								'class' => 'form-control input-simulacao',
								'value' => set_value('email', null, false)
							]; ?>

							<?php $data_error = !empty(form_error($data['id'])) ? 'border border-danger' : '' ?>
							<?php $data['class'] = 'form-control input-simulacao ' . $data_error; ?>

							<label for="<?php echo $data['id'] ?>">E-mail</label>
							<?php echo form_input($data) ?>
							<small class="text-danger">
								<?php echo form_error($data['id']) ?>
							</small>
						</div>
					</div>

					<div class="col-12 col-sm-12 col-md-6">
						<div class="form-group">
							<?php $data = [
								'type' => 'text',
								'name' => 'celular',
								'id' => 'celular',
								'placeholder' => '(00) 90000-0000',
								'class' => 'form-control celular-mask',
								'value' => set_value('celular', null, false)
							]; ?>

							<?php $data_error = !empty(form_error($data['id'])) ? 'border border-danger' : '' ?>
							<?php $data['class'] = 'form-control celular-mask ' . $data_error; ?>

							<label for="<?php echo $data['id'] ?>">Celular</label>
							<?php echo form_input($data) ?>
							<small class="text-danger">
								<?php echo form_error($data['id']) ?>
							</small>
						</div>
					</div>

					<div class="col-12">
						<label for="observacao">Observação (<small>Deixe alguma observação</small>)</label>
						<textarea name="observacao" id="obervacao" rows="7" class="form-control" value="<?php echo set_value('observacao') ?>"></textarea>
					</div>
					<div class="col-12 d-flex justify-content-center py-3 flex-column align-items-center">
						<div id="recaptcha" class="g-recaptcha <?php echo isset($recaptcha_not_checked) && $recaptcha_not_checked == true ? 'border-danger' : '' ?>" data-sitekey="<?php echo SITE_KEY_RECAPTCHA ?>" data-callback="onSubmitRecaptcha"></div>
						<?php if(isset($recaptcha_not_checked) && $recaptcha_not_checked == true){ ?>
							<p class="text-danger"><small>Por favor marque a caixa de validação acima.</small></p>
						<?php } ?>
					</div>
					<div class="col-12 text-center pt-3">
						<p><strong>Atenção</strong>, nenhum dos dados acima são salvos/armazenados, são apenas para envio da mensagem via e-mail.</p>
					</div>

					<div class="col-12 mt-3 mb-3 d-flex justify-content-center">
						<button type="submit" class="btn btn-lg btn-saiba-mais text-white rounded-pill px-4 d-flex gap-4 align-items-center">
							Solicitar orçamento
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail-forward" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" fill="none" stroke-linecap="round" stroke-linejoin="round">
								<path stroke="none" d="M0 0h24v24H0z" fill="none" />
								<path d="M12 18h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v7.5" />
								<path d="M3 6l9 6l9 -6" />
								<path d="M15 18h6" />
								<path d="M18 15l3 3l-3 3" />
							</svg>
						</button>
					</div>
				</div>

				<?php echo form_close() ?>

			</div>

		</div>
	</div>
</section>
<!-- Sessão de contato -->

<!-- Sessão de como funciona -->
<section class="duvidas-frequentes sessao" id="duvidas-frequentes">
	<div class="container">
		<div class="row mb-5">
			<div class="col-12 text-center">
				<h2>Dúvidas Frequentes</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">

				<!--Accordion wrapper-->

				<!--Accordion wrapper-->
				<div class="accordion" id="accordionExample">
					<div class="row d-flex justify-content-center">

						<div class="col-12 col-sm-10 col-md-8">
							<div class="accordion-item">
								<h2 class="accordion-header" id="headingOne">
									<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
										Cobra taxa antecipada para liberação de empréstimo?
									</button>
								</h2>
								<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
									<div class="accordion-body">
										Não, correspondente bancários conveniados e certificados pelo banco central do Brasil não realizam esse tipo de cobrança.
										Muito cuidado, pode ser golpe.
									</div>
								</div>
							</div>
						</div>

						<div class="col-12 col-sm-10 col-md-8">
							<div class="accordion-item">
								<h2 class="accordion-header" id="headingTwo">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
										Quais tipos de empréstimos disponíveis?
									</button>
								</h2>
								<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
									<div class="accordion-body">
										Empréstimo Consignado : Esse empréstimo é descontado diretamente da folha de pagamento, tem taxas diferenciadas, liberado para Servidores Públicos, Municipais e Federais, também pensionista e aposentados do INSS e algumas empresas privadas que possui convênio.
										Empréstimo Pessoal: atende autônomos, pagamento via boleto ou débito em conta, liberação dos valores de acordo com o perfil de cada cliente e não libera com restrição no nome.
										Empréstimo pessoal com garantia de veículo: Pagamento via boleto ou debito em conta, necessário ter um veículo quitado com ano maior de 2006.
										Empréstimo FGTS: Antecipação de até 7 anos do saque aniversário FGTS
										Liberação para todos os públicos desde de que tenha saldo FGTS, sem burocracia e consultar ao SPC/Serasa.
										Empréstimo na conta de energia: Liberação de valores onde é cobrado diretamente da conta de energia, mesmo com restrição, requisito não ter conta em atraso e a mesma ser no nome do solicitante.

									</div>
								</div>
							</div>
						</div>

						<div class="col-12 col-sm-10 col-md-8">
							<div class="accordion-item">
								<h2 class="accordion-header" id="headingThree">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
										Quais principais banco você trabalha ?
									</button>
								</h2>
								<div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
									<div class="accordion-body">
										Santander, Olé , Banco do Brasil , Pan , Safra , Mercantil , Daycoval, Itaú , Bradesco e outros ...
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
</section>
<!-- /. Sessão de como funciona -->

<!-- Sessão de contato -->


<script src='https://www.google.com/recaptcha/api.js'></script>
  <script>
    (function() {

      $("#form-contato").on('submit', function(e) {
        e.preventDefault();
        validateRecaptcha();
      });

      function validateRecaptcha(event) {
        grecaptcha.execute();
      }

    })();

    function onSubmitRecaptcha(response) {
      document.getElementById('form-recaptcha').submit();
    }
  </script>