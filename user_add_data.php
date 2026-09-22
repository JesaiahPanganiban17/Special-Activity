<?php include 'initialize.php'; ?>
<?php
if (!$connection) {
    $_SESSION['alert_message'] = $_SESSION['db_error'] ?? "Database connection unavailable.";
    header('Location: user_add.php');
    exit();
}

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if (empty($firstname)) {
    $error_message = "Firstname is required";
} elseif (empty($lastname)) {
    $error_message = "Lastname is required";
} elseif (empty($password)) {
    $error_message = "Password is required";
} elseif (empty($username)) {
    $error_message = "Username is required";
} elseif (empty($confirm_password)) {
    $error_message = "Confirm Password is required";
} elseif ($confirm_password !== $password) {
    $error_message = "Password and confirm password not match";
} else {
    $error_message = null;
}

if (!empty($error_message)) {
    $_SESSION['alert_message'] = $error_message;
    header('Location: user_add.php');
} else {
    $sql = "INSERT INTO users (firstname, lastname, username, password) 
            VALUES ('" . $connection->real_escape_string($firstname) . "', 
                    '" . $connection->real_escape_string($lastname) . "', 
                    '" . $connection->real_escape_string($username) . "', 
                    '" . $connection->real_escape_string($password) . "')";

    if ($connection->query($sql) === TRUE) {
        $_SESSION['alert_message'] = "New record has been created";
        header('Location: user_records.php');
    } else {
        die($connection->error);
    }
}
?>
