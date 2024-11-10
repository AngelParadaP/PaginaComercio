<?php
    // Comprueba si el número recibido es mayor o igual a 60
    $numero = $_REQUEST['numero'];
    $ban = 0;

    if ($numero >= 60) {
        $ban = 1;
    }

    echo $ban; // Devuelve una bandera
?>
