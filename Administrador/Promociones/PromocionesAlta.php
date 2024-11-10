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
    <title>Registro de una nueva promocion</title>
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

    function validateForm() {
        let valid = true;
        $('.error').hide(); // Ocultar errores previos

        // Validaciones locales
        if ($('#nombre').val() === "") {
            $('#nombreError').show();
            valid = false;
        }

        if ($('#archivo').val() === "") {
            $('#archivoError').show();
            valid = false;
        }

        hideError();
        return Promise.resolve(valid);  // Devuelve true si es válido, false si hay errores
    }


    $(document).ready(function() {
        // Validación al enviar el formulario
        $('#promocionForm').on('submit', function(e) {
            e.preventDefault();  // Evitar el envío predeterminado

            validateForm().then(function(isFormValid) {
                if (isFormValid) {
                    $('#promocionForm').off('submit').submit();  // Si es válido, enviar el formulario
                }
            });
        });

    });
</script>

</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container">
        <h1>Registrar nueva promocion</h1>

        <form id="promocionForm" action="PromocionesSalva.php" method="POST" enctype="multipart/form-data">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre">
            <div id="nombreError" class="error">Por favor, ingresa el nombre de la promocion.</div>

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

            <input type="submit" class="button-submit" value="Guardar promocion">
            <a href="promociones_lista.php"><button type="button" class="button-submit">Regresar</button></a>

        </form>
    </div>
</body>
</html>
