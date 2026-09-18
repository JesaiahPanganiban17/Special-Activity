<?php include 'initialize.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Record List</title>
    <style type="text/css">
        table {
            border-collapse: collapse;
            width: 80%;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<div align="center">
    <h3>User's Record List</h3>
    <?php
        if (isset($_SESSION['alert_message'])) {
            echo '<div align="center">' . $_SESSION['alert_message'] . '</div>';
            unset($_SESSION['alert_message']);
        }
    ?>
    <br />
    <a href="user_add.php">Add User</a>
    <br /><br />

    <?php
        $sql = "SELECT id, firstname, lastname, username, created_at FROM users ORDER BY id DESC";
        $result = $connection->query($sql);

        if ($result && $result->num_rows > 0) {
            echo '<table class="center">';
            echo '<tr><th>ID</th><th>Firstname</th><th>Lastname</th><th>Username</th><th>Created At</th></tr>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . htmlspecialchars($row['firstname']) . '</td>';
                echo '<td>' . htmlspecialchars($row['lastname']) . '</td>';
                echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                echo '<td>' . $row['created_at'] . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo '<p>No user records found.</p>';
        }
    ?>
</div>
</body>
</html>
