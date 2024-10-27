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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senha_nv = $_POST['nv_senha'];
    $conf_senha = $_POST['conf_senha'];
    $senha_criptografada = password_hash($conf_senha, PASSWORD_DEFAULT);

    if ($senha_nv != $conf_senha) {
        $erro = "As senhas são diferentes";
    } else {
        $parametros = [
            ":id" => $id_sessao,
            ":senha" => $senha_criptografada
        ];

        $query = $coneccao->execute_non_query('UPDATE cliente SET Senha = :senha WHERE id = :id', $parametros);
        $msg = "Senha alterada com sucesso";
    }
}

require_once('header_user.php');





?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/bootstrap/bootstrap.min.css">
    <title>Alterar senha | Console Zone</title>
    <link rel="stylesheet" href="../../assets/css/style_user_carrinho.css?">
</head>

<body>
    <div class="container border border-black mt-4 h-75 w-75">
        <div class="row h-100">
            <div class="col-3 border-end border-black h-100">
                <div class="img mt-3">
                    <img src="../../assets/img/icons/icone_cliente.png" alt="Cliente" class="img-fluid">

                </div>
                <p class="text-center h2 pt-2"> <?= $primeiroNome ?></p>

                <div class="row pt-4">
                    <button class="btn btn-outline-light h-100 w-100" onclick="window.location.href='../user.php'">Perfil</button>
                </div>

                <div class="row pt-2">
                    <button class="btn btn-outline-light h-100 w-100" onclick="window.location.href='../user/endereco.php'">Adicionar endereço</button>
                </div>

                <div class="row pt-2">
                    <button class="btn btn-outline-light h-100 w-100" onclick="window.location.href='../user/senha.php'">Alterar senha</button>
                </div>


                <div class="row pt-2">
                    <button class="btn btn-outline-warning h-100 w-100" onclick="window.location.href='../user/delete.php'"><a href="#" class="btn-excluir">Excluir conta </a></button>
                </div>
            </div>
            <div class="col">
                <div class="row pt-3">
                    <p class="text-center">Alterar senha</p>
                    <hr>
                </div>

                <form action="senha.php" method="post">

                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            <label for="nv_senha" class="form-label">Nova senha</label>
                            <input type="password" name="nv_senha" id="1" class="form-control form-control-sm mb-1">
                        </div>
                    </div>

                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            <label for="conf_senha" class="form-label">Confirme a senha</label>
                            <input type="password" name="conf_senha" id="2" class="form-control form-control-sm mb-1">
                        </div>
                    </div>
                    <div class="row justify-content-center mt-2">
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary w-100">Mudar</button>
                        </div>
                    </div>
                </form>
                <?php if (!empty($erro)) : ?>
                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            <p class="text-warning text-center"><?= $erro ?></p>
                        </div>
                    </div>
                <?php elseif (!empty($msg)) : ?>
                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            <p class="text-success text-center"><?= $msg ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="../../assets/bootstrap/bootstrap.bundle.js"></script>
</body>

</html>