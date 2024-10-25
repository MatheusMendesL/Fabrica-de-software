<?php


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

if($login){
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
    <link rel="stylesheet" href="assets/css/style_public.css">
</head>

<div class="container-fluid container-header align-items-center justify-content">
    <div class="row">
        <div class="col-3 text-center">
            <img src="assets/img/cad_log/logonovasemfundo.png" alt="Logo empresa" class="logo ml-4">
        </div>
        <div class="col-6 lista">
            <ul class="list-unstyled list-inline text-center p-4 mt-4 lista-alinhada">
                <li class="list-inline-item mx-3"><a href="index.php" class="link">Início</a></li>
                <li class="list-inline-item mx-3"><a href="#" class="link">Catálogo</a></li>
                <li class="list-inline-item mx-3"><a href="#" class="link">Sobre</a></li>
                <li class="list-inline-item mx-3"><a href="#" class="link">Contato</a></li>
            </ul>
        </div>
        <div class="col-3 text-center pr-5">
            <?php if (!$login) : ?>
                <button class="btn btn-primary btn-cad"><a href="pages/cadastro.php" class="link-btn">Cadastrar-se</a></button>
                <button class="btn btn-primary btn-cad"><a href="pages/login.php" class="link-btn">Login</a></button>
            <?php else: ?>
                <button class="btn btn-primary btn-cad">
                    <a href="pages/user.php" class="link-btn">
                        <i class="bi bi-person"></i> <?= $nome ?>
                    </a>
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="assets/bootstrap/bootstrap.bundle.js"></script>