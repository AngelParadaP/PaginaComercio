<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require './PHPMailer/Exception.php';
require './PHPMailer/PHPMailer.php';
require './PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $asunto = $_POST['asunto'];
    $mensaje = $_POST['mensaje'];

    if (empty($nombre) || empty($correo) || empty($asunto) || empty($mensaje)) {
        echo "Todos los campos son obligatorios.";
        exit();
    }

    // Configuración de PHPMailer
    $mail = new PHPMailer;

    // Configuración SMTP de Gmail
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // Servidor SMTP de Gmail
    $mail->SMTPAuth = true;
    $mail->Username = '';  //tu correo de gmail
    $mail->Password = '';  //tu contraseña de gmail, recomendado usar la contraseña para aplicaciones
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom($correo, $nombre); 
    $mail->addAddress('');  // tu correo de gmail

    // Contenido del correo
    $mail->Subject = $asunto;
    $mail->Body    = "Nombre: $nombre\nCorreo: $correo\nAsunto: $asunto\nMensaje: \n$mensaje\n";

    if (!$mail->send()) {
        http_response_code(500);
    } else {
        http_response_code(200);
    }
}
?>
