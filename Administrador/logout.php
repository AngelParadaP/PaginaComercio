<?php
require "funciones/conecta.php"; // Incluye el archivo de conexión
$con = conecta(); // Establece la conexión
session_start(); // Inicia la sesión
session_destroy(); // Destruye la sesión

header("Location: ./login/index.php"); // Redirige a index.php
exit(); // Asegúrate de salir para evitar que se ejecute más código
?>
