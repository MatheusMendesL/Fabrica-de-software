<?php

session_start();

use Database_class\Database;

require_once('../../Database/config.php');
require_once('../../Database/Database.php');


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

if (!$login) {
    header("location:../cadastro.php");
}

$coneccao = new Database(MYSQL_CONFIG);
$parametros = [
    ":id" => $id_sessao
];
$query = $coneccao->executar_query('SELECT * FROM cliente WHERE id = :id', $parametros);
$result = $query->results[0];

$nomeCompleto = $result->Nome;
$nomeCompleto = trim($nomeCompleto);

$partes = explode(" ", $nomeCompleto);
$primeiroNome = $partes[0];


require_once('header_user.php');

if (!empty($_GET['delete'])) {
    session_unset();
    session_destroy();
    $query = $coneccao->execute_non_query('DELETE FROM cliente WHERE id = :id', $parametros);
    header("location:../../index.php");
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/bootstrap/bootstrap.min.css">
    <title>Excluir conta | Console Zone</title>
    <link rel="stylesheet" href="../../assets/css/style_user_carrinho.css?v=1.1">
    <style>
        .container {
            max-width: 1200px;
        }

        .col-user {
            border-right: 1px solid #000;
        }

        .img-fluid {
            max-width: 150px;
            margin: 0 auto;
            display: block;
        }

        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 15px;
            }

            .btn {
                font-size: 0.9rem;
            }

            .card {
                margin-bottom: 1rem;
            }

            .img-fluid {
                max-width: 60px;
            }

            .h2 {
                font-size: 1.5rem;
            }

            .col-user {
                border-right: 0;
                border-bottom: 1px solid #000;
            }
        }

        @media (min-width: 992px) {
            .container-todo {
                height: 30em !important;
            }

            .col-user {
                height: 30em;
            }

        }
    </style>
</head>

<body>
    <div class="container border border-black mt-4 shadow-lg">
        <div class="row row-btns">
            <div class="col-md-3 col-sm-12 border-end border-bottom border-black shadow-lg col-user text-center py-3 col-user">
                <img src="../../assets/img/icons/user.png" alt="Cliente" class="img-fluid mb-2">
                <p class="h2 text-white"><?= $primeiroNome ?></p>

                <div class="d-grid gap-4 pt-3">
                    <button class="btn btn-outline-light w-100" onclick="window.location.href='../../pages/user.php'">Perfil</button>
                    <button class="btn btn-outline-light w-100" onclick="window.location.href='../../pages/user/endereco.php'">Endereços</button>
                    <button class="btn btn-outline-light w-100" onclick="window.location.href='../../pages/user/senha.php'">Alterar senha</button>
                    <button class="btn btn-outline-danger w-100" onclick="window.location.href='../../pages/user/delete.php'">Excluir conta</button>
                </div>
            </div>
            <div class="col py-3 mx-auto">
                <p class="text-center mt-5 pt-5">Deseja mesmo excluir sua conta?</p>
                <div class="col text-center pt-3">
                    <a href="delete.php?&delete=yes" class="btn btn-outline-danger w-25">Sim</a>
                    <a href="../user.php" class="btn btn-outline-light w-25">Não</a>
                </div>
            </div>
        </div>
    </div>
    <script src="../../assets/bootstrap/bootstrap.bundle.js"></script>
</body>

</html>