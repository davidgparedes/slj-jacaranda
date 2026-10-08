<?php include '../includes/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-header bg-purple text-white text-center py-4">
                    <h4 class="mb-0"><i class="bi bi-key"></i> Recuperar Contraseña</h4>
                </div>
                <div class="card-body p-4 text-center">
                    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'no_existe'): ?>
                        <div class="alert alert-danger">No encontramos ninguna cuenta con ese correo.</div>
                    <?php endif; ?>
                    
                    <p class="text-muted">Ingresa el correo electrónico con el que te registraste y generaremos una contraseña temporal para ti.</p>
                    
                    <form action="../acciones/procesar_recuperacion.php" method="POST">
                        <div class="mb-3">
                            <input type="email" name="correo" class="form-control form-control-lg bg-light" placeholder="tu@correo.com" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Restablecer Contraseña</button>
                    </form>
                    <div class="mt-3">
                        <a href="login.php" class="text-purple fw-bold text-decoration-none">Volver al inicio de sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>