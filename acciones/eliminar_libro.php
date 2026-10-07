<?php
session_start();
include '../config/conexion.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Al eliminar, la base de datos borrará automáticamente sus comentarios gracias al "ON DELETE CASCADE"
    $conexion->query("DELETE FROM libros WHERE id_libro = $id");
}
header("Location: ../modulos/admin_libros.php");
?>