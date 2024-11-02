<?php

session_start();

use Database_class\Database;

require_once('../../assets/favicon/favicon.php');
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senha_nv = $_POST['nv_senha'];
    $conf_senha = $_POST['conf_senha'];
    $senha_criptografada = password_hash($conf_senha, PASSWORD_DEFAULT);

    if ($senha_nv != $conf_senha) {
        $erro = "As senhas são diferentes";
    } elseif ($senha_nv == null or $conf_senha == null) {
        $erro = "Alguma informação está vazia";
    } else {
        $parametros = [
            ":id" => $id_sessao,
            ":senha" => $senha_criptografada
        ];

        $query = $coneccao->execute_non_query('UPDATE cliente SET Senha = :senha WHERE id = :id', $parametros);
        $msg = "Senha alterada com sucesso";
    }
}

require_once('header_user.php');





?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/bootstrap/bootstrap.min.css">
    <title>Alterar senha | Console Zone</title>
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
        <div class="row">
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
            <div class="col-md-9 py-3 mx-auto">
                <form action="senha.php" method="post">
                    <div class="row">
                        <p class="text-center h2 p-3 border-bottom border-black">Alterar senha</p>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            <label for="nv_senha" class="form-label">Nova senha</label>
                            <input type="password" name="nv_senha" id="1" class="form-control form-control-sm mb-1">
                        </div>
                    </div>

                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            <label for="conf_senha" class="form-label">Confirme a senha</label>
                            <input type="password" name="conf_senha" id="2" class="form-control form-control-sm mb-1">
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary w-100">Mudar</button>
                        </div>
                    </div>
                </form>
                <?php if (!empty($erro)) : ?>
                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            <p class="text-danger text-center"><?= $erro ?></p>
                        </div>
                    </div>
                <?php elseif (!empty($msg)) : ?>
                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            <p class="text-center"><?= $msg ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="../../assets/bootstrap/bootstrap.bundle.js"></script>
</body>

</html>