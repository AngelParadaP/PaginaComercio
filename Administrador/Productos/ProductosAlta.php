<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['nomUser'])) {
    // Si no hay sesión abierta, redirige a index.php
    header("Location: ../login/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de nuevo producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f8ff;
        }
        .container {
            margin-top: 10%;
            width: 400px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-size: 14px;
            color: #555;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"],
        textarea {
            padding: 10px;
            width: 95%;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
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
        .error {
            color: red;
            font-size: 12px;
            display: none;
        }
             .upload-container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #4a90e2;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .file-upload input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        .upload-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            color: #ffffff;
            background-color: #4a90e2;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .upload-button:hover {
            background-color: #357ab7;
        }
        .upload-button .icon {
            margin-right: 8px;
            font-size: 18px;
        }
                #preview {
            display: none;
            margin-top: 10px;
            max-width: 100px;
            border-radius: 50%;
        }
    </style>
    <script src="../jquery-3.3.1.min.js"></script>
<script>
        function cambiartextoimagen() {
            uploadButton.innerHTML = '<span class="icon">✔️</span> Archivo subido'; // Cambiar el texto del botón
        }
    // Función para ocultar errores después de 5 segundos
    function hideError() {
        setTimeout(function() {
            $('.error').fadeOut();
        }, 5000);
    }

    // Función para verificar si el código ya está registrado
    function checkcodigoExists() {
        let codigo = $('#codigo').val();
        return new Promise(function(resolve, reject) {
            if (codigo) {
                $.ajax({
                    url: 'check_codigo.php',
                    type: 'POST',
                    data: { codigo: codigo },
                    success: function(response) {
                        if (response === 'exists') {
                            $('#codigoError').text('El código ' + codigo + ' ya está registrado, ingrese uno nuevo.').show();
                            resolve(false);  // Código ya existe
                        } else {
                            $('#codigoError').hide(); // No existe el código
                            resolve(true);  // Código no existe
                        }
                    },
                    error: function() {
                        reject('Error en la solicitud AJAX');
                    }
                });
            } else {
                resolve(true);  // Si no se ingresó código, dejar pasar la validación para que el formulario lo maneje
            }
        });
    }

    // Función para validar el formulario
    function validateForm() {
        let valid = true;
        $('.error').hide(); // Ocultar errores previos

        // Validaciones locales
        if ($('#nombre').val() === "") {
            $('#nombreError').show();
            valid = false;
        }
        if ($('#codigo').val() === "") {
            $('#codigoError').text('Por favor, ingresa el código del producto.').show();
            valid = false;
        }
        if ($('#costo').val() === "" || $('#costo').val() <= 0) {
            $('#costoError').show();
            valid = false;
        }
        if ($('#stock').val() === "" || $('#stock').val() < 0) {
            $('#stockError').show();
            valid = false;
        }
        if ($('#archivo').val() === "") {
            $('#archivoError').show();
            valid = false;
        }

        if ($('#descripcion').val() === "") {
            $('#descripcionError').show();
            valid = false;
        }

        // Si las validaciones locales pasaron, verificar el código
        if (valid) {
            return checkcodigoExists().then(function(isValidCodigo) {
                if (!isValidCodigo) {
                    hideError();  // Mostrar errores si el código ya existe
                }
                return isValidCodigo;  // Retornar resultado final de la validación
            });
        }

        hideError();
        return Promise.resolve(false);  // Si no pasó las validaciones locales, devolver false
    }

    $(document).ready(function() {
        // Validación al enviar el formulario
        $('#productoForm').on('submit', function(e) {
            e.preventDefault();  // Evitar el envío predeterminado

            validateForm().then(function(isFormValid) {
                if (isFormValid) {
                    $('#productoForm').off('submit').submit();  // Si es válido, enviar el formulario
                }
            });
        });

        // Verificar el código cuando se pierde el foco
        $('#codigo').on('blur', function() {
            checkcodigoExists();  // Llamada para verificar el código
        });
    });
</script>

</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container">
        <h1>Registrar nuevo producto</h1>

        <form id="productoForm" action="Productos_Salva.php" method="POST" enctype="multipart/form-data">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre">
            <div id="nombreError" class="error">Por favor, ingresa el nombre del producto.</div>

            <label for="codigo">Código:</label>
            <input type="text" id="codigo" name="codigo">
            <div id="codigoError" class="error">Por favor, ingresa el código del producto.</div>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"></textarea>
            <div id="descripcionError" class="error">Por favor, ingresa una descripcion valida.</div>


            <label for="costo">Costo:</label>
            <input type="number" step="0.01" id="costo" name="costo">
            <div id="costoError" class="error">Por favor, ingresa un costo válido.</div>

            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock">
            <div id="stockError" class="error">Por favor, ingresa una cantidad de stock válida.</div>

            <div class="upload-container">
                <label for="archivo">Foto:</label>
                <div class="file-upload">
                    <button class="upload-button" type="button" id="uploadButton">
                        <span class="icon">📷</span> Subir imagen
                    </button>
                    <input type="file" id="archivo" name="archivo" accept="image/*" onchange="cambiartextoimagen()">
                </div>
                <div id="archivoError" class="error">Por favor, sube una foto.</div>
                <img id="preview" src="#" alt="Vista previa">
            </div>

            <input type="submit" class="button-submit" value="Guardar producto">
            <a href="Productos_lista.php"><button type="button" class="button-submit">Regresar</button></a>

        </form>
    </div>
</body>
</html>
