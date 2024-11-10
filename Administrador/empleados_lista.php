<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['nomUser'])) {
    // Si no hay sesión abierta, redirige a index.php
    header("Location: ./login/index.php");
    exit();
}
?>

<?php
    require "funciones/conecta.php";
    
    $con = conecta();
    $sql = "SELECT * FROM usuarios WHERE eliminado = 0";
    $res = $con->query($sql);
    
    $num_empleados = $res->num_rows;  
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de empleados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f0f8ff; 
            color: #333;
        }
        .container {
            width: 80%;
            margin: 70px auto;
            background-color: white; 
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        .button-create {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #4a90e2;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .button-create:hover {
            background-color: #357ab7;
        }
        .button-submit, .button-details {
            width: 100%;
            padding: 12px;
            background-color: red; 
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .button-submit:hover {
            background-color: darkred; 
        }
        .button-details {
            background-color: green; 
        }
        .button-details:hover{
            background-color: #15992d; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #4a90e2;
        }
        th {
            background-color: #4a90e2;
            color: white;
        }
        tr:hover {
            background-color: #e6f1ff; 
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="./jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
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
                url : 'empleados_elimina.php',
                type : 'post',
                dataType : 'json',
                data: { id: idEliminar },
                success : function(response){
                    if(response.success){
                        Toastify({
                            text: "Registro eliminado con éxito",
                            duration: 3000,
                            backgroundColor: "green"
                        }).showToast();
                        // Eliminar la fila de la tabla sin recargar
                        $("tr[data-id='" + idEliminar + "']").remove();
                    } else {
                        Toastify({
                            text: "Error al eliminar el registro",
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
    </head>
    <body>

    <div class="container">
        <?php include 'menu.php'; ?>

        <h1>Listado de empleados (<?php echo $num_empleados; ?>)</h1>
        <a href="empleados_alta.php" class="button-create">Crear nuevo registro</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre completo</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Ver detalle</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $res->fetch_array()) {
                    $id = $row["id"];
                    $nombre = $row["nombre"];
                    $apellidos = $row["apellidos"];
                    $correo = $row["correo"];
                    $rol = $row["rol"] == 1 ? "Gerente" : "Ejecutivo";  // Rol dependiendo del valor
                    echo "
                    <tr data-id='$id'>
                        <td>$id</td>
                        <td>$nombre $apellidos</td>
                        <td>$correo</td>
                        <td class='role'>$rol</td>
                        <td><a href='./verdetalles.php?id=$id'><button class='button-details'>Ver detalles</button></a></td>
                        <td><a href='./editar_usuario.php?id=$id'><button class='button-details'>Editar</button></a></td>
                        <td><button onclick='mostrarModal($id)' class='button-submit'>Eliminar</button></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
        <div id="modalConfirm" class="modal">
            <div class="modal-content">
                <h2>¿Estás seguro de que deseas eliminar este registro?</h2>
                <div class="modal-buttons">
                    <button class="button-cancel" onclick="ocultarModal()">Cancelar</button>
                    <button class="button-confirm" onclick="eliminaAjax()">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
