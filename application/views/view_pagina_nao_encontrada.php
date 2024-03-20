<div class="container-fluid p-0 m-0" id="page-not-found">
    <div class="row d-flex justify-content-center align-items-center vh-100">
        <div class="col-12">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-7 justify-content-center d-flex align-items-center flex-column gap-3">
                        <div class="texts">
                            <h1 class="titulo-not-found">404</h1>
                            <h3 class="subtitulo">Ooops.. Página não encontrada</h3>
                            <p class="descricao mb-4">Parece que a página que tentou acessar não existe.</p>
                            <a href="<?php echo base_url() ?>" class="btn btn btn-dark d-flex gap-2 justify-content-center rounded-pill w-auto px-5 py-2 align-items-center page-not-found-btn-voltar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                                </svg> <span>Voltar</span>
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