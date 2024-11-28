<?php

session_start();

require_once('../assets/favicon/favicon.php');
require_once('../Database/config.php');
require_once('../Database/Database.php');
require_once('../public/header.php');

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

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo de produtos | Console Zone</title>
    <link rel="stylesheet" href="../assets/css/style_catalogo_sobre.css">
</head>

<body>


    <div class="container mt-2 container-sobre rounded p-4 ">
        <div class="row">
            <p class="h1">Sobre</p>
        </div>

        <div class="col">

            <div class="row mt-4">
                <p class="h3">Quem somos</p>
                <p class="h5 mt-3">Somos uma empresa especializada em venda de consoles e produtos relacionados, como acessórios para o seu console, além de termos uma variedade de marcas.</p>
            </div>

            <div class="row mt-4">
                <p class="h3">Nossa visão</p>
                <p class="h5 mt-3">Ser a referência número um em vendas de consoles e acessórios, promovendo a diversão e a inovação tecnológica.</p>
            </div>

            <div class="row mt-4 mb-3">
                <p class="h3">Nossa Missão</p>
                <p class="h5 mt-3">Oferecer produtos de alta qualidade, suporte excepcional e uma experiência de compra inesquecível para nossos clientes.</p>
            </div>
        </div>
    </div>

    <?php
    require_once('../public/footer_index.php')
    ?>
</body>

</html>