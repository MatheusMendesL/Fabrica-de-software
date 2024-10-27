<?php

session_start();

use Database_class\Database;

require_once('../assets/favicon/favicon.php');
require_once('../Database/config.php');
require_once('../Database/Database.php');

$erro = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ligacao = new Database(MYSQL_CONFIG);

    $email = $_POST['Email'];
    $senha = $_POST['Senha'];

    $parametros_verificacao_email = [
        ':email' => $email
    ];


    $query_verificacao_email = $ligacao->executar_query('SELECT * FROM cliente WHERE Email = :email', $parametros_verificacao_email);

    if ($query_verificacao_email->affected_rows == 0) {

        $erro = 'Esse email não existe';
        
    } else {
        $cliente = $query_verificacao_email->results[0];

        if (!password_verify($senha, $cliente->Senha)) {

            $erro = 'A senha está incorreta';

        } else {

            $_SESSION['user_id'] = $cliente->id;
            header('location:../index.php?id=' . $cliente->id);

        }
    }

}



?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Login</title>
</head>

<body>

    <div class="container-fluid container-img">
        <div class="col">
            <img src="../assets/img/cad_log/logonovasemfundo.png" alt="logo empresa" class="img_logo img-fluid">
        </div>
    </div>

    <div class="container container-form">
        <h2 class="text-center p-3">Faça seu login</h2>
        <form action="login.php" method="post">

            <div class="row">
                <div class="col-12">
                    <label for="Email" class="form-label">Email</label>
                    <input type="text" name="Email" id="2" class="form-control mb-1" placeholder="exemplo@dominio.com" required>
                </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <label for="Senha" class="form-label pt-2">Senha</label>
                    <input type="password" name="Senha" id="3" class="form-control  mt-2 mb-1" placeholder="Sua senha" required>
                </div>


                <div class="row">
                    <div class="col-12">
                        <input type="submit" value="Logar" class="btn btn-info mt-3 btn-enviar">
                    </div>
                </div>
        </form>
        <div class="row pt-1">
            <p>Não tem cadastro? <a href="cadastro.php">Clique aqui!</a></p>
        </div>
        <?php if (!empty($erro)) : ?>
            <div class="row">
                <p class="text-danger"><?= $erro ?></p>
            </div>
        <?php endif; ?>
    </div>




    <script src="./assets/bootstrap/bootstrap.bundle.js"></script>
</body>

</html>