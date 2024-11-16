<?php
session_start();

use Database_class\Database;

require_once('../assets/favicon/favicon.php');
require_once('../Database/config.php');
require_once('../Database/Database.php');
require_once('../public/header.php');

$post = null;
$results = null;
$params = null;
$query_filtro = null;
$query_normal = null;
$results_filtros_marca = null;
$results_filtros_categoria = null;

$coneccao = new Database(MYSQL_CONFIG);
$query = $coneccao->executar_query('SELECT DISTINCT marca_produto FROM produto');
if ($query->affected_rows == 0) {
    $results_filtros_marca = false;
}

$query_categoria = $coneccao->executar_query('SELECT Nome_categoria, ID_categoria FROM categoria_produto');
if ($query_categoria->affected_rows == 0) {
    $results_filtros_categoria = false;
}


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $post = false;
    $query_produtos = $coneccao->executar_query('
        SELECT p.*, c.Nome_categoria
        FROM produto p
        JOIN categoria_produto c ON p.ID_categoria = c.ID_categoria ORDER BY Nome_produto
    ');
} else {
    $post = true;
    $preco_min = isset($_POST['min']) ? $_POST['min'] : '';
    $preco_max = isset($_POST['max']) ? $_POST['max'] : '';

    $query_normal = 'SELECT p.*, c.Nome_categoria
                     FROM produto p
                     JOIN categoria_produto c ON p.ID_categoria = c.ID_categoria
                     WHERE 1=1';

    if (!empty($preco_min) && !empty($preco_max)) {
        $query_normal .= " AND p.Preco >= $preco_min AND p.Preco <= $preco_max";
    } elseif (!empty($preco_min)) {
        $query_normal .= " AND p.Preco >= $preco_min";
    } elseif (!empty($preco_max)) {
        $query_normal .= " AND p.Preco <= $preco_max";
    }

    $marcas_selecionadas = isset($_POST['marca']) ? $_POST['marca'] : [];
    if (!empty($marcas_selecionadas)) {
        $marcas_placeholder = implode("','", array_map('addslashes', $marcas_selecionadas));
        $query_normal .= " AND p.Marca_produto IN ('$marcas_placeholder')";
    }

    $categorias_selecionadas = isset($_POST['categoria']) ? $_POST['categoria'] : [];
    if (!empty($categorias_selecionadas)) {
        $categorias_placeholder = implode(",", array_map('intval', $categorias_selecionadas));
        $query_normal .= " AND p.ID_categoria IN ($categorias_placeholder)";
    }

    $pesquisa = isset($_POST['Pesquisa']) ? $_POST['Pesquisa'] : '';

    if (!empty($pesquisa)) {
        $query_normal .= " AND (p.Nome_produto LIKE :pesquisa OR p.Marca_produto LIKE :pesquisa)";
        $params = [':pesquisa' => '%' . $pesquisa . '%'];
    }

    $query_filtro = $coneccao->executar_query($query_normal, $params);
    if ($query_filtro == $query_normal) {
        $results = false;
    };
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo de produtos | Console Zone</title>
    <link rel="stylesheet" href="../assets/css/style_catalogo_sobre.css?">
</head>

<body>
    <div class="container-fluid mt-4 d-flex">
        <div class="col-3 h-100 col-filtros">
            <p class="fw-bold h3">Filtros</p>
            <form action="catalogo.php" method="post">
                <div class="card card_filtros">
                    <div class="card-body">
                        <p class="card-title text-primary">Preço</p>

                        <div class="row d-flex mb-3">
                            <input type="number" name="min" id="1" placeholder="De" class="form-control w-50" value="<?= $preco_min ?>">
                            <input type="number" name="max" id="2" placeholder="Até" class="form-control w-50" value="<?= $preco_max ?>">
                        </div>

                    </div>
                </div>

                <div class="card card_filtros mt-2">
                    <div class="card-body">
                        <div class="card-title text-primary">Marca</div>
                        <div class="row mb-3 d-flex">

                            <?php if ($results_filtros_marca === false) : ?>
                                <p class="text-danger">Não há nenhuma marca</p>
                            <?php else: ?>
                                <?php foreach ($query->results as $result): ?>
                                    <div class="d-flex align-items-center mb-2">
                                        <label for="<?= $result->marca_produto ?>" class="form-label me-2"><?= $result->marca_produto ?></label>
                                        <input type="checkbox" name="marca[]" value="<?= $result->marca_produto ?>"
                                            <?php
                                            if (isset($_POST['marca']) && in_array($result->marca_produto, $_POST['marca'])) {
                                                echo 'checked';
                                            }
                                            ?>>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>



                <div class="card card_filtros mt-2">
                    <div class="card-body">
                        <div class="card-title text-primary">Categorias</div>
                        <div class="row mb-3 d-flex">

                            


                            <?php if ($results_filtros_categoria === false) : ?>
                                <p class="text-danger">Não há nenhuma categoria</p>
                            <?php else: ?>
                                <?php foreach ($query_categoria->results as $result): ?>
                                    <div class="d-flex align-items-center mb-2">
                                        <label for="<?= $result->ID_categoria ?>" class="form-label me-2"><?= $result->Nome_categoria ?></label>
                                        <input type="checkbox" name="categoria[]" value="<?= $result->ID_categoria ?>"
                                            <?php
                                            if (isset($_POST['categoria']) && in_array($result->ID_categoria, $_POST['categoria'])) {
                                                echo 'checked';
                                            }
                                            ?>>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <Input type="submit" class="btn btn-primary mt-3 w-75" value="Alterar filtros">

            </form>
        </div>

        <div class="col-9">

            <div class="row d-flex align-items-center">
                <p class="fw-bold h3">Produtos</p>
                <form action="catalogo.php" method="post" class="d-flex">
                    <div class="row gap-3 w-100">
                        <input type="text" name="Pesquisa" placeholder="Pesquise algo" class="form-control form-control-sm w-50">
                        <input type="submit" value="Pesquisar" class="btn btn-primary w-25">
                    </div>
                </form>
            </div>


            <div class="container pt-3">
                <div class="row d-flex gap-4">
                    <?php if ($post == false) : ?>
                        <?php foreach ($query_produtos->results as $result): ?>
                            <div class="card card_produto text-center" onclick="window.location.href = 'produto.php?id=<?= $result->ID_produto ?>'">
                                <div class="card-body">
                                    <img src="<?= $result->img_produto ?>" alt="Produto" class="img-fluid img_produto">
                                    <div class="card-body d-flex flex-column p-3">
                                        <h6 class="card-title mt-3"><?= $result->Nome_produto ?></h6>
                                        <h6 class="card-title preco_product">R$<?= $result->Preco ?></h6>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php elseif ($post == true) : ?>

                        <?php if ($query_filtro->affected_rows == 0): ?>
                            <p class="alert alert-danger text-center mt-3">Sua pesquisa não obteve resultados</p>
                        <?php endif; ?>

                        <?php foreach ($query_filtro->results as $result): ?>
                            <div class="card card_produto text-center" onclick="window.location.href = 'produto.php?id=<?= $result->ID_produto ?>'">
                                <div class="card-body">
                                    <img src="<?= $result->img_produto ?>" alt="Produto" class="img-fluid img_produto">
                                    <div class="card-body d-flex flex-column p-3">
                                        <h6 class="card-title mt-3"><?= $result->Nome_produto ?></h6>
                                        <h6 class="card-title preco_product">R$<?= $result->Preco ?></h6>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


    <?php
    require_once('../public/footer_index.php')
    ?>
</body>

</html>