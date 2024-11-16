<?php

session_start();

use Database_class\Database;

require_once('../assets/favicon/favicon.php');
require_once('../Database/config.php');
require_once('../Database/Database.php');


$login = null;
$items = null;
$exclusao = null;
$id_item_carrinho = null;
$total_compra = null;

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
    ':id' => $id_sessao
];
$query = $coneccao->executar_query('SELECT * FROM carrinho WHERE :id = ID_usuario', $parametros);

if ($query->affected_rows == 0) {
    $parametros = [
        ":id_usuario" => $id_sessao
    ];
    $query = $coneccao->execute_non_query("INSERT INTO carrinho VALUES(0, :id_usuario, NOW())", $parametros);
}


$parametros = [
    ':id' => $id_sessao
];
$query_select = $coneccao->executar_query('SELECT * FROM carrinho WHERE :id = ID_usuario', $parametros);
$result = $query_select->results[0];
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
            p.ID_produto,
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

$card = isset($_GET['card']) ? $_GET['card'] : '';


if ($card) {
    $exclusao = true;
    $id_item_carrinho = $_GET['id_produto'];
}

$delete = isset($_GET['delete']) ? $_GET['delete'] : '';

if($delete){
    $id_item_carrinho = $_GET['id_produto'];
    $parametros = [
        ':id_item' => $id_item_carrinho,
        ':id_carrinho' => $id_carrinho
    ];
    $query = $coneccao->execute_non_query('DELETE FROM items_carrinho WHERE :id_item = ID_produto AND :id_carrinho = ID_carrinho', $parametros);
    header('location:carrinho.php');
}

require_once('../public/header.php');

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho | Console Zone</title>
    <link rel="stylesheet" href="../assets/css/style_user_carrinho.css?">
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

        .link_excluir{
            transition: 1s ease !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid w-75">
        <div class="row justify-content-center mt-2">
            <?php if ($items == false) : ?>
                <p class="alert alert-danger text-center w-75">Não foi encontrado nenhum produto no seu carrinho</p>
        </div>
    <?php elseif ($items == true) : ?>
        <div class="row mb-2">
            <p class="h3 fw-bold text-center">Items do seu carrinho</p>
        </div>
        <?php foreach ($query_produtos->results as $result): ?>
            <div class="row mb-2 mt-2 justify-content-center">
                <div class="card bg-white text-dark p-3 w-50">
                    <div class="d-flex align-items-center">

                        <div class="me-3 flex-grow-1">
                            <p class="card-title fw-bold"><?= $result->Nome_produto ?> - <?= $result->Marca_produto ?></p>
                            <p class="card-title fw-bold">Quantidade: <?= $result->Quantidade ?></p>
                            <p class="card-title fw-bold">Valor total: R$<?= $result->Quantidade * $result->Preco ?></p>
                            <a href="carrinho.php?id_produto=<?= $result->ID_produto ?>&card=sim" class=" link-danger link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover link_excluir mb-3">Excluir do carrinho</a>
                        </div>

                        <?php $total_compra += $result->Quantidade * $result->Preco; ?>

                        <div>
                            <img src="<?= $result->img_produto ?>" alt="Produto" class="img-fluid img_carrinho" style="max-width: 150px;">
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    <?php endif ?>
            <?php if($total_compra != null) : ?>
                <p class="fw-bold text-center mt-3 h4">Total: R$<?= $total_compra ?></p>
            <?php endif; ?>
            </div>

    <?php if ($exclusao) : ?>
        <div class="overlay">
            <div class="card card-fundo">
                <p class="text-center">Deseja mesmo excluir seu item do carrinho?</p>
                <div class="d-flex justify-content-between mt-3">
                    <button class="btn btn-outline-danger w-50" onclick="window.location.href='carrinho.php?id_produto=<?= $id_item_carrinho ?>&delete=yes'">Sim</button>
                    <button class="btn btn-outline-dark w-50" onclick="window.location.href='carrinho.php'">Não</button>
                </div>
            </div>
        </div>
    <?php endif; ?>

</body>

</html>