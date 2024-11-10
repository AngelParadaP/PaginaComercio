<?php
session_start();
?>
<?php
    require "../funciones/conecta.php";
    
    $con = conecta();
    $sql = "SELECT * FROM productos WHERE eliminado = 0";
    $res = $con->query($sql);
    
    $num_productos = $res->num_rows;  
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f0f8ff;
            color: #333;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            margin-top: 70px;
            background-color: white; 
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        .button-create {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 60px;
            height: 60px;
            margin: 20px auto;
            background-color: #4a90e2;
            color: white;
            font-size: 36px;
            border-radius: 50%;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .button-create:hover {
            background-color: #357ab7;
        }
        .button-create:after {
            content: "+";
            font-weight: bold;
        }
        .card {
            width: 30%;
            display: inline-block;
            background-color: #fff;
            margin: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-body {
            padding: 15px;
        }
        .card h2 {
            font-size: 22px;
            margin: 10px 0;
        }
        .card p {
            font-size: 16px;
            color: #555;
        }
        .card .price {
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
            color: #333;
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
        }    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container">
        <h1>Listado de productos (<?php echo $num_productos; ?>)</h1>
        <?php
        while ($row = $res->fetch_array()) {
            $id = $row["id"];
            $nombre = $row["nombre"];
            $descripcion = $row["descripcion"];
            $costo = $row["costo"];
            $codigo = $row["codigo"];
            $archivo = $row["archivo"] ? "../Productos/archivos/" . $row["archivo"] : "default.jpg"; 

            echo "
                <div class='card' data-id='$id'>
                    <a href='./verdetalles_producto.php?id=$id' style='text-decoration: none; color: inherit;'>
                        <img src='$archivo' alt='Imagen del producto'>
                        <div class='card-body'>
                            <h2>$nombre</h2>
                            <p>$descripcion</p>
                            <p>codigo: $codigo</p>
                            <p class='price'>\$$costo</p>
                        </div>
                    </a>
                    <div class='card-buttons'>";
                    
                    if (isset($_SESSION['nomUser'])) {
                        echo "
                        <form class='add-to-cart-form' data-id='$id'>
                            <input type='hidden' name='producto_id' value='$id'>
                            <input type='hidden' name='cliente_id' value='" . $_SESSION['idUser'] . "'>
                            <input type='number' name='cantidad' class='cantidad-input' min='1' value='1'>
                            <button type='submit' class='add-to-cart-btn'>Agregar al carrito</button>
                        </form>";
                    }

            echo "</div>
                </div>";
        }
        ?>
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
