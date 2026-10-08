<?php 
include '../includes/header.php'; 
include '../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$query = "SELECT l.* FROM libros l JOIN favoritos f ON l.id_libro = f.id_libro WHERE f.id_usuario = $id_usuario ORDER BY f.fecha_agregado DESC";
$resultado = $conexion->query($query);
?>

<div class="container mt-4 mb-5">
    <h3 class="text-purple fw-bold mb-4"><i class="bi bi-heart-fill text-danger"></i> Mis Libros Favoritos</h3>
    
    <div class="row">
        <?php if($resultado->num_rows > 0): ?>
            <?php while($libro = $resultado->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-dark fw-bold"><?= $libro['titulo'] ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?= $libro['autor'] ?></h6>
                            <p class="card-text text-success fw-bold fs-4 mt-2">$<?= $libro['precio'] ?></p>
                            
                            <div class="mt-auto d-grid gap-2">
                                <a href="detalle_libro.php?id=<?= $libro['id_libro'] ?>" class="btn btn-outline-primary btn-sm">Ver Detalle</a>
                                <!-- Botón para quitar de favoritos desde esta vista -->
                                <a href="../acciones/toggle_favorito.php?id=<?= $libro['id_libro'] ?>&origen=favoritos" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-heartbreak"></i> Quitar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center py-4">
                    <i class="bi bi-info-circle fs-4 d-block mb-2"></i>
                    Aún no tienes libros en tu lista de favoritos. <a href="catalogo.php" class="fw-bold">Explora el catálogo</a>.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>