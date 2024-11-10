<?php
require "../funciones/conecta.php";

$con = conecta();

$sqlPromocion = "SELECT archivo FROM promociones ORDER BY RAND() LIMIT 1";
$resPromocion = $con->query($sqlPromocion);
$promocion = $resPromocion->fetch_assoc();

$sqlProductos = "SELECT * FROM productos WHERE eliminado = 0 ORDER BY RAND() LIMIT 6";
$resProductos = $con->query($sqlProductos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

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


.swiper-wrapper {
    display: flex;
}

.swiper-container {
    width: 100%;
    height: 300px;
    margin: 0 auto;
    overflow: hidden;
    position: relative;
}

.swiper-slide {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    position: relative;
}

.swiper-slide img {
    width: 100%;
    height: 100%;
    object-fit: fill; 
    position: absolute;
    top: 0;
    left: 0;
    border-radius: 10px;
}

.swiper-button-next,
.swiper-button-prev {
    color: #4a90e2;
}

.swiper-pagination-bullet {
    background: #4a90e2;
}



        </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container">
                <!-- Carrusel de Promociones , div en caso de que el slider no agrade
        <div class="carousel">
            <img src="../Promociones/archivos/<?php echo $promocion['archivo']; ?>" alt="Promoción" style="width:100%; height:300px; object-fit:fill; border-radius:10px;">
        </div>
        -->
<div class="swiper-container">
    <div class="swiper-wrapper">
        <?php
        $sqlPromociones = "SELECT archivo FROM promociones ORDER BY RAND() LIMIT 6";
        $resPromociones = $con->query($sqlPromociones);
        
        while ($promocion = $resPromociones->fetch_assoc()) {
            echo '<div class="swiper-slide">
                    <img src="../Promociones/archivos/' . $promocion['archivo'] . '" alt="Promoción" style="width:100%; height:300px; object-fit:cover; border-radius:10px;">
                  </div>';
        }
        ?>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</div>



        <h2>Productos destacados</h2>
        <div class="productos">
            <?php while ($producto = $resProductos->fetch_assoc()) {
                $id = $producto["id"];
                $nombre = $producto["nombre"];
                $descripcion = $producto["descripcion"];
                $costo = $producto["costo"];
                $codigo = $producto["codigo"];
                $archivo = $producto["archivo"] ? "../Productos/archivos/" . $producto["archivo"] : "default.jpg";
            ?>
                <div class="card" data-id="<?php echo $id; ?>">
                    <a href="./verdetalles_producto.php?id=<?php echo $id; ?>" style="text-decoration: none; color: inherit;">
                        <img src="<?php echo $archivo; ?>" alt="Imagen del producto">
                        <div class="card-body">
                            <h2><?php echo $nombre; ?></h2>
                            <p><?php echo $descripcion; ?></p>
                            <p>Código: <?php echo $codigo; ?></p>
                            <p class="price">$<?php echo $costo; ?></p>
                        </div>
                    </a>
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
            <?php } ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>


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

    const swiper = new Swiper('.swiper-container', {
        slidesPerView: 1,            
        spaceBetween: 0,             
        loop: true,                  
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        autoplay: {
            delay: 3000,             
            disableOnInteraction: false,
        },
    });


    </script>
</body>
</html>
