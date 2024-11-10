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
    $promocion_id = $_GET['id'];

    // Preparar consulta para obtener los detalles del producto
    $sql = "SELECT nombre, status, archivo FROM promociones WHERE id = $promocion_id";
    $res = $con->query($sql);

    if ($res->num_rows > 0) {
        // Datos del producto encontrados
        $promocion = $res->fetch_assoc();
    } else {
        echo "promocion no encontrado.";
        die();
    }
} else {
    echo "ID de promocion no especificado.";
    die();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Promocion</title>
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
        <a href="promociones_lista.php"><button type="button" class="button-submit" style="margin-top: 20px;">◀ Regresar</button></a>

        <form id="edit-form" action="update_promocion.php" method="POST" enctype="multipart/form-data">
            <div class="card">
                <h2>Edición de promocion</h2>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($promocion_id); ?>">
                
                <!-- Sección de la imagen del producto -->
                <div class="profile-pic-container">
                    <img src="./archivos/<?php echo htmlspecialchars($promocion['archivo']); ?>" alt="Imagen de la promocion" class="product-pic" id="current-product-pic">
                    <img src="archivos/icono_lapiz.png" alt="Editar" class="edit-icon" onclick="document.getElementById('file-input').click();">
                    <input type="file" id="file-input" name="archivo" style="display: none;" accept="image/*" onchange="uploadImage();">
                </div>

                <label class="label" for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($promocion['nombre']); ?>" class="input-field" required>
                <div id="nombre-error-message" class="error-message">Por favor, ingresa el nombre de la promocion.</div>


                <label class="label" for="status">Status</label>
                <select name="status" id="status" class="input-field" required>
                    <option value="1" <?php echo $promocion['status'] == 1 ? 'selected' : ''; ?>>Activa</option>
                    <option value="0" <?php echo $promocion['status'] == 0 ? 'selected' : ''; ?>>Agotada</option>
                </select>
                <div id="stock-error-message" class="error-message">Por favor, selecciona un estado válido.</div>


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
        // Si no hay código, simplemente enviar el formulario
        if (valid) {
            document.getElementById("edit-form").submit();
        }
    
}


    </script>
</body>
</html>
