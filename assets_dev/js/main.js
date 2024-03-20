(function () {
  $('[data-toggle="tooltip"]').tooltip();

  valida_formulario();

  var $pagina = $("#pagina-principal");
  var $btn_tema = $(".altera-tema");

  $btn_tema.on("click", function () {
    $pagina.toggleClass("pagina-dark");
    if ($pagina.hasClass("pagina-dark")) {
      $(".logotipo-normal").addClass("d-none");
      $(".logotipo-branco").removeClass("d-none");
    } else {
      $(".logotipo-normal").removeClass("d-none");
      $(".logotipo-branco").addClass("d-none");
    }
  });

  function valida_formulario() {
    var form_error = $("#form-simulacao").data("error");

    if (form_error != "error") {
      $("#form-simulacao").removeAttr("data-error");
    }

    if (form_error == "error") {
      $("html,body").animate(
        {
          scrollTop:
            $("#form-simulacao").offset().top - $(".navbar").outerHeight() - 50,
        },
        500
      );
    }
  }

  $(".js-btn-enviar-email").on("click", function () {
    var nome = $(".js-input-nome").val();
    var email = $(".js-input-email").val();
    var celular = $(".js-input-celular").val();
    var message = $(".js-input-message").val();

    if (nome != "" && email != "" && celular != "" && message != "") {
      $(this).html("Enviando, aguarde...").attr("disabled", "disabled");
      $("#contactForm").submit();
    }
  });

})();
