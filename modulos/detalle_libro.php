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
    <div class="row bg-white p-4 shadow-sm rounded">
        <div class="col-md-4 text-center">
            <img src="../assets/img/<?= $libro['portada'] ?>" class="img-fluid rounded shadow" alt="Portada">
        </div>
        <div class="col-md-8">
            <h2 class="text-purple fw-bold"><?= $libro['titulo'] ?></h2>
            <h5 class="text-muted"><?= $libro['autor'] ?> | <?= $libro['editorial'] ?></h5>
            
            <div class="d-flex align-items-center mt-3 mb-3">
                <span class="badge bg-info text-dark fs-6 me-3"><i class="bi bi-file-earmark-text"></i> Formato: <?= $libro['formato'] ?></span>
                <span class="text-warning fs-5 fw-bold"><i class="bi bi-star-fill"></i> <?= $promedio ?> / 5</span>
            </div>
            
            <h5 class="mt-4 fw-bold">Sinopsis</h5>
            <p class="lead fs-6 text-secondary"><?= $libro['sinopsis'] ?></p>
            
            <h3 class="text-success mt-4 fw-bold">$<?= $libro['precio'] ?> MXN</h3>
            
            <div class="mt-4">
                <button class="btn btn-primary btn-lg"><i class="bi bi-cart-plus"></i> Agregar al Carrito</button>
                <a href="catalogo.php" class="btn btn-outline-secondary btn-lg ms-2">Volver al Catálogo</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>