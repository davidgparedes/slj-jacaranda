<?php 
include '../includes/header.php'; 
include '../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$query_genero = $conexion->query("SELECT l.genero FROM libros l JOIN favoritos f ON l.id_libro = f.id_libro WHERE f.id_usuario = $id_usuario GROUP BY l.genero ORDER BY COUNT(*) DESC LIMIT 1");

$genero_favorito = "";
if ($query_genero->num_rows > 0) {
    $row = $query_genero->fetch_assoc();
    $genero_favorito = $row['genero'];
    $sugerencias = $conexion->query("SELECT * FROM libros WHERE genero = '$genero_favorito' AND id_libro NOT IN (SELECT id_libro FROM favoritos WHERE id_usuario = $id_usuario) LIMIT 4");
}
?>

<div class="container mt-5 mb-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: #6f42c1;"><i class="bi bi-lightbulb-fill text-warning"></i> Para ti</h2>
        <p class="text-muted">Recomendaciones basadas en tus libros favoritos.</p>
    </div>

    <?php if ($genero_favorito != "" && $sugerencias->num_rows > 0): ?>
        <h4 class="mb-4">Por tu interés en: <span class="badge" style="background-color: #0d6efd;"><?= htmlspecialchars($genero_favorito) ?></span></h4>
        <div class="row">
            <?php while($libro = $sugerencias->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm h-100 border-0" style="border-radius: 18px; overflow: hidden; background-color: #fcfcfc;">
                        
                        <!-- Imagen de Portada agregada -->
                        <?php 
                            $portada = !empty($libro['imagen_portada']) ? $libro['imagen_portada'] : 'https://via.placeholder.com/300x450/e0e0e0/6f42c1?text=Sin+Portada'; 
                        ?>
                        <img src="<?= htmlspecialchars($portada) ?>" class="card-img-top" alt="Portada de <?= htmlspecialchars($libro['titulo']) ?>" style="height: 350px; object-fit: cover; border-bottom: 1px solid #f0f0f0;">

                        <div class="card-body d-flex flex-column text-center p-3">
                            <h5 class="card-title fw-bold text-dark mb-1" style="font-family: 'Playfair Display', serif; font-size: 1.1rem;">
                                <?= htmlspecialchars($libro['titulo']) ?>
                            </h5>
                            <h6 class="card-subtitle text-muted small mb-3">
                                <?= htmlspecialchars($libro['autor']) ?>
                            </h6>
                            
                            <div class="mt-auto d-grid gap-2">
                                <!-- Botón original de Ver Libro (estilizado) -->
                                <a href="detalle_libro.php?id=<?= $libro['id_libro'] ?>" class="btn btn-outline-primary btn-sm rounded-pill fw-bold" style="border-color: #6f42c1; color: #6f42c1;">
                                    <i class="bi bi-eye"></i> Ver Libro
                                </a>
                                
                                <!-- NUEVO: Botón funcional de Agregar al Carrito -->
                                <a href="../acciones/procesar_compra.php?accion=agregar&id=<?= $libro['id_libro'] ?>" class="btn text-white btn-sm rounded-pill shadow-sm fw-bold" style="background-color: #6f42c1;">
                                    <i class="bi bi-cart-plus"></i> Al Carrito
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center py-4 rounded border-0 shadow-sm">
            <i class="bi bi-info-circle fs-4 d-block mb-2"></i>
            Agrega libros a tus favoritos para darte recomendaciones precisas.
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>