<?php
require ('connection.php');
$ID = $_GET['id'];

// Check if empid parameter is set
if (isset($ID)) {


    try {
        // Establish database connection
        $pdo = new PDO('mysql:host=localhost;dbname=password_vault', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute SQL query to delete record
        $sql = "DELETE FROM employee WHERE empid = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $ID);
        $query = $stmt->execute();

        // Check if deletion was successful
        if ($query) {
            // Redirect with success message
            header('location: dashboard.php?msg=Record%20Deleted%20Successfully');
            exit();
        } else {
            // Handle deletion error
            echo "Error deleting record.";
        }
    } catch (PDOException $e) {
        // Handle PDO connection error
        echo "Connection failed: " . $e->getMessage();
    }
} else {
    // Handle missing empid parameter
    echo "Employee ID not provided.";
}
?>