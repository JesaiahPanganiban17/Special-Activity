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
        $connection = mysqli_init();
        
        // Point to the Aiven CA certificate file
        $connection->ssl_set(NULL, NULL, __DIR__ . '/ca.pem', NULL, NULL);
        
        // Connect using the SSL flag
        $connection->real_connect($host, $user, $password, $database, (int) $port, NULL, MYSQLI_CLIENT_SSL);

        if ($connection->connect_error) {
            $connection = null;
            $_SESSION['db_error'] = 'Database connection failed: ' . $connection->connect_error;
        }
    } catch (Throwable $e) {
        $connection = null;
        $_SESSION['db_error'] = 'Database connection failed: ' . $e->getMessage();
    }
}

if (!$connection) {
    $_SESSION['db_error'] = 'Missing or invalid MySQL environment variables.';
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
