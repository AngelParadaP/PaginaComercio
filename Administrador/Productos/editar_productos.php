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
    $sql = "SELECT nombre, codigo, costo, stock, descripcion, archivo FROM productos WHERE id = $producto_id";
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
    <title>Editar Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            color: #333;
            text-align: center;
        }
        .card {
            width: 80%;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .input-field {
            width: 90%;
            padding: 10px;
            margin: 10px auto;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .label {
            display: block;
            font-size: 0.9em;
            color: #555;
            margin: 5px 0;
            text-align: left;
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
        .error-message {
            color: red;
            font-size: 0.8em; /* Tamaño más pequeño */
            margin-top: 5px;
            display: none; /* Inicialmente oculto */
        }
        .success-message {
            color: green;
            margin-top: 10px;
        }
        .img-preview {
            max-width: 100%;
            height: auto;
            margin: 10px 0;
        }
        .profile-pic-container {
            position: relative;
            display: inline-block;
        }
        .product-pic {
            width: 150px;
            height: 150px;
            border-radius: 10px; /* Cambiar si es necesario */
            object-fit: cover;
            border: 2px solid #4a90e2;
            transition: opacity 0.3s;
        }

        .edit-icon {
            position: absolute;
            top: 5px; /* Ajusta según necesites */
            right: 5px; /* Ajusta según necesites */
            cursor: pointer;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            padding: 5px;
            width: 30px; /* Ajusta el tamaño del icono */
            height: 30px; /* Ajusta el tamaño del icono */
            object-fit: contain; /* Mantiene la proporción */
            z-index: 1; /* Asegura que el icono esté encima */
        }

        .container{
            margin-top: 60px;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?> <!-- Incluir el menú -->

    <div class="container">
        <a href="productos_lista.php"><button type="button" class="button-submit" style="margin-top: 20px;">◀ Regresar</button></a>

        <form id="edit-form" action="update_productos.php" method="POST" enctype="multipart/form-data">
            <div class="card">
                <h2>Edición de Productos</h2>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto_id); ?>">
                
                <!-- Sección de la imagen del producto -->
                <div class="profile-pic-container">
                    <img src="./archivos/<?php echo htmlspecialchars($producto['archivo']); ?>" alt="Imagen del producto" class="product-pic" id="current-product-pic">
                    <img src="archivos/icono_lapiz.png" alt="Editar" class="edit-icon" onclick="document.getElementById('file-input').click();">
                    <input type="file" id="file-input" name="archivo" style="display: none;" accept="image/*" onchange="uploadImage();">
                </div>

                <label class="label" for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" class="input-field" required>
                <div id="nombre-error-message" class="error-message">Por favor, ingresa el nombre del producto.</div>

                <label class="label" for="codigo">Código</label>
                <input type="text" name="codigo" id="codigo" value="<?php echo htmlspecialchars($producto['codigo']); ?>" class="input-field" required onblur="checkCodigoRepetido(this.value)">
                <div id="codigo-error-message" class="error-message"></div>

                <label class="label" for="descripcion">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" value="<?php echo htmlspecialchars($producto['descripcion']); ?>" class="input-field" required>
                <div id="descripcion-error-message" class="error-message">Por favor, ingresa la descripción del producto.</div>

                <label class="label" for="costo">Costo</label>
                <input type="number" name="costo" id="costo" value="<?php echo htmlspecialchars($producto['costo']); ?>" class="input-field" required>
                <div id="costo-error-message" class="error-message">Por favor, ingresa un costo válido.</div>

                <label class="label" for="stock">Stock</label>
                <input type="number" name="stock" id="stock" value="<?php echo htmlspecialchars($producto['stock']); ?>" class="input-field" required>
                <div id="stock-error-message" class="error-message">Por favor, ingresa una cantidad válida de stock.</div>

                <button type="button" class="button-submit" onclick="validateForm()">Guardar</button>
            </div>
        </form>
    </div>

    <script>
        function uploadImage() {
            var fileInput = document.getElementById("file-input");
            var currentProductPic = document.getElementById("current-product-pic");

            if (fileInput.files && fileInput.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    currentProductPic.src = e.target.result;
                };

                reader.readAsDataURL(fileInput.files[0]);
            }
        }

 function validateForm() {
    let valid = true;
    let codigoValido = true; // Variable para controlar la validez del código
    // Ocultar todos los mensajes de error
    document.querySelectorAll('.error-message').forEach(function(message) {
        message.style.display = 'none';
    });

    // Validaciones
    if (document.getElementById('nombre').value.trim() === "") {
        document.getElementById('nombre-error-message').style.display = 'block';
        valid = false;
    }
    if (document.getElementById('codigo').value.trim() === "") {
        document.getElementById('codigo-error-message').textContent = 'Por favor, ingresa el código del producto.';
        document.getElementById('codigo-error-message').style.display = 'block';
        valid = false;
    }
    if (document.getElementById('descripcion').value.trim() === "") {
        document.getElementById('descripcion-error-message').style.display = 'block';
        valid = false;
    }
    if (document.getElementById('costo').value.trim() === "" || parseFloat(document.getElementById('costo').value) <= 0) {
        document.getElementById('costo-error-message').style.display = 'block';
        valid = false;
    }
    if (document.getElementById('stock').value.trim() === "" || parseInt(document.getElementById('stock').value) < 0) {
        document.getElementById('stock-error-message').style.display = 'block';
        valid = false;
    }

    // Verificar si el código ya está registrado
    const codigo = document.getElementById('codigo').value.trim();
    if (codigo !== "") {
        const productoId = document.querySelector('input[name="id"]').value; // Obtén el ID del producto
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `check_codigoEditar.php?codigo=${codigo}&id=${productoId}`, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                if (xhr.responseText === 'exists') {
                    document.getElementById('codigo-error-message').textContent = 'El código ya está en uso. Por favor, elige otro.';
                    document.getElementById('codigo-error-message').style.display = 'block';
                    codigoValido = false; // El código ya existe
                }

                // Si todo es válido, enviar el formulario
                if (valid && codigoValido) {
                    document.getElementById("edit-form").submit();
                }
            }
        };
        xhr.send();
    } else {
        // Si no hay código, simplemente enviar el formulario
        if (valid) {
            document.getElementById("edit-form").submit();
        }
    }
}

// Función para verificar si el código ya está registrado
function checkCodigoRepetido(codigo) {
    let codigoErrorMessage = document.getElementById('codigo-error-message');
    codigoErrorMessage.style.display = 'none'; // Ocultar mensaje de error al comprobar

    const productoId = document.querySelector('input[name="id"]').value; // Obtén el ID del producto

    // Realiza una llamada AJAX para verificar el código
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `check_codigoEditar.php?codigo=${codigo}&id=${productoId}`, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            if (xhr.responseText === 'exists') {
                codigoErrorMessage.textContent = 'El código ya está en uso. Por favor, elige otro.';
                codigoErrorMessage.style.display = 'block';
            }
        }
    };
    xhr.send();
}


    </script>
</body>
</html>
