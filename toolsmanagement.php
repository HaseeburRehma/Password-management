<?php require 'connection.php'; ?>
<?php require ('includes/check_login.php'); ?>
<!-- php require('includes/header.php'); ?> -->
<!DOCTYPE html>
<html lang="en">
<?php require 'includes/header_lib.php'; ?>
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
              <h5><i class="nav-icon fas fa-tasks"></i> Tool Management</h5>
            </div>

            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <!-- <li class="breadcrumb-item active"><a href="#">My wallet</a></li> -->
                <li class="breadcrumb-item active">Tool Management</li>
              </ol>
            </div>
          </div>
          <div class="col-sm-6">


            <!-- <a href="#" class="d-block">Salman Abid</a> -->
          </div>
        </div>
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
        <section class="content">

          <div class="container-fluid">
            <div id="messageContainer">
              <?php echo (isset($_GET['msgcd'])) ? "<div id='errorMsg' class='alert alert-danger'>" . $_GET['msgcd'] . "</div>" : ""; ?>
              <?php echo (isset($_GET['msgct'])) ? "<div id='successMsg' class='alert alert-success'>" . $_GET['msgct'] . "</div>" : ""; ?>
              <?php echo (isset($_GET['walet'])) ? "<div id='walltMsg' class='alert alert-success'>" . $_GET['walet'] . "</div>" : ""; ?>

            </div>
            <div class="row">
              <div class="col-md-12 mb-2">
                <a href="toolcodegenerator.php" class="btn btn-success float-right pr-3"><i
                    class="fas fa-wallet mr-1"></i> Add
                  Tool</a>
              </div>
              <div class="col-md-12 ">
                <div class="card ">
                  <div class="card-header bg-info">
                    <h3 class="card-title">My Tools </h3>

                  </div>


                  <div class="card-body">

                    <?php

                    $sql = "SELECT tool_owner,tool_id,user_id,id, (select car_name  from carname where c_id = c.tool_id ) as car from getcar c where  user_id = '" . $_SESSION['ID'] . "' ";
                    $query = mysqli_query($con, $sql);

                    ?>
                    <table id="example1" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th width="">Tool Name</th>
                          <th>Tool Code</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php

                        if (mysqli_num_rows($query) > 0) {
                          while ($row = mysqli_fetch_array($query)) {
                            ?>
                            <tr>
                              <td> <?php echo $row['tool_owner']; ?> </td>
                              <td><?php echo $row['car']; ?></td>
                              <td>
                                <div style="display: flex;">
                                  <?php
                                  echo '<a href="updatetool.php?id=' . ($row['id']) . '" class="settings mr-1 "  title="edit " data-toggle="tooltip"><i class="fa fa-edit"></i></a>';

                                  echo '<a href="deletetool.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this record?\');" title="Delete" class="delete mr-1" data-toggle="tooltip"> <i class="fa fa-trash"></i></a>';

                                  ?>
                                </div>
                              </td>
                            </tr>

                          <?php } ?>
                        </tbody>
                      <?php } ?>
                      <!-- <php } ?> -->
                    </table>


                  </div>

                </div>

              </div>


            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->





    <?php require ('includes/footer.php') ?>
    <?php require ('includes/footer_lib.php') ?>
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- ./wrapper -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="plugins/jszip/jszip.min.js"></script>
    <script src="plugins/pdfmake/pdfmake.min.js"></script>
    <script src="plugins/pdfmake/vfs_fonts.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../dist/js/demo.js"></script>
    <script>
      $(function () {
        $("#example1").DataTable({
          "responsive": true, "lengthChange": false, "autoWidth": false,
          "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
        });
      });
      $(document).ready(function () {
        // Function to remove messages after 3 seconds
        setTimeout(function () {
          $('#errorMsg, #successMsg, #walltMsg').fadeOut('slow', function () {
            $(this).remove();
          });
        }, 3000);
      });
    </script>
    <script type="">
    function confirmDelet(){
       var value =  confirm("Do you want to delete this user?");
       if(value == false){
         return false;
       }
    }
</script>

</body>

</html>