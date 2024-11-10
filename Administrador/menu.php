<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navegación</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Enlace a Font Awesome -->
    <style>
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
        <a href="./login/bienvenido.php" ><i class="fas fa-home"></i>Inicio</a>
        <a href="empleados_lista.php"><i class="fas fa-users"></i>Empleados</a>
        <a href="./Productos/Productos_lista.php"><i class="fas fa-box"></i>Productos</a>
        <a href="./Promociones/promociones_lista.php"><i class="fas fa-tag"></i>Promociones</a>
        <a href="./Pedidos/pedidos.php"><i class="fa-solid fa-cart-shopping"></i>Pedidos</a>
        <a href="logout.php" class="right"><i class="fas fa-sign-out-alt"></i>Cerrar sesión</a>
    </div>
</body>
</html>

