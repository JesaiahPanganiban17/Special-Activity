<?php
session_start();

mysqli_report(MYSQLI_REPORT_OFF);

$host = trim(getenv('DB_HOST') ?: '');
$user = trim(getenv('DB_USER') ?: '');
$password = getenv('DB_PASSWORD') ?: '';
$database = trim(getenv('DB_NAME') ?: '');
$port = (int) (getenv('DB_PORT') ?: 3306);

$connection = null;

if (!empty($host) && !empty($user) && !empty($database)) {
    try {
        $conn = mysqli_init();
        $conn->ssl_set(NULL, NULL, NULL, NULL, NULL);

        if (@$conn->real_connect($host, $user, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT)) {
            $connection = $conn;
        } else {
            $_SESSION['db_error'] = 'Database connection failed: ' . mysqli_connect_error();
        }
    } catch (Throwable $e) {
        $_SESSION['db_error'] = 'Database error: ' . $e->getMessage();
    }
} else {
    $_SESSION['db_error'] = 'Missing required database environment variables.';
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
