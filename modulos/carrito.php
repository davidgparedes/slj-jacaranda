<?php
include '../includes/header.php';
include '../config/conexion.php';

$carrito = $_SESSION['carrito'] ?? [];
$total_general = 0;
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-4" style="color: #6f42c1;"><i class="bi bi-cart3"></i> Mi Carrito de Compras</h2>

    <?php if (!empty($carrito)): ?>
        <div class="row">
            <!-- Tabla de Productos -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Libro</th>
                                    <th>Precio</th>
                                    <th style="width: 130px;">Cantidad</th>
                                    <th>Subtotal</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($carrito as $id_libro => $item): 
                                    $subtotal = $item['precio'] * $item['cantidad'];
                                    $total_general += $subtotal;
                                    $portada = !empty($item['imagen_portada']) ? $item['imagen_portada'] : 'https://via.placeholder.com/150x220/e0e0e0/6f42c1?text=Sin+Portada';
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?= htmlspecialchars($portada) ?>" alt="Portada" class="rounded me-3 shadow-sm" style="width: 50px; height: 70px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark"><?= htmlspecialchars($item['titulo']) ?></h6>
                                                    <small class="text-muted"><?= htmlspecialchars($item['autor'] ?? 'Autor no disponible') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold">$<?= number_format($item['precio'], 2) ?></td>
                                        <td>
                                            <!-- Formulario con cambio automático al modificar el número -->
                                            <form action="../acciones/procesar_compra.php" method="POST" class="d-flex align-items-center">
                                                <input type="hidden" name="accion" value="actualizar">
                                                <input type="hidden" name="id_libro" value="<?= htmlspecialchars($id_libro) ?>">
                                                <input type="number" name="cantidad" value="<?= htmlspecialchars($item['cantidad']) ?>" min="1" max="<?= htmlspecialchars($item['existencias'] ?? 99) ?>" class="form-control form-control-sm text-center fw-bold me-1" style="width: 65px; border-color: #6f42c1;" onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td class="fw-bold text-success">$<?= number_format($subtotal, 2) ?></td>
                                        <td class="text-center">
                                            <a href="../acciones/procesar_compra.php?accion=eliminar&id=<?= htmlspecialchars($id_libro) ?>" class="btn btn-sm btn-outline-danger border-0" title="Eliminar del carrito">
                                                <i class="bi bi-trash fs-5"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <a href="catalogo.php" class="btn btn-outline-secondary fw-bold">
                        <i class="bi bi-arrow-left"></i> Seguir Comprando
                    </a>
                    <a href="../acciones/procesar_compra.php?accion=vaciar" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Deseas vaciar todo tu carrito?');">
                        <i class="bi bi-trash"></i> Vaciar Carrito
                    </a>
                </div>
            </div>

            <!-- Resumen del Pedido -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white fw-bold">
                        Resumen del Pedido
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total de Artículos:</span>
                            <span class="fw-bold"><?= array_sum(array_column($carrito, 'cantidad')) ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5 fw-bold">Total a Pagar:</span>
                            <span class="fs-4 fw-bold text-success">$<?= number_format($total_general, 2) ?> MXN</span>
                        </div>
                        <a href="checkout.php" class="btn text-white w-100 btn-lg fw-bold shadow-sm" style="background-color: #6f42c1;">
                            <i class="bi bi-credit-card"></i> Proceder al Pago
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center py-5 rounded border-0 shadow-sm">
            <i class="bi bi-cart-x fs-1 d-block mb-3 text-secondary"></i>
            <h4 class="fw-bold">Tu carrito está vacío</h4>
            <p class="text-muted">Aún no has agregado ningún libro a tu compra.</p>
            <a href="catalogo.php" class="btn text-white fw-bold px-4 mt-2" style="background-color: #6f42c1;">
                <i class="bi bi-book"></i> Explorar Catálogo
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>