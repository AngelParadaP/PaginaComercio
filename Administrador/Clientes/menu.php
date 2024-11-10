<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navegación</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #4a90e2;
            padding: 8px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar .logo {
            display: flex;
            align-items: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
            text-decoration: none;
            margin-left: 15px;
        }

        .navbar a {
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            font-size: 17px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
        }

        .navbar a:hover {
            background-color: #357ab7;
        }

        .navbar i {
            margin-right: 8px;
        }

        .navbar .menu-items {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="#" class="logo"><i class="fas fa-store"></i>Mi Tienda</a>
        <div class="menu-items">
            <a href="./Home.php"><i class="fas fa-home"></i>Home</a>
            <a href="Productos_lista.php"><i class="fas fa-box"></i>Productos</a>
            <a href="./contacto.php"><i class="fas fa-users"></i>Contacto</a>
            <?php if (isset($_SESSION['nomUser'])): ?>
                <a href="./Logout.php"><i class="fas fa-sign-out-alt"></i>Salir</a>
                <a href="./carrito01.php"><i class="fas fa-shopping-cart"></i>Ver carrito</a>
                <span style="color: white; padding: 8px;">Bienvenido <?php echo $_SESSION['nomUser']; ?></span>
            <?php else: ?>
                <a href="./Login.php"><i class="fas fa-sign-in-alt"></i>Login</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
