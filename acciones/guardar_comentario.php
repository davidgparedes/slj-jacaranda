<?php
session_start();
include '../config/conexion.php';

// Verificamos que el usuario esté logueado y que los datos vengan por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['id_usuario'])) {
    
    $id_libro = (int)$_POST['id_libro'];
    $id_usuario = (int)$_SESSION['id_usuario'];
    $calificacion = (int)$_POST['calificacion'];
    // Evitamos inyección de SQL en el texto del comentario
    $comentario = $conexion->real_escape_string($_POST['comentario']);

    // Insertar en la base de datos (Asumiendo que tu tabla tiene estas columnas básicas)
    $sql = "INSERT INTO comentarios (id_libro, id_usuario, calificacion, comentario, fecha) 
            VALUES ('$id_libro', '$id_usuario', '$calificacion', '$comentario', NOW())";

    if ($conexion->query($sql)) {
        // Si todo sale bien, regresamos a la página del libro con un mensaje de éxito
        header("Location: ../modulos/detalle_libro.php?id=$id_libro&msg=comentario_ok");
    } else {
        echo "Error al guardar el comentario: " . $conexion->error;
    }
} else {
    // Si intenta entrar sin sesión, lo mandamos al login
    header("Location: ../modulos/login.php");
}
?>