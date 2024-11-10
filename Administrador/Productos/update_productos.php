<?php
require "../funciones/conecta.php";
$con = conecta();

$id = $_REQUEST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$codigo = $_POST['codigo'];
$costo = $_POST['costo'];
$stock = $_POST['stock'];  // Añadido para recoger el stock
$archivo_nombre = $_FILES['archivo']['name'];
$archivo_tmp = $_FILES['archivo']['tmp_name'];

// Iniciar la consulta SQL de actualización
$sql = "UPDATE productos SET 
            nombre = '$nombre', 
            descripcion = '$descripcion', 
            codigo = '$codigo',
            costo = '$costo', 
            stock = '$stock'";  // Añadido el campo de stock

// Si se proporciona un nuevo archivo, manejarlo
if ($archivo_nombre) {
    // Obtener la extensión del archivo
    $ext = pathinfo($archivo_nombre, PATHINFO_EXTENSION);
    // Generar un nombre único para el archivo usando el hash MD5
    $archivo_file = md5_file($archivo_tmp) . '.' . $ext;
    $dir = "archivos/";
    // Mover el archivo subido al directorio correspondiente
    if (move_uploaded_file($archivo_tmp, $dir . $archivo_file)) {
        $sql .= ", archivo_n = '$archivo_nombre', archivo = '$archivo_file'";
    } else {
        echo "Error al subir el archivo.";
        exit;
    }
}

$sql .= " WHERE id = $id";

// Ejecutar la consulta
$res = $con->query($sql);

// Comprobar si la actualización fue exitosa
if ($res) {
    echo "Datos del producto actualizados correctamente.";
} else {
    echo "Error al actualizar los datos: " . $con->error;
}

// Cerrar la conexión
$con->close();

// Redirigir a la lista de productos
header("Location: productos_lista.php");
exit;
?>
