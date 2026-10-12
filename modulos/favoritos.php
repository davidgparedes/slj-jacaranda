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
    <h3 class="fw-bold mb-4" style="color: #6f42c1;"><i class="bi bi-heart-fill text-danger"></i> Mis Libros Favoritos</h3>
    
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php if($resultado->num_rows > 0): ?>
            <?php while($libro = $resultado->fetch_assoc()): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 18px; overflow: hidden; background-color: #fcfcfc;">
                        
                        <div class="position-relative">
                            <!-- Botón flotante para quitar de favoritos (Mantiene tu lógica origen=favoritos) -->
                            <div class="position-absolute top-0 end-0 m-3">
                                <a href="../acciones/toggle_favorito.php?id=<?= $libro['id_libro'] ?>&origen=favoritos" class="btn btn-light bg-white rounded-circle shadow-sm p-2 d-flex align-items-center justify-content-center text-danger" style="width: 35px; height: 35px;" title="Quitar de Favoritos">
                                    <i class="bi bi-heart-fill"></i>
                                </a>
                            </div>

                            <!-- Imagen de Portada -->
                            <?php 
                                $portada = !empty($libro['imagen_portada']) ? $libro['imagen_portada'] : 'https://via.placeholder.com/300x450/e0e0e0/6f42c1?text=Sin+Portada'; 
                            ?>
                            <img src="<?= htmlspecialchars($portada) ?>" class="card-img-top" alt="Portada de <?= htmlspecialchars($libro['titulo']) ?>" style="height: 350px; object-fit: cover; border-bottom: 1px solid #f0f0f0;">
                        </div>

                        <div class="card-body d-flex flex-column text-center p-3">
                            <h5 class="card-title text-dark fw-bold mb-1" style="font-family: 'Playfair Display', serif; font-size: 1.1rem;">
                                <?= htmlspecialchars($libro['titulo']) ?>
                            </h5>
                            <h6 class="card-subtitle mb-2 text-muted small">
                                <?= htmlspecialchars($libro['autor']) ?>
                            </h6>
                            
                            <p class="card-text text-dark fw-bold fs-4 mt-2 mb-3">$<?= number_format($libro['precio'], 2) ?></p>
                            
                            <div class="mt-auto d-grid gap-2">
                                <!-- Botón de Detalle -->
                                <a href="detalle_libro.php?id=<?= $libro['id_libro'] ?>" class="btn btn-outline-primary btn-sm rounded-pill fw-bold" style="border-color: #6f42c1; color: #6f42c1;">
                                    <i class="bi bi-eye"></i> Ver Detalle
                                </a>
                                <!-- Botón de Carrito -->
                                <a href="../acciones/procesar_compra.php?accion=agregar&id=<?= $libro['id_libro'] ?>" class="btn text-white btn-sm rounded-pill shadow-sm fw-bold" style="background-color: #6f42c1;">
                                    <i class="bi bi-cart-plus"></i> Al Carrito
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center py-4 rounded border-0 shadow-sm">
                    <i class="bi bi-info-circle fs-4 d-block mb-2"></i>
                    Aún no tienes libros en tu lista de favoritos. <a href="catalogo.php" class="fw-bold" style="color: #6f42c1;">Explora el catálogo</a>.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>