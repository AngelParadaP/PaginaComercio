<?php
session_start(); 


require "../funciones/conecta.php";
$con = conecta();

if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];

    $sql = "SELECT nombre, codigo, costo, stock, descripcion, archivo, eliminado FROM productos WHERE id = $producto_id";
    $res = $con->query($sql);

    if ($res->num_rows > 0) {
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
        .card-buttons {
    display: flex;
    justify-content: center; 
    align-items: center; 
    padding: 10px;
}
        .card-buttons a {
            width: 32%;
            text-decoration: none;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            transition: background-color 0.3s;
        }


        .cantidad-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
            margin-right: 5px;
        }
        .button-submit:hover {
            background-color: #357ab7;
            
        }

        .add-to-cart-btn {
    background-color: #4a90e2;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
}

        .add-to-cart-btn:hover {
            background-color: #357ab7;
        }
    </style>
</head>
<body>
     <?php include 'menu.php'; ?> 

    <div class="container">
        <a href="Home.php"><button type="button" class="button-submit" style="margin-top: 20px;">◀ Regresar</button></a>

        <div class="card">
            <?php if ($producto['archivo']) : ?>
                <img class="card-img-top" src="../Productos/archivos/<?php echo htmlspecialchars($producto['archivo']); ?>" alt="Imagen del producto">
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
                        echo htmlspecialchars($producto['eliminado'] == 0 ? 'Disponible' : 'Agotado');
                    ?>
                </p>
            </div>

            <div class="card-buttons">
                <?php
                if (isset($_SESSION['nomUser'])) {
                    echo "
                    <form class='add-to-cart-form' action='agregar_al_carrito.php' method='POST'>
                        <input type='hidden' name='producto_id' value='$id'>
                        <input type='hidden' name='cliente_id' value='" . $_SESSION['idUser'] . "'>
                        <input type='number' name='cantidad' class='cantidad-input' min='1' value='1'>
                        <button type='submit' class='add-to-cart-btn'>Agregar al carrito</button>
                    </form>";
                }
                ?>
            </div>

        </div>
    </div>
        <script>
        $(document).on('submit', '.add-to-cart-form', function(e) {
            e.preventDefault(); 

            var form = $(this);
            var formData = form.serialize(); 
            $.ajax({
                url: 'agregar_al_carrito.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        Toastify({
                            text: "¡Producto agregado al carrito!",
                            backgroundColor: "green",
                            duration: 3000
                        }).showToast();
                    } else {
                        Toastify({
                            text: "Error al agregar el producto al carrito.",
                            backgroundColor: "red",
                            duration: 3000
                        }).showToast();
                    }
                },
                error: function() {
                    Toastify({
                        text: "Hubo un error al procesar tu solicitud.",
                        backgroundColor: "red",
                        duration: 3000
                    }).showToast();
                }
            });
        });
    </script>
        <?php include 'footer.php'; ?>
</body>
</html>
