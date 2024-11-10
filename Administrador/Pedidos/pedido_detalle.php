<?php
session_start(); 
if (!isset($_SESSION['nomUser'])) {
    header("Location: ../login/index.php");
    exit();
}require "../funciones/conecta.php";

$con = conecta();
$pedido_id = $_GET['id'];

// Consulta para obtener la información del pedido y del cliente asociado
$sql_pedido = "SELECT p.id, p.cliente_id, p.total, p.total_con_iva, p.fecha, c.nombre, c.apellidos, c.correo
               FROM pedidos p
               JOIN clientes c ON p.cliente_id = c.id
               WHERE p.id = ?";
$stmt_pedido = $con->prepare($sql_pedido);
$stmt_pedido->bind_param("i", $pedido_id);
$stmt_pedido->execute();
$result_pedido = $stmt_pedido->get_result();
$pedido = $result_pedido->fetch_assoc();

$sql_productos = "SELECT pp.cantidad, pr.costo, (pp.cantidad * pr.costo) AS subtotal, pr.nombre, pr.id AS producto_id
                  FROM productos_pedido pp
                  JOIN productos pr ON pp.producto_id = pr.id
                  WHERE pp.pedido_id = ?";
$stmt_productos = $con->prepare($sql_productos);
$stmt_productos->bind_param("i", $pedido_id);
$stmt_productos->execute();
$result_productos = $stmt_productos->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Pedido</title>
    <style>
        body {
            background-color: #f0f8ff;
            color: #333;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            margin-top: 60px;
            color: #333;
        }
                .button-submit {
            width: 100%;
            padding: 12px;
            background-color: #4a90e2;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .button-submit:hover {
            background-color: #357ab7;
        }
        .container {
            max-width: 800px;
                        background-color: white;

            width: 100%;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            text-align: center;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h3 {
            color: #555;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .info-section p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
            color: #333;
        }
        .total-section {
            text-align: right;
            font-weight: bold;
            font-size: 1.1em;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php include "menu.php"?>

<div class="container">
    <h2>Detalle del Pedido #<?= htmlspecialchars($pedido['id']) ?></h2>

    <div class="info-section">
        <h3>Información del Cliente</h3>
        <p><strong>Nombre:</strong> <?= htmlspecialchars($pedido['nombre'] . " " . $pedido['apellidos']) ?></p>
        <p><strong>Correo:</strong> <?= htmlspecialchars($pedido['correo']) ?></p>
    </div>

    <div class="info-section">
        <h3>Información del Pedido</h3>
        <p><strong>Subtotal:</strong> $<?= number_format($pedido['total'], 2) ?></p>
        <p><strong>Total con IVA:</strong> $<?= number_format($pedido['total_con_iva'], 2) ?></p>
        <p><strong>Fecha del Pedido:</strong> <?= htmlspecialchars($pedido['fecha']) ?></p>
    </div>

    <div class="info-section">
        <h3>Productos del Pedido</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Costo Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($producto = $result_productos->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['producto_id']) ?></td>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td><?= htmlspecialchars($producto['cantidad']) ?></td>
                        <td>$<?= number_format($producto['costo'], 2) ?></td>
                        <td>$<?= number_format($producto['subtotal'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="total-section">
        Gran Total del Pedido con IVA: $<?= number_format($pedido['total_con_iva'], 2) ?>
    </div>
            <a href="pedidos.php"><button type="button" class="button-submit">Regresar</button></a>

</div>

</body>
</html>
