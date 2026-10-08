<?php 
include '../config/conexion.php';
include '../includes/header.php'; 

// Evitar que entren si no hay sesión o si el carrito está vacío
if (!isset($_SESSION['id_usuario'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
if(empty($carrito)){
    echo "<script>window.location.href='catalogo.php';</script>";
    exit();
}

// Calcular el total real a cobrar
$total_pagar = 0;
foreach($carrito as $item) {
    $total_pagar += $item['precio'] * $item['cantidad'];
}
?>

<div class="container mt-5 mb-5">
    <h3 class="text-purple fw-bold mb-4"><i class="bi bi-credit-card"></i> Finalizar Compra</h3>
    
    <div class="row">
        <!-- Formulario de Envío y Pago -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form action="../acciones/procesar_pago.php" method="POST">
                        <input type="hidden" name="total" value="<?= $total_pagar ?>">
                        
                        <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt"></i> 1. Datos de Envío</h5>
                        <div class="mb-3">
                            <label class="form-label">Dirección Completa (Calle, Número, Colonia)</label>
                            <input type="text" name="direccion" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Código Postal</label>
                            <!-- Solo permite 5 números -->
                            <input type="text" name="codigo_postal" class="form-control" pattern="\d{5}" maxlength="5" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Ej. 73089" title="Debe contener exactamente 5 números" required>
                        </div>

                        <h5 class="fw-bold mb-3"><i class="bi bi-wallet2"></i> 2. Método de Pago</h5>
                        <div class="row mb-3">
                            <div class="col-12 mb-3">
                                <label class="form-label">Número de Tarjeta</label>
                                <!-- Solo permite 16 números -->
                                <input type="text" class="form-control" pattern="\d{16}" maxlength="16" minlength="16" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="1234567890123456" title="Debe contener exactamente 16 números" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Fecha de Expiración</label>
                                <!-- Formato MM/AA automatizado -->
                                <input type="text" class="form-control" pattern="(0[1-9]|1[0-2])\/[0-9]{2}" maxlength="5" oninput="formatearFecha(this)" placeholder="MM/AA" title="Formato MM/AA (Ej. 12/28)" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">CVV</label>
                                <!-- Solo permite 3 números -->
                                <input type="password" class="form-control" pattern="\d{3}" maxlength="3" minlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="123" title="Debe contener 3 números" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100 mt-3">
                            <i class="bi bi-lock-fill"></i> Pagar Seguro y Confirmar Pedido
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Resumen de la compra -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h5 class="fw-bold border-bottom pb-2">Resumen</h5>
                    <div class="d-flex justify-content-between mb-2 mt-3">
                        <span>Subtotal</span>
                        <span>$<?= number_format($total_pagar, 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Envío (Paquetexpress)</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5 text-purple">
                        <span>Total</span>
                        <span>$<?= number_format($total_pagar, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para darle formato a la fecha de expiración automáticamente -->
<script>
function formatearFecha(input) {
    var val = input.value.replace(/[^0-9]/g, '');
    if(val.length > 2) {
        val = val.substring(0,2) + '/' + val.substring(2,4);
    }
    input.value = val;
}
</script>

<?php include '../includes/footer.php'; ?>