<?php
include '../config/conexion.php';
include '../includes/header.php';

// Lógica para el buscador (Req 8)
$busqueda = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$sql = "SELECT * FROM libros WHERE titulo LIKE '%$busqueda%' OR autor LIKE '%$busqueda%' OR genero LIKE '%$busqueda%'";
$resultado = $conexion->query($sql);
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-purple"><i class="bi bi-journal-bookmark"></i> Catálogo de Libros</h2>
    </div>

    <!-- Buscador de libros (Req 8) -->
    <form class="d-flex mb-4 shadow-sm" method="GET" action="catalogo.php">
        <input class="form-control me-2" type="search" name="buscar" placeholder="Buscar por título, autor o género literario..." value="<?php echo htmlspecialchars($busqueda); ?>">
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Buscar</button>
        <?php if($busqueda != ''): ?>
            <a href="catalogo.php" class="btn btn-outline-secondary ms-2">Limpiar</a>
        <?php endif; ?>
    </form>

    <!-- Cuadrícula de libros -->
    <div class="row">
        <?php if ($resultado->num_rows > 0): ?>
            <?php while($libro = $resultado->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm border-0 bg-white">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-dark fw-bold"><?php echo htmlspecialchars($libro['titulo']); ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($libro['autor']); ?></h6>
                            <p class="card-text small mb-1"><span class="badge bg-secondary"><?php echo htmlspecialchars($libro['genero']); ?></span></p>
                            <p class="card-text text-success fw-bold fs-4 mt-2">$<?php echo number_format($libro['precio'], 2); ?></p>
                            
                            <!-- Alertas de Inventario en tiempo real (Req 17 y 18) -->
                            <?php if ($libro['existencias'] == 0): ?>
                                <div class="alert alert-danger py-1 px-2 text-center small mb-3 fw-bold"><i class="bi bi-x-circle"></i> Agotado</div>
                            <?php elseif ($libro['existencias'] <= 5): ?>
                                <div class="alert alert-warning py-1 px-2 text-center small mb-3 fw-bold"><i class="bi bi-exclamation-triangle"></i> ¡Últimas <?php echo $libro['existencias']; ?> piezas!</div>
                            <?php else: ?>
                                <div class="alert alert-success py-1 px-2 text-center small mb-3"><i class="bi bi-check-circle"></i> Disponible: <?php echo $libro['existencias']; ?></div>
                            <?php endif; ?>

                            <div class="mt-auto">
                                <a href="../acciones/procesar_compra.php?accion=agregar&id=<?php echo $libro['id_libro']; ?>" class="btn btn-sm btn-primary w-100 <?php echo ($libro['existencias'] == 0) ? 'disabled' : ''; ?>">
                                <i class="bi bi-cart-plus"></i> Agregar al carrito
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-emoji-frown display-1 text-muted"></i>
                <p class="mt-3 text-muted fs-5">No se encontraron libros que coincidan con tu búsqueda.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>