<?php 
include '../includes/header.php'; 
include '../config/conexion.php';

// Validar que solo el administrador pueda entrar (opcional, si ya manejas roles)
// if($_SESSION['rol'] != 'admin') { header("Location: catalogo.php"); exit(); }

$query = "SELECT id_usuario, nombre, correo, rol FROM usuarios ORDER BY id_usuario DESC";
$resultado = $conexion->query($query);
?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-purple fw-bold"><i class="bi bi-people-fill"></i> Usuarios Registrados</h3>
        <span class="badge bg-primary fs-6">Total: <?= $resultado->num_rows ?></span>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="bg-purple text-white">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="py-3">Nombre Completo</th>
                            <th class="py-3">Correo Electrónico</th>
                            <th class="py-3">Rol</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($resultado->num_rows > 0): ?>
                            <?php while($usuario = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td class="px-4 align-middle fw-bold"><?= $usuario['id_usuario'] ?></td>
                                    <td class="align-middle"><?= $usuario['nombre'] ?></td>
                                    <td class="align-middle text-muted"><?= $usuario['correo'] ?></td>
                                    <td class="align-middle">
                                        <?php if($usuario['rol'] == 'admin'): ?>
                                            <span class="badge bg-danger">Administrador</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Cliente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 align-middle text-center">
                                        <button class="btn btn-sm btn-outline-primary" title="Ver Historial">
                                            <i class="bi bi-journal-text"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Suspender Cuenta">
                                            <i class="bi bi-person-x"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No hay usuarios registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>