<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['nomUser'])) {
    // Si no hay sesión abierta, redirige a index.php
    header("Location: ../login/index.php");
    exit();
}require "../funciones/conecta.php";

$con = conecta();

// Consulta para obtener los pedidos cerrados
$sql = "SELECT p.id, p.cliente_id FROM pedidos p";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Pedidos Cerrados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f0f8ff;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 70px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        .button-create {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #4a90e2;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .button-create:hover {
            background-color: #357ab7;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #4a90e2;
        }
        th {
            background-color: #4a90e2;
            color: white;
        }
        tr:hover {
            background-color: #e6f1ff;
        }
        .button-details {
            width: 100%;
            padding: 10px;
            background-color: green;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .button-details:hover {
            background-color: #15992d;
        }
    </style>
</head>
<?php include "menu.php"?>
<body>
    <div class="container">
        <h1>Listado de Pedidos Cerrados</h1>
        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Cliente ID</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['cliente_id']) ?></td>
                        <td><a href="pedido_detalle.php?id=<?= htmlspecialchars($row['id']) ?>"><button class="button-details">Ver detalle</button></a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
