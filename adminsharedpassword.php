<?php require 'connection.php'; ?>
<?php require ('includes/check_login.php'); ?>

<?php
$is_admin = false;
if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
    $is_admin = true;
}
//Establish database connection using PDO
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


error_log('Received POST data: ' . print_r($_POST, true));

if (isset($_POST['rowId'], $_POST['recipient'], $_POST['username'], $_POST['purpose'], $_POST['url'], $_POST['password'], $_POST['loggedInUserFullName'])) {

    $rowId = $_POST['rowId'];
    $recipient = $_POST['recipient'];
    $username = $_POST['username'];
    $purpose = $_POST['purpose'];
    $url = $_POST['url'];
    $password = $_POST['password'];
    $loggedInUserFullName = $_POST['loggedInUserFullName'];

    try {

        $sql = "INSERT INTO sharedpassword (user_name, purpose, url, password, receiver_name, rowId, loggedInUserFullName) 
        VALUES (:username, :purpose, :url, :password, :recipient, :rowId, :loggedInUserFullName)";
        $stmt = $pdo->prepare($sql);
        $execute_success = $stmt->execute([
            'username' => $username,
            'purpose' => $purpose,
            'url' => $url,
            'password' => $password,
            'recipient' => $recipient,
            'rowId' => $rowId,
            'loggedInUserFullName' => $loggedInUserFullName,
        ]);


        if ($execute_success) {
            // Insert notification into notifications table
            try {
                $notification_message = "You received a password from $loggedInUserFullName";
                $sql = "INSERT INTO notifications (recipient_id, message, created_at) VALUES (:recipient_id, :message, NOW())";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'recipient_id' => $recipient,
                    'message' => $notification_message,
                ]);
            } catch (PDOException $e) {
                echo "Error inserting notification: " . $e->getMessage();
            }


            $_SESSION['success_message'] = "Password shared successfully!";
            header("Location: clientwallet.php?shared_success=1");
        } else {
            echo "Failed to insert data";
        }

    } catch (PDOException $e) {

        echo "Error: " . $e->getMessage();
    }

}




?>

<!DOCTYPE html>
<html lang="en">
<?php require 'includes/header_lib.php'; ?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="shortcut icon" href="dist/img/encs-logo.png">



<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="dist/img/encs-loader.png" alt="AdminLTELogo" height="60" width="60">
        </div>


        <!--navbar -->
        <?php require 'includes/navbar.php' ?>
        <!-- /.sidebar -->
        </aside>

        <?php require 'includes/left_panel.php' ?>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">

                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h5><i class="nav-icon fas fa-share"></i> Shared Password</h5>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">SharedPassword</li>
                            </ol>
                        </div>
                    </div>

                    <?php
                    if (isset($_GET['deleted_success'])) {
                        echo '<div id=dangerAlert class="alert alert-danger alert-dismissible fade show" role="alert">Password record deleted successfully!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                    }
                    ?>
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

            <section class="content ">
                <div class="container-fluid">
                    <div class="col-md-12 ">

                        <div class="card ">
                            <div class="card-header bg-info">
                                <h3 class="card-title">Shared Passwords</h3>
                            </div>
                            <div class="card-body">
                                <?php

                                $sql = "SELECT * FROM sharedpassword";
                                $stmt = $pdo->query($sql);
                                $sharedPasswords = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                ?>


                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="15%">UserName</th>
                                            <th width="15%">Purpose</th>
                                            <th width="15%">Password</th>
                                            <th width="10%">URL/IP Address</th>
                                            <th width="10%">Sender</th>
                                            <th width="10%">Receiver</th>
                                            <th width="15%">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($stmt->rowCount() > 0) {
                                            foreach ($sharedPasswords as $row) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $row['user_name']; ?></td>
                                                    <td><?php echo $row['purpose']; ?></td>


                                                    <td id="password">
                                                        <input type="password" id="pass_<?php echo $row['id']; ?>" readonly
                                                            value="<?php echo $row['password']; ?>"
                                                            style="background-color: transparent; border: none; outline: none; color: inherit;">

                                                    </td>


                                                    <td><?php echo $row['url']; ?></td>
                                                    <td><?php echo $row['loggedInUserFullName']; ?></td>
                                                    <td>
                                                        <?php
                                                        // Fetch receiver's full name based on the receiver_name ID
                                                        $receiver_id = $row['receiver_name'];
                                                        $sql_receiver = "SELECT full_name FROM user WHERE uid = :receiver_id";
                                                        $stmt_receiver = $pdo->prepare($sql_receiver);
                                                        if ($stmt_receiver) {
                                                            $stmt_receiver->execute(['receiver_id' => $receiver_id]);
                                                            $receiver = $stmt_receiver->fetch(PDO::FETCH_ASSOC);
                                                            if ($receiver) {
                                                                echo $receiver['full_name'];
                                                            } else {
                                                                echo "Receiver not found";
                                                            }
                                                        } else {
                                                            echo "Error in query";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>


                                                        <span style="display: flex; align-items: center;">
                                                            <?php
                                                            echo '<a href="deletesharepass.php?id=' . $row['id'] . '&deleted_success=1" onclick="return confirm(\'Are you sure you want to delete this record?\');" title="Delete" class="delete" data-toggle="tooltip"><i class="fa fa-trash" id="delete" style="color: red; font-size: 18px; margin-inline-end: 8px;"></i></a>';
                                                            ?>
                                                            <i type="button" class="far fa-copy"
                                                                onclick="copyPassword(<?php echo $row['id']; ?>)"
                                                                aria-hidden="true" title="copy"
                                                                id="copy<?php echo $row['id']; ?>"
                                                                data-original-title="Copy to clipboard"
                                                                style="color: #17a2b8; font-size: 18px; margin-inline-start: 8px; margin-inline-end: 8px;"></i>
                                                            <i class="fas fa-eye" aria-hidden="true" title="view"
                                                                id="eye<?php echo $row['id']; ?>"
                                                                onclick="togglePassword(<?php echo $row['id']; ?>)"
                                                                style="color: #17a2b8; font-size: 18px; margin-inline-start: 8px;"></i>
                                                        </span>





                                                    </td>


                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="5">No shared passwords available</td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->

                <!-- /.container-fluid -->
            </section>
        </div>
    </div>


    <script>
        function togglePassword(id) {
            var passwordField = document.getElementById('pass_' + id);
            var eyeIcon = document.getElementById('eye' + id);
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // Function to copy password to clipboard
        function copyPassword(id) {
            var passwordField = document.getElementById('pass_' + id);
            passwordField.select();
            document.execCommand('copy');

            var copyIcon = document.getElementById('copy' + id);
            copyIcon.setAttribute('data-original-title', 'Copied!');

            setTimeout(function () {
                copyIcon.setAttribute('data-original-title', 'Copy to clipboard');
            }, 1000);
        }
        $(document).ready(function () {
            setTimeout(function () {
                $("#dangerAlert").alert('close');
            }, 3000);
        });
    </script>
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


</body>

</html>