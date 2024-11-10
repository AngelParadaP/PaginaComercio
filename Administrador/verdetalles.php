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

echo '<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f8ff;
        color: #333;
        text-align: center;
        margin: 0;
        padding-top: 80px; /* Margen superior para evitar que el contenido quede oculto por el menú */
    }
    table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }
    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #4a90e2;
    }
    th {
        background-color: #4a90e2;
        color: white;
    }
    tr:hover {
        background-color: #e6f1ff;
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
    .profile-pic {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin-top: 20px;
        border: 4px solid #4a90e2;
    }
</style>';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
</head>
<body>
    <?php include 'menu.php'; ?> <!-- Incluir el menú -->

    <?php
    $row = $res->fetch_array();
    $nombre = $row["nombre"];
    $apellidos = $row["apellidos"];
    $correo = $row["correo"];
    $rol = $row["rol"] == 1 ? "Gerente" : "Ejecutivo";  
    $status = $row["eliminado"] == 0 ? "Activo" : "Eliminado"; 
    $archivo_file = $row["archivo_file"];

    echo '<a href="empleados_lista.php"><button type="button" class="button-submit">◀</button></a>';

    // Verificar si el usuario tiene una imagen de perfil; si no, usar la imagen predeterminada
    $imagen = $archivo_file ? "./archivos/$archivo_file" : "./archivos/usericon.jpg";

    // Mostrar imagen de perfil
    echo '<div><img src="' . $imagen . '" alt="Imagen de perfil" class="profile-pic"></div>';

    // Mostrar tabla de datos del usuario
    echo '<table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estatus</th>
            </tr>
            <tr>
                <td>' . $id . '</td>
                <td>' . $nombre . ' ' . $apellidos . '</td>
                <td>' . $correo . '</td>
                <td>' . $rol . '</td>
                <td>' . $status . '</td>
            </tr>
          </table>';
    ?>
</body>
</html>
