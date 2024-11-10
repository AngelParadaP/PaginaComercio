<?php
require "../funciones/conecta.php";
session_start(); 

$con = conecta();

if ($con->connect_error) {
    die("Conexión fallida: " . $con->connect_error); 
}

$email = $_POST['correo']; 
$password = $_POST['password'];

$password_hashed = ($password);

$sql = "SELECT * FROM clientes WHERE correo = ? AND pass = ? AND eliminado = 0";
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $email, $password_hashed);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hash_password = $row['pass'];

    if ($password_hashed == $hash_password) {
        $response['success'] = true;
        
        $_SESSION['idUser'] = $row['id'];
        $_SESSION['nomUser'] = $row['nombre'] ;
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
