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
        <h2 class="text-purple fw-bold"><i class="bi bi-lightbulb-fill text-warning"></i> Para ti</h2>
        <p class="text-muted">Recomendaciones basadas en tus libros favoritos.</p>
    </div>

    <?php if ($genero_favorito != "" && $sugerencias->num_rows > 0): ?>
        <h4 class="mb-4">Por tu interés en: <span class="badge bg-primary"><?= $genero_favorito ?></span></h4>
        <div class="row">
            <?php while($libro = $sugerencias->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm h-100 text-center">
                        <div class="card-body">
                            <h5 class="fw-bold"><?= $libro['titulo'] ?></h5>
                            <h6 class="text-muted"><?= $libro['autor'] ?></h6>
                            <a href="detalle_libro.php?id=<?= $libro['id_libro'] ?>" class="btn btn-primary w-100 mt-2">Ver Libro</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center py-4">Agrega libros a tus favoritos para darte recomendaciones precisas.</div>
    <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>