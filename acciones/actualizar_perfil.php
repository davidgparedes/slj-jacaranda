<?php
session_start();
include '../config/conexion.php';

// Verificar que venga de un formulario POST y haya sesión activa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $password = $conexion->real_escape_string($_POST['password']); 

    // Actualizar nombre y contraseña en la base de datos
    $sql = "UPDATE usuarios SET nombre = '$nombre', password = '$password' WHERE id_usuario = $id_usuario";
    
    if ($conexion->query($sql) === TRUE) {
        // Actualizamos la variable de sesión para que el cambio de nombre se refleje arriba a la derecha al instante
        $_SESSION['nombre'] = $nombre;
        
        // Redirigimos de vuelta al perfil con un mensaje de éxito
        header("Location: ../modulos/perfil.php?msg=actualizado");
        exit();
    } else {
        echo "Error actualizando el perfil: " . $conexion->error;
    }
} else {
    header("Location: ../modulos/perfil.php");
    exit();
}
?>