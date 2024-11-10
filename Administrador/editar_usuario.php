<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['nomUser'])) {
    // Si no hay sesión abierta, redirige a index.php
    header("Location: ./login/index.php");
    exit();
}
?>

<?php
require "funciones/conecta.php";
$con = conecta();
$id = $_REQUEST['id'];

$sql = "SELECT * FROM usuarios WHERE id = $id";
$res = $con->query($sql);

// Estilos
echo '<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f8ff;
        color: #333;
        text-align: center;
    }
    .form-container {
        width: 80%;
        margin: 20px auto;
        padding: 20px;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }
    .profile-pic-container {
        position: relative;
        display: inline-block;
    }
    .profile-pic {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #4a90e2;
        transition: opacity 0.3s;
    }

    .edit-icon {
        position: absolute;
        top: 0; /* Ajusta según necesites */
        left: 0; /* Ajusta según necesites */
        cursor: pointer;
        display: block; /* Cambiado de none a block */
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        padding: 5px;
        width: 30px; /* Ajusta el tamaño del icono */
        height: 30px; /* Ajusta el tamaño del icono */
        object-fit: contain; /* Mantiene la proporción */
        z-index: 1; /* Asegura que el icono esté encima */
    }
    .profile-pic-container {
        position: relative;
        display: inline-block;
    }
    .profile-pic {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #4a90e2;
        transition: opacity 0.3s;
    }

    .profile-pic-container:hover .profile-pic {
        opacity: 0.7; /* Oscurecer la imagen */
    }
    .input-field {
        width: 90%;
        padding: 10px;
        margin: 10px auto;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .button-submit {
        width: 10%;
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
        margin-top: 10px;
    }
    .success-message {
        color: green;
        margin-top: 10px;
    }
</style>';

// Obtener datos del usuario
$row = $res->fetch_array();
$nombre = $row["nombre"];
$apellidos = $row["apellidos"];
$correo = $row["correo"];
$rol = $row["rol"] == 1 ? "Gerente" : "Ejecutivo";  
$status = $row["eliminado"] == 0 ? "Activo" : "Eliminado"; 
$archivo_file = $row["archivo_file"];
$imagen = $archivo_file ? "./archivos/$archivo_file" : "./archivos/usericon.jpg";

// Incluir el menú
include 'menu.php'; 

// Botón para volver atrás
echo '<a href="empleados_lista.php"><button type="button" class="button-submit" style="margin-top: 50px;">◀ Regresar</button></a>';

// Mostrar imagen de perfil y botón de edición
echo '<form id="edit-form" action="update_usuario.php" method="POST" enctype="multipart/form-data">
<div class="form-container">
        <div class="profile-pic-container">
            <img src="' . $imagen . '" alt="Imagen de perfil" class="profile-pic" id="current-profile-pic">
            <img src="archivos/icono_lapiz.png" alt="Editar" class="edit-icon" onclick="document.getElementById(\'file-input\').click();">
<input type="file" id="file-input" name="archivo" style="display: none;" accept="image/*" onchange="uploadImage();">
        </div>
        <h2>Edición de empleados</h2>
        <input type="hidden" name="id" value="' . $id . '"> <!-- ID del usuario -->
        <input type="text" name="nombre" placeholder="Nombre" value="' . $nombre . '" class="input-field" required>
        <input type="text" name="apellidos" placeholder="Apellidos" value="' . $apellidos . '" class="input-field" required>
<input type="email" name="correo" placeholder="Correo" value="' . $correo . '" class="input-field" required onblur="checkEmail()">
        <select name="rol" class="input-field">
            <option value="1"' . ($row["rol"] == 1 ? ' selected' : '') . '>Gerente</option>
            <option value="2"' . ($row["rol"] == 2 ? ' selected' : '') . '>Ejecutivo</option>
        </select>
        <input type="password" name="password" placeholder="Nueva Contraseña (opcional)" class="input-field">

        <div id="error-message" class="error-message"></div>
        <button type="button" class="button-submit" onclick="validateForm()">Guardar</button>
      </form>
      <div id="email-error" class="error-message"></div>
      <div id="success-message" class="success-message"></div>
    </div>';

echo '<script>

        function checkEmail() {
        var email = document.querySelector(\'input[name="correo"]\').value;
        var emailErrorDiv = document.getElementById("email-error");
        
        if (email) {
            fetch("check_email_editar.php?correo=" + encodeURIComponent(email) + "&id=' . $id . '")
            .then(response => response.text())
            .then(data => {
                if (data === "exists") {
                    emailErrorDiv.textContent = "El correo " + email + " ya está registrado.";
                } else {
                    emailErrorDiv.textContent = "";
                }
            });
        }
    }

function validateForm() {
    var form = document.getElementById("edit-form");
    var email = document.querySelector(\'input[name="correo"]\').value;
    var inputs = form.querySelectorAll("input[required]");
    var errorMessage = document.getElementById("error-message");
    var emailErrorDiv = document.getElementById("email-error");
    var isEmpty = false;

    inputs.forEach(input => {
        if (!input.value) {
            isEmpty = true;
        }
    });

    if (isEmpty) {
        errorMessage.textContent = "Faltan campos por llenar.";
        setTimeout(() => errorMessage.textContent = "", 5000);
        return; // Detener la ejecución si faltan campos
    }

    // Verificar si el correo ya existe
    if (email) {
        fetch("check_email_editar.php?correo=" + encodeURIComponent(email) + "&id=' . $id . '")
        .then(response => response.text())
        .then(data => {
            if (data === "exists") {
                emailErrorDiv.textContent = "El correo " + email + " ya está registrado.";
                setTimeout(() => emailErrorDiv.textContent = "", 5000);
            } else {
                emailErrorDiv.textContent = "";
                // Si el correo no está duplicado, se envía el formulario
                form.submit();
            }
        });
    }
}


    function uploadImage() {
        var fileInput = document.getElementById("file-input");
        var currentProfilePic = document.getElementById("current-profile-pic");

        // Verificar si hay un archivo seleccionado
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();

            // Función que se ejecuta una vez que la lectura del archivo se complete
            reader.onload = function(e) {
                // Cambiar la fuente de la imagen de perfil actual
                currentProfilePic.src = e.target.result;
            };

            // Leer el archivo como una URL de datos
            reader.readAsDataURL(fileInput.files[0]);
        }
    }
</script>';
?>
