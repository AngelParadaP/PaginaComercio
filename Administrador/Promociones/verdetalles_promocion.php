<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['nomUser'])) {
    // Si no hay sesión abierta, redirige a index.php
    header("Location: ../login/index.php");
    exit();
}

require "../funciones/conecta.php";
$con = conecta();

// Obtener el ID de la promocion desde la URL
if (isset($_GET['id'])) {
    $promociones_id = $_GET['id'];

    // Preparar consulta para obtener los detalles de la promocion
    $sql = "SELECT nombre, archivo, status FROM promociones WHERE id = $promociones_id";
    $res = $con->query($sql);

    if ($res->num_rows > 0) {
        // Datos de la promocion encontrados
        $promocion = $res->fetch_assoc();
    } else {
        echo "promocion no encontrado.";
        die();
    }
} else {
    echo "ID de la promocion no especificado.";
    die();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la promocion</title>
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
        <a href="promociones_lista.php"><button type="button" class="button-submit" style="margin-top: 20px;">◀ Regresar</button></a>

        <div class="card">
            <?php if ($promocion['archivo']) : ?>
                <img class="card-img-top" src="archivos/<?php echo htmlspecialchars($promocion['archivo']); ?>" alt="Imagen de promocion">
            <?php else : ?>
                <img class="card-img-top" src="https://via.placeholder.com/500x250?text=Sin+Imagen" alt="Imagen no disponible">
            <?php endif; ?>

            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($promocion['nombre']); ?></h5>
                <p class="card-text">
                    <strong>status:</strong>
                    <?php
                        // Determina el estado basado en el campo eliminado
                        echo htmlspecialchars($promocion['status'] == 1 ? 'Activa' : 'Agotada');
                    ?>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
