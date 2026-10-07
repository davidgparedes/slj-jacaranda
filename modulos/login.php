<?php
include '../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-header bg-purple text-white text-center py-3">
                    <h4 class="mb-0"><i class="bi bi-person-circle"></i> Iniciar Sesión</h4>
                </div>
                <div class="card-body p-4">
                    <?php if(isset($_GET['error'])): ?>
                        <div class="alert alert-danger text-center"><i class="bi bi-exclamation-triangle"></i> Correo o contraseña incorrectos.</div>
                    <?php endif; ?>
                    
                    <form action="../acciones/procesar_login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control" placeholder="ejemplo@correo.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 bg-purple border-0"><i class="bi bi-box-arrow-in-right"></i> Entrar al Sistema</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>