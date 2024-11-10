<?php
session_start();
if (isset($_SESSION['nomUser'])) {
    header("Location: Home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <style>
        body {
               display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
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
            margin-top:150px;
            flex:1;
            width: 400px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }


footer {
    background-color: #333;
    color: #fff;
    padding: 20px;
    text-align: center;
    width: 100%; 
    margin-top: auto; 
}
        h2 {
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
        input[type="password"] {
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
        #mensaje {
            text-align: center;
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function validateForm() {
                let valid = true;
                $('.error').hide();

                if ($('#nombre').val() === "") {
                    $('#nombreError').show();
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

                return valid;
            }

            $('#loginForm').on('submit', function(event) {
                event.preventDefault();

                $('#mensaje').text('');

                if (!validateForm()) {
                    $('#mensaje').text('Por favor, complete todos los campos.');
                    return;
                }

                $.ajax({
                    url: 'verificar_usuario.php',
                    type: 'POST',
                    data: {
                        nombre: $('#nombre').val(),
                        correo: $('#correo').val(),
                        password: $('#password').val()
                    },
                    success: function(response) {
                        if (response === 'existe') {
                            window.location.href = 'Home.php';
                        } else {
                            $('#mensaje').text('Usuario no existe o no está activo.');
                        }
                    },
                    error: function() {
                        $('#mensaje').text('Error en la comunicación con el servidor.');
                    }
                });
            });
        });
    </script>
</head>
<body>
        <?php include 'menu.php'; ?>

<div class="container">
    <h2>Inicio de Sesión</h2>
    <form id="loginForm">

        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo">
        <div id="correoError" class="error">Por favor, ingresa el correo.</div>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password">
        <div id="passwordError" class="error">Por favor, ingresa la contraseña.</div>

        <button type="submit" class="button-submit">Iniciar Sesión</button>
    </form>

    <div id="mensaje"></div>
</div>
    <?php include 'footer.php'; ?>

</body>
</html>
