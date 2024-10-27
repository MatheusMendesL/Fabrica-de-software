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


require_once('public/header_index.php');

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Console Zone</title>
</head>

<body>



</body>

</html>