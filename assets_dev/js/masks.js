$('.cpf-mask').mask("000.000.000-00", {
    reverse: true
});

$('.celular-mask').mask("(00) 90000-0000", {
    reverse: false,
    completed: function() {
        // Garante que não exceda 11 dígitos (2 DDD + 9 números)
        let valor = $(this).val().replace(/\D/g, '');
        if (valor.length > 11) {
            valor = valor.substring(0, 11);
            $(this).val(valor.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3'));
        }
    }
});

// Limita a 11 dígitos numéricos em campos com classe celular-mask
$('.celular-mask').on('input', function() {
    let valor = $(this).val().replace(/\D/g, '');
    if (valor.length > 11) {
        valor = valor.substring(0, 11);
        $(this).mask("(00) 90000-0000").val(valor.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3'));
    }
});

$('.cnpj-mask').mask("000.000.000-000", {
    reverse: true
});

$('.money-mask').mask("#.##0,00", {
    reverse: true
});

$('.perc-mask').mask("000,00", {
    reverse: true
});

$('.weight-mask').mask("#0,000", {
    reverse: true
});
