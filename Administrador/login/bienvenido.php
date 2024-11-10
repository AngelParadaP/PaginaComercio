<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['nomUser'])) {
    // Si no hay sesión abierta, redirige a index.php
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Enlace a Font Awesome -->
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #74ebd5 0%, #ACB6E5 100%);
            color: #333;
        }
        .container {
            text-align: center;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            color: #4a90e2;
            font-size: 28px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            background-color: #4a90e2;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #357ab7;
        }
        .navbar {
            position: fixed; /* Fija el menú en la parte superior */
            top: 0; /* Alinea el menú al borde superior */
            left: 0; /* Asegura que el menú comience desde el borde izquierdo */
            width: 100%; /* Asegura que ocupe el 100% de la pantalla */
            background-color: #4a90e2; /* Color de fondo del menú */
            padding: 8px;
            border-radius: 0 0 10px 10px; /* Bordes redondeados en la parte inferior */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000; /* Asegura que el menú esté encima del contenido */
            display: flex; /* Utiliza flexbox para centrar los elementos */
            justify-content: center; /* Centra los enlaces horizontalmente */
        }

        .navbar a {
            color: white;
            text-align: center;
            padding: 8px 15px;
            text-decoration: none;
            font-size: 17px;
            transition: background-color 0.3s;
            display: flex; /* Permite usar flexbox en los enlaces */
            align-items: center; /* Centra verticalmente el texto y el icono */
        }

        .navbar a:hover {
            background-color: #357ab7; /* Color al pasar el mouse */
        }

        .navbar a.active {
            background-color: #357ab7; /* Color de la página activa */
            color: white;
        }

        .navbar .right {
            margin-left: auto; /* Empuja este enlace hacia la derecha */
        }

        .navbar i {
            margin-right: 8px; /* Espacio entre el icono y el texto */
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="bienvenido.php" ><i class="fas fa-home"></i>Inicio</a>
        <a href="../empleados_lista.php"><i class="fas fa-users"></i>Empleados</a>
        <a href="../Productos/Productos_lista.php"><i class="fas fa-box"></i>Productos</a>
        <a href="../Promociones/promociones_lista.php"><i class="fas fa-tag"></i>Promociones</a>
        <a href="../Pedidos/pedidos.php"><i class="fa-solid fa-cart-shopping"></i>Pedidos</a>

        <a href="../logout.php" class="right"><i class="fas fa-sign-out-alt"></i>Cerrar sesión</a>
    </div>
    <div class="container">
        
        <h1>Hola, <?php echo htmlspecialchars($_SESSION['nomUser']); ?>, bienvenido al sistema.</h1>
        <p>Has iniciado sesión correctamente.</p>
        <a href="../empleados_lista.php" class="button">Ir a la lista de empleados</a>
    </div>

</body>
</html>
