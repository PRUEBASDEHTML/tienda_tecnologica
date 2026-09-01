<?php
$host = getenv('MYSQLHOST') ?: getenv('MYSQL_HOST') ?: 'localhost';
$usuario = getenv('MYSQLUSER') ?: getenv('MYSQL_USER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: getenv('MYSQL_PASSWORD') ?: '';
$base_datos = getenv('MYSQLDATABASE') ?: getenv('MYSQL_DATABASE') ?: 'tienda_tecnologica';
$port = getenv('MYSQLPORT') ?: getenv('MYSQL_PORT') ?: '3306';

$conn = new mysqli($host, $usuario, $password, $base_datos, $port);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>