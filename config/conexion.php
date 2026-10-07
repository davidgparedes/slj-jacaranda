<?php
$host = "localhost";
$usuario = "root";      // Usuario por defecto en XAMPP
$password = "";          // Contraseña vacía por defecto en XAMPP
$bd = "jacaranda_db";

$conexion = new mysqli($host, $usuario, $password, $bd);

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Configurar caracteres especiales (tildes y ñ)
$conexion->set_charset("utf8mb4");
?>