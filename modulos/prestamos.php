<?php
include '../config/conexion.php';
include '../includes/header.php';

// Consultas para llenar las opciones del formulario
$usuarios = $conexion->query("SELECT id_usuario, nombre FROM usuarios");
$libros = $conexion->query("SELECT id_libro, titulo FROM libros WHERE existencias > 0");

// Consulta para mostrar los préstamos actuales
$sql_prestamos = "SELECT p.id_prestamo, p.id_libro, u.nombre AS usuario, l.titulo AS libro, p.fecha_limite, p.estatus 
                  FROM prestamos p 
                  JOIN usuarios u ON p.id_usuario = u.id_usuario 
                  JOIN libros l ON p.id_libro = l.id_libro 
                  WHERE p.estatus != 'devuelto' ORDER BY p.fecha_limite ASC";
$prestamos = $conexion->query($sql_prestamos);
?>

<div class="container mt-4">
    <h2 class="text-purple mb-4"><i class="bi bi-clock-history"></i> Gestión de Préstamos y Multas</h2>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'prestadook'): ?>
        <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> Préstamo registrado y descontado del inventario. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'devueltook'): ?>
        <div class="alert alert-info alert-dismissible fade show"><i class="bi bi-arrow-return-left"></i> Libro devuelto al inventario exitosamente. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row">
        <!-- Formulario de Nuevo Préstamo (Req 28) -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-purple text-white fw-bold">Registrar Nuevo Préstamo</div>
                <div class="card-body">
                    <form action="../acciones/procesar_prestamo.php?accion=prestar" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Usuario Lector</label>
                            <select name="id_usuario" class="form-select" required>
                                <option value="">Seleccione un usuario...</option>
                                <?php while($u = $usuarios->fetch_assoc()): ?>
                                    <option value="<?php echo $u['id_usuario']; ?>"><?php echo htmlspecialchars($u['nombre']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Libro a Prestar</label>
                            <select name="id_libro" class="form-select" required>
                                <option value="">Seleccione un libro disponible...</option>
                                <?php while($l = $libros->fetch_assoc()): ?>
                                    <option value="<?php echo $l['id_libro']; ?>"><?php echo htmlspecialchars($l['titulo']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Fecha Límite de Devolución</label>
                            <input type="date" name="fecha_limite" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-journal-arrow-up"></i> Registrar Préstamo</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de Préstamos Activos y Vencidos (Req 29, 30, 32) -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white fw-bold">Préstamos Activos en Curso</div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover mb-0">
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
                            <?php if ($prestamos->num_rows > 0): ?>
                                <?php while($p = $prestamos->fetch_assoc()): ?>
                                    <?php 
                                    $hoy = date('Y-m-d');
                                    $vencido = ($hoy > $p['fecha_limite']);
                                    // Cálculo automático de multa: $10 pesos por día de retraso
                                    $dias_retraso = $vencido ? (strtotime($hoy) - strtotime($p['fecha_limite'])) / (60 * 60 * 24) : 0;
                                    $multa = $dias_retraso * 10;
                                    ?>
                                    <tr>
                                        <td class="align-middle fw-bold"><?php echo htmlspecialchars($p['usuario']); ?></td>
                                        <td class="align-middle text-muted"><?php echo htmlspecialchars($p['libro']); ?></td>
                                        <td class="align-middle"><?php echo $p['fecha_limite']; ?></td>
                                        <td class="align-middle">
                                            <?php if($vencido): ?>
                                                <span class="badge bg-danger">Vencido</span><br>
                                                <small class="text-danger fw-bold">Multa: $<?php echo number_format($multa, 2); ?></small>
                                            <?php else: ?>
                                                <span class="badge bg-success">En tiempo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle">
                                            <!-- Botón de Devolución (Req 31) -->
                                            <a href="../acciones/procesar_prestamo.php?accion=devolver&id=<?php echo $p['id_prestamo']; ?>&libro=<?php echo $p['id_libro']; ?>" class="btn btn-sm btn-outline-secondary">
                                                Devolver
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">No hay libros prestados actualmente.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>