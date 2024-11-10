<?php
require "../funciones/conecta.php";
if (isset($_POST['codigo'])) {
    $codigo = $_POST['codigo'];
    $con = conecta();    
    $codigo = $con->real_escape_string($codigo);
    $query = "SELECT codigo FROM productos WHERE codigo = '$codigo'";
    $result = $con->query($query);
    
    if ($result->num_rows > 0) {
        echo 'exists';
    } else {
        echo 'not_exists';
    }
    $con->close();
}
?>
