<?php 
include '../includes/header.php'; 
include '../config/conexion.php';

$libros = $conexion->query("SELECT * FROM libros");
?>
<div class="container mt-5 mb-5">
    <h3 class="text-purple fw-bold mb-4"><i class="bi bi-journal-text"></i> Gestión de Inventario</h3>
    <table class="table table-hover bg-white shadow-sm rounded">
        <thead class="bg-purple text-white">
            <tr><th>ID</th><th>Título</th><th>Autor</th><th>Precio</th><th>Stock</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            <?php while($libro = $libros->fetch_assoc()): ?>
            <tr>
                <td><?= $libro['id_libro'] ?></td>
                <td><?= $libro['titulo'] ?></td>
                <td><?= $libro['autor'] ?></td>
                <td>$<?= $libro['precio'] ?></td>
                <td><?= $libro['existencias'] ?></td>
                <td>
                    <a href="editar_libro.php?id=<?= $libro['id_libro'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                    <a href="../acciones/eliminar_libro.php?id=<?= $libro['id_libro'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este libro del catálogo?');"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>