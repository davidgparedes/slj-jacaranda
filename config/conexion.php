<?php

$host = "jacaranda-db.c9ku2ygcq8zx.us-east-2.rds.amazonaws.com";
$usuario = "admin";
$password = "JACARANDA2026.";
$bd = "Jacaranda";
$puerto = 3306;

$conexion = new mysqli(
    $host,
    $usuario,
    $password,
    $bd,
    $puerto
);

if ($conexion->connect_error) {
    error_log("Error de conexión a la base de datos: " . $conexion->connect_error);
    die("No se pudo conectar con la base de datos.");
}

$conexion->set_charset("utf8mb4");
?>