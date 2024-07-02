<?php require ('connection.php'); ?>
<?php require ('includes/check_login.php'); ?>
<?php
// database connection parameters
$host = 'localhost';
$dbname = 'password_vault';
$username = 'root';
$password = '';

// create a PDO instance
try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
} catch (PDOException $e) {
  echo "Database connection failed: " . $e->getMessage();
  exit();
}

if (isset($_POST['save'])) {
  $client_name = $_POST['client_name'];
  $user = $_SESSION['ID'];

  $check_username = "SELECT * FROM clients WHERE client_name=:client_name AND user_id=:user_id";
  $check_username_query = $pdo->prepare($check_username);
  $check_username_query->bindParam(':client_name', $client_name);
  $check_username_query->bindParam(':user_id', $user);
  $check_username_query->execute();

  $usernamecount = $check_username_query->rowCount();

  if ($client_name == "") {
    // code
  }
  if ($usernamecount > 0) {
    $_SESSION['status'] = "Client Already Exist, Please Use Different";
  } else {
    $query = "INSERT INTO clients (user_id, client_name) VALUES (:user_id, :client_name)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user);
    $stmt->bindParam(':client_name', $client_name);
    $stmt->execute();
    $wallet_id = $pdo->lastInsertId();

    // Redirect the user to the newly created wallet
    header("Location: clientwallet.php?id=" . $wallet_id . '&success=1');
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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

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

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header mb-2">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5><i class="nav-icon fas fa-folder-plus"></i> Create Wallet</h5>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Create Wallet</li>
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
      <form method="POST" action="">
        <!-- Main content -->

        <section class="content d-flex justify-content-center " style="margin-left: 120px;">
          <div class="container-fluid">
            <?php


            // print_r($_SESSION);
            if (isset($_SESSION['status'])) {
              ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <strong> <?php echo $_SESSION['status']; ?></strong>
              </div>



              <?php
              unset($_SESSION['status']);
            }

            ?>

            <!-- SELECT2 EXAMPLE -->
            <div class="row">
              <div class="col-md-10 mb-2">
                <div class="card card-info">
                  <div class="card-header ">
                    <h3 class="card-title mb-0">Create a New Wallet </h3>

                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">

                    <div class="form-group">
                      <label for="exampleInputEmail1">Wallet Name</label>
                      <br>

                      <input type="Name" name="client_name" class="form-control" id="exampleInputname"
                        placeholder="Enter your wallet name" required>

                    </div>
                    <!-- /.form-group -->
                  </div>
                  <div class="footer col-md-12" style="margin-left: 18px; margin-bottom: 20;">
                    <button type="submit" name="save" value="save" class="btn btn-success"> Create </button>
                  </div>
                  <!-- /.card-body -->

                  <!-- Visit <a href="https://select2.github.io/">Select2 documentation</a> for more examples and information about
            the plugin.-->
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