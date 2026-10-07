<?php
session_start();
include '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $conexion->real_escape_string($_POST['correo']);
    $password = $_POST['password'];

    // Buscar al usuario en la base de datos
    $sql = "SELECT id_usuario, nombre, rol, password FROM usuarios WHERE correo = '$correo'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        // Verificamos la contraseña (en tu DB de prueba la guardamos como texto plano: "admin123")
        if ($password === $usuario['password']) {
            // Guardamos los datos en la sesión
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            
            // Redirigir según el rol
            if ($usuario['rol'] == 'admin') {
                header("Location: ../modulos/panel_admin.php");
            } else {
                header("Location: ../modulos/catalogo.php");
            }
            exit();
        } else {
            header("Location: ../modulos/login.php?error=1");
            exit();
        }
    } else {
        header("Location: ../modulos/login.php?error=1");
        exit();
    }
} else {
    header("Location: ../modulos/login.php");
    exit();
}
?>