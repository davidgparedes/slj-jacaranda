<?php 
include '../includes/header.php'; 
include '../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}
$id_usuario = $_SESSION['id_usuario'];

// Procesar nueva solicitud
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_compra = intval($_POST['id_compra']);
    $motivo = $conexion->real_escape_string($_POST['motivo']);
    $conexion->query("INSERT INTO garantias (id_compra, id_usuario, motivo) VALUES ($id_compra, $id_usuario, '$motivo')");
    echo "<div class='alert alert-success text-center m-3'>Solicitud de garantía enviada correctamente. Folio generado.</div>";
}

// Obtener compras del usuario para el formulario
$compras = $conexion->query("SELECT id_compra, fecha FROM compras WHERE id_usuario = $id_usuario ORDER BY fecha DESC");
// Obtener historial de garantías
$garantias = $conexion->query("SELECT * FROM garantias WHERE id_usuario = $id_usuario ORDER BY fecha DESC");
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-shield-exclamation"></i> Solicitar Garantía</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="fw-bold">Selecciona la Compra</label>
                            <select name="id_compra" class="form-select" required>
                                <option value="">Elige un folio...</option>
                                <?php while($c = $compras->fetch_assoc()): ?>
                                    <option value="<?= $c['id_compra'] ?>">Folio #<?= str_pad($c['id_compra'], 5, '0', STR_PAD_LEFT) ?> - <?= date('d/m/Y', strtotime($c['fecha'])) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Motivo del defecto</label>
                            <textarea name="motivo" class="form-control" rows="3" placeholder="Ej. Páginas en blanco, pasta desprendida..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100"><i class="bi bi-send"></i> Enviar Solicitud</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <h4 class="text-purple fw-bold mb-3">Mis Solicitudes</h4>
            <div class="table-responsive bg-white shadow-sm rounded p-3">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Folio Garantía</th>
                            <th>Compra</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Comprobante</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($g = $garantias->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold">GAR-<?= $g['id_garantia'] ?></td>
                                <td>#<?= str_pad($g['id_compra'], 5, '0', STR_PAD_LEFT) ?></td>
                                <td class="small text-muted"><?= $g['motivo'] ?></td>
                                <td>
                                    <?php if($g['estado'] == 'Pendiente'): ?>
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    <?php elseif($g['estado'] == 'Aprobada'): ?>
                                        <span class="badge bg-success">Aprobada</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Rechazada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($g['estado'] == 'Aprobada'): ?>
                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-file-pdf"></i> PDF</button>
                                    <?php else: ?>
                                        <span class="text-muted small">No disponible</span>
                                    <?php endif; ?>
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