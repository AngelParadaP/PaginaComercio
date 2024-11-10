<?php
session_start();
include 'menu.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Contacto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
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
            margin-top:200px;
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
        .form-control {
            padding: 10px;
            width: 95%;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .button-submit {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 15px;
        }
        .button-submit:hover {
            background-color: #357ab7;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Formulario de Contacto</h1>
        <form id="contactForm">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" 
                    value="<?php echo isset($_SESSION['nomUser']) ? $_SESSION['nomUser'] : ''; ?>"
                    <?php echo isset($_SESSION['nomUser']) ? 'readonly' : ''; ?> required>
            </div>
            
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" 
                    value="<?php echo isset($_SESSION['correoUser']) ? $_SESSION['correoUser'] : ''; ?>"
                    <?php echo isset($_SESSION['correoUser']) ? 'readonly' : ''; ?> required>
            </div>

            <div class="form-group">
                <label for="asunto">Asunto</label>
                <input type="text" name="asunto" id="asunto" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="mensaje">Mensaje</label>
                <textarea name="mensaje" id="mensaje" class="form-control" rows="5" required></textarea>
            </div>

            <button type="submit" class="button-submit">Enviar Mensaje</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>

    

    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#contactForm').on('submit', function(e) {
                e.preventDefault(); 
                
                $.ajax({
                    url: 'procesar_contacto.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        Toastify({
                            text: "Mensaje enviado con éxito.",
                            backgroundColor: "#4CAF50",
                            duration: 3000
                        }).showToast();

                        if ("<?php echo isset($_SESSION['nomUser']); ?>") {
                            $('#asunto, #mensaje').val('');
                        }
                        // Limpiar los campos si el envío fue exitoso y no hay sesión activa
                        if (!"<?php echo isset($_SESSION['nomUser']); ?>") {
                            $('#nombre, #correo, #asunto, #mensaje').val('');
                        }
                    },
                    error: function(xhr) {
                        Toastify({
                            text: "Error al enviar el mensaje.",
                            backgroundColor: "#FF0000",
                            duration: 3000
                        }).showToast();
                    }
                });
            });
        });
    </script>
</body>

</html>
