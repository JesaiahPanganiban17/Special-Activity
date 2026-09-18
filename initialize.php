<?php
session_start();

mysqli_report(MYSQLI_REPORT_OFF);

$host = getenv('DB_HOST');
if (empty($host) && getenv('VERCEL') === '1') {
    $host = null;
} elseif (empty($host)) {
    $host = 'localhost';
}

$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: 'lab_app';
$port = getenv('DB_PORT') ?: 3306;
$useSsl = filter_var(getenv('DB_SSL') ?: false, FILTER_VALIDATE_BOOLEAN);

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
