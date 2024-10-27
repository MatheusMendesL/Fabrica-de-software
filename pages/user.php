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



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['Nome'];
    $tel = $_POST['tel'];
    $nasc = $_POST['nasc'];

    if($nome == null || $tel == null || $nasc == null){
        $erro = "Alguma informação está vazia";
    } else {
        $parametros = [
            ":id" => $id_sessao,
            ":nome" => $nome,
            ":tel" => $tel,
            ":nasc" => $nasc
        ];
        $query = $coneccao->executar_query("UPDATE cliente SET Nome = :nome, Telefone = :tel, Data_nascimento = :nasc WHERE id = :id", $parametros);
    
    }
   
}

$parametros = [
    ":id" => $id_sessao
];
$query = $coneccao->executar_query('SELECT * FROM cliente WHERE id = :id', $parametros);
$result = $query->results[0];

$nomeCompleto = $result->Nome;
$nomeCompleto = trim($nomeCompleto);

$partes = explode(" ", $nomeCompleto);
$primeiroNome = $partes[0];


require_once('../public/header.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
    <title>Editar Perfil | Console Zone</title>
    <link rel="stylesheet" href="../assets/css/style_user_carrinho.css">
</head>

<body>
    <div class="container border border-black mt-4 h-75 w-75">
        <div class="row h-100">
            <div class="col-3 border-end border-black h-100">
                <div class="img mt-3">
                    <img src="../assets/img/icons/icone_cliente.png" alt="Cliente" class="img-fluid">

                </div>
                <p class="text-center h2 pt-2"> <?= $primeiroNome ?></p>


                <div class="row pt-4">
                    <button class="btn btn-outline-light h-100 w-100" onclick="window.location.href='#'">Perfil</button>
                </div>

                <div class="row pt-2">
                    <button class="btn btn-outline-light h-100 w-100" onclick="window.location.href='../pages/user/endereco.php'">Adicionar endereço</button>
                </div>

                <div class="row pt-2">
                    <button class="btn btn-outline-light h-100 w-100" onclick="window.location.href='../pages/user/senha.php'">Alterar senha</button>
                </div>

                <div class="row pt-2">
                    <button class="btn btn-outline-warning h-100 w-100" onclick="window.location.href='../pages/user/delete.php'">Excluir conta</button>
                </div>
            </div>
            <div class="col">
                <form action="user.php" method="post">
                    <div class="row pt-3">
                        <p class="text-center">Perfil</p>
                        <hr>
                    </div>
                    <div class="row justify-content-center mt-2">
                        <div class="col-6">
                            <label for="Nome" class="form-label">Nome</label>
                            <input type="text" name="Nome" id="1" class="form-control form-control-sm mb-1" value="<?= $results->Nome ?>">
                        </div>
                    </div>
                    <div class="row justify-content-center mt-2">
                        <div class="col-6">
                            <label for="tel" class="form-label">Telefone</label>
                            <input type="text" name="tel" id="2" class="form-control form-control-sm mb-1" value="<?= $results->Telefone ?>">
                        </div>
                    </div>
                    <div class="row justify-content-center mt-2">
                        <div class="col-6">
                            <label for="nasc" class="form-label">Data de nascimento</label>
                            <input type="date" name="nasc" id="2" class="form-control form-control-sm mb-1" value="<?= $results->Data_nascimento ?>">
                        </div>
                    </div>

                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary w-100 "> Salvar </button>
                        </div>
                    </div>


                    <?php if (!empty($erro)) : ?>
                        <div class="row justify-content-center mt-3">
                            <div class="col-6">
                                <p class="text-warning text-center"><?= $erro ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                </form>
            </div>
        </div>
    </div>
    <script src="../assets/bootstrap/bootstrap.bundle.js"></script>
</body>

</html>