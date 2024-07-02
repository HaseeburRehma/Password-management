<?php require ('connection.php'); ?>
<?php require ('includes/check_login.php'); ?>

<?php



// if(isset($_POST['save_coll']))

//     {

// die(print_r($_POST));

//        $client_id =mysqli_real_escape_string($con, $_POST['client_id']);
//        $user=$_SESSION['ID'];
//        // die (print_r($_SESSION));
//        $Purpose =mysqli_real_escape_string($con, $_POST['purpose']);
//        $username =mysqli_real_escape_string($con, $_POST['uname']);
//        $cname =mysqli_real_escape_string($con, $_POST['c_name']);
//        $Description = mysqli_real_escape_string($con,$_POST['description']);
//        $Password =mysqli_real_escape_string($con, $_POST['password']);
//        $cpassword =mysqli_real_escape_string($con, $_POST['cpassword']);

//        $query = mysqli_query($con, "INSERT INTO wallet (client_id, user_uid, password,cpassword, purpose, uname, c_name, description)
// VALUES ('$client_id', '$user','$Password','$cpassword', '$Purpose','$username', '$cname', '$Description')")
//          or die(mysqli_error($con));


//          echo "<script>document.location='addrecord.php?wall=Record added'</script>";

//    }


?>
<?php
// Establish database connection using PDO
$host = 'localhost';
$dbname = 'password_vault';
$username = 'root';
$password = '';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Error connecting to database: " . $e->getMessage();
  exit();
}

if (isset($_POST['save_coll'])) {
  $client_id = $_POST['client_id'];

  $user = $_SESSION['ID'];
  $purpose = $_POST['purpose'];
  $username = $_POST['uname'];
  $cname = $_POST['c_name'];
  $description = $_POST['description'];
  $password = $_POST['password'];
  $cpassword = $_POST['cpassword'];

  // Prepare the SQL query
  $stmt = $pdo->prepare("INSERT INTO wallet (client_id, user_uid, password, cpassword, purpose, uname, c_name, description) 
        VALUES (:client_id, :user, :password, :cpassword, :purpose, :username, :cname, :description)");

  // Bind the parameters to the query
  $stmt->bindParam(':client_id', $client_id);
  $stmt->bindParam(':user', $user);
  $stmt->bindParam(':password', $password);
  $stmt->bindParam(':cpassword', $cpassword);
  $stmt->bindParam(':purpose', $purpose);
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':cname', $cname);
  $stmt->bindParam(':description', $description);

  // Execute the query
  $stmt->execute();
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have saved the necessary data in variables
    $clientId = $_POST['client_id'];  // Assuming you have the client ID

    // Redirect the user to updatee.php with the appropriate query parameters
    header("Location: clientwallet.php?id=$clientId&success=1");
    exit();
  }



}
?>




<head>
  <?php require ('includes/header_lib.php') ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>ENCS</title>
  <link rel="shortcut icon" href="dist/img/encs-logo.png">

<body class="hold-transition sidebar-mini layout-fixed">
  <script>
    function myfun() {
      var a = document.getElementById("pass").value;
      var b = document.getElementById("Password").value;
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
  <script>
    // Dismiss the alert after 5 seconds
    setTimeout(function () {
      document.getElementById('alertDiv').style.display = 'none';
      history.replaceState(null, null, window.location.pathname);
    }, 4000);
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
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5><i class="nav-icon fas fa-file-medical"></i> Add Credentials</h5>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Add Credentials</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>



      <?php require ('includes/left_panel.php');
      // if(isset($_GET['successmessage'])){
      //     echo "<div class='alert alert-success'>". $_GET['successmessage'] ." </div>";
      // }elseif(isset($_GET['errormessage'])){
      //     echo "<div class='alert alert-danger'>". $_GET['errormessage'] ." </div>";      
      // }
      ?>
      <style type="text/css">
        .alert-success {
          color: #155724;
          background-color: #d4edda;
          border-color: #c3e6cb;
        }

        .alert-danger {
          color: #721c24;
          background-color: #f8d7da;
          border-color: #f5c6cb;
        }
      </style>
      <form method="POST" action="" onsubmit="return myfun()">
        <!-- Main content -->

        <section class="content ">
          <div class="container-fluid">
            <?php echo (isset($_GET['delwltt'])) ? '<div id="alertDiv" class="alert alert-danger alert-dismissible fade show" role="alert">
' . $_GET['delwltt'] . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button></div>' : ''; ?>
            <?php echo (isset($_GET['wall'])) ? "<div class='alert alert-success'>" . $_GET['wall'] . "</div>" : ""; ?>
            <?php echo (isset($_GET['wallet'])) ? "<div class='alert alert-success col-md-12'>" . $_GET['wallet'] . "</div>" : ""; ?>
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Add Credentials</h3>

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

                      <!-- <select name="client_id" class="form-control" tabindex="1" required>
                      <option value="">Select Wallet</option>
                      <php $client_query = mysqli_query($con,"SELECT * from clients where user_id = ".$_SESSION['ID']."") ;?>
                      <php while($row = mysqli_fetch_assoc($client_query)){ ?>
                         <option value="<php echo $row['id'];?>"><php echo $row['client_name'] ?></option>
                      <php } ?>
                    </select>
 -->
                      <select name="client_id" class="form-control" tabindex="1" required>
                        <option value="">Select Wallet</option>
                        <?php
                        $user = $_SESSION['ID'];

                        // Prepare the SQL query
                        $stmt = $pdo->prepare("SELECT * FROM clients WHERE user_id = :user");

                        // Bind the user parameter to the query
                        $stmt->bindParam(':user', $user);

                        // Execute the query
                        $stmt->execute();

                        // Fetch all rows from the result set
                        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($clients as $row) {
                          ?>
                          <option value="<?php echo $row['id']; ?>"><?php echo $row['client_name']; ?></option>
                          <?php
                        }
                        ?>
                      </select>


                    </div>
                  </div>
                  <!-- /.form-group -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputaddress">User Name</label>
                      <input type="Name" name="uname" class="form-control" id="exampleInputPurpose"
                        placeholder="Enter Your User Name" tabindex="2" required>
                    </div>
                  </div>
                  <!-- /.form-group -->

                </div>
                <!-- /.col -->
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputaddress">Purpose</label>
                      <input type="Name" name="purpose" class="form-control" id="exampleInputPurpose"
                        placeholder="Enter Your Purpose" tabindex="3" required>

                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                      <label for="exampleInputnumber">Password</label>
                      <input type="password" name="password" class="form-control" id="pass"
                        placeholder="Enter Your Password" value="" tabindex="5">
                      <span id="message" style="color:red;"> </span>
                    </div>


                  </div>
                  <!-- /.form-group -->

                  <div class="col-md-6 ">
                    <div class="form-group">
                      <label for="exampleInputnumber">URL/IP Address</label>
                      <input type="name" name="c_name" class="form-control" placeholder="Enter Your URL/IP Address"
                        value="" tabindex="4">
                    </div>
                    <div class="form-group">
                      <label>Confirm Password</label>
                      <input type="password" name="cpassword" class="form-control" id="Password"
                        placeholder="Enter Password Again" value="" tabindex="6" required>
                      <span id="messages" style="color:red;"></span>
                    </div>


                  </div>
                  <!-- /.form-group -->
                </div>
                <div class="row">
                  <div class="col-md-12 mb-3 ">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Description</label>
                      <textarea type="Description" name="description" class="form-control" id="exampleInputdescription"
                        rows="6" placeholder="Enter Description" tabindex="7"></textarea>
                      <!-- <input type="Description" name="description" class="form-control" id="exampleInputdescription" placeholder="Enter Description" required> -->

                    </div>
                    <!-- /.form-group -->
                  </div>
                  <!-- /.col -->






                  <div class="footer col-md-12  ">
                    <button type="submit" name="save_coll" value="save" class="btn btn-success" tabindex="8"> Save
                      Record </button>

                    <!-- <a type="button" href="movie_gen.php" class="btn btn-link " tabindex="7">Cancel</a> -->
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