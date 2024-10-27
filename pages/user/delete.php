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


require_once('header_user.php');

if(!empty($_GET['delete'])){
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
</head>

<body>
    <div class="container border border-black mt-4 h-75 w-75 shadow-lg">
        <div class="row h-100">
            <div class="col-3 border-end border-black h-100 shadow-lg">
                <div class="img mt-3">
                    <img src="../../assets/img/icons/icone_cliente.png" alt="Cliente" class="img-fluid">

                </div>
                <p class="text-center h2 pt-2 text-dark"> <?= $primeiroNome ?></p>

                <div class="row pt-4">
                    <button class="btn btn-outline-dark h-100 w-100" onclick="window.location.href='../user.php'">Perfil</button>
                </div>

                <div class="row pt-2">
                    <button class="btn btn-outline-dark h-100 w-100" onclick="window.location.href='../user/endereco.php'">Endereços</button>
                </div>

                <div class="row pt-2">
                    <button class="btn btn-outline-dark h-100 w-100" onclick="window.location.href='../user/senha.php'">Alterar senha</button>
                </div>


                <div class="row pt-2">
                    <button class="btn btn-outline-danger h-100 w-100" onclick="window.location.href='../user/delete.php'"><a href="#" class="btn-excluir">Excluir conta </a></button>
                </div>

            </div>
            <div class="col py-3 mx-auto shadow">
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