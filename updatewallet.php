<?php require ('connection.php'); ?>
<?php require ('includes/check_login.php'); ?>

<?php require('includes/header.php'); ?>

<?php
    if (isset($_GET['id'])){
    $ID=$_GET['id'];
    $sql="SELECT * FROM clients WHERE id='$ID'";
    $query=mysqli_query($con,$sql);
    $update=mysqli_fetch_assoc($query);

    }
   
?>
<?php

    if(isset($_POST['save_coll']))
 
    {
         $ID = $_POST['id'];
         $user_id = $_POST['user_id'];
        // $Name = $_POST['full_name'];       
        // $Username = $_POST['user_name'];
        $client = $_POST['client_name'];
// print_r($_POST);
// die();


    $sql= "UPDATE clients SET user_id='$user_id', client_name='$client' WHERE id='$ID'";

        $query=mysqli_query($con,$sql);
        if ($query) {
        

            echo "<script>document.location='index.php?id=".$user_id."&ttt=Wallet name updated'</script>";

        }
       
        
    }
?>



<head>
 <?php require('includes/header_lib.php') ?>
 <title>ENCS</title>
<link rel="shortcut icon" href="dist/img/encs-logo.png">
<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/encs-loader.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="dashboard.php" class="nav-link">Dashboard</a>
      </li>
     <!--  <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> -->
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <!-- <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a> -->
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      
      <!-- Notifications Dropdown Menu -->
      
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    <li class="nav-item">
    <a class="nav-link" href="Login/logout.php"><i class="fa fa-power -off"></i>Logout</a>
</li>
  </ul>
    <!-- <li class="">
        <a class="nav-link"  href="logout.php">
          Logout
        </a>
      </li> -->
    <!-- <li class="nav-item">
            <a href="logout.php" class="nav-link">
              <i class="nav-icon fas fa-book"></i> 
              <p>
                Log out
               <i class="fas fa-angle-left right"></i> 
              </p>
            </a> -->
  </nav>
  <!-- /.navbar -->
 <!-- Main content -->
 <?php require('includes/left_panel.php') ?>
    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><i class="nav-icon fas fa-edit"></i> Edit credentials</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="addrecord.php">Edit credentials</a></li>
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
                  <label for="exampleInputnumber">Client Name</label>
                  <input type="text" name="client_name" class="form-control" id="pass"  placeholder="Enter Your Name" value="<?php echo $update['client_name']; ?>" >
             
                </div>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="exampleInputaddress">User ID</label>
                  <input type="Name" name="user_id" class="form-control" id="exampleInputPurpose" placeholder="Enter Your Password" value="<?php echo $update['user_id']; ?>">
                
              </div>
              <!-- /.col -->
               <input type="hidden" name="id" value="<?php echo $update['id']; ?>">
            </div>
            <!-- /.row -->

      
           
            
          
          <div class="footer col-md-12 mb-4">
            <button type="submit" name="save_coll" value="save" class="btn btn-success"> Save Record </button>

                    <a type="button" href="index.php" class="btn btn-link " >Cancel</a>
                  </div> 
            <!-- Visit <a href="https://select2.github.io/">Select2 documentation</a> for more examples and information about
            the plugin.-->
        </div> 
      </div>
      </div> </div>
        <!-- /.card -->                   
      
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </form>
  </div>
  <!-- /.content-wrapper -->

<?php require('includes/footer.php') ?>
<?php require('includes/footer_lib.php') ?>

</body>
</head>
</html>
