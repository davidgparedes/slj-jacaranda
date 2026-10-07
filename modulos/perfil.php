<?php 
include '../includes/header.php'; 
include '../config/conexion.php';

// Redirigir si no ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$resultado = $conexion->query("SELECT nombre, correo, password FROM usuarios WHERE id_usuario = $id_usuario");
$datos_usuario = $resultado->fetch_assoc();
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-purple text-white text-center py-4">
                    <h4 class="mb-0"><i class="bi bi-person-vcard"></i> Mi Perfil</h4>
                    <p class="mb-0 small">Actualiza tu información personal</p>
                </div>
                <div class="card-body p-4">
                    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'actualizado'): ?>
                        <div class="alert alert-success"><i class="bi bi-check-circle"></i> Tus datos han sido actualizados correctamente.</div>
                    <?php endif; ?>
                    
                    <form action="../acciones/actualizar_perfil.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control form-control-lg bg-light" value="<?php echo $datos_usuario['nombre']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Correo Electrónico (No modificable)</label>
                            <input type="email" class="form-control form-control-lg bg-light text-muted" value="<?php echo $datos_usuario['correo']; ?>" readonly>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold">Contraseña</label>
                            <input type="password" name="password" class="form-control form-control-lg bg-light" value="<?php echo $datos_usuario['password']; ?>" required>
                            <div class="form-text">Puedes escribir una nueva contraseña si deseas cambiarla.</div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100"><i class="bi bi-save"></i> Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>