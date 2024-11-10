<?php
require "funciones/conecta.php";
$con = conecta();

$id = $_REQUEST['id'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$correo = $_POST['correo'];
$rol = $_POST['rol'];
$password = $_POST['password'];

$passEnc = !empty($password) ? md5($password) : null;

$archivo_nombre = $_FILES['archivo']['name'];
$archivo_tmp = $_FILES['archivo']['tmp_name'];

$sql = "UPDATE usuarios SET 
            nombre = '$nombre', 
            apellidos = '$apellidos', 
            correo = '$correo', 
            rol = '$rol'";

// Si hay una nueva contraseña, agregarla a la consulta
if ($passEnc) {
    $sql .= ", pass = '$passEnc'";
}

// Si se proporciona un nuevo archivo, manejarlo
if ($archivo_nombre) {
    $ext = pathinfo($archivo_nombre, PATHINFO_EXTENSION);
    $archivo_file = md5_file($archivo_tmp) . '.' . $ext;
    $dir = "archivos/";
    move_uploaded_file($archivo_tmp, $dir . $archivo_file);
    $sql .= ", archivo_nombre = '$archivo_nombre', archivo_file = '$archivo_file'";
}

$sql .= " WHERE id = $id";

$res = $con->query($sql);

if ($res) {
    echo "Datos actualizados correctamente.";
} else {
    echo "Error al actualizar los datos: " . $con->error;
}

$con->close();
header("Location: empleados_lista.php");

?>
