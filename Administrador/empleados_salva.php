<?php
require "funciones/conecta.php";
$con = conecta();

$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$correo = $_POST['correo'];
$pass = $_POST['password'];
$rol = $_POST['rol'];
$passEnc = md5($pass);

$archivo_nombre = $_FILES['archivo']['name'];
$archivo_tmp = $_FILES['archivo']['tmp_name'];

if ($archivo_nombre) {
    $ext = pathinfo($archivo_nombre, PATHINFO_EXTENSION);
    
    $archivo_file = md5_file($archivo_tmp) . '.' . $ext;
    
    $dir = "archivos/";

    move_uploaded_file($archivo_tmp, $dir . $archivo_file);
}

$sql = "INSERT INTO usuarios 
        (nombre, apellidos, correo, pass, rol, archivo_nombre, archivo_file) 
        VALUES 
        ('$nombre', '$apellidos', '$correo', '$passEnc', '$rol', '$archivo_nombre', '$archivo_file')";

$res = $con->query($sql);

header("Location: empleados_lista.php");
?>
