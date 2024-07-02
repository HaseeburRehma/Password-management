<?php require 'connection.php'; ?>
<?php require ('includes/check_login.php'); ?>
<?php require ('includes/check_admin.php'); ?>
<?php
// Establish database connection using PDO
$dsn = "mysql:host=localhost;dbname=password_vault;charset=UTF8";
$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
    exit();
}

// Count active users


/* Count inactive users
$sql_inactive_users = "SELECT COUNT(uid) AS inactive_users FROM user WHERE disable = 1";
$query_inactive_users = mysqli_query($pdo, $sql_inactive_users);
$row_inactive_users = mysqli_fetch_assoc($query_inactive_users);
$inactive_users = $row_inactive_users['inactive_users'];

*/



?>

<!-- php require 'includes/footer_wal.php'; ?> -->
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require 'includes/header_lib.php'; ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <title>ENCS</title> -->
    <link rel="shortcut icon" href="dist/img/encs-logo.png">
    <style>
        #eye:hover {
            color: blue !important;
        }

        #delete:hover {
            color: blue !important;
        }

        #update:hover {
            color: blue !important;
        }

        .username {
            position: relative;
            display: inline-block;
            font-style: italic;
            color: white;
        }

        .username::before {
            content: attr(data-username);
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            overflow: hidden;
            white-space: nowrap;
            color: white;
            animation: revealUsername 3s forwards;
        }

        .small-box.bg-warning .inner h3,
        .small-box.bg-warning .inner p {
            color: white;
        }
    </style>

<body class="hold-transition sidebar-mini layout-fixed">
    <script>
        // var state= false;
        // function toggle() {
        //   if (state) {
        //   document.getElementById("password").setAttribute("type","password");
        //   document.getElementById("eye").style.color='#7a797e';
        //   state=false;
        //   console.log('1')
        // }
        //    else {
        //     document.getElementById("password").setAttribute("type","text");
        //   document.getElementById("eye").style.color='#5887ef';
        //   state=true;
        //   console.log('2')
        //   }
        // }
        function toggle(id) {
            var x = document.getElementById("pass" + id);
            if (x.type === "password") {
                x.type = "text";

            } else {
                x.type = "password";

            }
        }
        function copyToClipboard(text) {
            var sampleTextarea = document.createElement("textarea");
            document.body.appendChild(sampleTextarea);
            sampleTextarea.value = text;
            sampleTextarea.select();
            document.execCommand("copy");
            document.body.removeChild(sampleTextarea);
        }




        function myFunction(id) {
            /* Get the text field */
            var copyText = document.getElementById("pass" + id);
            // copytoclipboard(copyText.value);
            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */

            /* Copy the text inside the text field */
            // navigator.clipboard.writeText(copyText.value);
            copyToClipboard(copyText.value);
            /* Alert the copied text */
            alert("Password Copied successfully  ");
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
                            <h5><i class="nav-icon fas fa-tachometer-alt"></i> Dashboard</h5>
                        </div>

                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <!-- <li class="breadcrumb-item active"><a href="#">My wallet</a></li> -->
                                <!-- <li class="breadcrumb-item active"><a href="#">Wallet</a></li> -->
                            </ol>
                        </div>
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

                <?php
                if (isset($_SESSION['success_msg'])) {
                    echo "<div class='alert alert-success'>" . $_SESSION['success_msg'] . "</div>";
                    unset($_SESSION['success_msg']);
                }

                ?>
                <section class="content">

                    <div class="container-fluid">
                        <?php echo (isset($_GET['msg'])) ? "<div class='alert alert-danger'>
    " . $_GET['msg'] . "<button type='button' class='close' data-dismiss='alert'>&times;</button></div>" : ""; ?>

                        <?php
                        if (isset($_GET['update'])) {
                            echo "<div id='updateMessage' class='alert alert-success'>" . $_GET['update'] . "</div>";
                            echo "<script>
            setTimeout(function(){
                document.getElementById('updateMessage').style.display = 'none';
            }, 5000); // 5000 milliseconds = 5 seconds
          </script>";
                        }
                        ?>

                        <?php
                        if (isset($_GET['add'])) {
                            echo "<div id='addMessage' class='alert alert-success'>" . $_GET['add'] . "</div>";
                            echo "<script>
            setTimeout(function(){
                document.getElementById('addMessage').style.display = 'none';
            }, 5000); // 5000 milliseconds = 5 seconds
          </script>";
                        }
                        ?>
                        <?php
                        if (isset($_GET['success'])) {
                            $successMessage = $_GET['success'];
                            echo "<div id='updateMessage' class='alert alert-success'>$successMessage</div>";
                            echo "<script>
        setTimeout(function(){
            document.getElementById('updateMessage').style.display = 'none';
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>";
                        } elseif (isset($_GET['error'])) {
                            $errorMessage = $_GET['error'];
                            echo "<div id='updateMessage' class='alert alert-danger'>$errorMessage</div>";
                        }
                        ?>

                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <a href="addemployee.php" class="btn btn-success float-right pr-3"><i
                                        class="fas fa-user-plus mr-1"></i>
                                    Create Employee</a>
                            </div>
                            <div class="col-md-12 ">
                                <div class="card ">
                                    <div class="card-header bg-info">
                                        <h3 class="card-title">User Management
                                            <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
                                            <lord-icon src="https://cdn.lordicon.com/wrvsvaoj.json" trigger="hover"
                                                colors="primary:#121331"
                                                style="width:25px;height:25px; margin-bottom:-7px;">
                                            </lord-icon>
                                        </h3>

                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <?php
                                        $sql = "SELECT * FROM employee ORDER BY empid ASC";
                                        if ($result = mysqli_query($con, $sql)) {
                                            if (mysqli_num_rows($result) > 0) {
                                                ?>
                                                <table id="example1" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th width="1%">EmpID </th>
                                                            <th width="15%">FullName</th>
                                                            <th width="5%">Email</th>
                                                            <th width="5%">Position</th>
                                                            <th width="5%">Reporting To</th>
                                                            <th width="5%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        while ($row = mysqli_fetch_array($result)) {

                                                            echo "<tr>";

                                                            echo "<td>";
                                                            echo "" . $row["empid"];
                                                            echo "</td>";

                                                            echo "<td>";


                                                            // Check if the user is newly registered and disabled
                                                

                                                            $imagePath = '';
                                                            if (!empty($row['profile_image'])) {
                                                                $imagePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $row['profile_image']);
                                                                echo '<div style="display: flex; align-items: center;">';
                                                                echo '<img src="' . $imagePath . '" alt="User Image" class="rounded-circle" style="width: 35px; height: 35px; margin-top: -3px; margin-right: 10px;">';
                                                            } else {
                                                                echo '<div>';
                                                                echo '<img src="includes/images/blankprofile.png" alt="User Image" class="rounded-circle" style="width: 35px; height: 35px; margin-top: -3px; margin-right: 10px;">';
                                                            }

                                                            echo $row['fname'];
                                                            echo '</div>';
                                                            echo "</td>";


                                                            echo "<td>" . $row['email'] . "</td>";
                                                            echo "<td>" . $row['position'] . "</td>";
                                                            echo "<td>" . $row['reporting'] . "</td>";




                                                            echo "<td>"; ?>

                                                            <?php
                                                            echo '<a href="updateemployee.php?id=' . $row['empid'] . '" class="settings"   title="update" data-toggle="tooltip"><i class="fa fa-pencil-square-o ml-1" id="update" style="color: #17a2b8; font-size: 18px; margin-inline: 12px;"></i></a>';
                                                            echo '<a href="deleteemployee.php?id=' . $row['empid'] . '" onclick="return confirm(\'Are you sure you want to delete this record?\');" title="Delete" class="delete"  data-toggle="tooltip"> <i class="fa fa-trash ml-1" id="delete" style="color: red; font-size: 18px; margin-inline: 12px;"></i></a>';
                                                            echo '<a href="viewprofile1.php?id=' . $row['empid'] . '" title="View Profile" class="view" data-toggle="tooltip"><i class="fa fa-user ml-1" style="color: green; font-size: 18px; margin-inline: 8px; margin-inline: 12px;"></i></a>';

                                                            echo "</td>";



                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>

                                            <?php } ?>
                                        <?php } ?>
                                    </div>







                                </div>
                            </div><!-- /.container-fluid -->
                        </div>
                    </div>
                </section>
                <!-- /.content -->
            </section>
        </div>

    </div>
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

        function toggleUserStatus(userId, checked) {
            var disable = checked ? 0 : 1;
            var action = checked ? 'enable' : 'disable';
            if (confirm("Are you sure you want to " + action + " this user?")) {
                $.ajax({
                    url: 'disable_user.php',
                    method: 'POST',
                    data: { userId: userId, disable: disable },
                    success: function (response) {
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        alert("Error " + action + "ing user: " + error);
                    }
                });
            } else {
                // If the user cancels, revert the checkbox to its previous state
                $('#switch_' + userId).prop('checked', !checked);
            }
        }


        /*
  
        function disableUser(userId) {
          if (confirm("Are you sure you want to disable this user?")) {
            $.ajax({
              url: 'disable_user.php',
              method: 'POST',
              data: { userId: userId, disable: 1 },
              success: function (response) {
                console.log(response);
                location.reload();
              },
              error: function (xhr, status, error) {
                alert("Error disabling user: " + error);
              }
            });
          }
        }
  
        function enableUser(userId) {
          if (confirm("Are you sure you want to enable this user?")) {
            $.ajax({
              url: 'disable_user.php',
              method: 'POST',
              data: { userId: userId, disable: 0 },
              success: function (response) {
                location.reload();
              },
              error: function (xhr, status, error) {
                alert("Error enabling user: " + error);
              }
            });
          }
        }
  
  
        */

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
</head>

</html>