<?php require 'connection.php'; ?>
<?php require ('includes/check_login.php'); ?>

<!-- php require 'includes/footer_wal.php'; ?> -->
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
                <h5><i class="nav-icon fas fa-user-check"></i> View Used Code</h5>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active">View Used Code</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>

        <section class="content">

          <div class="container-fluid">
            <?php echo (isset($_GET['aaa'])) ? "<div class='alert alert-danger'>" . $_GET['aaa'] . "</div>" : ""; ?>
            <?php echo (isset($_GET['ttt'])) ? "<div class='alert alert-success'>" . $_GET['ttt'] . "</div>" : ""; ?>
            <div class="row">
              <div class="col-md-12 mb-2">
                <a href="availabletoolcode.php" class="btn btn-success float-right pr-3"><i
                    class="fas fa-wallet mr-1"></i>Availble Code</a>
              </div>
              <div class="col-md-12 ">
                <div class="card ">
                  <div class="card-header bg-info">
                    <h3 class="card-title">Used Client Code</h3>
                    <!-- <a href="addrecord.php" class="btn btn-secondary float-right"><i class="material-icons">&#xE147;</i> <span>Add Wallet</span></a> -->
                  </div>
                  <!-- /.card-header -->

                  <div class="card-body">
                    <?php
                    // $sql = "SELECT * FROM `moviename` as mv where (select count(*) from getmov where movi_id = mv.m_id) != 0";
                    $sql = "SELECT mv.*, gm.code_owner
        FROM `moviename` AS mv
        INNER JOIN `getmov` AS gm ON mv.m_id = gm.movi_id
        WHERE (SELECT COUNT(*) FROM `getmov` WHERE movi_id = mv.m_id) != 0";

                    // $sql="SELECT * FROM client ";
                    $query = mysqli_query($con, $sql);

                    // while($row = mysqli_fetch_array($query)) {
                    
                    ?>


                    <table id="example1" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <!-- <th>#</th> -->
                          <th width="30%">Client</th>
                          <th width="">Client Code</th>




                          <!-- <th >Action</th> -->

                        </tr>
                      </thead>
                      <tbody>
                        <?php

                        if (mysqli_num_rows($query) > 0) {
                          while ($row = mysqli_fetch_array($query)) { ?>
                            <tr>

                              <!-- <td> <php echo $row['m_id']; ?> </td> -->

                              <td> <?php echo $row['code_owner']; ?> </td>
                              <td> <?php echo $row['movies_name']; ?> </td>





                            </tr>

                            <?php
                          } ?>
                        </tbody>
                        <?php
                        } ?>
                      <!-- <sphp } ?> -->
                    </table>


                  </div>

                </div>

              </div>


            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <!-- Control Sidebar -->



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