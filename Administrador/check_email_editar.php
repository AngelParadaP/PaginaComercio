<?php
require "funciones/conecta.php";
$con = conecta();

$correo = isset($_GET['correo']) ? $con->real_escape_string($_GET['correo']) : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta para verificar si el correo ya existe y no es del mismo usuario que está editando
$query = "SELECT correo FROM usuarios WHERE correo = '$correo' AND id != $id";
$result = $con->query($query);

if ($result->num_rows > 0) {
    echo 'exists';
} else {
    echo 'not_exists';
}

$con->close();
?>
