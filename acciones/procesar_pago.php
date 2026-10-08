<?php
session_start();
include '../config/conexion.php';

// Validar que haya sesión y el carrito no esté vacío
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['carrito'])) {
    header("Location: ../modulos/carrito.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $total = floatval($_POST['total']);
    
    // Recibir los datos de envío
    $direccion = $conexion->real_escape_string($_POST['direccion']);
    $codigo_postal = $conexion->real_escape_string($_POST['codigo_postal']);

    // 1. Registrar la transacción global en 'compras'
    $conexion->query("INSERT INTO compras (id_usuario, total, estado) VALUES ($id_usuario, $total, 'Completado')");
    $id_compra = $conexion->insert_id; // Obtenemos el folio generado

    // 2. Registrar los datos de entrega en 'envios'
    $conexion->query("INSERT INTO envios (id_compra, direccion, codigo_postal) VALUES ($id_compra, '$direccion', '$codigo_postal')");

    // 3. LA MAGIA: Descontar existencias libro por libro
    foreach ($_SESSION['carrito'] as $id_libro => $item) {
        $cantidad = intval($item['cantidad']);
        
        // Restar del inventario
        $conexion->query("UPDATE libros SET existencias = existencias - $cantidad WHERE id_libro = $id_libro");
        
        // Registrar en el historial de movimientos (Req 15)
        $motivo = "Venta en línea - Folio #" . $id_compra;
        $conexion->query("INSERT INTO movimientos_inventario (id_libro, tipo, cantidad, motivo) VALUES ($id_libro, 'Salida', $cantidad, '$motivo')");
    }

    // 4. Vaciar el carrito virtual
    unset($_SESSION['carrito']);

    // 5. Redirigir al historial para ver el éxito
    header("Location: ../modulos/historial_compras.php?msg=compra_exitosa");
    exit();
} else {
    header("Location: ../modulos/carrito.php");
    exit();
}
?>