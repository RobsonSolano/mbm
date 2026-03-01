<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato</title>
</head>

<style>
    body {
        background-color: #f8f8ff;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        width: 100%;
        padding: 24px 16px;
        gap: 16px;
    }

    .container {
        display: flex;
        align-self: center;
        flex-direction: column;
        border-radius: 3px;
        padding: 16px;
        width: 80%;
        text-align: start;
        background-color: #fff;
    }
    h2{
        text-align: center;
    }
    h4{
        text-align: center;
        color: #666;
        font-weight: 400;
    }

    .dados-formulario{
        border-top: 1px solid #ccc;
        padding-top: 10px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 10px;
    }

    .rodape-txt{
        text-align: center;
        color: #666;
        font-weight: 400;
    }
</style>

<body>

    <div class="container">
        <h2>Novo contato vindo do site</h2>
        <h4><i>Este e-mail é um e-mail automático, não responda este e-mail.</i></h4>
        
        <div class="dados-formulario">
            <h3><u>Dados do contato</u></h3>
            <p><strong>Nome</strong>: <?php echo isset($nome) ? $nome : '' ?></p>
            <p><strong>E-mail</strong>: <?php if (!empty($email)): ?><a href="mailto:<?php echo $email ?>"><?php echo $email ?></a><?php else: ?>Não informado<?php endif; ?></p>
            <p><strong>Celular</strong>: <?php if (!empty($celular)): ?><a href="tel:+<?php echo $celular ?>"><?php echo $celular ?></a><?php else: ?>Não informado<?php endif; ?></p>
            <?php if (!empty($cidade)): ?>
            <p><strong>Cidade</strong>: <?php echo $cidade ?></p>
            <?php endif; ?>
            <p><strong>Observação</strong>:
                <?php echo isset($observacao) && !empty($observacao) ? $observacao : "Nenhuma observação informada." ?>
            </p>
        </div>

        <p class="rodape-txt">Formulário preenchido em: <b><?php echo date('d/m/Y H:i:s') ?></b></p>
    </div>

</body>

</html>