<?php
require "funciones/conecta.php";
    $con = conecta();
    $id = $_REQUEST['id'];
    $sql = "UPDATE usuarios SET eliminado = 1 WHERE id = $id";
    $res = $con->query($sql);

    if ($res) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
}?>


