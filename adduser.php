<?php

require ('connection.php');
require ('includes/check_login.php');
require ('includes/header.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['save_coll'])) {
  // Check if form fields are set
  if (isset($_POST['full_name'], $_POST['user_name'], $_POST['email'], $_POST['password'], $_POST['is_admin'])) {
    $Name = $_POST['full_name'];
    $User_name = $_POST['user_name'];
    $Email = $_POST['email'];
    $Password = $_POST['password'];
    $role = $_POST['is_admin'];
    $disable = 1; // Set disable value to 1 for new user added

    // Password length check
    if (strlen($Password) != 16) {
      $_SESSION['status'] = "Password must be exactly 16 characters.";
    } else {
      // File upload
      if (isset($_FILES['user_image'])) {
        $uploadDir = 'C:/xampp/htdocs/passwordvault/uploadimage/';
        $uploadFile = $uploadDir . basename($_FILES['user_image']['name']);

        if (move_uploaded_file($_FILES['user_image']['tmp_name'], $uploadFile)) {
          try {
            // Prepare SQL statement
            $sql = "INSERT INTO user (full_name, user_name, email, password, is_admin, user_image, disable) VALUES (:Name,
:User_name, :Email, :Password, :role, :user_image, :disable)";
            $stmt = $db->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':Name', $Name);
            $stmt->bindParam(':User_name', $User_name);
            $stmt->bindParam(':Email', $Email);
            $stmt->bindParam(':Password', $Password);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':user_image', $uploadFile); // Bind user image path
            $stmt->bindParam(':disable', $disable);

            // Execute the query
            if ($stmt->execute()) {
              // Redirect after successful insertion
              echo "
<script>alert('User added successfully');</script>";
              echo "
<script>document.location = 'dashboard.php'</script>";
            } else {
              echo "Error: Failed to execute query.";
            }
          } catch (PDOException $e) {
            // Handle database errors
            echo "Error: " . $e->getMessage();
          }
        } else {
          echo "Sorry, there was an error uploading your file.";
        }
      } else {
        echo "Error: No file uploaded.";
      }
    }
  } else {
    echo "Error: Form fields are not set.";
  }
}
?>





<!-- php require('../includes/left_panel.php'); ?> -->

<head>
  <?php require ('includes/header_lib.php') ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h5><i class="nav-icon fas fa-user-plus"></i> Create User</h5>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Create User</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <?php require ('includes/left_panel.php') ?>

      <!--  if(isset($_GET['successmessage'])){
                   echo "<div class='alert alert-success'>". $_GET['successmessage'] ." </div>";
             }elseif(isset($_GET['errormessage'])){
                 echo "<div class='alert alert-danger'>". $_GET['errormessage'] ." </div>";      
                } -->
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

      <!-- Main content -->
      <form method="POST" action="" enctype="multipart/form-data">
        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-info">
              <div class="card-header text-center">
                <h3 class="card-title">Create a New User</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="exampleInputName">Full Name</label>
                          <input type="text" name="full_name" class="form-control" id="exampleInputName"
                            placeholder="Enter Your Full Name">
                        </div>
                        <!-- /.form-group -->
                      </div>
                      <!-- /.col -->
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="exampleInputUserName">User Name</label>
                          <input type="text" name="user_name" class="form-control" id="exampleInputUserName"
                            placeholder="Enter Your User Name">
                        </div>
                        <!-- /.form-group -->
                      </div>
                      <!-- /.col -->
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="exampleInputEmail">Email</label>
                          <input type="email" name="email" class="form-control" id="exampleInputEmail"
                            placeholder="Enter Your Email">
                        </div>
                        <!-- /.form-group -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="exampleInputPassword">Password</label>
                          <input type="password" name="password" class="form-control" id="exampleInputPassword"
                            placeholder="Enter Your Password" required minlength="16" maxlength="16">
                        </div>
                        <!-- /.form-group -->
                      </div>
                      <!-- /.col -->
                      <div class="col-md-12">
                        <div class="form-group" data-validate="Image is required">
                          <label for="exampleInputFile">Choose User Profile</label>
                          <input type="file" class="form-control-file" id="exampleInputFile" name="user_image" required>
                        </div>
                        <!-- /.form-group -->
                      </div>
                      <!-- /.col -->
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="exampleInputRole" class="form-control-label">Role</label>
                          <select class="form-control" name="is_admin">
                            <?php if ($role['is_admin'] == 1) {
                              $admin_option = "selected";
                              $user_option = "";
                            } else {
                              $user_option = "selected";
                              $admin_option = "";
                            } ?>
                            <option value="1" <?php echo $admin_option; ?>>Admin</option>
                            <option value="0" <?php echo $user_option; ?>>User</option>
                          </select>
                        </div>
                        <!-- /.form-group -->
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <div class="row justify-content-center">
                  <div class="col-md-6 text-center">
                    <button type="submit" name="save_coll" value="save" class="btn btn-success">Create</button>
                    <a href="dashboard.php" class="btn btn-link">Cancel</a>
                  </div>
                </div>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
      </form>




    </div>
    <!-- /.content-wrapper -->
  </div <?php require ('includes/footer.php') ?> <?php require ('includes/footer_lib.php') ?> </body>
  </head>

  </html>