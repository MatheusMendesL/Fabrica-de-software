<?php

session_start();

use Database_class\Database;

require_once('assets/favicon/favicon.php');
require_once('Database/config.php');
require_once('Database/Database.php');


$login = null;


if (empty($_SESSION['user_id'])) {

    $login = false;
} else {

    $login = true;
}

$id = isset($_GET['id']) ? $_GET['id'] : "";
$id_sessao = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "";


if (isset($_GET['id']) && $id != $id_sessao) {

    echo "O id foi alterado <br>";
    echo "Clique nesse link para login <a href='login.php'> Aqui </a> <br>";
    echo "Clique aqui para cadastrar <a href='cadastro.php'> Aqui </a>";
    exit();
}


require_once('public/header_index.php');

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style_index.css?">
    <title>Console Zone</title>
</head>

<body>
    <div class="container h-75 mt-3 p-2 m-auto shadow-lg d-flex container_inicio">
        <div class="row h-100 w-50 align-items-center">
            <div class="col-9">
                <p class="h2 txt_principal">
                    <strong> Bem vindo a nossa loja!</strong>
                </p>
                <p class="h4 txt_principal">
                    Conheça um pouco dos nosso produtos clicando abaixo!
                </p>
                <div class="row justify-content-center btn_row">
                    <button class="btn btn-light rounded-pill w-50">Clique aqui!</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4 mx-auto my-auto w-100">
                <img src="assets/img/index/PS5.png" alt="Console PS5" class="img-fluid img_ps5">
            </div>
        </div>
    </div>

    <div class="container">
        Produtos
    </div>
</body>
</html>