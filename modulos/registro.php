<?php include '../includes/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-purple text-white text-center py-4">
                    <h4 class="mb-0"><i class="bi bi-person-plus-fill"></i> Crear Cuenta Nueva</h4>
                    <p class="mb-0 small">Únete a la Librería Jacaranda</p>
                </div>
                <div class="card-body p-4">
                    <?php if(isset($_GET['error']) && $_GET['error'] == 'correo'): ?>
                        <div class="alert alert-danger"><i class="bi bi-exclamation-octagon"></i> Ese correo ya está registrado. Intenta con otro.</div>
                    <?php endif; ?>
                    
                    <form action="../acciones/procesar_registro.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control form-control-lg bg-light" placeholder="Ej. Juan Pérez" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold">Correo Electrónico</label>
                            <input type="email" name="correo" class="form-control form-control-lg bg-light" placeholder="tu@correo.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold">Contraseña</label>
                            <input type="password" name="password" class="form-control form-control-lg bg-light" placeholder="Crea una contraseña segura" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">Registrarme</button>
                        <div class="text-center">
                            <span class="text-muted">¿Ya tienes cuenta?</span> <a href="login.php" class="text-purple fw-bold text-decoration-none">Inicia Sesión aquí</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>