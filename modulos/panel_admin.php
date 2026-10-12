<?php
include '../config/conexion.php';
include '../includes/header.php';

// Lógica para el buscador en el panel de administrador
$buscar_admin = isset($_GET['buscar_admin']) ? $_GET['buscar_admin'] : '';

if ($buscar_admin != '') {
    $sql = "SELECT * FROM libros WHERE titulo LIKE '%$buscar_admin%' OR autor LIKE '%$buscar_admin%' OR isbn LIKE '%$buscar_admin%' ORDER BY id_libro DESC";
} else {
    $sql = "SELECT * FROM libros ORDER BY id_libro DESC";
}
$resultado = $conexion->query($sql);
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-4" style="color: #6f42c1;"><i class="bi bi-speedometer2"></i> Panel de Administración - Inventario</h2>

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
                <div class="card-header text-white fw-bold" style="background-color: #6f42c1;">
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
                        <div class="mb-3">
                            <label class="form-label">URL de la Portada</label>
                            <input type="url" name="imagen_portada" class="form-control" placeholder="https://ejemplo.com/imagen.jpg">
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
                        <button type="submit" class="btn text-white w-100" style="background-color: #6f42c1;">
                            <i class="bi bi-save"></i> Guardar Libro
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de Inventario -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Inventario Actual</span>
                    
                    <form action="panel_admin.php" method="GET" class="d-flex" style="width: 300px;">
                        <input type="text" name="buscar_admin" class="form-control form-control-sm me-2" placeholder="Buscar ISBN, título o autor..." value="<?php echo htmlspecialchars($buscar_admin); ?>">
                        <button type="submit" class="btn btn-sm btn-secondary" title="Buscar"><i class="bi bi-search"></i></button>
                        <?php if($buscar_admin != ''): ?>
                            <a href="panel_admin.php" class="btn btn-sm btn-outline-light ms-1" title="Limpiar búsqueda"><i class="bi bi-x-circle"></i></a>
                        <?php endif; ?>
                    </form>
                </div>
                
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ISBN</th>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Acciones</th>
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
                                        <td>
                                            <a href="editar_libro.php?id=<?= $libro['id_libro'] ?>" class="btn btn-sm btn-warning mb-1" title="Editar">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </a>
                                            <a href="../acciones/eliminar_libro.php?id=<?= $libro['id_libro'] ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('¿Estás seguro de que deseas eliminar este libro del inventario?');" title="Eliminar">
                                                <i class="bi bi-trash"></i> Eliminar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center py-4">No se encontraron libros en el inventario.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>