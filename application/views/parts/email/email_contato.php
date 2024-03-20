<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato</title>
</head>

<style>
    body{
        background-color: #f8f8ff;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 16px;
    }

    .container{
        display: flex;
        align-self: center;
        max-width: 650px;
        border-radius: 16px;
        padding: 16px;
        text-align: start;
        background-color: #fff;
    }
</style>

<body>

    <div class="container">
        <h3>Novo contato vindo do site</h3>
        <p><strong>Nome</strong>: <?php echo $nome ?></p>
        <p><strong>E-mail</strong>: <a href="mailto:<?php echo $email ?>"><?php echo $email ?></a></p>
        <p><strong>Celular</strong>: <a href="tel:+<?php echo $celular ?>"><?php echo $celular ?></a></p>
        <p><strong>Observação</strong>:
            <?php echo isset($observacao) && !empty($observacao) ? $observacao : "Nenhuma observação informada." ?>
        </p>
    </div>

</body>

</html>