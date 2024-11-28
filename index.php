<?php

session_start();

use Database_class\Database;

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

$consoles = !isset($_GET['consoles']) ? '' : $_GET['consoles'];
$acessorios = !isset($_GET['acessorios']) ? '' : $_GET['acessorios'];
$coneccao = new Database(MYSQL_CONFIG);
$products = null;
$query_results = null;
$estilo_console = null;
$estilo_acessorios = null;



if ($consoles) {
    $parametro = [
        ':id_console' => 1
    ];

    $estilo_console = "color: #1E90FF;";
    $query = $coneccao->executar_query("SELECT * FROM produto WHERE ID_categoria = :id_console LIMIT 5 ", $parametro);
    $linhas_mudadas = $query->affected_rows;
    $query_results = $query->results;
    if ($linhas_mudadas == 0) {
        $products = false;
    } else {
        $products = true;
    }
} elseif ($acessorios) {
    $parametro = [
        ':id_acessorio' => 2
    ];

    $estilo_acessorios = "color: #1E90FF;";

    $query = $coneccao->executar_query("SELECT * FROM produto WHERE ID_categoria = :id_acessorio LIMIT 5", $parametro);
    $linhas_mudadas = $query->affected_rows;
    $query_results = $query->results;
    if ($linhas_mudadas == 0) {
        $products = false;
    } else {
        $products = true;
    }
}

require_once('public/header_index.php');

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style_index.css">
    <title>Console Zone</title>
</head>

<body>
    <div class="container h-75 mt-5 p-2 m-auto shadow-lg d-flex container_inicio mb-5 escondido">
        <div class="row h-100 w-50 align-items-center">
            <div class="col-10">
                <p class="h2 txt_principal">
                    <strong> Bem vindo a nossa loja!</strong>
                </p>
                <div class="row mt-4">
                    <p class="h4 txt_principal">
                        Conheça um pouco dos nossos produtos clicando abaixo!
                    </p>
                </div>
                <div class="row justify-content-center btn_row">
                    <button class="btn btn-light rounded-pill w-50" onclick="window.location.href = '#products'">Clique aqui!</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-4 mx-auto my-auto w-100">
                <img src="assets/img/index/PS5.png" alt="Console PS5" class="img-fluid img_ps5">
            </div>
        </div>
    </div>

    <div class="container container_feedback escondido mt-3">
        <div class="col pb-3">
            <p class="h2 title_feedback pt-5 pb-3">Feedbacks de clientes</p>
        </div>

        <div class="row row-cols-1 row-cols-md-3 gap-5 justify-content-center">
            <div class="card w-25 h-100 card_feedback shadow-lg p-3 ">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title p-2 position-relative card_title_feedback">
                        Mariana Vicente
                    </h6>

                    <h6 class="card-subtitle">
                        "Variedade de produtos com preços bem acessíveis"
                    </h6>
                </div>
            </div>
            <div class="card w-25 h-100 card_feedback shadow-lg p-3 ">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title p-2 position-relative card_title_feedback">
                        André Macedo
                    </h6>

                    <h6 class="card-subtitle">
                        "Produtos de extrema qualidade e um ótimo atendimento online"
                    </h6>
                </div>
            </div>
            <div class="card w-25 h-100 card_feedback shadow-lg p-3 ">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title p-2 position-relative card_title_feedback">
                        Renata Guedes
                    </h6>

                    <h6 class="card-subtitle">
                        "Produto chegou antes do prazo e sem defeitos 10/10"
                    </h6>
                </div>
            </div>
        </div>
    </div>

    <div class="container container_produtos escondido mt-5 pt-5" id="products">
        <div class="row">
            <p class="h2 pt-5">Produtos</p>
        </div>
        <div class="row justify-content-center">
            <div class="row align-items-center justify-content-center mt-2">
                <div class="col-6 d-flex align-items-center justify-content-center lista">
                    <ul class="list-unstyled list-inline text-center p-4 mt-3 lista-alinhada">
                        <li class="list-inline-item"><a href="index.php?consoles=sim" class="products p-3" id="console" style="<?= $estilo_console ?>">Consoles</a></li>
                        <li class="list-inline-item"><a href="index.php?acessorios=sim" class="products p-3" id="acessorios" style="<?= $estilo_acessorios ?>">Acessórios</a></li>

                    </ul>
                </div>
            </div>
        </div>
        <div class="row d-flex gap-5 justify-content-between mt-5">
            <?php if ($products == true) :  ?>
                <?php foreach ($query_results as $resultados) : ?>
                    <div class="card card_products bg-white shadow text-center" onclick="window.location.href = 'pages/produto.php?id=<?= $resultados->ID_produto ?>'" style="width: 11em !important;">
                        <img src="<?= $resultados->img_produto ?>" alt="Imagem de produto" class="img-fluid img_card mx-auto h-100">
                        <div class="card-body d-flex flex-column p-3">
                            <h6 class="card-title mt-3"><?= $resultados->Nome_produto ?></h6>
                            <h6 class="card-title preco_product mt-auto">R$<?= $resultados->Preco ?></h6>
                        </div>
                    </div>

                <?php endforeach; ?>

            <?php elseif ($products == false) : ?>
                <div class="row d-flex justify-content-center">
                    <p class="alert alert-warning text-center mt-3 w-50">Não há produtos</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="assets/bootstrap/bootstrap.bundle.js"></script>
    <script src="assets/js/script_index.js"></script>
    <?php
    require_once('public/footer_index.php');
    ?>


</body>



</html>