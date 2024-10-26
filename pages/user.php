<?php

session_start();

use Database_class\Database;

require_once('../assets/favicon/favicon.php');
require_once('../Database/config.php');
require_once('../Database/Database.php');


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
    header("location:cadastro.php");
}

$coneccao = new Database(MYSQL_CONFIG);
$parametros = [
    ":id" => $id_sessao
];
$query = $coneccao->executar_query('SELECT * FROM cliente WHERE id = :id', $parametros);
$result = $query->results[0];


require_once('../public/header.php');

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style_user_carrinho.css">
    <title>Usuário</title>
</head>

<body>
    <div class="container border border-black mt-4 h-75 w-75">
        <div class="row h-100">
            <div class="col-3 border-end border-black h-100">
                <div class="img mt-2">
                    <img src="../assets/img/icons/icone_cliente.png" alt="Cliente" class="img-fluid">

                </div>
                <p class="text-center h2"> <?= $result->Nome ?></p>
                <div class="row pt-3">
                    <button class="btn btn-outline-light h-100 w-100"><a href="../pages/user.php" class="btn-editar-cad">Atualizar cadastro</button>
                </div>
                <div class="row pt-2">
                    <button class="btn btn-outline-light h-100 w-100"><a href="../pages/user/endereco.php" class="btn-endereco">Adicionar endereço </a></button>
                </div>

                <div class="row pt-5 mt-5">
                    <button class="btn btn-outline-danger h-100 w-100"><a href="../pages/user/delete.php" class="btn-excluir">Excluir conta </a></button>
                </div>
            </div>
            <div class="col">
                <form action="user.php">
                    <div class="row pt-3">
                        <div class="col-6">
                            <label for="Nome" class="form-label">Nome</label>
                            <input type="text" name="Nome" id="1" class="form-control form-control-sm mb-1" value="<?= $results->Nome ?>">
                                </div>
                            <div class="col-6">
                                <label for="Email" class="form-label">Email</label>
                                <input type="text" name="Email" id="2" class="form-control form-control-sm mb-1" value="<?= $results->Email ?>">
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../assets/bootstrap/bootstrap.bundle.js"></script>
</body>

</html>