<?php
include 'config/conexion.php';
include 'includes/header.php';
?>

<div class="p-5 mb-4 bg-light rounded-3 border">
  <div class="container-fluid py-5 text-center">
    <h1 class="display-4 fw-bold text-purple">Bienvenido al Sistema de Lectura Jacaranda</h1>
    <p class="col-md-8 fs-4 mx-auto text-secondary">
      Explora nuestro catálogo digital, gestiona compras en línea, consulta disponibilidad de inventario y administra préstamos de libros.
    </p>
    <a class="btn btn-primary btn-lg bg-purple border-0 px-4" href="modulos/catalogo.php" role="button">
      <i class="bi bi-search"></i> Ver Catálogo de Libros
    </a>
  </div>
</div>

<div class="row text-center mt-4">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <i class="bi bi-journal-check display-4 text-primary mb-2"></i>
            <h5>Catálogo e Inventario</h5>
            <p class="text-muted">Consulta de existencias en tiempo real y filtrado por autor, género o título.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <i class="bi bi-cart-check display-4 text-success mb-2"></i>
            <h5>Compras y Envíos</h5>
            <p class="text-muted">Procesamiento de órdenes de compra con emisión de comprobante digital.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 shadow-sm">
            <i class="bi bi-exclamation-triangle display-4 text-warning mb-2"></i>
            <h5>Préstamos y Multas</h5>
            <p class="text-muted">Control automático de fechas límite de devolución y cálculo de penalizaciones.</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>