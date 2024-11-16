<?php

session_start();

use Database_class\Database;

require_once('../Database/config.php');
require_once('../Database/Database.php');
require_once('../assets/favicon/favicon.php');

$alert = null;

if (!$_GET['id']) {
    header('location:../index.php');
}

$product_id = $_GET['id'];
$coneccao = new Database(MYSQL_CONFIG);
$parametros = [
    ':id' => $product_id
];

$query = $coneccao->executar_query('SELECT * FROM produto WHERE ID_produto = :id', $parametros);
$produto = $query->results[0];
$Nome_produto = $produto->Nome_produto;
$preco_produto = $produto->Preco;
$marca_produto = $produto->Marca_produto;
$img_produto = $produto->img_produto;
$descricao = $produto->Descricao;
$disponibilade = $produto->Disponivel;

$adicionar_carrinho = isset($_GET['adicionar']) ? $_GET['adicionar'] : '';

if ($adicionar_carrinho) {
    $alert = true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $parametros = [
        ':id' => $_SESSION['user_id']
    ];
    $query = $coneccao->executar_query('SELECT * FROM carrinho WHERE ID_usuario = :id', $parametros);
    $linhas_afetadas = $query->affected_rows;

    if ($linhas_afetadas == 0) {
        $query = $coneccao->execute_non_query('INSERT INTO carrinho VALUES(0, :id, NOW())', $parametros);
    }
    
    $query = $coneccao->executar_query('SELECT * FROM carrinho WHERE ID_usuario = :id', $parametros);
    $result = $query->results[0];   
    $id_carrinho = $result->ID_carrinho;
    $_SESSION['user_id_carrinho'] = $id_carrinho;

    $parametros = [
        ':id_carrinho' => $id_carrinho,
        ':id_produto' => $product_id,
        ':quantidade' => $_POST['Quantidade_produto']
    ];

    $query = $coneccao->execute_non_query('INSERT INTO items_carrinho VALUES(0, :id_carrinho, :id_produto, :quantidade)', $parametros);

}

require_once('../public/header.php');

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style_produto.css">
    <title><?= $Nome_produto ?></title>
</head>

<body>
    <div class="container container_produto rounded border border-info-subtle d-flex bg-white text-dark shadow-lg ">
        <div class="col my-auto">
            <img src="<?= $img_produto ?>" alt="Produto img" class="img_product img-fluid">
        </div>
        <div class="col text-center">
            <div class="row">
                <p class="h3 mt-5 mr-5 mb-3"><?= $Nome_produto ?> - <?= $marca_produto ?></p>
            </div>

            <div class="row">
                <p class="h3 text-info">R$<?= $preco_produto ?></p>
            </div>

            <div class="row justify-content-center mt-3 mx-auto">
                <p>Quantidade</p>
                <form method="post" action="produto.php?id=<?= $produto->ID_produto ?>&adicionar=sim">
                    <Select name="Quantidade_produto" id="1" class="form-select w-25 mx-auto">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </Select>
                    <div class="row justify-content-center mt-5">
                        <?php if ($disponibilade == 1): ?>
                            <button type="submit" class="btn btn-primary w-50 rounded">Adicionar ao carrinho</button>
                        <?php else: ?>
                            <p class="alert alert-danger text-center w-50">Produto fora de estoque</p>
                        <?php endif; ?>
                    </div>
                </form>
            </div>



        </div>
    </div>

    <?php if ($alert == true) : ?>
        <div class="col mt-5">
            <div class="alert alert-success alert-dismissible fade show w-25" role="alert">
                Seu item foi adicionado ao carrinho
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
</body>

</html>