<?php
include '../config/conexion.php';
include '../includes/header.php';

// Obtener todos los libros para la tabla
$sql = "SELECT * FROM libros ORDER BY id_libro DESC";
$resultado = $conexion->query($sql);
?>

<div class="container mt-4">
    <h2 class="text-purple mb-4"><i class="bi bi-speedometer2"></i> Panel de Administración - Inventario</h2>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'ok'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> Libro guardado correctamente en el catálogo.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Formulario para agregar libros -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-purple text-white fw-bold">
                    Registrar Nuevo Libro
                </div>
                <div class="card-body">
                    <form action="../acciones/guardar_libro.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" name="isbn" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" name="titulo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Autor</label>
                            <input type="text" name="autor" class="form-control" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Editorial</label>
                                <input type="text" name="editorial" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Género</label>
                                <input type="text" name="genero" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label">Precio ($)</label>
                                <input type="number" step="0.01" name="precio" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Existencias</label>
                                <input type="number" name="existencias" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Guardar Libro</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de Inventario -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white fw-bold">
                    Inventario Actual
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ISBN</th>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Precio</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado->num_rows > 0): ?>
                                <?php while($libro = $resultado->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($libro['isbn']); ?></td>
                                        <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
                                        <td><?php echo htmlspecialchars($libro['autor']); ?></td>
                                        <td>$<?php echo number_format($libro['precio'], 2); ?></td>
                                        <td>
                                            <span class="badge <?php echo ($libro['existencias'] <= 5) ? 'bg-danger' : 'bg-success'; ?>">
                                                <?php echo $libro['existencias']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-3">No hay libros registrados.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>