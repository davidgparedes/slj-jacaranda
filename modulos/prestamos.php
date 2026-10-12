<?php
include '../config/conexion.php';
include '../includes/header.php';

// Validamos que exista una sesión activa
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario_sesion = $_SESSION['id_usuario'];

// Consultamos los datos del usuario logueado con SELECT * para evitar errores de columnas no existentes
$query_usuario = $conexion->query("SELECT * FROM usuarios WHERE id_usuario = $id_usuario_sesion");
$datos_sesion = $query_usuario->fetch_assoc();

// Detectamos el rol desde la sesión o desde las posibles columnas (rol, tipo, tipo_usuario)
$rol = $_SESSION['rol'] ?? $_SESSION['tipo'] ?? $datos_sesion['rol'] ?? $datos_sesion['tipo'] ?? $datos_sesion['tipo_usuario'] ?? '';
$es_admin = (strtolower($rol) === 'admin' || strtolower($rol) === 'administrador' || $rol == 1);

$nombre_usuario = $_SESSION['nombre'] ?? $datos_sesion['nombre'] ?? 'Usuario';

// Consultas para llenar las opciones del formulario
if ($es_admin) {
    // Si es ADMIN, cargamos a TODOS los usuarios para el select
    $usuarios = $conexion->query("SELECT id_usuario, nombre FROM usuarios");
}
$libros = $conexion->query("SELECT id_libro, titulo FROM libros WHERE existencias > 0");

// Consulta para mostrar los préstamos actuales
if ($es_admin) {
    // El ADMIN ve todos los préstamos que no se han devuelto
    $sql_prestamos = "SELECT p.id_prestamo, p.id_libro, u.nombre AS usuario, l.titulo AS libro, p.fecha_limite, p.estatus 
                      FROM prestamos p 
                      JOIN usuarios u ON p.id_usuario = u.id_usuario 
                      JOIN libros l ON p.id_libro = l.id_libro 
                      WHERE p.estatus != 'devuelto' ORDER BY p.fecha_limite ASC";
} else {
    // El CLIENTE solo ve SUS préstamos que no se han devuelto
    $sql_prestamos = "SELECT p.id_prestamo, p.id_libro, u.nombre AS usuario, l.titulo AS libro, p.fecha_limite, p.estatus 
                      FROM prestamos p 
                      JOIN usuarios u ON p.id_usuario = u.id_usuario 
                      JOIN libros l ON p.id_libro = l.id_libro 
                      WHERE p.estatus != 'devuelto' AND p.id_usuario = $id_usuario_sesion ORDER BY p.fecha_limite ASC";
}

$prestamos = $conexion->query($sql_prestamos);
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-4" style="color: #6f42c1;"><i class="bi bi-clock-history"></i> Gestión de Préstamos y Multas</h2>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'prestadook'): ?>
        <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> Préstamo registrado y descontado del inventario. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'devueltook'): ?>
        <div class="alert alert-info alert-dismissible fade show"><i class="bi bi-arrow-return-left"></i> Libro devuelto al inventario exitosamente. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row">
        <!-- Formulario de Nuevo Préstamo -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header text-white fw-bold" style="background-color: #6f42c1;">Registrar Nuevo Préstamo</div>
                <div class="card-body">
                    <form action="../acciones/procesar_prestamo.php?accion=prestar" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Usuario Lector</label>
                            
                            <?php if ($es_admin): ?>
                                <!-- Vista ADMINISTRADOR: Select desplegable con todos los usuarios -->
                                <select name="id_usuario" class="form-select border-purple" required>
                                    <option value="">Seleccione un usuario...</option>
                                    <?php while($u = $usuarios->fetch_assoc()): ?>
                                        <option value="<?php echo $u['id_usuario']; ?>"><?php echo htmlspecialchars($u['nombre']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            <?php else: ?>
                                <!-- Vista CLIENTE: Campo bloqueado con tu nombre de usuario -->
                                <input type="text" class="form-control bg-light text-muted" value="<?php echo htmlspecialchars($nombre_usuario); ?>" readonly>
                                <input type="hidden" name="id_usuario" value="<?php echo $id_usuario_sesion; ?>">
                            <?php endif; ?>

                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Libro a Prestar</label>
                            <select name="id_libro" class="form-select border-purple" required>
                                <option value="">Seleccione un libro disponible...</option>
                                <?php while($l = $libros->fetch_assoc()): ?>
                                    <option value="<?php echo $l['id_libro']; ?>"><?php echo htmlspecialchars($l['titulo']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Fecha Límite de Devolución</label>
                            <input type="date" name="fecha_limite" class="form-control border-purple" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <button type="submit" class="btn text-white w-100 fw-bold" style="background-color: #6f42c1;"><i class="bi bi-journal-arrow-up"></i> Registrar Préstamo</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de Préstamos Activos -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-bold">Préstamos Activos en Curso</div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Lector</th>
                                <th>Libro</th>
                                <th>Fecha Límite</th>
                                <th>Estatus / Multa</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($prestamos && $prestamos->num_rows > 0): ?>
                                <?php while($p = $prestamos->fetch_assoc()): ?>
                                    <?php 
                                    $hoy = date('Y-m-d');
                                    $vencido = ($hoy > $p['fecha_limite']);
                                    $dias_retraso = $vencido ? (strtotime($hoy) - strtotime($p['fecha_limite'])) / (60 * 60 * 24) : 0;
                                    $multa = $dias_retraso * 10;
                                    ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo htmlspecialchars($p['usuario']); ?></td>
                                        <td class="text-muted"><?php echo htmlspecialchars($p['libro']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($p['fecha_limite'])); ?></td>
                                        <td>
                                            <?php if($vencido): ?>
                                                <span class="badge bg-danger">Vencido</span><br>
                                                <small class="text-danger fw-bold">Multa: $<?php echo number_format($multa, 2); ?></small>
                                            <?php else: ?>
                                                <span class="badge bg-success">En tiempo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="../acciones/procesar_prestamo.php?accion=devolver&id=<?php echo $p['id_prestamo']; ?>&libro=<?php echo $p['id_libro']; ?>" class="btn btn-sm btn-outline-secondary fw-bold">
                                                Devolver
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-info-circle fs-4 d-block mb-2"></i> No hay libros prestados actualmente.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>