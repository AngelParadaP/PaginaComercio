<?php
require "../funciones/conecta.php";
$con = conecta();

$codigo = isset($_GET['codigo']) ? $con->real_escape_string($_GET['codigo']) : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta para verificar si el correo ya existe y no es del mismo usuario que está editando
$query = "SELECT codigo FROM productos WHERE codigo = '$codigo' AND id != $id";
$result = $con->query($query);

if ($result->num_rows > 0) {
    echo 'exists';
} else {
    echo 'not_exists';
}

$con->close();
?>
