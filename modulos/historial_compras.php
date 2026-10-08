<?php 
include '../includes/header.php'; 
include '../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$query = $conexion->query("SELECT * FROM compras WHERE id_usuario = $id_usuario ORDER BY fecha DESC");
?>

<div class="container mt-5 mb-5">
    <h3 class="text-purple fw-bold mb-4"><i class="bi bi-bag-check"></i> Mi Historial de Compras</h3>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-purple text-white">
                    <tr>
                        <th class="py-3 px-4">Folio</th>
                        <th class="py-3">Fecha</th>
                        <th class="py-3">Total</th>
                        <th class="py-3">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($query->num_rows > 0): ?>
                        <?php while($compra = $query->fetch_assoc()): ?>
                            <tr>
                                <td class="px-4 fw-bold">#<?= str_pad($compra['id_compra'], 5, '0', STR_PAD_LEFT) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($compra['fecha'])) ?></td>
                                <td class="fw-bold text-success">$<?= number_format($compra['total'], 2) ?></td>
                                <td><span class="badge bg-success"><?= $compra['estado'] ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center py-4">No tienes compras registradas.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>