<?php
require 'connection.php';
require 'includes/check_login.php';
require 'includes/check_admin.php';

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

// Query to fetch employee data based on session ID
$sql = "SELECT e.* FROM Employee e JOIN User u ON e.userid = u.uid WHERE u.uid = :uid";

try {
    $uid = $_SESSION['ID']; // Session ID from User table

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':uid', $uid);
    $stmt->execute();

    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        echo "You are not authorized to view this page.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php require 'includes/header_lib.php'; ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <title>ENCS</title> -->
    <link rel="shortcut icon" href="dist/img/encs-logo.png">
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>

    <style>
        .generate-pdf-btn {
            border-radius: 5px;
            margin-left: 650px;
            margin-bottom: 5px;
            color: white;
        }

        .no-scroll {
            overflow: hidden;
        }

        .profile-details {
            margin-bottom: 63px;
            font-weight: 600;
            text-transform: uppercase;
            margin-inline-start: 169px;
            margin-top: -122px;
        }


        .user-name,
        .user-role {
            margin: 0;
        }

        .profile-info {
            width: 100%;
            display: grid;
            border: 1px solid transparent;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .info-item {
            margin-bottom: 18px;
        }

        .info-label {
            font-size: 18px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
            vertical-align: top;
        }

        .info-value {
            font-size: 16px;
            font-weight: 400;
            display: inline-block;
            vertical-align: top;
            margin-left: 10px;
        }

        .contact-header,
        .personal,
        .work,
        .resume {
            padding: 10px;
            border-radius: 5px;
            font-weight: 600;
            text-transform: uppercase;
            color: #fff;
            opacity: 0.9;
        }

        .contact-header {
            background-color: #007bff;
        }

        .personal {
            background-color: #155724;
        }

        .work {
            background-color: yellowgreen;
        }

        .resume {
            background-color: #ff9d00;

        }

        #about .logo {

            border-radius: 50%;
            max-width: 150px;
            max-height: 150px;
            transition: border 2s;
        }


        .about-img {
            width: 150px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fit-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
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
                                <li class="breadcrumb-item"><a id="generatePDF" class="btn btn-success">Generate PDF</a>
                                </li>
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
                        <!-- #region -->
                        <div class="card-body" id="content">
                            <div class="row">
                                <div class="col-md-12 mb-12">
                                    <div class="card">
                                        <div class="card-header bg-info">
                                            <h3 class="card-title">
                                                Profile Information
                                                <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
                                                <lord-icon src="https://cdn.lordicon.com/wrvsvaoj.json" trigger="hover"
                                                    colors="primary:#121331"
                                                    style="width:25px;height:25px; margin-bottom:-7px;">
                                                </lord-icon>

                                            </h3>
                                        </div>
                                        <div id="example1">
                                            <div class="card-body no-scroll">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-12 mb-12">
                                                            <div class="user-profile">
                                                                <div class="user-image text-center" id="about">
                                                                    <?php
                                                                    $imagePath = '';
                                                                    if (!empty($employee['profile_image'])) {
                                                                        $imagePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $employee['profile_image']);
                                                                        echo '<div class="about-img">';
                                                                        echo '<img class="logo fit-image" src="' . $imagePath . '" alt="User Image">';
                                                                    } else {
                                                                        echo '<div class="about-img">';
                                                                        echo '<img class="logo fit-image" src="includes/images/blankprofile.png" alt="User Image">';
                                                                    }
                                                                    echo '</div>';
                                                                    ?>


                                                                </div>
                                                                <div class="profile-details">
                                                                    <h1 class="user-name">
                                                                        <b><?php echo $employee['fname']; ?></b>
                                                                    </h1>
                                                                    <br>
                                                                    <h3 class="user-role" style="font-size: 20px;">
                                                                        <b><?php echo $employee['position']; ?></b>
                                                                    </h3>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mb-12">
                                                            <div class="work-info">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="personal-info">
                                                                            <h3 class="personal">Personal Information
                                                                            </h3>
                                                                            <div class="info-item">
                                                                                <span class="info-label"><b> Employee
                                                                                        ID:</b></span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['empid']; ?></span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Date of
                                                                                    Birth:</span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['bdate']; ?></span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Blood
                                                                                    Group:</span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['bloodgroup']; ?></span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Marital
                                                                                    Status:</span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['marital']; ?></span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Gender:</span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['gender']; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="contact-info">
                                                                            <h3 class="contact-header">Contact
                                                                                Information</h3>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Mobile
                                                                                    Number:</span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['mnumber']; ?></span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Emergency
                                                                                    Number:</span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['Emnumber']; ?></span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Temporary
                                                                                    Address:</span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['temp_address']; ?></span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Permanent
                                                                                    Address:</span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['permanent_address']; ?></span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Country /
                                                                                    Province:</span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['country']; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="work-info">
                                                                <div class="row">
                                                                    <div class="col-md-6 ">
                                                                        <div class="work-info">
                                                                            <h3 class="work">Work Information</h3>
                                                                            <div class="info-item">
                                                                                <span
                                                                                    class="info-label">Department:</span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['dept']; ?></span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Joining
                                                                                    Date:</span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['joindate']; ?></span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Email
                                                                                    Address:</span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['email']; ?></span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Reporting
                                                                                    To:</span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['reporting']; ?></span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span class="info-label">Source of
                                                                                    Hire:</span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['Hire']; ?></span>
                                                                            </div>
                                                                            <div class="info-item">
                                                                                <span
                                                                                    class="info-label">Experience:</span>
                                                                                <span
                                                                                    class="info-value"><?php echo $employee['experience']; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 ">
                                                                        <div class="attachments-info">
                                                                            <h3 class="resume">Work Information</h3>
                                                                            <span class="info-label">Resume Attachment
                                                                            </span>
                                                                            <p>
                                                                                <?php
                                                                                $resumePath = '';
                                                                                if (!empty($employee['resume'])) {
                                                                                    $resumePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $employee['resume']);
                                                                                    $documentName = basename($resumePath);

                                                                                    echo "<a href=\"$resumePath\" target=\"_blank\">$documentName</a>";
                                                                                }
                                                                                ?>
                                                                            </p>
                                                                            <span class="info-label">CNIC Attachment
                                                                            </span>
                                                                            <p>

                                                                                <?php
                                                                                $cnicPath = '';
                                                                                if (!empty($employee['cnic'])) {
                                                                                    $cnicPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $employee['cnic']);
                                                                                    $documentName = basename($cnicPath);

                                                                                    echo "<a href=\"$cnicPath\" target=\"_blank\">$documentName</a>";
                                                                                }
                                                                                ?>
                                                                            </p>
                                                                            <span class="info-label">Degree
                                                                                Attachment
                                                                            </span>
                                                                            <p>

                                                                                <?php
                                                                                $degreePath = '';
                                                                                if (!empty($employee['degree'])) {
                                                                                    $degreePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $employee['degree']);
                                                                                    $documentName = basename($degreePath);

                                                                                    echo "<a href=\"$cnicPath\" target=\"_blank\">$documentName</a>";
                                                                                }
                                                                                ?>
                                                                            </p>
                                                                            <span class="info-label">Experience Letter
                                                                                Attachment
                                                                            </span>
                                                                            <p>
                                                                                <?php
                                                                                $experiencePath = '';
                                                                                if (!empty($employee['experienceletter'])) {
                                                                                    $experiencePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $employee['experienceletter']);
                                                                                    $documentName = basename($experiencePath);
                                                                                    echo "<a href=\"$cnicPath\" target=\"_blank\">$documentName</a>";
                                                                                }
                                                                                ?>
                                                                            </p>

                                                                            <span class="info-label">Other Documents /
                                                                                Certifications
                                                                            </span>
                                                                            <p>
                                                                                <?php
                                                                                // Check if other documents exist
                                                                                if (!empty($employee['otherdoc'])) {
                                                                                    // Decode JSON string to get file paths
                                                                                    $otherdocPaths = json_decode($employee['otherdoc'], true);
                                                                                    $otherDir = 'C:/xampp/htdocs/passwordvault/uploadotherdocuments/';

                                                                                    // Loop through each file path
                                                                                    foreach ($otherdocPaths as $otherdocPath) {
                                                                                        // Extract the document name from the file path
                                                                                        $documentName = basename($otherdocPath);

                                                                                        // Create the URL to the view_document.php script with the file path as a query parameter
                                                                                        $viewUrl = "view_document.php?path=" . urlencode($otherDir . $documentName);

                                                                                        // Create a link to view each document in a new tab
                                                                                        echo "<a href=\"$viewUrl\" target=\"_blank\">$documentName</a><br>";
                                                                                    }
                                                                                } else {
                                                                                    // If no other documents exist, display a message
                                                                                    echo "No other documents uploaded.";
                                                                                }
                                                                                ?>
                                                                            </p>

                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- /.container-fluid -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

    <script>
        document.getElementById('generatePDF').addEventListener('click', function () {
            console.log('Button clicked');

            const element = document.getElementById('content');
            if (!element) {
                console.error('Element with ID "content" not found');
                return;
            }

            console.log('Element found:', element);

            // Use html2canvas to convert HTML to canvas
            html2canvas(element, {
                onrendered: function (canvas) {
                    console.log('Canvas rendered:', canvas);

                    const imgData = canvas.toDataURL('image/png');

                    const pdfDocDefinition = {
                        pageSize: 'A4',
                        pageOrientation: 'portrait',
                        content: [
                            {
                                image: imgData,
                                fit: [500, 842] // A4 page dimensions in points
                            }
                        ]
                    };

                    pdfMake.createPdf(pdfDocDefinition).download('Employee.pdf');
                }
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

</head>

</html>