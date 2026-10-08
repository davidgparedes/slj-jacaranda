<?php 
include '../includes/header.php'; 
include '../config/conexion.php';

$notificaciones = $conexion->query("SELECT * FROM notificaciones ORDER BY fecha DESC");
?>

<div class="container mt-5 mb-5">
    <div class="d-flex align-items-center mb-4">
        <h3 class="text-purple fw-bold mb-0"><i class="bi bi-bell-fill text-warning"></i> Centro de Notificaciones</h3>
    </div>

    <div class="row">
        <?php while($noti = $notificaciones->fetch_assoc()): ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100 border-start border-4 
                    <?php 
                        if($noti['tipo'] == 'Evento') echo 'border-info';
                        elseif($noti['tipo'] == 'Descuento') echo 'border-success';
                        else echo 'border-purple'; 
                    ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge 
                                <?php 
                                    if($noti['tipo'] == 'Evento') echo 'bg-info text-dark';
                                    elseif($noti['tipo'] == 'Descuento') echo 'bg-success';
                                    else echo 'bg-purple text-white'; 
                                ?>">
                                <?= $noti['tipo'] ?>
                            </span>
                            <small class="text-muted"><i class="bi bi-clock"></i> <?= date('d/m/Y', strtotime($noti['fecha'])) ?></small>
                        </div>
                        <h5 class="card-title fw-bold text-dark"><?= $noti['titulo'] ?></h5>
                        <p class="card-text text-secondary"><?= $noti['mensaje'] ?></p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>