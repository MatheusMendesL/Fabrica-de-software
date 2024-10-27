<?php

session_start();

use Database_class\Database;

require_once('../../../assets/favicon/favicon.php');
require_once('../../../Database/config.php');
require_once('../../../Database/Database.php');


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
$id = $_SESSION['user_id'];
$parametros = [
    ':id' => $id
];
$query = $coneccao->executar_query('SELECT * FROM cliente WHERE id = :id', $parametros);
$results = $query->results[0];
$nome = $results->Nome;

$nomeCompleto = $results->Nome;
$nomeCompleto = trim($nomeCompleto);

$partes = explode(" ", $nomeCompleto);
$primeiroNome = $partes[0];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title>Adicionar endereço | Console Zone</title>
    <link rel="stylesheet" href="../../../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../../assets/css/style_public.css">
    <link rel="stylesheet" href="../../../assets/css/style_user_carrinho.css?v=1.1">
</head>

<body>

    <div class="container-fluid container-header align-items-center justify-content">
        <div class="row">
            <div class="col-3 text-center">
                <img src="../../../assets/img/cad_log/logonovasemfundo.png" alt="Logo empresa" class="logo ml-4 img-fluid">
            </div>
            <div class="col-6 lista">
                <ul class="list-unstyled list-inline text-center p-4 mt-3 mx-5 lista-alinhada">
                    <li class="list-inline-item"><a href="../../../index.php" class="link">Início</a></li>
                    <li class="list-inline-item"><a href="#" class="link">Catálogo</a></li>
                    <li class="list-inline-item"><a href="#" class="link">Sobre</a></li>
                    <li class="list-inline-item"><a href="#" class="link">Contato</a></li>
                </ul>
            </div>
            <div class="col-3 text-center pr-5">
                <?php if ($login) : ?>
                    <button class="btn btn-primary btn-cad" onclick="window.location.href='../../user.php'">
                        <i class="bi bi-person"></i> <?= $nome ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container border border-black mt-4 h-75 w-75 shadow-lg">
        <div class="row h-100">
            <div class="col-3 border-end border-black h-100 shadow-lg">
                <div class="img mt-3">
                    <img src="../../../assets/img/icons/icone_cliente.png" alt="Cliente" class="img-fluid">
                </div>
                <p class="text-center h2 pt-2 text-dark"> <?= $primeiroNome ?></p>

                <div class="row pt-4">
                    <button class="btn btn-outline-dark h-100 w-100" onclick="window.location.href='../../user.php'">Perfil</button>
                </div>

                <div class="row pt-2">
                    <button class="btn btn-outline-dark h-100 w-100" onclick="window.location.href='../../user/endereco.php'">Endereços</button>
                </div>

                <div class="row pt-2">
                    <button class="btn btn-outline-dark h-100 w-100" onclick="window.location.href='../../user/senha.php'">Alterar senha</button>
                </div>


                <div class="row pt-2">
                    <button class="btn btn-outline-danger h-100 w-100" onclick="window.location.href='../../user/delete.php'"><a href="#" class="btn-excluir">Excluir conta </a></button>
                </div>
            </div>
                <div class="col">
                <form action="user.php" method="post">
                    <div class="row">
                        <p class="text-center h2 p-3 border-bottom border-black">Adicionar endereço</p>
                    </div>
                </form>
                </div>
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