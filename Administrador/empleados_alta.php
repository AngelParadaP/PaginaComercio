<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['nomUser'])) {
    // Si no hay sesión abierta, redirige a index.php
    header("Location: ./login/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de nuevo empleado</title>
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
        input[type="email"],
        input[type="password"],
        select {
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
        label {
            font-size: 16px;
            color: #4a90e2;
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

    <script src="./jquery-3.3.1.min.js"></script>
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

            $('.error').hide(); 

            if ($('#nombre').val() === "") {
                $('#nombreError').show();
                valid = false;
            }
            if ($('#apellidos').val() === "") {
                $('#apellidosError').show();
                valid = false;
            }
            if ($('#correo').val() === "") {
                $('#correoError').show();
                valid = false;
            }
            if ($('#password').val() === "") {
                $('#passwordError').show();
                valid = false;
            }
            if ($('#rol').val() === "") {
                $('#rolError').show();
                valid = false;
            }
            if ($('#archivo').val() === "") {
                $('#archivoError').show();
                valid = false;
            }

            let correo = $('#correo').val();
            if (correo) {
                $.ajax({
                    url: 'check_email.php',
                    type: 'POST',
                    data: { correo: correo },
                    async: false, 
                    success: function(response) {
                        if (response === 'exists') {
                            $('#correoError').text('El correo '+correo+' ya está registrado, ingrese uno nuevo.').show();
                            hideError(); 
                            valid = false; 
                        }
                    }
                });
            }

            if (!valid || !emailValid) {
                hideError(); 
            }

            return valid;
        }

        // Comprobar si el correo ya está registrado
        function checkEmailExists() {
            let correo = $('#correo').val();
            if (correo) {
                $.ajax({
                    url: 'check_email.php', 
                    type: 'POST',
                    data: { correo: correo },
                    success: function(response) {
                        if (response === 'exists') {
                            $('#correoError').text('El correo '+correo+' ya está registrado, ingrese uno nuevo.').show();
                            hideError(); 
                        }
                    }
                });
            }
        }

        $(document).ready(function() {
            $('#empleadoForm').on('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault(); 
                }
            });

            $('#correo').on('blur', function() {
                checkEmailExists();
            });
        });

    </script>
</head>
<body>
<?php include 'menu.php'; ?>

<div class="container">
    
    <h1>Registrar nuevo empleado</h1>

<form id="empleadoForm" action="empleados_salva.php" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre">
        <div id="nombreError" class="error">Por favor, ingresa el nombre.</div>

        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos">
        <div id="apellidosError" class="error">Por favor, ingresa los apellidos.</div>

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo">
        <div id="correoError" class="error">Por favor, ingresa el correo.</div>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password">
        <div id="passwordError" class="error">Por favor, ingresa la contraseña.</div>

        <label for="rol">Rol:</label>
        <select id="rol" name="rol">
            <option value="">Selecciona un rol</option>
            <option value="1">Gerente</option>
            <option value="2">Ejecutivo</option>
        </select>
        <div id="rolError" class="error">Por favor, selecciona un rol.</div>

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

        <button type="submit" class="button-submit">Guardar empleado</button>
        <a href="empleados_lista.php"><button type="button" class="button-submit">Regresar</button></a>
    </form>
</div>

</body>
</html>
