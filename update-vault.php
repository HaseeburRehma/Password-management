<?php require ('connection.php'); ?>
<?php require ('includes/check_login.php'); ?>

<?php require ('includes/header.php'); ?>

<?php
//     if (isset($_GET['id'])){
//     $ID=$_GET['id'];
//     if(!ctype_digit($ID)){
//       echo "You are not authorized to acess this page";
//       exit();
//     }
//     $sql="SELECT * FROM wallet WHERE wid='$ID'";
//     $query=mysqli_query($con,$sql);
//     $update=mysqli_fetch_assoc($query);

//     }

// $get_client = "SELECT * FROM `clients`  where id =".$ID.""; 
//   $client_query = mysqli_query($con,$get_client);
//   $client = mysqli_fetch_assoc($client_query);
// Assuming your database connection details
$dbHost = 'localhost';
$dbName = 'password_vault';
$dbUser = 'root';
$dbPass = '';

try {
  $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  // Handle the PDO connection error
  echo "Connection failed: " . $e->getMessage();
  exit();
}
if (isset($_GET['id'])) {
  $ID = $_GET['id'];
  if (!ctype_digit($ID)) {
    echo "You are not authorized to access this page";
    exit();
  }

  $sql = "SELECT * FROM wallet WHERE wid = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':id', $ID, PDO::PARAM_INT);
  $stmt->execute();
  $update = $stmt->fetch(PDO::FETCH_ASSOC);

  $get_client = "SELECT * FROM `clients` WHERE id = :id";
  $stmt_client = $pdo->prepare($get_client);
  $stmt_client->bindParam(':id', $ID, PDO::PARAM_INT);
  $stmt_client->execute();
  $client = $stmt_client->fetch(PDO::FETCH_ASSOC);
}

?>

<?php

// if(isset($_POST['save_coll']))

// {

//     $user=$_SESSION['ID'];
//     $ID = $_POST['wid'];
//     $client_id = $_POST['client_id'];
//     // $Name = $_POST['full_name'];       
//     // $Username = $_POST['user_name'];
//     $Purpose = $_POST['purpose'];
//     $uname = $_POST['uname'];
//     $cname = $_POST['c_name'];
//     $Password = $_POST['password'];
//     $cpassword = $_POST['cpassword'];
//     $Description = $_POST['description'];

//    $sql="UPDATE wallet SET client_id='$client_id', purpose='$Purpose',uname='$uname', c_name='$cname', password='$Password',
//    cpassword='$cpassword', description='$Description' WHERE wid='$ID'";


//     $query=mysqli_query($con,$sql);
//     if ($query) {


//         echo "<script>document.location='updatee.php?id=".$client_id."&utp=Record updated'</script>";

//     }


// }
?>
<?php


if (isset($_POST['save_coll'])) {
  $user = $_SESSION['ID'];
  $ID = $_POST['wid'];
  $client_id = $_POST['client_id'];
  $Purpose = $_POST['purpose'];
  $uname = $_POST['uname'];
  $cname = $_POST['c_name'];
  $Password = $_POST['password'];
  $cpassword = $_POST['cpassword'];
  $Description = $_POST['description'];

  $sql = "UPDATE wallet SET client_id=:client_id, purpose=:purpose, uname=:uname, c_name=:c_name, password=:password, cpassword=:cpassword, description=:description WHERE wid=:id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);
  $stmt->bindParam(':purpose', $Purpose);
  $stmt->bindParam(':uname', $uname);
  $stmt->bindParam(':c_name', $cname);
  $stmt->bindParam(':password', $Password);
  $stmt->bindParam(':cpassword', $cpassword);
  $stmt->bindParam(':description', $Description);
  $stmt->bindParam(':id', $ID, PDO::PARAM_INT);
  $query = $stmt->execute();

  if ($query) {
    echo "<script>document.location='clientwallet.php?id=" . $client_id . "&utp=Record updated'</script>";
  }
}
?>



<head>
  <?php require ('includes/header_lib.php') ?>
  <title>ENCS</title>
  <link rel="shortcut icon" href="dist/img/encs-logo.png">

<body class="hold-transition sidebar-mini layout-fixed">
  <script>
    function myfun() {
      var a = document.getElementById("passs").value;
      var b = document.getElementById("Passwords").value;
      if (a == "") {
        document.getElementById("message").innerHTML = "* Please fill this field";
        return false;
      }
      if (a != b) {
        document.getElementById("messages").innerHTML = "* Confirm password does not match with the password provided";
        return false;
      }
    }

  </script>
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="dist/img/encs-loader.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <?php require 'includes/navbar.php' ?>

    <!-- /.navbar -->
    <!-- Main content -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header mb-2">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5><i class="nav-icon fas fa-edit"></i> Edit Credentials</h5>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Edit Credentials</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>


      <?php echo (isset($_GET['wall'])) ? "<div class='alert alert-success'>" . $_GET['wall'] . "</div>" : ""; ?>
      <?php echo (isset($_GET['wallet'])) ? "<div class='alert alert-success col-md-12'>" . $_GET['wallet'] . "</div>" : ""; ?>
      <?php echo (isset($_GET["user"])) ? "<div class='alert alert-success col-md-12'>" . $_GET["user"] . "</div>" : ""; ?>
      <?php require ('includes/left_panel.php');
      // if(isset($_GET['successmessage'])){
      //     echo "<div class='alert alert-success'>". $_GET['successmessage'] ." </div>";
      // }elseif(isset($_GET['errormessage'])){
      //     echo "<div class='alert alert-danger'>". $_GET['errormessage'] ." </div>";      
      // }
      ?>
      <form method="POST" action="" onsubmit="return myfun()">
        <!-- Main content -->

        <section class="content ">
          <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-info">
              <div class="card-header text-center">
                <h3 class="card-title">Edit Credentials</h3>

                <!-- <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div> -->
              </div>

              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">

                    <div class="form-group">
                      <label for="exampleInputEmail1">Wallet Name</label>
                      <!-- <input type="Name" name="c_name" class="form-control" id="exampleInputname" placeholder="Enter Your Full Name"> -->

                      <select name="client_id" class="form-control" tabindex="1" required>

                        <?php $client_query = mysqli_query($con, "SELECT * from clients where user_id = " . $_SESSION['ID'] . ""); ?>
                        <?php while ($row = mysqli_fetch_assoc($client_query)) { ?>
                          <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $_GET['client_id']) {
                               echo "selected";
                             } ?>><?php echo $row['client_name'] ?></option>
                        <?php } ?>
                      </select>

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputaddress">User Name</label>
                      <input type="Name" name="uname" class="form-control" id="exampleInputPurpose"
                        placeholder="Enter Your user name" value="<?php echo $update['uname']; ?>">

                    </div>

                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputnumber">Purpose</label>
                      <input type="text" name="purpose" class="form-control" id="pass" placeholder="Enter Your Purpose"
                        value="<?php echo $update['purpose']; ?>">


                    </div>

                    <!-- /.form-group -->

                    <div class="form-group">
                      <label for="exampleInputnumber">Password</label>
                      <input type="text" name="password" class="form-control" id="passs"
                        placeholder="Enter Your Purpose" value="<?php echo $update['password']; ?>">
                      <span id="message" style="color:red;"> </span>

                    </div>

                  </div>



                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="exampleInputnumber">URL/IP Address</label>
                      <input type="text" name="c_name" class="form-control" placeholder="Enter Your URL/IP"
                        value="<?php echo $update['c_name']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Confirm Password</label>
                      <input type="text" name="cpassword" class="form-control" id="Passwords"
                        value="<?php echo $update['cpassword']; ?>"></input>
                      <span id="messages" style="color:red;"> </span>

                      <!-- <input type="Description" name="description" class="form-control" id="exampleInputdescription" placeholder="Enter Description" required> -->
                    </div>

                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12 ">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Description</label>
                      <textarea type="Description" name="description" class="form-control" id="exampleInputdescription"
                        rows="3" cols="4"><?php echo $update['description']; ?></textarea>
                      <!-- <input type="Description" name="description" class="form-control" id="exampleInputdescription" placeholder="Enter Description" required> -->

                    </div>

                    <!-- /.col -->
                    <input type="hidden" name="wid" value="<?php echo $update['wid']; ?>">
                  </div>
                  <!-- /.row -->



                  <div class="footer col-md-12 ">
                    <button type="submit" name="save_coll" value="save" class="btn btn-success"> Save Record </button>

                    <?php echo '<a type="button" href="clientwallet.php?id=' . $_GET['client_id'] . '""" class="btn btn-link " >'; ?>Cancel</a>
                  </div>
                  <!-- Visit <a href="https://select2.github.io/">Select2 documentation</a> for more examples and information about
            the plugin.-->
                </div>
              </div>
            </div>
          </div>
    </div>
    <!-- /.card -->

    <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </form>
  </div>
  <!-- /.content-wrapper -->

  <?php require ('includes/footer.php') ?>
  <?php require ('includes/footer_lib.php') ?>

</body>
</head>

</html>