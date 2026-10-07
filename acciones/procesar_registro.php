<?php
session_start();
include '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $correo = $conexion->real_escape_string($_POST['correo']);
    $password = $_POST['password']; 

    // Verificar si el correo ya existe
    $check = $conexion->query("SELECT id_usuario FROM usuarios WHERE correo = '$correo'");
    if ($check->num_rows > 0) {
        header("Location: ../modulos/registro.php?error=correo");
        exit();
    }

    // Insertar el nuevo usuario con rol de cliente
    $sql = "INSERT INTO usuarios (nombre, correo, password, rol) VALUES ('$nombre', '$correo', '$password', 'cliente')";
    
    if ($conexion->query($sql) === TRUE) {
        // Auto-iniciar sesión después de registrarse
        $_SESSION['id_usuario'] = $conexion->insert_id;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['rol'] = 'cliente';
        
        header("Location: ../modulos/catalogo.php?msg=bienvenido");
    } else {
        echo "Error: " . $conexion->error;
    }
}
?>