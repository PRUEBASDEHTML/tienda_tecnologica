<?php
// Intentar leer la URL completa de conexión si Railway la provee
$database_url = getenv('MYSQL_URL') ?: getenv('DATABASE_URL');

if ($database_url) {
    $dbparts = parse_url($database_url);
    $host = $dbparts['host'] ?? 'localhost';
    $usuario = $dbparts['user'] ?? 'root';
    $password = $dbparts['pass'] ?? '';
    $base_datos = ltrim($dbparts['path'], '/');
    $port = $dbparts['port'] ?? '3306';
} else {
    $host = getenv('MYSQLHOST') ?: getenv('MYSQL_HOST') ?: 'localhost';
    $usuario = getenv('MYSQLUSER') ?: getenv('MYSQL_USER') ?: 'root';
    $password = getenv('MYSQLPASSWORD') ?: getenv('MYSQL_PASSWORD') ?: getenv('MYSQLROOTPASSWORD') ?: '';
    $base_datos = getenv('MYSQLDATABASE') ?: getenv('MYSQL_DATABASE') ?: 'tienda_tecnologica';
    $port = getenv('MYSQLPORT') ?: getenv('MYSQL_PORT') ?: '3306';
}

$conn = new mysqli($host, $usuario, $password, $base_datos, $port);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>