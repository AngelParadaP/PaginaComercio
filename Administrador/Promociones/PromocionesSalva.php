<?php
require "../funciones/conecta.php";
$con = conecta();

$nombre = $_POST['nombre'];
$archivo_nombre = $_FILES['archivo']['name'];
$archivo_tmp = $_FILES['archivo']['tmp_name'];

// Verificamos si se subió una imagen
if ($archivo_nombre) {
    $ext = pathinfo($archivo_nombre, PATHINFO_EXTENSION); // Obtenemos la extensión del archivo
    $archivo_file = md5_file($archivo_tmp) . '.' . $ext; // Creamos un nombre único basado en el hash del archivo
    $dir = "archivos/"; // Directorio donde se guardará la imagen

    move_uploaded_file($archivo_tmp, $dir . $archivo_file); // Movemos el archivo al directorio
} else {
    $archivo_file = null; // Si no se sube un archivo, el valor será nulo
}

// Validar que todos los campos estén llenos
if ($nombre != "") {
    // Inserción en la base de datos con eliminado = 0
    $sql = "INSERT INTO promociones (nombre, archivo) 
            VALUES ('$nombre','$archivo_file')";
    
    $res = $con->query($sql); // Ejecutamos la consulta

    header("Location: promociones_lista.php"); // Redireccionamos a la lista de productos
} else {
    echo "Error: Todos los campos son obligatorios.";
}
?>
