<?php
require ('connection.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Establish database connection using PDO
$dsn = "mysql:host=localhost;dbname=password_vault;charset=UTF8";
$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
    exit();
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_SESSION['role']) && $_SESSION['role'] == '1') {
        if (isset($_POST['userId']) && isset($_POST['disable'])) {
            $userId = $_POST['userId'];
            $disableStatus = $_POST['disable'];

            $updateQuery = "UPDATE user SET disable = :disableStatus WHERE uid = :userId";
            $stmt = $pdo->prepare($updateQuery);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':disableStatus', $disableStatus, PDO::PARAM_INT);

            // Execute the query
            if ($stmt->execute()) {
                echo "User disable status updated successfully.";
            } else {
                echo "Error updating user disable status: " . $stmt->errorInfo()[2];
            }
        } else {
            echo "Missing user ID or disable status.";
        }
    } else {
        echo "You are not authorized to perform this action.";
    }
} else {
    echo "Invalid request method.";
}
?>