<?php
require('connection.php');
//Establish database connection using PDO
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
$ID = $_GET['id'];

$sql = "DELETE FROM clients WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $ID, PDO::PARAM_INT);
$query = $stmt->execute();

if ($query) {
    echo "<script>document.location='mywallets.php?id=&delmsg=Wallet deleted'</script>";
    exit();
} else {
    // Handle the deletion error
    // You can display an error message or redirect to an error page
    echo "Error deleting record.";
}
?>
