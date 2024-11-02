<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Database_class\Database;

require_once('../assets/vendor/autoload.php');
require_once('../assets/vendor/phpmailer/phpmailer/src/SMTP.php');
require_once('../assets/favicon/favicon.php');
require_once('../Database/config.php');
require_once('../Database/Database.php');

$login = empty($_SESSION['user_id']) ? false : true;

$emailenviado = -1;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($login) {
        $coneccao = new Database(MYSQL_CONFIG);
        $id = $_SESSION['user_id'];
        $parametros = [
            ':id' => $id
        ];
        $query = $coneccao->executar_query('SELECT * FROM cliente WHERE id = :id', $parametros);
        $results = $query->results[0];
    }

    $nome = !empty($_POST['Nome']) ?  $_POST['Nome'] : $results->Nome;
    $mail = !empty($_POST['Email']) ? $_POST['Email'] : $results->Email;
    $motivo = $_POST['Motivo'];
    $desc = $_POST['Descricao'];

    if (!empty($nome) && !empty($mail) && !empty($motivo) && !empty($desc)) {
        $email = new PHPMailer(true);
        try {
            $email->IsSMTP();
            $email->Host = Mailer_Host;
            $email->SMTPAuth = true;
            $email->Username = Mailer_Username;
            $email->Password = Mailer_pass;
            $email->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $email->Port = Mailer_port;
            $email->CharSet = Mailer_charset;


            $email->setFrom('matheusmendelopes@gmail.com', 'Destinário');
            $email->addAddress('consolezone76@gmail.com', 'Remetente');

            $email->isHTML(true);
            $email->Subject = "Email do usuário: $mail, Nome do usuário: $nome";
            $email->Body = "Motivo do contato: $motivo <br> Descrição: $desc <br> Enviado no dia: " . date('d/m/Y');
            $email->send();
            $msg = "Email enviado com sucesso";
            $emailenviado = 1;
        } catch (Exception $err) {
            echo "Erro ao enviar: " . $err->getMessage();
            exit();
            $emailenviado = 0;
        }
    } else {
        $emailenviado = 0;
    }
}

require_once('../public/header.php');

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato | Console Zone</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        body {
            background: linear-gradient(135deg, #17232f, #1a3c6b, #2368a2, #4a9edf);
            color: #ffffff !important;
            font-family: "Poppins", sans-serif;
        }

        .card {
            padding: 0;
            position: fixed;
            bottom: 20px;
            width: 16em;
            display: none;
        }

        .sumir {
            display: none;
        }

        .progress-bar-success {
            height: 5px;
            background-color: #28a745;
            width: 100%;
            transition: width 5s linear;
        }

        .progress-bar-error {
            height: 5px;
            background-color: rgb(200, 0, 0);
            width: 100%;
            transition: width 5s linear;
        }
    </style>
</head>

<body>
    <div class="container pt-3 ">
        <div class="col-md-6 offset-md-3 p-2">
            <form action="contato.php" method="POST">
                <?php if (!$login) : ?>
                    <div class="mb-3">
                        <label for="Nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="Nome" placeholder="Digite seu nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="Email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" name="Email" placeholder="Digite seu e-mail" required>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="Motivo" class="form-label">Motivo do seu contato</label>
                    <input type="text" class="form-control" name="Motivo" placeholder="Descreva o motivo" required>
                </div>
                <div class="mb-3">
                    <label for="Descricao" class="form-label">Descrição</label>
                    <textarea class="form-control" name="Descricao" rows="4" placeholder="Descreva sua mensagem" required></textarea>
                </div>
                <div class="row pt-3 justify-content-center">
                    <button type="submit" class="btn btn-primary w-75" id="btn_enviar">Enviar Email</button>
                </div>
            </form>
        </div>
    </div>
    <div class="container-fluid">
        <div class="col">
            <?php if ($emailenviado == 1) : ?>
                <div class="card text-bg-success text-black" id="card_sumir" style="width: 16em;">
                    <div class="card-title text-center p-2">
                        O email foi enviado com sucesso!
                    </div>
                    <div class="progress-bar-success" id="progress-bar"></div>
                </div>

            <?php elseif ($emailenviado == 0): ?>
                <div class="card text-bg-danger" id="card_sumir">
                    <div class="card-title text-center p-2">
                        O email não foi enviado!
                    </div>
                    <div class="progress-bar-error" id="progress-bar"></div>
                </div>
            <?php endif; ?>
        </div>

    </div>
    <script>
        const card = document.getElementById('card_sumir');
        const progressBar = document.getElementById('progress-bar');

        if (card) {
            card.style.display = 'block';


            setTimeout(() => {
                progressBar.style.width = '0';
            }, 100);

            setTimeout(() => {
                card.classList.add('sumir');
            }, 5000);

            setTimeout(() => {
                card.style.display = 'none';
            }, 5300);
        }
    </script>
</body>

</html>