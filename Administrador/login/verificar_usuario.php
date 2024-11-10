<?php
require "../funciones/conecta.php";
session_start(); // Inicia la sesión

$con = conecta();

if ($con->connect_error) {
    die("Conexión fallida: " . $con->connect_error); 
}

$email = $_POST['correo']; 
$password = $_POST['password'];

$password_hashed = md5($password);

$sql = "SELECT * FROM usuarios WHERE correo = ? AND pass = ? AND eliminado = 0";
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $email, $password_hashed);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró un usuario
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hash_password = $row['pass'];

    // Verifica si la contraseña coincide
    if ($password_hashed == $hash_password) {
        $response['success'] = true;
        
        // Establece las variables de sesión
        $_SESSION['idUser'] = $row['id'];
        $_SESSION['nomUser'] = $row['nombre'] . ' ' . $row['apellidos'];
        $_SESSION['correoUser'] = $row['correo'];

        echo 'existe';
    } else {
        echo 'no_existe';
    }
} else {
    echo 'no_existe'; 
}

$stmt->close();
$con->close(); 
?>
