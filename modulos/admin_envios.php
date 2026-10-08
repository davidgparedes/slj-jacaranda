<?php 
include '../includes/header.php'; 
include '../config/conexion.php';

// Actualizar el estado del envío si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_envio'])) {
    $id_envio = intval($_POST['id_envio']);
    $estado = $conexion->real_escape_string($_POST['estado_entrega']);
    $guia = $conexion->real_escape_string($_POST['guia_reparto']);
    
    $conexion->query("UPDATE envios SET estado_entrega = '$estado', guia_reparto = '$guia' WHERE id_envio = $id_envio");
    echo "<div class='alert alert-success text-center m-3'>Estado del envío actualizado correctamente.</div>";
}

$envios = $conexion->query("SELECT e.*, c.fecha, u.nombre FROM envios e JOIN compras c ON e.id_compra = c.id_compra JOIN usuarios u ON c.id_usuario = u.id_usuario ORDER BY c.fecha DESC");
?>

<div class="container mt-5 mb-5">
    <h3 class="text-purple fw-bold mb-4"><i class="bi bi-truck"></i> Gestión de Envíos y Logística</h3>
    
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-purple text-white">
                        <tr>
                            <th class="py-3 px-3">Folio Compra</th>
                            <th class="py-3">Cliente</th>
                            <th class="py-3">Dirección</th>
                            <th class="py-3">Guía (Tracking)</th>
                            <th class="py-3 px-3 text-center">Estado y Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($envio = $envios->fetch_assoc()): ?>
                            <tr>
                                <td class="align-middle px-3 fw-bold">#<?= str_pad($envio['id_compra'], 5, '0', STR_PAD_LEFT) ?></td>
                                <td class="align-middle"><?= $envio['nombre'] ?></td>
                                <td class="align-middle small"><?= $envio['direccion'] ?> <br> CP: <?= $envio['codigo_postal'] ?></td>
                                <td class="align-middle">
                                    <form method="POST" class="d-flex">
                                        <input type="hidden" name="id_envio" value="<?= $envio['id_envio'] ?>">
                                        <input type="text" name="guia_reparto" class="form-control form-control-sm me-2" value="<?= $envio['guia_reparto'] ?>" placeholder="Nº Guía">
                                </td>
                                <td class="align-middle px-3">
                                        <select name="estado_entrega" class="form-select form-select-sm mb-2">
                                            <option value="Preparando" <?= $envio['estado_entrega'] == 'Preparando' ? 'selected' : '' ?>>Preparando</option>
                                            <option value="En tránsito" <?= $envio['estado_entrega'] == 'En tránsito' ? 'selected' : '' ?>>En tránsito</option>
                                            <option value="Entregado" <?= $envio['estado_entrega'] == 'Entregado' ? 'selected' : '' ?>>Entregado</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-success w-100">Actualizar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>