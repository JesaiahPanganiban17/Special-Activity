<?php
session_start();

mysqli_report(MYSQLI_REPORT_OFF);

$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_NAME');
$port = getenv('DB_PORT') ?: 3306;

$connection = null;

if (!empty($host) && !empty($user) && !empty($database)) {
    try {
        $connection = new mysqli($host, $user, $password, $database, (int) $port);

        if ($connection->connect_error) {
            $connection = null;
            $_SESSION['db_error'] = 'Database connection failed. Check MySQL is running and your DB settings are correct.';
        }
    } catch (Throwable $e) {
        $connection = null;
        $_SESSION['db_error'] = 'Database connection failed: ' . $e->getMessage();
    }
}

if (!$connection) {
    $_SESSION['db_error'] = 'Missing or invalid MySQL environment variables. Set DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, and DB_PORT in Vercel.';
}

if ($connection) {
    $createTableSql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        firstname VARCHAR(100) NOT NULL,
        lastname VARCHAR(100) NOT NULL,
        username VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if (!$connection->query($createTableSql)) {
        $_SESSION['db_error'] = 'Table creation failed: ' . $connection->error;
        $connection = null;
    }
}
?>
