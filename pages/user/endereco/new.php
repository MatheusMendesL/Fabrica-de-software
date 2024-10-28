<?php

session_start();

use Database_class\Database;

require_once('../../../assets/favicon/favicon.php');
require_once('../../../Database/config.php');
require_once('../../../Database/Database.php');


$login = null;
$erro = null;


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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cep = $_POST['CEP'];
    $endereco = $_POST['Endereco'];
    $numero = $_POST['Numero'];
    $bairro = $_POST['Bairro'];
    $complemento = $_POST['Complemento'];
    $estado = $_POST['Estado'];
    $cidade = $_POST['Cidade'];

    if (empty($cep) || empty($endereco) || empty($numero) || empty($bairro) || empty($estado) || empty($cidade)) {
        $erro = true;
    } else {
        $par_id = [
            ':id' => $id_sessao
        ];
        $query = $coneccao->executar_query('SELECT * FROM endereco WHERE ID_cliente = :id', $par_id);
        $linhas_mudadas = $query->affected_rows;
        if ($linhas_mudadas >= 3) {
            $msg = true;
        } else {
            $parametros = [
                ':cep' => $cep,
                ':endereco' => $endereco,
                ':numero' => $numero,
                ':bairro' => $bairro,
                ':complemento' => $complemento,
                ':estado' => $estado,
                ':cidade' => $cidade,
                ':id' => $id_sessao
            ];
            $query = $coneccao->execute_non_query('INSERT INTO endereco VALUES(0, :cep, :endereco, :numero, :bairro, :complemento, :estado, :cidade, :id) ', $parametros);
            header('location:../endereco.php');
        }
    }
}

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
                <ul class="list-unstyled list-inline text-center p-4 mt-3 mr-5 lista-alinhada">
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
            <div class="col py-3 mx-auto mt-2">
                <form action="new.php" method="post">
                    <div class="row">
                        <p class="text-center h2 p-3 border-bottom border-black">Adicionar endereço</p>
                    </div>
                    <div class="row justify-content-center mt-1">
                        <div class="col-6">
                            <label for="CEP" class="form-label">CEP</label>
                            <input type="text" name="CEP" id="1" class="form-control form-control-sm mb-1" placeholder="Seu CEP">
                        </div>
                        <div class="col-6">
                            <label for="Bairro" class="form-label">Bairro</label>
                            <input type="text" name="Bairro" id="2" class="form-control form-control-sm mb-1" placeholder="Seu Bairro" readonly>
                        </div>

                    </div>

                    <div class="row justify-content-center mt-2">
                        <div class="col-12">
                            <label for="Endereco" class="form-label">Endereço</label>
                            <input type="text" name="Endereco" id="3" class="form-control form-control-sm mb-1" placeholder="Seu Endereço" readonly>
                        </div>
                    </div>

                    <div class="row justify-content-center mt-2">
                        <div class="col-6">
                            <label for="Numero" class="form-label">Número</label>
                            <input type="number" name="Numero" id="4" class="form-control form-control-sm mb-1" placeholder="Numero da sua casa">
                        </div>
                        <div class="col-6">
                            <label for="Complemento" class="form-label">Complemento</label>
                            <input type="text" class="form-control form-control-sm mb-1" id="5" placeholder="Complemento" name="Complemento">
                        </div>
                    </div>
                    <div class="row justify-content-center mt-2">
                        <div class="col-6">
                            <label for="Estado" class="form-label">Estado</label>
                            <input type="text" name="Estado" id="6" class="form-control form-control-sm mb-1" placeholder="Seu estado" readonly>
                        </div>
                        <div class="col-6">
                            <label for="Cidade" class="form-label">Cidade</label>
                            <input type="text" name="Cidade" class="form-control form-control-sm mb-1" id="7" placeholder="Sua cidade" readonly>
                        </div>
                    </div>

                    <div class="row justify-content-center mt-2">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
            <?php if ($erro) : ?>
                <script>
                    alert('Alguma informação esta vazia')
                </script>
            <?php elseif (!empty($msg)) : ?>
                <script>
                    alert('Você já tem endereços demais!')
                </script>
            <?php endif; ?>
        </div>
    </div>
    </div>
    <script src="../../../assets/js/script_cep.js"></script>
    <script src="../../assets/bootstrap/bootstrap.bundle.js"></script>
</body>

</html>