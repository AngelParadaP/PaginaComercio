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
    <title>Carrito de Compras</title>
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
            #modal-confirmacion {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    #modal-confirmacion .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        width: 300px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    #modal-confirmacion .modal-content p {
        font-size: 18px;
        margin-bottom: 20px;
        color: #333;
    }
    #modal-confirmacion .button-submit {
        margin: 5px;
        width: 100px;
    }
    #modal-confirmacion .button-submit.red {
        background-color: #d9534f;
    }
    </style>
</head>
<body>
    <?php include 'menu.php'; ?>

<div class="container">
    <h2>Resumen de compra</h2>
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
                        <?= number_format($row['cantidad']) ?>
                        
                    </p>
                    <p>Subtotal: $<span class="subtotal"><?= number_format($subtotal, 2) ?></span></p>
                </div>
            </div>
            <?php $total += $subtotal; ?>
        <?php endwhile; ?>
    </div>
    
    <p class="total">Total: $<span id="total"><?= number_format($total, 2) ?></span></p>
    <p class="iva">IVA (16%): $<span id="iva"><?= number_format($total * 0.16, 2) ?></span></p>
    <p class="total">Total con IVA: $<span id="total-con-iva"><?= number_format($total * 1.16, 2) ?></span></p>
<div style="text-align: center; margin-top: 20px;">
    <button id="finalizar-compra" class="button-submit">Finalizar Compra</button>
</div>

<!-- Modal de Confirmación -->
<div id="modal-confirmacion">
    <div class="modal-content">
        <p>¿Deseas finalizar la compra?</p>
        <button id="confirmar-compra" class="button-submit">Sí</button>
        <button id="cancelar-compra" class="button-submit red">No</button>
    </div>
</div>


</div>
    <?php include 'footer.php'; ?>



<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

$(document).ready(function() {
    $('#finalizar-compra').on('click', function() {
        $('#modal-confirmacion').css('display', 'flex');
    });

    $('#cancelar-compra').on('click', function() {
        $('#modal-confirmacion').css('display', 'none');
    });

    $('#confirmar-compra').on('click', function() {
        $.ajax({
            url: 'procesar_pedido.php',
            type: 'POST',
            data: { cliente_id: <?= $cliente_id; ?> },
            success: function(response) {
                const res = JSON.parse(response);
                if (res.success) {
                    Toastify({text: "Compra finalizada exitosamente", backgroundColor: "green"}).showToast();
                    setTimeout(() => window.location.reload(), 2000); 
                } else {
                    Toastify({text: "Error al finalizar la compra", backgroundColor: "red"}).showToast();
                }
            }
        });
        $('#modal-confirmacion').css('display', 'none');
    });
});


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
