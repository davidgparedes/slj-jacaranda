<?php
include '../config/conexion.php';

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

// 1. REGISTRAR UN NUEVO PRÉSTAMO
if ($accion == 'prestar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = (int)$_POST['id_usuario'];
    $id_libro = (int)$_POST['id_libro'];
    $fecha_prestamo = date('Y-m-d');
    $fecha_limite = $_POST['fecha_limite'];

    // Insertar el préstamo en la base de datos
    $sql = "INSERT INTO prestamos (id_usuario, id_libro, fecha_prestamo, fecha_limite, estatus) 
            VALUES ($id_usuario, $id_libro, '$fecha_prestamo', '$fecha_limite', 'activo')";
            
    if ($conexion->query($sql) === TRUE) {
        // Descontar 1 unidad del inventario del libro
        $conexion->query("UPDATE libros SET existencias = existencias - 1 WHERE id_libro = $id_libro");
        header("Location: ../modulos/prestamos.php?msg=prestadook");
        exit();
    } else {
        echo "Error: " . $conexion->error;
    }
}

// 2. REGISTRAR LA DEVOLUCIÓN DE UN LIBRO
if ($accion == 'devolver' && isset($_GET['id']) && isset($_GET['libro'])) {
    $id_prestamo = (int)$_GET['id'];
    $id_libro = (int)$_GET['libro'];

    // Marcar el estatus como 'devuelto'
    $sql = "UPDATE prestamos SET estatus = 'devuelto' WHERE id_prestamo = $id_prestamo";
    
    if ($conexion->query($sql) === TRUE) {
        // Regresar 1 unidad al inventario del libro
        $conexion->query("UPDATE libros SET existencias = existencias + 1 WHERE id_libro = $id_libro");
        header("Location: ../modulos/prestamos.php?msg=devueltook");
        exit();
    } else {
        echo "Error: " . $conexion->error;
    }
}

// Si entran sin acción válida
header("Location: ../modulos/prestamos.php");
exit();
?>