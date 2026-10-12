<?php
session_start();
include '../config/conexion.php';

// Si el carrito no existe en la sesión, lo creamos
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Ahora leemos la acción tanto por URL (GET) como por formulario oculto (POST)
$accion = isset($_GET['accion']) ? $_GET['accion'] : (isset($_POST['accion']) ? $_POST['accion'] : '');

// AGREGAR AL CARRITO
if ($accion == 'agregar' && isset($_GET['id'])) {
    $id_libro = intval($_GET['id']);
    
    // CORRECCIÓN: Se añadieron 'autor' e 'imagen_portada' a la consulta
    $sql = "SELECT id_libro, titulo, autor, imagen_portada, precio, existencias FROM libros WHERE id_libro = $id_libro";
    $resultado = $conexion->query($sql);
    
    if ($resultado && $resultado->num_rows > 0) {
        $libro = $resultado->fetch_assoc();
        
        // Si ya está en el carrito, aumentamos la cantidad
        if (isset($_SESSION['carrito'][$id_libro])) {
            if ($_SESSION['carrito'][$id_libro]['cantidad'] < $libro['existencias']) {
                $_SESSION['carrito'][$id_libro]['cantidad']++;
            }
        } else {
            // Si no está, lo agregamos con cantidad 1
            if ($libro['existencias'] > 0) {
                // CORRECCIÓN: Se añadieron autor, imagen_portada y existencias al arreglo de sesión
                $_SESSION['carrito'][$id_libro] = [
                    'titulo'         => $libro['titulo'],
                    'autor'          => $libro['autor'],
                    'imagen_portada' => $libro['imagen_portada'],
                    'precio'         => $libro['precio'],
                    'cantidad'       => 1,
                    'existencias'    => $libro['existencias'],
                    'stock_maximo'   => $libro['existencias']
                ];
            }
        }
    }
    // Redirigir a la vista del carrito
    header("Location: ../modulos/carrito.php");
    exit();
}

// NUEVO: ACTUALIZAR CANTIDAD DIRECTAMENTE DESDE EL CARRITO
if ($accion == 'actualizar' && isset($_POST['id_libro']) && isset($_POST['cantidad'])) {
    $id_libro = intval($_POST['id_libro']);
    $nueva_cantidad = intval($_POST['cantidad']);

    // Verificamos que el libro exista en la sesión del carrito
    if (isset($_SESSION['carrito'][$id_libro])) {
        if ($nueva_cantidad > 0) {
            // Validamos que la nueva cantidad manual no supere el límite de existencias
            $max_stock = isset($_SESSION['carrito'][$id_libro]['stock_maximo']) ? $_SESSION['carrito'][$id_libro]['stock_maximo'] : 99;
            if ($nueva_cantidad > $max_stock) {
                $nueva_cantidad = $max_stock; // Lo topamos al máximo posible
            }
            // Actualizamos la cantidad
            $_SESSION['carrito'][$id_libro]['cantidad'] = $nueva_cantidad;
        } else {
            // Si el usuario pone 0 o menos, eliminamos el artículo del carrito
            unset($_SESSION['carrito'][$id_libro]);
        }
    }
    // Redirigir de nuevo al carrito para reflejar los cambios de precio al instante
    header("Location: ../modulos/carrito.php");
    exit();
}

// ELIMINAR DEL CARRITO
if ($accion == 'eliminar' && isset($_GET['id'])) {
    $id_libro = intval($_GET['id']);
    if (isset($_SESSION['carrito'][$id_libro])) {
        unset($_SESSION['carrito'][$id_libro]);
    }
    header("Location: ../modulos/carrito.php");
    exit();
}

// VACIAR CARRITO
if ($accion == 'vaciar') {
    $_SESSION['carrito'] = [];
    header("Location: ../modulos/carrito.php");
    exit();
}

// SIMULAR PAGO
if ($accion == 'pagar') {
    $_SESSION['carrito'] = [];
    header("Location: ../modulos/carrito.php?pago=exito");
    exit();
}

// Si entran directo al archivo sin acción, los mandamos al catálogo
header("Location: ../modulos/catalogo.php");
exit();
?>