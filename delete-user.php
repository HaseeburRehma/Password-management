<?php
require ('connection.php');

$ID = $_GET['id'];
$loggedInUserRole = $_SESSION['ID']; // Assuming you have stored the logged-in user's role in a session variable

if ($loggedInUserRole === '1') {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=password_vault', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "DELETE FROM user WHERE uid = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $ID);
        $query = $stmt->execute();

        if ($query) {
            header('location: dashboard.php?msg=Record%20Deleted%20Successfully');
            exit();
        } else {
            // Handle the deletion error
            // You can display an error message or redirect to an error page
            echo "Error deleting record.";
        }
    } catch (PDOException $e) {
        // Handle the PDO connection error
        // You can display an error message or redirect to an error page
        echo "Connection failed: " . $e->getMessage();
    }
} else {
    echo "You are not authorized to perform this action.";
}
?>