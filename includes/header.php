<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Lectura Jacaranda (SLJ)</title>
    <!-- CSS de Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { 
            background-color: #f4f6f9; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar { 
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
        }
        .navbar-brand { 
            font-weight: 800; 
            color: #d8b4fe !important; 
            letter-spacing: 1px;
        }
        .card {
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none !important;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
        }
        .bg-purple { 
            background: linear-gradient(135deg, #6f42c1, #4a2a85); 
            color: white; 
        }
        .text-purple { color: #6f42c1; }
        .btn-primary {
            background-color: #6f42c1;
            border-color: #6f42c1;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #4a2a85;
            border-color: #4a2a85;
        }
        .badge { font-size: 0.85em; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="/slj-jacaranda/index.php"><i class="bi bi-book-half"></i> Librería Jacaranda</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="/slj-jacaranda/modulos/catalogo.php"><i class="bi bi-journal-bookmark"></i> Catálogo de Libros</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/slj-jacaranda/modulos/prestamos.php"><i class="bi bi-clock-history"></i> Préstamos y Multas</a>
        </li>
        
        <!-- Mostrar Panel Admin solo si es administrador -->
        <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin'): ?>
            <li class="nav-item">
            <a class="nav-link" href="/slj-jacaranda/modulos/panel_admin.php"><i class="bi bi-speedometer2"></i> Panel Admin</a>
            </li>
        <?php endif; ?>
      </ul>
      
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="/slj-jacaranda/modulos/carrito.php">
              <i class="bi bi-cart3"></i> Carrito 
              <?php if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
                  <span class="badge bg-danger rounded-pill"><?php echo count($_SESSION['carrito']); ?></span>
              <?php endif; ?>
          </a>
        </li>
        
        <!-- Cambiar botones según si hay sesión iniciada -->
        <?php if(isset($_SESSION['nombre'])): ?>
            <li class="nav-item dropdown ms-2">
                <a class="nav-link dropdown-toggle text-purple fw-bold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nombre']; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <!-- Botón nuevo de Mi Perfil -->
                    <li><a class="dropdown-item" href="/slj-jacaranda/modulos/perfil.php"><i class="bi bi-person-vcard"></i> Mi Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="/slj-jacaranda/acciones/cerrar_sesion.php"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li class="nav-item">
            <a class="btn btn-outline-light ms-2" href="/slj-jacaranda/modulos/login.php"><i class="bi bi-person"></i> Iniciar Sesión</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container">