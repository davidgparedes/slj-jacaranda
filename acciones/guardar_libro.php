<?php
include '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir los datos del formulario
    $isbn = $conexion->real_escape_string($_POST['isbn']);
    $titulo = $conexion->real_escape_string($_POST['titulo']);
    $autor = $conexion->real_escape_string($_POST['autor']);
    $editorial = $conexion->real_escape_string($_POST['editorial']);
    $genero = $conexion->real_escape_string($_POST['genero']);
    $precio = $_POST['precio'];
    $existencias = $_POST['existencias'];

    // Consulta SQL para insertar el libro
    $sql = "INSERT INTO libros (isbn, titulo, autor, editorial, genero, precio, existencias) 
            VALUES ('$isbn', '$titulo', '$autor', '$editorial', '$genero', $precio, $existencias)";
    
    if ($conexion->query($sql) === TRUE) {
        // Redirigir de vuelta al panel con mensaje de éxito
        header("Location: ../modulos/panel_admin.php?msg=ok");
        exit();
    } else {
        echo "Error al guardar el libro: " . $conexion->error;
    }
} else {
    // Si intentan entrar directamente al archivo, regresarlos
    header("Location: ../modulos/panel_admin.php");
    exit();
}
?>