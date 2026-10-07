<?php
session_start();
include '../config/conexion.php';

// Si el carrito no existe en la sesión, lo creamos
if (!isset($_SESSION['carrito'])) {$_SESSION['carrito'] = [];
}

$accion = isset($_GET['accion']) ?$_GET['accion'] : '';

// AGREGAR AL CARRITO
if ($accion == 'agregar' && isset($_GET['id'])) {
    $id_libro =$_GET['id'];
    
    // Verificamos que el libro exista en la base de datos
    $sql = "SELECT id_libro, titulo, precio, existencias FROM libros WHERE id_libro = $id_libro";
    $resultado = $conexion->query($sql);
    
    if ($resultado->num_rows > 0) {
        $libro =$resultado->fetch_assoc();
        
        // Si ya está en el carrito, aumentamos la cantidad
        if (isset($_SESSION['carrito'][$id_libro])) {
            // Validamos que no exceda las existencias
            if ($_SESSION['carrito'][$id_libro]['cantidad'] <$libro['existencias']) {
                $_SESSION['carrito'][$id_libro]['cantidad']++;
            }
        } else {
            // Si no está, lo agregamos con cantidad 1
            if ($libro['existencias'] > 0) {
                $_SESSION['carrito'][$id_libro] = [
                    'titulo' => $libro['titulo'],
                    'precio' => $libro['precio'],
                    'cantidad' => 1,
                    'stock_maximo' => $libro['existencias']
                ];
            }
        }
    }
    // Redirigir a la vista del carrito
    header("Location: ../modulos/carrito.php");
    exit();
}

// ELIMINAR DEL CARRITO
if ($accion == 'eliminar' && isset($_GET['id'])) {
    $id_libro =$_GET['id'];
    if (isset($_SESSION['carrito'][$id_libro])) {
        unset($_SESSION['carrito'][$id_libro]);
    }
    header("Location: ../modulos/carrito.php");
    exit();
}

// VACIAR CARRITO
if ($accion == 'vaciar') {$_SESSION['carrito'] = [];
    header("Location: ../modulos/carrito.php");
    exit();
}

// SIMULAR PAGO (Vacía el carrito y muestra un mensaje de éxito)
if ($accion == 'pagar') {
    // Aquí iría el código para insertar en la tabla `ventas` y actualizar `existencias` en `libros`
    // Por ahora, simularemos el pago vaciando el carrito y redirigiendo con un mensaje de éxito.
    $_SESSION['carrito'] = [];
    header("Location: ../modulos/carrito.php?pago=exito");
    exit();
}

// Si entran directo al archivo sin acción, los mandamos al catálogo
header("Location: ../modulos/catalogo.php");
exit();
?>