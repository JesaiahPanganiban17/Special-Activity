<?php
session_start();

$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: 'lab_app';
$port = getenv('DB_PORT') ?: 3306;
$useSsl = filter_var(getenv('DB_SSL') ?: false, FILTER_VALIDATE_BOOLEAN);

$connection = new mysqli();

if ($useSsl) {
    $connection->ssl_set(null, null, null, null, null, null);
    $connected = $connection->real_connect($host, $user, $password, $database, (int) $port, null, MYSQLI_CLIENT_SSL);
} else {
    $connected = $connection->real_connect($host, $user, $password, $database, (int) $port);
}

if (!$connected) {
    die('Connection failed: ' . $connection->connect_error);
}

$createTableSql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$connection->query($createTableSql)) {
    die('Table creation failed: ' . $connection->error);
}
?>
