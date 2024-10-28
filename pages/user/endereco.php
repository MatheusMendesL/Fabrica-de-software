<?php

session_start();

use Database_class\Database;

require_once('../../assets/favicon/favicon.php');
require_once('../../Database/config.php');
require_once('../../Database/Database.php');

$login = null;
$aparecer = null;
$id_endereco = null;

if (empty($_SESSION['user_id'])) {
    $login = false;
} else {
    $login = true;
}

$id = isset($_GET['id']) ? $_GET['id'] : "";
$id_sessao = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "";

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

$query = $coneccao->executar_query('SELECT * FROM endereco WHERE ID_cliente = :id', $parametros);
$linhas_mudadas = $query->affected_rows;
$result = $query->results;

if (!empty($_GET['excluir'])) {
    $aparecer = true;
    $id_endereco = $_GET['id'];
}


if(!empty($_GET['delete'])){
    $id_endereco = $_GET['id'];
    $parametros = [
        ':id' => $id_endereco
    ];
    
    $query = $coneccao->execute_non_query('DELETE FROM endereco WHERE ID_endereco = :id', $parametros);
    header("location:../user.php");
}
require_once('header_user.php');

//TODO: arrumar
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/bootstrap/bootstrap.min.css">
    <title>Endereços cadastrados | Console Zone</title>
    <link rel="stylesheet" href="../../assets/css/style_user_carrinho.css?v=1.1">
    <style>
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 2;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .card-fundo {
            width: 100%;
            max-width: 400px;
            padding: 30px;
        }

        .card {
            width: 100%;
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 15px;
                height: 69rem !important;
            }
            .btn {
                font-size: 0.9rem;
            }
            .card {
                margin-bottom: 1rem;
            }
            .img-fluid {
                max-width: 80px;
                margin: 0 auto;
                display: block;
            }
            .h2 {
                font-size: 1.5rem;
            }
            .col-user{
                border-right: 0px;
            }
        }
        
    </style>
</head>

<body>
    <div class="container border border-black mt-4 shadow-lg h-75">
        <div class="row h-100">
            <div class="col-md-3 border-end border-bottom border-black shadow-lg col-user">
                <div class="text-center mt-3">
                    <img src="../../assets/img/icons/icone_cliente.png" alt="Cliente" class="img-fluid mb-2 w-50">
                    <p class="text-dark h2"><?= $primeiroNome ?></p>
                </div>

                <div class="d-grid gap-2 pt-4">
                    <button class="btn btn-outline-dark" onclick="window.location.href='../user.php'">Perfil</button>
                    <button class="btn btn-outline-dark" onclick="window.location.href='../user/endereco.php'">Endereços</button>
                    <button class="btn btn-outline-dark" onclick="window.location.href='../user/senha.php'">Alterar senha</button>
                    <button class="btn btn-outline-danger" onclick="window.location.href='../user/delete.php'">Excluir conta</button>
                </div>
            </div>
            <div class="col-md-9 py-3 shadow">
                <div class="row">
                    <p class="text-center h2 p-3 border-bottom border-black">Endereços cadastrados</p>
                </div>

                <?php if ($linhas_mudadas == 0) : ?>
                    <div class="row justify-content-center py-3">
                        <p class="text-center">Não há nenhum endereço cadastrado</p>
                        <div class="col-12 col-md-6">
                            <button class="btn btn-primary w-100" onclick="window.location.href = 'endereco/new.php'">Cadastre um aqui</button>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="row row-cols-1 row-cols-md-3 g-3 mt-2">
                        <?php foreach ($result as $index => $enderecos) : ?>
                            <div class="col">
                                <div class="card shadow-lg bg-dark text-light h-100">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">Endereço <?= $index + 1 ?></h5>
                                        <h6 class="card-subtitle mb-2"><?= $enderecos->Endereco . ", " .  $enderecos->Numero . " " . $enderecos->Complemento ?></h6>
                                        <p class="card-subtitle mb-2"><?= $enderecos->Cidade . ", " . $enderecos->Estado . " - " . $enderecos->CEP ?></p>
                                        <div class="mt-auto">
                                            <a href="endereco.php?id=<?= $enderecos->ID_endereco ?>&excluir=yes" class="text-danger">Excluir</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                    <div class="row mt-4 text-center">
                        <div class="col-12">
                            <button class="btn btn-primary w-100" onclick="window.location.href='endereco/new.php'">Adicionar novo endereço</button>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($erro)) : ?>
                    <div class="row justify-content-center mt-3">
                        <div class="col-12 col-md-6">
                            <p class="text-danger text-center"><?= $erro ?></p>
                        </div>
                    </div>
                <?php elseif (!empty($msg)) : ?>
                    <div class="row justify-content-center mt-3">
                        <div class="col-12 col-md-6">
                            <p class="text-center"><?= $msg ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($aparecer) : ?>
        <div class="overlay">
            <div class="card card-fundo">
                <p class="text-center">Deseja mesmo excluir esse endereço?</p>
                <div class="d-flex justify-content-between mt-3">
                    <button class="btn btn-outline-danger w-50" onclick="window.location.href='endereco.php?id=<?= $id_endereco ?>&delete=yes'">Sim</button>
                    <button class="btn btn-outline-dark w-50" onclick="window.location.href='endereco.php'">Não</button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="../../assets/bootstrap/bootstrap.bundle.js"></script>
</body>

</html>

