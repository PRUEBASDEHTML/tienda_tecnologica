<?php

// Si Railway provee las variables de entorno, las usamos; si no, usamos los valores locales de XAMPP
$host = getenv('MYSQLHOST') ?: 'localhost';
$usuario = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';
$base_datos = getenv('MYSQLDATABASE') ?: 'tienda_tecnologica';
$port = getenv('MYSQLPORT') ?: '3306';

// Crear conexión (incluyendo el puerto por si Railway lo requiere)
$conn = new mysqli($host, $usuario, $password, $base_datos, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Configurar UTF-8
$conn->set_charset("utf8mb4");

?>