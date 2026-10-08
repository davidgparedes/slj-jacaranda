<?php
session_start();
include '../config/conexion.php';

// Redirigir al login si no hay sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../modulos/login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_libro = intval($_GET['id']);
$origen = isset($_GET['origen']) ? $_GET['origen'] : 'catalogo';

// Verificar si el libro ya está en los favoritos del usuario
$check = $conexion->query("SELECT id_favorito FROM favoritos WHERE id_usuario = $id_usuario AND id_libro = $id_libro");

if ($check->num_rows > 0) {
    // Si ya es favorito, lo eliminamos
    $conexion->query("DELETE FROM favoritos WHERE id_usuario = $id_usuario AND id_libro = $id_libro");
} else {
    // Si no es favorito, lo agregamos
    $conexion->query("INSERT INTO favoritos (id_usuario, id_libro) VALUES ($id_usuario, $id_libro)");
}

// Redirigir a la página desde donde se hizo el clic
if ($origen == 'detalle') {
    header("Location: ../modulos/detalle_libro.php?id=$id_libro");
} elseif ($origen == 'favoritos') {
    header("Location: ../modulos/favoritos.php");
} else {
    header("Location: ../modulos/catalogo.php");
}
?>