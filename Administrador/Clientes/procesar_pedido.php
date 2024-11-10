<?php
session_start();
require "../funciones/conecta.php";

$cliente_id = $_SESSION['idUser'] ?? null;

if (!$cliente_id) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
    exit;
}

$con = conecta();

$con->begin_transaction();

try {
    $sql = "SELECT p.id, p.costo, c.cantidad FROM productos p JOIN compras c ON p.id = c.producto_id WHERE c.cliente_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $total = 0;
    $productos = [];

    while ($row = $result->fetch_assoc()) {
        $subtotal = $row['costo'] * $row['cantidad'];
        $total += $subtotal;
        $productos[] = [
            'producto_id' => $row['id'],
            'cantidad' => $row['cantidad']
        ];
    }

    $total_con_iva = $total * 1.16;

    $sql = "INSERT INTO pedidos (cliente_id, total, total_con_iva) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("idd", $cliente_id, $total, $total_con_iva);
    $stmt->execute();
    $pedido_id = $stmt->insert_id; 

    $sql = "INSERT INTO productos_pedido (pedido_id, producto_id, cantidad) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);

    foreach ($productos as $producto) {
        $stmt->bind_param("iii", $pedido_id, $producto['producto_id'], $producto['cantidad']);
        $stmt->execute();
    }

    $sql = "DELETE FROM compras WHERE cliente_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();

    $con->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $con->rollback(); 
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$stmt->close();
$con->close();
?>
