<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['nomUser'])) {
    // Si no hay sesión abierta, redirige a index.php
    header("Location: ../login/index.php");
    exit();
}

require "../funciones/conecta.php";
$con = conecta();

// Obtener el ID del producto desde la URL
if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];

    // Preparar consulta para obtener los detalles del producto
    $sql = "SELECT nombre, codigo, costo, stock, descripcion, archivo, eliminado FROM productos WHERE id = $producto_id";
    $res = $con->query($sql);

    if ($res->num_rows > 0) {
        // Datos del producto encontrados
        $producto = $res->fetch_assoc();
    } else {
        echo "Producto no encontrado.";
        die();
    }
} else {
    echo "ID del producto no especificado.";
    die();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card {
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
            border: 1px solid #ddd;
        }
        .card-img-top {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #333;
            text-align: center;
        }

        .container {
            margin-top: 60px;
        }
        .button-submit {
            margin-left: 25px;
            width: 90%;
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
    </style>
</head>
<body>
    <?php include 'menu.php'; ?> <!-- Incluir el menú -->

    <div class="container">
        <a href="productos_lista.php"><button type="button" class="button-submit" style="margin-top: 20px;">◀ Regresar</button></a>

        <div class="card">
            <?php if ($producto['archivo']) : ?>
                <img class="card-img-top" src="archivos/<?php echo htmlspecialchars($producto['archivo']); ?>" alt="Imagen del producto">
            <?php else : ?>
                <img class="card-img-top" src="https://via.placeholder.com/500x250?text=Sin+Imagen" alt="Imagen no disponible">
            <?php endif; ?>

            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                <p class="card-text">
                    <strong>Código:</strong> <?php echo htmlspecialchars($producto['codigo']); ?><br>
                    <strong>Costo:</strong> $<?php echo htmlspecialchars(number_format($producto['costo'], 2)); ?><br>
                    <strong>Stock:</strong> <?php echo htmlspecialchars($producto['stock']); ?> unidades<br>
                    <strong>Descripción:</strong> <?php echo htmlspecialchars($producto['descripcion']); ?><br>
                    <strong>Estado:</strong> 
                    <?php
                        // Determina el estado basado en el campo eliminado
                        echo htmlspecialchars($producto['eliminado'] == 0 ? 'Disponible' : 'Agotado');
                    ?>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
