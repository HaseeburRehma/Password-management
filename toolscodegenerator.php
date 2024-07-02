<?php require 'connection.php'; ?>
<?php require ('includes/check_login.php'); ?>

<?php
error_reporting(0);
if (isset($_POST['save_car'])) {
  session_start(); // Start the session if not already started
  $user = $_SESSION['ID'];
  $project_name = trim($_POST['tool_owner']);
  $mov_name = trim($_POST['tol_name']);

  // Establish a PDO database connection
  try {
    $pdo = new PDO("mysql:host=localhost;dbname=password_vault", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    die("Error: " . $e->getMessage());
  }

  // Prepare and execute the SQL query using PDO
  $sql = "INSERT INTO `getcar` (user_id, tool_owner, tool_id) VALUES (:user, :project_name, :mov_name)";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':user', $user, PDO::PARAM_INT);
  $stmt->bindParam(':project_name', $project_name, PDO::PARAM_STR);
  $stmt->bindParam(':mov_name', $mov_name, PDO::PARAM_STR);

  try {
    $stmt->execute();

    // Check if the insertion was successful
    if ($stmt->rowCount() > 0) {
      echo "<script>document.location='toolsmanagement.php?walet=Tool added successfully'</script>";
    } else {
      echo "Insertion failed";
    }
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}





?>

<!DOCTYPE html>
<html lang="en">
<?php require 'includes/header_lib.php'; ?>
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



    <!-- /.sidebar -->
    </aside>

    <!-- Main content -->
    <?php require 'includes/left_panel.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5><i class="nav-icon fas fa-tools"></i> Tool Code Generator</h5>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Tool Code Generator</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>


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
            <!-- <php echo (isset($_GET['walet']))?"<div class='alert alert-success'>".$_GET['walet']."</div>":""; ?> -->
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
                  <div class="card-header text-center">
                    <h3 class="card-title">Project Code Generator</h3>


                  </div>

                  <!-- /.card-header -->
                  <div class="card-body">

                    <div class="form-group">
                      <label for="exampleInputEmail1">Tool Name</label>
                      <input type="Name" name="tool_owner" class="form-control" id="exampleInputname"
                        placeholder="Enter your tool name" required>
                      <br />
                      <div class="form-group mt-2">
                        <button type="button" class="btn btn-primary btn-md" onclick="generatecar()">Generate Tool
                          Code</button>

                        <select id="car_names" name="tol_name"
                          style="display: none; border: white; margin-bottom: 2%; margin-left: 178px; text-transform: capitalize; appearance: none;">s
                          <option>Please Generate Name</option>
                        </select>

                        <div id="message" style="margin-inline-start: 10px; !important"></div>

                        <div id="result"></div>

                      </div>
                      <div class="footer col-md-12">
                        <button type="submit" name="save_car" value="save" id="save_records" disabled
                          class="btn btn-success"> Save Record </button>

                      </div>

                    </div>
                  </div>







                </div>
              </div>
              <!-- /.col -->


            </div>
          </div>
          <!-- /.card -->

          <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
      </form>



    </div>

  </div>
  <script>
    function generatecar() {
      $.ajax({

        'url': 'get_car.php',
        'type': 'GET',
        'data': {
          'car': 'set'
        },
        'success': function (data) {
          var data = JSON.parse(data);
          $("#car_names").html("<option value='" + data.c_id + "' selected> Suggested Code is : " + data.car_name + "</option>");
          $("#car_names").attr("disabled");
          $('#car_names').show();
          $('#link').show();
          $('#link').attr('href', 'addclient.php?tool_id=' + data.c_id);
          $('#save_records').removeAttr("disabled");
        },
        'error': function (request, error) {
          alert("Request: " + JSON.stringify(request));
        }
      });
    }
  </script>


  </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->



  <?php require ('includes/footer.php') ?>
  <?php require ('includes/footer_lib.php') ?>

</body>

</html>