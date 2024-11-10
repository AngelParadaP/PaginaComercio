<?php
session_start();
require "../funciones/conecta.php";  

$producto_id = $_POST['producto_id'];
$nueva_cantidad = $_POST['cantidad'];

$con = conecta();

if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}

$sql = "UPDATE compras SET cantidad = ? WHERE producto_id = ? AND cliente_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("iii", $nueva_cantidad, $producto_id, $_SESSION['idUser']);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $sql = "SELECT costo FROM productos WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $producto_id);
    $stmt->execute();
    $stmt->bind_result($costo);
    $stmt->fetch();

    $subtotal = $costo * $nueva_cantidad;

    echo json_encode([
        'success' => true,
        'subtotal' => number_format($subtotal, 2, '.', '')  
    ]);
} else {
    echo json_encode(['success' => false]);
}

$stmt->close();
$con->close();
?>
