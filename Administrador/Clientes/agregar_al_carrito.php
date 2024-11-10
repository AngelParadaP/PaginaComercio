<?php
session_start();
require "../funciones/conecta.php";
$con = conecta();

if (isset($_POST['producto_id'], $_POST['cliente_id'], $_POST['cantidad'])) {
    $producto_id = $_POST['producto_id'];
    $cliente_id = $_POST['cliente_id'];
    $cantidad = $_POST['cantidad'];

    if ($cantidad > 0) {
        $sql_check = "SELECT * FROM compras WHERE cliente_id = '$cliente_id' AND producto_id = '$producto_id'";
        $result_check = $con->query($sql_check);

        if ($result_check->num_rows > 0) {
            $sql_update = "UPDATE compras SET cantidad = cantidad + $cantidad WHERE cliente_id = '$cliente_id' AND producto_id = '$producto_id'";
            if ($con->query($sql_update) === TRUE) {
                echo 'success';
            } else {
                echo 'error';
            }
        } else {
            $sql_insert = "INSERT INTO compras (cliente_id, producto_id, cantidad) VALUES ('$cliente_id', '$producto_id', '$cantidad')";
            if ($con->query($sql_insert) === TRUE) {
                echo 'success';
            } else {
                echo 'error';
            }
        }
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
