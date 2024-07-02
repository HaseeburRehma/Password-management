<?php
require('connection.php'); ?>

<?php
$ID = $_GET['id'];



		$query=mysqli_query($con,"DELETE  FROM clients WHERE id=$ID") or die(mysqli_error($con));
		 // header('location: addclient.php?waldel=Record Deleted Successfully');
		echo "<script>document.location='index.php?id=&aaa=Wallet deleted'</script>";
		?>