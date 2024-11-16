<?php

session_start();

use Database_class\Database;

require_once('../assets/favicon/favicon.php');
require_once('../Database/config.php');
require_once('../Database/Database.php');

$erro = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $ligacao = new Database(MYSQL_CONFIG);

    $nome = $_POST['Nome'];
    $email = $_POST['Email'];
    $senha = $_POST['Senha'];
    $telefone = $_POST['telefone'];
    $nasc = $_POST['nasc'];
    $cpf = $_POST['cpf'];

    $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);


    $parametros_verificacao_email = [
        ':email' => $email
    ];

    $parametros_verificacao_cpf = [
        ':cpf' => $cpf
    ];

    $query_email = $ligacao->executar_query('SELECT * FROM cliente WHERE :email = email', $parametros_verificacao_email);
    $query_cpf = $ligacao->executar_query('SELECT * FROM cliente WHERE :cpf = CPF', $parametros_verificacao_cpf);


    if ($query_email->affected_rows != 0) {

        $erro = 'Esse email já está cadastrado';
    } else if ($query_cpf->affected_rows != 0) {

        $erro = 'Esse CPF já está cadastrado';
    } else {

        $parametros = [
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senha_criptografada,
            ':telefone' => $telefone,
            ':nasc' => $nasc,
            ':cpf' => $cpf
        ];

        $parametros_select = [
            ':email' => $email
        ];

        $query = $ligacao->execute_non_query('INSERT INTO cliente(Nome,Email,Senha,Telefone,Data_nascimento, CPF, created_at)VALUES(:nome, :email, :senha, :telefone, :nasc, :cpf, NOW())', $parametros);
        $query_select = $ligacao->executar_query('SELECT * FROM cliente WHERE Email = :email', $parametros_select);
        $results = $query_select->results[0];
        $_SESSION['user_id'] = $results->id;
        header('location:../index.php?id=' . $results->id);
        
        
    }
}

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
    <title>Cadastro</title>
</head>

<body>

    <div class="container-fluid container-img">
        <div class="col">
            <img src="../assets/img/cad_log/logonovasemfundo.png" alt="logo empresa" class="img-fluid">
        </div>
    </div>

    <div class="container container-form">
        <h2 class="text-center p-3">Crie sua conta!</h2>
        <form action="cadastro.php" method="post">

            <div class="row">
                <div class="col-6">
                    <label for="Nome" class="form-label">Nome</label>
                    <input type="text" name="Nome" id="1" class="form-control form-control-sm mb-1" placeholder="Seu nome" required>
                </div>
                <div class="col-6">
                    <label for="Email" class="form-label">Email</label>
                    <input type="text" name="Email" id="2" class="form-control form-control-sm mb-1" placeholder="exemplo@dominio.com" required>
                </div>
            </div>


            <div class="row">
                <div class="col-6">
                    <label for="Senha" class="form-label pt-2">Senha</label>
                    <input type="password" name="Senha" id="3" class="form-control form-control-sm mt-2 mb-1" placeholder="Sua senha" required>
                </div>
                <div class="col-6">
                    <label for="tel" class="form-label pt-2">Telefone</label>
                    <input type="tel" name="telefone" id="4" class="form-control form-control-sm mt-2 mb-1" placeholder="+00 (00) 00000-0000" required>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label for="nasc" class="form-label pt-2">Data de nascimento</label>
                    <input type="date" name="nasc" id="5" class="form-control form-control-sm mt-2 mb-1" required>
                </div>
                <div class="col-6">
                    <label for="cpf" class="form-label pt-2">CPF</label>
                    <input type="text" name="cpf" id="6" class="form-control form-control-sm mt-2 mb-1" placeholder="000.000.000-00" required>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <input type="submit" value="Cadastrar" class="btn btn-info mt-3 btn-enviar" style="width: 100% !important;">
                </div>
            </div>
        </form>

        <div class="row pt-1">
            <p>Já tem cadastro? <a href="login.php" >Clique aqui!</a></p>
        </div>

        <?php if (!empty($erro)) : ?>
            <div class="row">
                <p class="text-danger"><?= $erro ?></p>
            </div>
        <?php endif; ?>
    </div>


    <script src="../assets/bootstrap/bootstrap.bundle.js"></script>

</body>

</html>