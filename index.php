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

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Console Zone</title>
    <link rel="stylesheet" href="assets/css/style_index.css">
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
    <link rel="shortcut icon" href="assets/img/cad_log/logonovasemfundo.png" type="image/x-icon">
</head>

<body>
    <header>
        <div class="container">
            <div class="row">
                <img src="assets/img/cad_log/logonovasemfundo.png" alt="Logo Console Zone">
            </div>
        </div>
    </header>
    
    <div id="carouselExampleCaptions" class="carousel slide">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets/img/index/maos.jpg" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>First slide label</h5>
                    <p>Some representative placeholder content for the first slide.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/img/index/catalogojogos.png" class="d-block w-100 " alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Second slide label</h5>
                    <p>Some representative placeholder content for the second slide.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/img/index/ctt.png" class="d-block w-100" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Third slide label</h5>
                    <p>Some representative placeholder content for the third slide.</p>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <script src="assets/bootstrap/bootstrap.bundle.js"></script>

</body>

</html>