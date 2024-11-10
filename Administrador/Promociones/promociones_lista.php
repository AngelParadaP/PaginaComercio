<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['nomUser'])) {
    // Si no hay sesión abierta, redirige a index.php
    header("Location: ../login/index.php");
    exit();
}
?>
<?php
    require "../funciones/conecta.php";
    
    $con = conecta();
    $sql = "SELECT * FROM promociones WHERE eliminado = 0";
    $res = $con->query($sql);
    
    $num_promociones = $res->num_rows;  
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de promociones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f0f8ff;
            color: #333;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            margin-top: 70px;
            background-color: white; 
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        .button-create {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 60px;
            height: 60px;
            margin: 20px auto;
            background-color: #4a90e2;
            color: white;
            font-size: 36px;
            border-radius: 50%;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .button-create:hover {
            background-color: #357ab7;
        }
        .button-create:after {
            content: "+";
            font-weight: bold;
        }
        .card {
            width: 30%;
            display: inline-block;
            background-color: #fff;
            margin: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
        }
.card img {
    height: 200px;      /* Fija la altura en 200px */
    width: 100%;        /* Ajusta el ancho automáticamente para mantener la proporción */
    display: block;     /* Elimina el espacio debajo de la imagen en contenedores inline */
    margin: 0 auto;     /* Centra la imagen horizontalmente si el contenedor es más ancho */
}

        .card-body {
            padding: 15px;
        }
        .card h2 {
            font-size: 22px;
            margin: 10px 0;
        }
        .card p {
            font-size: 16px;
            color: #555;
        }
        .card .price {
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
            color: #333;
        }
        .card-buttons {
            display: flex;
            justify-content: space-between;
            padding: 10px;
        }
        .card-buttons a {
            width: 32%;
            text-decoration: none;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .button-details {
            background-color: green;
            margin-right : 2px;
        }
        .button-details:hover {
            background-color: #15992d;
        }
        .button-edit {
            background-color: #4a90e2;
            margin-right:2px;
        }
        .button-edit:hover {
            background-color: #357ab7;
        }
        .button-delete {
            background-color: red;
        }
        .button-delete:hover {
            background-color: darkred;
        }
                .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            width: 30%;
            text-align: center;
        }
        .modal-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
        }
        .modal-buttons button {
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .button-cancel {
            background-color: #aaa;
            color: white;
        }
        .button-confirm {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container">
        <h1>Listado de promociones (<?php echo $num_promociones; ?>)</h1>
        <a href="PromocionesAlta.php" class="button-create"></a>

        <?php
        while ($row = $res->fetch_array()) {
            $id = $row["id"];
            $nombre = $row["nombre"];
            $archivo = $row["archivo"] ? "archivos/" . $row["archivo"] : "default.jpg"; 

echo "
    <div class='card' data-id='$id'>
        <a href='./verdetalles_promocion.php?id=$id' style='text-decoration: none; color: inherit;'> <!-- Envolver toda la tarjeta en un enlace -->
            <img src='$archivo' alt='Imagen de promocion'>
            <div class='card-body'>
                <h2>$nombre</h2>
            </div>
        </a>
        <div class='card-buttons'>
            <a href='./verdetalles_promocion.php?id=$id' class='button-details'>Ver detalles</a>
            <a href='./editar_promociones.php?id=$id' class='button-edit'>Editar</a>
            <a href='javascript:void(0)' onclick='mostrarModal($id)' class='button-delete'>Eliminar</a>
        </div>
    </div>";
}
        ?>

        <div id="modalConfirm" class="modal">
            <div class="modal-content">
                <h2>¿Estás seguro de que deseas eliminar esta promocion?</h2>
                <div class="modal-buttons">
                    <button class="button-cancel" onclick="ocultarModal()">Cancelar</button>
                    <button class="button-confirm" onclick="eliminaAjax()">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var idEliminar; 

        function mostrarModal(id) {
            idEliminar = id;
            document.getElementById('modalConfirm').style.display = 'block';
        }

        function ocultarModal() {
            document.getElementById('modalConfirm').style.display = 'none';
        }

        function eliminaAjax() {
            $.ajax({
                url : 'Promocion_elimina.php',
                type : 'post',
                dataType : 'json',
                data: { id: idEliminar },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        Toastify({
                            text: "Promocion eliminada con éxito",
                            duration: 3000,
                            backgroundColor: "green"
                        }).showToast();
                        $("div.card[data-id='" + idEliminar + "']").remove();
                    } else {
                        Toastify({
                            text: "Error al eliminar la promocion: " + response.error,
                            duration: 3000,
                            backgroundColor: "red"
                        }).showToast();
                    }
                },
                error: function(){
                    Toastify({
                        text: "Error al comunicarse con el servidor",
                        duration: 3000,
                        backgroundColor: "red"
                    }).showToast();
                }
            });
            ocultarModal(); 
        }
    </script>
</body>
