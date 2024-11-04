<?php

session_start();

use Database_class\Database;

require_once('../assets/favicon/favicon.php');
require_once('../Database/config.php');
require_once('../Database/Database.php');


$login = null;
$items = null;


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

require_once('../public/header.php');

$coneccao = new Database(MYSQL_CONFIG);
$parametros = [
    ':id' => $id_sessao
];
$query = $coneccao->executar_query('SELECT * FROM carrinho WHERE :id = ID_usuario', $parametros);

if ($query->affected_rows == 0) {
    $parametros = [
        ":id_usuario" => $id_sessao
    ];
    $query = $coneccao->execute_non_query("INSERT INTO carrinho VALUES(0, :id_usuario, NOW())", $parametros);
}

$result = $query->results[0];
$id_carrinho = $result->ID_carrinho;

$parametros_carrinho = [
    ':id_carrinho' => $id_carrinho
];

$query = $coneccao->executar_query('SELECT * FROM items_carrinho WHERE ID_carrinho = :id_carrinho', $parametros_carrinho);
if ($query->affected_rows == 0) {
    $items = false;
} else {
    $items = true;
}

if ($items) {

    $query_produtos = $coneccao->executar_query(
        'SELECT 
            ic.Quantidade,
            p.Nome_produto,
            p.Preco,
            p.Marca_produto,
            p.img_produto,
            p.Disponivel
        FROM 
            items_carrinho ic
        JOIN 
            produto p ON ic.ID_produto = p.ID_produto
        WHERE 
            ic.ID_carrinho = :id_carrinho',
        $parametros_carrinho
    );
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho | Console Zone</title>
    <link rel="stylesheet" href="../assets/css/style_user_carrinho.css">
</head>

<body>
    <div class="container-fluid w-75">
        <div class="row justify-content-center mt-2">
            <?php if ($items == false) : ?>
                <p class="alert alert-danger text-center w-75">Não foi encontrado nenhum produto no seu carrinho</p>
        </div>
    <?php elseif ($items == true) : ?>
        <div class="row">
            <p class="h3 fw-bold">Items do seu carrinho</p>
        </div>
        <?php foreach ($query_produtos->results as $result): ?>
            <div class="row mb-2 mt-2">
                <div class="card bg-white text-dark">
                    <img src="<?= $result->img_produto ?>" alt="Produto" class="img-fluid img_carrinho">
                    <p class="card-title fw-bold"><?= $result->Nome_produto ?> - <?= $result->Marca_produto ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif ?>

    </div>
</body>

</html>