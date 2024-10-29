<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('../assets/vendor/autoload.php');
require_once('../assets/vendor/phpmailer/phpmailer/src/SMTP.php');
require_once('../assets/favicon/favicon.php');
require_once('../Database/config.php');
require_once('../Database/Database.php');

$login = empty($_SESSION['user_id']) ? false : true;

if (!$login) {
    header("location:cadastro.php");
    exit();
}


//if($_SERVER['REQUEST_METHOD'] == 'POST'){
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
    $email->Subject = "Email do usuário: email colocar";
    $email->Body = "Motivo do contato: papapa <br> Descrição: papapa <br> Enviado no dia: " . date('d/m/Y');
    $email->send();
} catch (Exception $err) {
    echo "Erro ao enviar: " . $err->getMessage();
    exit();
}
//}

//Amanha acabo



require_once('../public/header.php');

?>
