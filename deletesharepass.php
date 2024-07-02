<?php
require 'connection.php';

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

// Check if the ID parameter is set
if (isset($_GET['id'])) {
    $ID = $_GET['id'];

    // Prepare and execute the deletion query
    $sql = "DELETE FROM sharedpassword WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $ID);

    // Execute the query
    $query = $stmt->execute();

    // Check if deletion was successful
    if ($query) {
        // Redirect back to the original page with a success message
        header('Location: sharedpassword.php?deleted_success=1');
        exit(); // Exit after redirection
    } else {
        // Handle the deletion error
        // You can display an error message or redirect to an error page
        echo "Error deleting record.";
    }
} else {
    // Handle the case when ID parameter is not set
    echo "ID parameter not provided.";
}
?>