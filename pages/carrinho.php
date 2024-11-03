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

if(!$login){
    header("location:cadastro.php");
}

require_once('../public/header.php');

$coneccao = new Database(MYSQL_CONFIG);
$parametros = [
    ':id' => $id_sessao
];
$query = $coneccao->executar_query('SELECT * FROM carrinho WHERE :id = ID_usuario', $parametros);
$result = $query->results[0];
$id_carrinho = $result->ID_carrinho;



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho | Console Zone</title>
</head>
<body>
    <div class="container mt-2">
        <div class="row mt-4">
            <p class="h3 fw-bold">Items no seu carrinho</p>
        </div>
    </div>
</body>
</html>