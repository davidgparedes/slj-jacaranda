<?php 
include '../includes/header.php'; 
include '../config/conexion.php';

$id = intval($_GET['id']);

// Si el formulario fue enviado, actualizamos los datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $conexion->real_escape_string($_POST['titulo']);
    $precio = $_POST['precio'];
    $sinopsis = $conexion->real_escape_string($_POST['sinopsis']);
    
    $conexion->query("UPDATE libros SET titulo='$titulo', precio='$precio', sinopsis='$sinopsis' WHERE id_libro=$id");
    echo "<div class='alert alert-success text-center mt-3'>Libro actualizado correctamente. <a href='admin_libros.php'>Volver al inventario</a></div>";
}

$query = $conexion->query("SELECT * FROM libros WHERE id_libro = $id");
$libro = $query->fetch_assoc();
?>

<div class="container mt-5 mb-5">
    <div class="card shadow p-4 col-md-8 mx-auto">
        <h4 class="text-purple fw-bold">Editar Título Bibliográfico</h4>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Título</label>
                <input type="text" name="titulo" class="form-control" value="<?= $libro['titulo'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Precio ($)</label>
                <input type="number" step="0.01" name="precio" class="form-control" value="<?= $libro['precio'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Sinopsis</label>
                <textarea name="sinopsis" class="form-control" rows="4"><?= $libro['sinopsis'] ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Guardar Cambios</button>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>