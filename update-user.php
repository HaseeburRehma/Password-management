<?php require ('connection.php'); ?>
<?php require ('includes/check_login.php'); ?>
<?php require ('includes/header.php'); ?>

<?php
if (isset($_GET['id'])) {
  $ID = $_GET['id'];
  if (!ctype_digit($ID)) {
    echo "You are not authorized to access this page";
    exit();
  }
  // database connection parameters
  $host = 'localhost';
  $dbname = 'password_vault';
  $username = 'root';
  $password = '';

  // create a PDO instance
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

  // Prepare SQL query to fetch user data
  $sql = "SELECT * FROM user WHERE uid = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':id', $ID);
  $stmt->execute();

  // Fetch user data as associative array
  $update = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<?php
if (isset($_POST['save'])) {
  $ID = $_POST['uid'];
  $Name = $_POST['full_name'];
  $Username = $_POST['user_name'];
  $Email = $_POST['email'];
  $Password = $_POST['password'];
  $Role = $_POST['is_admin'];

  // Check if a file was uploaded
  if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === UPLOAD_ERR_OK) {
    // File upload directory
    $uploadDir = 'C:/xampp/htdocs/passwordvault/uploadimage/';

    // Generate a unique filename to avoid overwriting existing files
    $uniqueFilename = uniqid('user_image_') . '_' . basename($_FILES['user_image']['name']);
    $uploadFile = $uploadDir . $uniqueFilename;

    // Move the uploaded file to the upload directory
    if (move_uploaded_file($_FILES['user_image']['tmp_name'], $uploadFile)) {
      // Update user data including the image path
      $sql = "UPDATE user SET full_name=:name, user_name=:username, email=:email, password=:password, is_admin=:role, user_image=:user_image WHERE uid=:id";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':name', $Name);
      $stmt->bindParam(':username', $Username);
      $stmt->bindParam(':email', $Email);
      $stmt->bindParam(':password', $Password);
      $stmt->bindParam(':role', $Role);
      $stmt->bindParam(':user_image', $uploadFile);
      $stmt->bindParam(':id', $ID);
      $query = $stmt->execute();

      if ($query) {
        echo "<script>document.location='dashboard.php?update=Record%20updated';</script>";
      }
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  } else {
    // If no file was uploaded, update user data excluding the image
    $sql = "UPDATE user SET full_name=:name, user_name=:username, email=:email, password=:password, is_admin=:role WHERE uid=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $Name);
    $stmt->bindParam(':username', $Username);
    $stmt->bindParam(':email', $Email);
    $stmt->bindParam(':password', $Password);
    $stmt->bindParam(':role', $Role);
    $stmt->bindParam(':id', $ID);
    $query = $stmt->execute();

    if ($query) {
      echo "<script>document.location='dashboard.php?update=Record%20updated';</script>";
    }
  }
}
?>




<!DOCTYPE html>
<html>

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
    <?php require ('includes/left_panel.php'); ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1><i class="nav-icon fas fa-edit"></i> Edit User</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Edit User</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>


      <?php echo (isset($_GET['wall'])) ? "<div class='alert alert-success'>" . $_GET['wall'] . "</div>" : ""; ?>
      <?php echo (isset($_GET['wallet'])) ? "<div class='alert alert-success col-md-12'>" . $_GET['wallet'] . "</div>" : ""; ?>

      <!--
      <form method="POST" action="" enctype="multipart/form-data">
        <section class="content">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header text-center">
                <h3 class="card-title">Edit Credentials</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputName">Name</label>
                      <input type="text" name="full_name" class="form-control"
                        value="<?php echo $update['full_name']; ?>" placeholder="Enter Your Name">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputUserName">User Name</label>
                      <input type="text" name="user_name" class="form-control"
                        value="<?php echo $update['user_name']; ?>" placeholder="Enter Your User Name">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail">Email</label>
                      <input type="email" name="email" class="form-control" value="<?php echo $update['email']; ?>"
                        placeholder="Edit Your Email">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputPassword">Password</label>
                      <input type="password" name="password" class="form-control"
                        value="<?php echo $update['password']; ?>" placeholder="Enter Your Password" required
                        maxlength="16" minlength="16">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="Name" class="form-control-label">Role</label>
                      <select class="form-control" name="is_admin">
                        <?php
                        $adminSelected = ($update['is_admin'] == 1) ? "selected" : "";
                        $userSelected = ($update['is_admin'] == 0) ? "selected" : "";
                        ?>
                        <option value="1" <?php echo $adminSelected; ?>>Admin</option>
                        <option value="0" <?php echo $userSelected; ?>>User</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputFile">Current User Image</label>
                      <br>

                      <?php
                      $imagePath = '';
                      if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]) {
                        $Name = $_SESSION['Name'];
                        $Email = $_SESSION['Email'];

                        try {
                          // Establish a database connection
                          $db = new PDO("mysql:host=localhost;dbname=password_vault", "root", "");
                          $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                          $stmt = $db->prepare("SELECT user_image FROM user WHERE uid = :userID");
                          $stmt->bindParam(':userID', $_SESSION['ID']);
                          $stmt->execute();
                          $result = $stmt->fetch(PDO::FETCH_ASSOC);

                          if ($result && isset($result['user_image'])) {
                            $imagePath = $result['user_image'];
                            // Convert the local file path to a relative URL
                            $imageURL = str_replace($_SERVER['DOCUMENT_ROOT'], '', $imagePath);
                            echo '<img src="' . $imageURL . '" alt="User Image" class="rounded-circle" style="width: 35px; height: 35px; margin-top: -3px;">';
                          } else {
                            echo '<img src="includes/images/blankprofile.png" alt="User Image" class="rounded-circle" style="width: 35px; height: 35px; margin-top: -3px;">';
                          }
                        } catch (PDOException $e) {
                          echo "Error: " . $e->getMessage();
                        }
                      }
                      ?>
                    </div>
                  </div>


                </div>
              </div>
              <div class="card-footer">
                <div class="row justify-content-center">
                  <div class="col-md-6 text-center">
                    <input type="hidden" name="uid" value="<?php echo $update['uid']; ?>">
                    <button type="submit" name="save" value="save" class="btn btn-success">Save Record</button>
                    <a href="dashboard.php" class="btn btn-link">Cancel</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </form> -->

      <form method="POST" action="" enctype="multipart/form-data">
        <section class="content">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header text-center">
                <h3 class="card-title">Edit Credentials</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputName">Name</label>
                      <input type="text" name="full_name" class="form-control"
                        value="<?php echo $update['full_name']; ?>" placeholder="Enter Your Name">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputUserName">User Name</label>
                      <input type="text" name="user_name" class="form-control"
                        value="<?php echo $update['user_name']; ?>" placeholder="Enter Your User Name">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail">Email</label>
                      <input type="email" name="email" class="form-control" value="<?php echo $update['email']; ?>"
                        placeholder="Edit Your Email">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputPassword">Password</label>
                      <input type="password" name="password" class="form-control"
                        value="<?php echo $update['password']; ?>" placeholder="Enter Your Password" required
                        maxlength="16" minlength="16">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="Name" class="form-control-label">Role</label>
                      <select class="form-control" name="is_admin">
                        <?php
                        $adminSelected = ($update['is_admin'] == 1) ? "selected" : "";
                        $userSelected = ($update['is_admin'] == 0) ? "selected" : "";
                        ?>
                        <option value="1" <?php echo $adminSelected; ?>>Admin</option>
                        <option value="0" <?php echo $userSelected; ?>>User</option>
                      </select>
                    </div>
                  </div>
                  <!-- File input for updating user image -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputFile">Current User Image</label>
                      <br>
                      <?php
                      $imagePath = '';

                      try {
                        // Establish a database connection
                        $db = new PDO("mysql:host=localhost;dbname=password_vault", "root", "");
                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $stmt = $db->prepare("SELECT user_image FROM user WHERE uid = :userID");
                        $stmt->bindParam(':userID', $update['uid'], PDO::PARAM_INT);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);

                        // Debugging: Print the result of the database query
                        //var_dump($result);
                      
                        if ($result && isset($result['user_image'])) {
                          $imagePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $result['user_image']);

                          // Debugging: Print the image path
                          //echo "Image Path: " . $imagePath;
                      
                          // Display the image
                          echo '<img src="' . $imagePath . '" alt="User Image" class="rounded-circle" style="width: 85px; height: 85px; margin-top: -3px;">';
                        } else {
                          echo '<img src="includes/images/blankprofile.png" alt="User Image" class="rounded-circle" style="width: 55px; height: 55px; margin-top: -3px;">';
                        }
                      } catch (PDOException $e) {
                        // Debugging: Print any errors that occur
                        echo "Error: " . $e->getMessage();
                      }
                      ?>

                      <br>
                      <input type="file" name="user_image" class="form-control-file">
                    </div>
                  </div>

                </div>
              </div>
              <div class="card-footer">
                <div class="row justify-content-center">
                  <div class="col-md-6 text-center">
                    <input type="hidden" name="uid" value="<?php echo $update['uid']; ?>">
                    <button type="submit" name="save" value="save" class="btn btn-success">Save Record</button>
                    <a href="dashboard.php" class="btn btn-link">Cancel</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </form>
    </div>
    <!-- /.content-wrapper -->

    <?php require ('includes/footer.php') ?>
    <?php require ('includes/footer_lib.php') ?>

</body>
</head>

</html>