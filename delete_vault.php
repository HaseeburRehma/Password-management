<?php
require ('connection.php');
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

$sql = "DELETE FROM wallet WHERE wid = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $ID);
$query = $stmt->execute();

if ($query) {
    // header('location: addclient.php?waldel=Record Deleted Successfully');
    echo "<script>document.location='addcredentials.php?id=&delwltt=Record deleted, insert another record'</script>";
    exit();
} else {
    // Handle the deletion error
    // You can display an error message or redirect to an error page
    echo "Error deleting record.";
}

?>