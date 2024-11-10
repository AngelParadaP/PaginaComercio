<?php
require "funciones/conecta.php";
if (isset($_POST['correo'])) {
    $correo = $_POST['correo'];
    $con = conecta();    
    $correo = $con->real_escape_string($correo);
    $query = "SELECT correo FROM usuarios WHERE correo = '$correo'";
    $result = $con->query($query);
    
    if ($result->num_rows > 0) {
        echo 'exists';
    } else {
        echo 'not_exists';
    }
    $con->close();
}
?>
