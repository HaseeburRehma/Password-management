<?php
require('connection.php'); ?>

<?php
$ID = $_GET['id'];

// Establish a PDO database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=password_vault", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Prepare and execute the SQL query using PDO
$sql = "DELETE FROM getcar WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $ID, PDO::PARAM_INT);

try {
    $stmt->execute();
    
    // Check if the deletion was successful
    if ($stmt->rowCount() > 0) {
        echo "<script>document.location='toolsmanagement.php?id=&msgcd=Tool record deleted successfully'</script>";
    } else {
        echo "Record not found or deletion failed";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
