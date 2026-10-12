<?php
include '../includes/header.php';
include '../config/conexion.php';

$id_libro = intval($_GET['id']);
$query = $conexion->query("SELECT * FROM libros WHERE id_libro = $id_libro");
$libro = $query->fetch_assoc();

// Calcular promedio de calificaciones
$promedio_q = $conexion->query("SELECT AVG(calificacion) as prom FROM comentarios WHERE id_libro = $id_libro");
$prom_data = $promedio_q->fetch_assoc();
$promedio = round($prom_data['prom'] ?? 0, 1);
?>

<div class="container mt-5 mb-5">
    <!-- Información del Libro -->
    <div class="row bg-white p-4 shadow-sm rounded">
        <div class="col-md-4 text-center">
            <!-- CORRECCIÓN 1: Leer la URL de la imagen de internet -->
            <?php 
                $portada = !empty($libro['imagen_portada']) ? $libro['imagen_portada'] : 'https://via.placeholder.com/300x450/e0e0e0/6f42c1?text=Sin+Portada'; 
            ?>
            <img src="<?= htmlspecialchars($portada) ?>" class="img-fluid rounded shadow" alt="Portada de <?= htmlspecialchars($libro['titulo']) ?>">
        </div>
        <div class="col-md-8">
            <h2 class="text-purple fw-bold"><?= htmlspecialchars($libro['titulo']) ?></h2>
            <h5 class="text-muted"><?= htmlspecialchars($libro['autor']) ?> | <?= htmlspecialchars($libro['editorial']) ?></h5>
            
            <div class="d-flex align-items-center mt-3 mb-3">
                <span class="badge bg-info text-dark fs-6 me-3"><i class="bi bi-file-earmark-text"></i> Formato: <?= htmlspecialchars($libro['formato']) ?></span>
                <span class="text-warning fs-5 fw-bold"><i class="bi bi-star-fill"></i> <?= $promedio ?> / 5</span>
            </div>
            
            <h5 class="mt-4 fw-bold">Sinopsis</h5>
            <p class="lead fs-6 text-secondary"><?= htmlspecialchars($libro['sinopsis']) ?></p>
            
            <h3 class="text-success mt-4 fw-bold">$<?= number_format($libro['precio'], 2) ?> MXN</h3>
            
            <div class="mt-4">
                <!-- CORRECCIÓN 2: Botón de carrito convertido en enlace funcional -->
                <a href="../acciones/procesar_compra.php?accion=agregar&id=<?= $libro['id_libro'] ?>" class="btn text-white btn-lg fw-bold shadow-sm" style="background-color: #6f42c1;">
                    <i class="bi bi-cart-plus"></i> Agregar al Carrito
                </a>
                <a href="catalogo.php" class="btn btn-outline-secondary btn-lg ms-2 fw-bold">Volver al Catálogo</a>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE RESEÑAS -->
    <div class="row bg-white p-4 shadow-sm rounded mt-4">
        <div class="col-12">
            <h4 class="fw-bold mb-4" style="color: #6f42c1;">Reseñas y Comentarios</h4>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'comentario_ok'): ?>
                <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> ¡Tu reseña se ha publicado correctamente!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['id_usuario'])): ?>
                <form action="../acciones/guardar_comentario.php" method="POST" class="mb-5 bg-light p-4 rounded shadow-sm border">
                    <input type="hidden" name="id_libro" value="<?= $libro['id_libro'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Calificación:</label>
                        <select name="calificacion" class="form-select border-purple" style="width: 250px;" required>
                            <option value="5">⭐⭐⭐⭐⭐ Excelente (5/5)</option>
                            <option value="4">⭐⭐⭐⭐ Muy bueno (4/5)</option>
                            <option value="3">⭐⭐⭐ Bueno (3/5)</option>
                            <option value="2">⭐⭐ Regular (2/5)</option>
                            <option value="1">⭐ Malo (1/5)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tu comentario:</label>
                        <textarea name="comentario" class="form-control" rows="3" placeholder="¿Qué te pareció este libro? Comparte tu opinión con la comunidad..." required></textarea>
                    </div>
                    
                    <button type="submit" class="btn text-white fw-bold px-4 rounded-pill" style="background-color: #6f42c1;">
                        <i class="bi bi-send"></i> Publicar Reseña
                    </button>
                </form>
            <?php else: ?>
                <div class="alert alert-info rounded-pill">
                    <i class="bi bi-info-circle"></i> Debes <a href="login.php" class="alert-link">iniciar sesión</a> para poder dejar una calificación y reseña.
                </div>
            <?php endif; ?>

            <hr class="mb-4">

            <h5 class="fw-bold mb-3">Opiniones de los lectores</h5>
            <?php
            $id_libro_actual = $libro['id_libro'];
            $query_comentarios = $conexion->query("SELECT c.calificacion, c.comentario, c.fecha, u.nombre FROM comentarios c JOIN usuarios u ON c.id_usuario = u.id_usuario WHERE c.id_libro = $id_libro_actual ORDER BY c.fecha DESC");

            if($query_comentarios && $query_comentarios->num_rows > 0):
                while($comentario = $query_comentarios->fetch_assoc()):
            ?>
                <div class="mb-3 bg-light p-3 rounded shadow-sm border-start border-4" style="border-color: #6f42c1 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong class="text-dark"><i class="bi bi-person-circle text-secondary"></i> <?= htmlspecialchars($comentario['nombre']) ?></strong>
                        <span class="text-warning fs-5">
                            <?= str_repeat('⭐', $comentario['calificacion']) ?>
                        </span>
                    </div>
                    <p class="text-muted mb-1" style="font-style: italic;">"<?= htmlspecialchars($comentario['comentario']) ?>"</p>
                    <small class="text-secondary" style="font-size: 0.8rem;"><?= date('d/m/Y H:i', strtotime($comentario['fecha'])) ?></small>
                </div>
            <?php
                endwhile;
            else:
            ?>
                <p class="text-muted text-center py-4 bg-light rounded border border-dashed">Aún no hay reseñas para este libro. ¡Sé el primero en dar tu opinión!</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>