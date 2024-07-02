<?php require ('connection.php'); ?>
<?php require ('includes/check_login.php'); ?>

<?php require ('includes/header.php'); ?>

<!-- 
    if (isset($_GET['id'])){
    $ID=$_GET['id'];
   if(!ctype_digit($ID)){
      echo "You are not authorized to acess this page";
      exit();
    }
    $sql="SELECT * FROM clients WHERE id='$ID'";
    $query=mysqli_query($con,$sql);
    $update=mysqli_fetch_assoc($query);

    }
   


    if(isset($_POST['save_coll']))
 
    {
         $ID = $_POST['id'];
         $user_id = $_POST['user_id'];
        
        $client = $_POST['client_name'];
     

    $sql= "UPDATE clients SET user_id='$user_id', client_name='$client'  WHERE id='$ID'";

        $query=mysqli_query($con,$sql);
        if ($query) {
        

            echo "<script>document.location='userdashboard.php?id=".$ID."&upmsg=Wallet name updated'</script>";

        }
       
        
    }
 -->

<?php
// Replace the database credentials with your own
$host = 'localhost';
$dbname = 'password_vault';
$username = 'root';
$password = '';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=UTF8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Error connecting to database: " . $e->getMessage();
  exit();
}

if (isset($_GET['id'])) {
  $ID = $_GET['id'];
  if (!ctype_digit($ID)) {
    echo "You are not authorized to access this page";
    exit();
  }

  $sql = "SELECT * FROM clients WHERE id=:id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':id', $ID, PDO::PARAM_INT);
  $stmt->execute();
  $update = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['save_coll'])) {
  $ID = $_POST['id'];
  $user_id = $_POST['user_id'];
  $client = $_POST['client_name'];

  $sql = "UPDATE clients SET user_id=:user_id, client_name=:client_name WHERE id=:id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->bindParam(':client_name', $client, PDO::PARAM_STR);
  $stmt->bindParam(':id', $ID, PDO::PARAM_INT);
  $query = $stmt->execute();

  if ($query) {
    echo "<script>document.location='mywallets.php?id=" . $ID . "&upmsg=Wallet name updated'</script>";
    exit();
  }
}
?>



<head>
  <?php require ('includes/header_lib.php') ?>
  <title>ENCS</title>
  <link rel="shortcut icon" href="dist/img/encs-logo.png">

<body class="hold-transition sidebar-mini layout-fixed">

  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="dist/img/encs-loader.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <?php require 'includes/navbar.php' ?>
    <!-- /.navbar -->
    <!-- Main content -->
    <?php require ('includes/left_panel.php') ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5><i class="nav-icon fas fa-edit"></i> Edit credentials</h5>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Edit credentials</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>



      <form method="POST" action="" onsubmit="return myfun()">
        <!-- Main content -->

        <section class="content ">
          <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-info">
              <div class="card-header text-center">
                <h3 class="card-title">Edit credentials</h3>

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
                      <label for="exampleInputnumber">Wallet Name</label>
                      <input type="text" name="client_name" class="form-control" id="pass" placeholder="Enter Your Name"
                        value="<?php echo $update['client_name']; ?>">

                    </div>
                    <!-- /.form-group -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-6 ">
                    <div class="form-group">
                      <!-- <label for="exampleInputaddress">User ID</label> -->
                      <input type="hidden" name="user_id" readonly class="form-control" id="exampleInputPurpose"
                        placeholder="Enter Your Password" value="<?php echo $update['user_id']; ?>">
                    </div>
                  </div>
                  <!-- /.form-group -->




                  <!-- /.col -->
                  <input type="hidden" name="id" value="<?php echo $update['id']; ?>">

                  <!-- /.row -->





                  <div class="footer col-md-12 ">
                    <button type="submit" name="save_coll" value="save" class="btn btn-success"> Save Record </button>

                    <a type="button" href="mywallets.php" class="btn btn-link ">Cancel</a>
                  </div>
                </div>
                <!-- Visit <a href="https://select2.github.io/">Select2 documentation</a> for more examples and information about
            the plugin.-->
              </div>
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