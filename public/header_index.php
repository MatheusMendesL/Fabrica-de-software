<?php


use Database_class\Database;

require_once('Database/config.php');
require_once('Database/Database.php');

$login = null;

if (empty($_SESSION['user_id'])) {

    $login = false;
} else {

    $login = true;
}

if ($login) {
    $coneccao = new Database(MYSQL_CONFIG);
    $id = $_SESSION['user_id'];
    $parametros = [
        ':id' => $id
    ];
    $query = $coneccao->executar_query('SELECT * FROM cliente WHERE id = :id', $parametros);
    $results = $query->results[0];
    $nome = $results->Nome;
}

?>


<head>
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style_public.css?">

</head>

<div class="container-fluid container-header" id='inicio'>
    <div class="row align-items-center justify-content-center">
        <div class="col-3 d-flex align-items-center justify-content-center">
            <img src="assets/img/cad_log/logonovasemfundo.png" alt="Logo empresa" class="logo ml-4 img-fluid">
        </div>
        <div class="col-6 d-flex align-items-center justify-content-center lista">
            <ul class="list-unstyled list-inline text-center p-4 mt-3 lista-alinhada">
                <li class="list-inline-item"><a href="index.php" class="link">Início</a></li>
                <li class="list-inline-item"><a href="pages/catalogo.php" class="link">Catálogo</a></li>
                <li class="list-inline-item"><a href="pages/sobre.php" class="link">Sobre</a></li>
                <li class="list-inline-item"><a href="pages/contato.php" class="link">Contato</a></li>
            </ul>
        </div>
        <div class="col-3 align-items-center justify-content-center pr-5 mb-4">
            <?php if (!$login) : ?>
                <button class="btn btn-primary btn-cad" onclick="window.location.href='pages/cadastro.php'">Cadastrar-se</button>
                <button class="btn btn-primary btn-cad" onclick="window.location.href='pages/login.php'">Login</button>
            <?php else: ?>

                <div class="d-flex align-items-center">
                    <button class="btn btn-primary btn-cad me-5" onclick="window.location.href='pages/user.php'">
                        <i class="bi bi-person"></i> <?= $nome ?>
                    </button>
                    <a href="pages/carrinho.php" class="text-link text-light link-offset-2 link-underline-opacity-100-hover mt-4"><i class="bi bi-bag carrinho fs-3 mt-4"></a></i>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="assets/bootstrap/bootstrap.bundle.js"></script>