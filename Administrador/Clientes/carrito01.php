<?php
session_start();
require "../funciones/conecta.php"; 

$con = conecta();

if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}

$cliente_id = $_SESSION['idUser'] ?? null; 

if (!$cliente_id) {
    die("Error: cliente_id no está definido en la sesión.");
}

$sql = "SELECT p.id, p.nombre, p.costo, p.archivo, c.cantidad 
        FROM productos p
        JOIN compras c ON p.id = c.producto_id
        WHERE c.cliente_id = ?";
$stmt = $con->prepare($sql);

if (!$stmt) {
    die("Error en la preparación de la consulta: " . $con->error);
}

$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f0f8ff;
            color: #333;
        }
        .button-submit {
            background-color: #4a90e2;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
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
        h2 {
            text-align: center;
        }
        .card {
            width: 100%;
            background-color: #fff;
            margin: 15px 0;
            display: flex;
            align-items: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin: 10px;
        }
        .card-body {
            flex-grow: 1;
            padding: 15px;
        }
        .card h3 {
            font-size: 20px;
            margin: 5px 0;
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
        .card .cantidad-input {
            width: 60px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
            margin-right: 5px;
        }
        .card .button-submit {
            background-color: #4a90e2;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }
        .card .button-submit:hover {
            background-color: #357ab7;
        }
        .total {
            font-size: 22px;
            font-weight: bold;
            margin-top: 20px;
        }
        .iva {
            font-size: 16px;
            color: #666;
        }
                /* Estilo del modal */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            width: 80%;
        }
        .modal h4 {
            margin-bottom: 15px;
            font-size: 18px;
        }
        .modal .button {
            padding: 10px 20px;
            border-radius: 5px;
            margin: 10px;
            cursor: pointer;
        }
        .modal .button.confirm {
            background-color: #4a90e2;
            color: white;
            border: none;
        }
        .modal .button.cancel {
            background-color: #ccc;
            border: none;
        }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

<div class="container">
    <h2>Carrito de Compras</h2>
    <div id="carrito-items">
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php 
                $subtotal = $row['costo'] * $row['cantidad']; 
                $archivo = $row['archivo'] ? "../Productos/archivos/" . $row['archivo'] : "../Productos/archivos/default.jpg"; 
            ?>
            <div class="card" data-producto-id="<?= $row['id'] ?>">
                <img src="<?= $archivo ?>" alt="<?= htmlspecialchars($row['nombre']) ?>">
                <div class="card-body">
                    <h3><?= htmlspecialchars($row['nombre']) ?></h3>
                    <p>Precio: $<?= number_format($row['costo'], 2) ?></p>
                    <p>
                        Cantidad: 
                        <input type="number" value="<?= $row['cantidad'] ?>" min="1"
                               onchange="actualizarCantidad(<?= $row['id'] ?>, this.value)">
                    </p>
                    <p>Subtotal: $<span class="subtotal"><?= number_format($subtotal, 2) ?></span></p>
                    <button class="button-submit" onclick="confirmarEliminar(<?= $row['id'] ?>)">Eliminar</button>
                </div>
            </div>
            <?php $total += $subtotal; ?>
        <?php endwhile; ?>
    </div>
    
    <p class="total">Total: $<span id="total"><?= number_format($total, 2) ?></span></p>
    <p class="iva">IVA (16%): $<span id="iva"><?= number_format($total * 0.16, 2) ?></span></p>
    <p class="total">Total con IVA: $<span id="total-con-iva"><?= number_format($total * 1.16, 2) ?></span></p>
    <a href="carrito02.php"><button type="button" class="button-submit">Proceder al pago</button></a>
   <!-- Modal -->
    <div class="modal" id="modalConfirmacion">
        <div class="modal-content">
            <h4>¿Estás seguro de que deseas eliminar este producto?</h4>
            <button class="button confirm" onclick="eliminarProducto()">Sí</button>
            <button class="button cancel" onclick="cerrarModal()">Cancelar</button>
        </div>
    </div>
</div>
    <?php include 'footer.php'; ?>



<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  let productoIdEliminar;

        function confirmarEliminar(productoId) {
            productoIdEliminar = productoId;
            document.getElementById('modalConfirmacion').style.display = 'flex';
        }

        function cerrarModal() {
            document.getElementById('modalConfirmacion').style.display = 'none';
        }

        function eliminarProducto() {
            $.ajax({
                url: 'eliminar_producto.php',
                type: 'POST',
                data: { producto_id: productoIdEliminar },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $("div[data-producto-id='" + productoIdEliminar + "']").remove();
                        actualizarTotal();
                        Toastify({
                            text: "Producto eliminado",
                            backgroundColor: "green",
                            duration: 3000
                        }).showToast();
                        cerrarModal();
                    } else {
                        Toastify({
                            text: "Error al eliminar",
                            backgroundColor: "red",
                            duration: 3000
                        }).showToast();
                    }
                }
            });
        }


function actualizarCantidad(productoId, nuevaCantidad) {
    $.ajax({
        url: 'actualizar_cantidad.php',
        type: 'POST',
        data: { producto_id: productoId, cantidad: nuevaCantidad },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $("div[data-producto-id='" + productoId + "'] .subtotal").text(response.subtotal);

                actualizarTotal();
            } else {
                Toastify({text: "Error al actualizar", backgroundColor: "red"}).showToast();
            }
        }
    });
}


function actualizarTotal() {
    let total = 0;

    $(".subtotal").each(function() {
        var subtotal = parseFloat($(this).text());  
        total += subtotal;
    });

    // Calcular el IVA y total con IVA
    const iva = total * 0.16;
    const totalConIva = total + iva;

    $("#total").text(total.toFixed(2));          // Total sin IVA
    $("#iva").text(iva.toFixed(2));              // IVA calculado
    $("#total-con-iva").text(totalConIva.toFixed(2)); // Total con IVA
}

</script>
