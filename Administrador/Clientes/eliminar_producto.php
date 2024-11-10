<?php
session_start();
require "../funciones/conecta.php"; 

$con = conecta();

if ($con->connect_error) {
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos"]);
    exit;
}

if (isset($_POST['producto_id'])) {
    $producto_id = (int) $_POST['producto_id'];
    $cliente_id = $_SESSION['idUser'] ?? null;

    if (!$cliente_id) {
        echo json_encode(["success" => false, "message" => "ID de cliente no encontrado en la sesión"]);
        exit;
    }

    $sql = "DELETE FROM compras WHERE cliente_id = ? AND producto_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $cliente_id, $producto_id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al eliminar el producto"]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Parámetro faltante"]);
}
$con->close();
?>
