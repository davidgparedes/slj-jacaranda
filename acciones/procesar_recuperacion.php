<?php
include '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $conexion->real_escape_string($_POST['correo']);

    // Verificar si el correo existe
    $query = $conexion->query("SELECT id_usuario FROM usuarios WHERE correo = '$correo'");
    
    if ($query->num_rows > 0) {
        // Generar una contraseña temporal aleatoria (ej: jaca_A7F3)
        $nueva_password = "jaca_" . substr(md5(time()), 0, 4);
        
        // Actualizar la contraseña en la base de datos
        $conexion->query("UPDATE usuarios SET password = '$nueva_password' WHERE correo = '$correo'");
        
        // Mostrar mensaje de éxito (Simulación de envío de correo)
        include '../includes/header.php';
        echo "<div class='container mt-5 text-center'>";
        echo "<div class='alert alert-success shadow p-5'>";
        echo "<h2><i class='bi bi-check-circle-fill'></i> ¡Contraseña Restablecida!</h2>";
        echo "<p class='fs-5 mt-3'>Tu nueva contraseña temporal es: <strong class='text-danger fs-3'>$nueva_password</strong></p>";
        echo "<p class='text-muted'>Usa esta contraseña para iniciar sesión y luego ve a 'Mi Perfil' para cambiarla por una que recuerdes.</p>";
        echo "<a href='../modulos/login.php' class='btn btn-primary mt-3'>Ir a Iniciar Sesión</a>";
        echo "</div></div>";
        include '../includes/footer.php';
        
    } else {
        // Si no existe el correo, regresar con error
        header("Location: ../modulos/recuperar.php?msg=no_existe");
    }
}
?>