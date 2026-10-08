<?php
include '../config/conexion.php';
include '../includes/header.php';

// Verificamos que $_SESSION['carrito'] exista, si no, lo inicializamos vacío
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$total = 0;
?>

<div class="container mt-4">
    <h2 class="text-purple mb-4"><i class="bi bi-cart3"></i> Tu Carrito Virtual</h2>

    <!-- Mensaje de Pago Exitoso -->
    <?php if(isset($_GET['pago']) && $_GET['pago'] == 'exito'): ?>
        <div class="alert alert-success text-center shadow-sm p-4 mb-4">
            <h4 class="alert-heading"><i class="bi bi-check-circle-fill"></i> ¡Pago Exitoso!</h4>
            <p>Tu orden ha sido procesada correctamente. Recibirás tu comprobante digital en breve.</p>
            <hr>
            <a href="catalogo.php" class="btn btn-success">Volver al Catálogo</a>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Libro</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Precio Unitario</th>
                                <th class="text-end">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($carrito)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-cart-x fs-1"></i><br>Tu carrito está vacío.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($carrito as $id_libro => $item): ?>
                                    <?php 
                                    $subtotal = $item['precio'] * $item['cantidad']; 
                                    $total += $subtotal;
                                    ?>
                                    <tr>
                                        <td class="align-middle fw-bold text-dark"><?php echo htmlspecialchars($item['titulo']); ?></td>
                                        <td class="align-middle text-center"><?php echo $item['cantidad']; ?></td>
                                        <td class="align-middle text-end">$<?php echo number_format($item['precio'], 2); ?></td>
                                        <td class="align-middle text-end fw-bold text-success">$<?php echo number_format($subtotal, 2); ?></td>
                                        <td class="align-middle text-center">
                                            <a href="../acciones/procesar_compra.php?accion=eliminar&id=<?php echo $id_libro; ?>" class="btn btn-sm btn-outline-danger" title="Eliminar del carrito">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <?php if (!empty($carrito)): ?>
                <a href="../acciones/procesar_compra.php?accion=vaciar" class="btn btn-outline-secondary"><i class="bi bi-trash3"></i> Vaciar Carrito</a>
                <a href="catalogo.php" class="btn btn-outline-primary ms-2"><i class="bi bi-arrow-left"></i> Seguir Comprando</a>
            <?php endif; ?>
        </div>

        <!-- Resumen de la Orden -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h4 class="card-title mb-4 border-bottom pb-2">Resumen de la Orden</h4>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">$<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Costo de Envío</span>
                        <span class="fw-bold">$0.00 <small class="text-success">(Gratis)</small></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="fw-bold text-dark">Total a Pagar</h5>
                        <h5 class="fw-bold text-purple">$<?php echo number_format($total, 2); ?></h5>
                    </div>
                    
                    <!-- Botón conectado a checkout.php -->
                    <a href="checkout.php" class="btn btn-success btn-lg w-100 <?php echo empty($carrito) ? 'disabled' : ''; ?>">
                        <i class="bi bi-credit-card"></i> Proceder al Pago
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>