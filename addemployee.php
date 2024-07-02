<?php
require 'connection.php';
require 'includes/check_login.php';

$is_admin = false;
if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
    $is_admin = true;
}

$dsn = "mysql:host=localhost;dbname=password_vault;charset=UTF8";
$username = "root";
$password = "";

// Directory paths for file uploads
$profileImageDir = 'C:/xampp/htdocs/passwordvault/uploadimage/';
$resumeDir = 'C:/xampp/htdocs/passwordvault/uploadresume/';
$cnicDir = 'C:/xampp/htdocs/passwordvault/uploadcnic/';
$degreeDir = 'C:/xampp/htdocs/passwordvault/uploaddegree/';
$experienceDir = 'C:/xampp/htdocs/passwordvault/uploadexperience/';
$otherDir = 'C:/xampp/htdocs/passwordvault/uploadotherdocuments/';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve form data
        $userid = $_POST['userid'];
        $fname = $_POST['fname'];
        $bloodgroup = $_POST['bloodgroup'];
        $bdate = $_POST['bdate'];
        $marital = $_POST['marital'];
        $gender = $_POST['gender'];
        $mnumber = $_POST['mnumber'];
        $Emnumber = $_POST['Emnumber'];
        $temp_address = $_POST['temp_address'];
        $permanent_address = $_POST['permanent_address'];
        $country = $_POST['country'];
        $dept = $_POST['dept'];
        $joindate = $_POST['joindate'];
        $email = $_POST['email'];
        $position = $_POST['position'];
        $accountnumber = $_POST['accountnumber'];
        $reporting = $_POST['reporting'];
        $Hire = $_POST['Hire'];
        $experience = $_POST['experience'];

        $maxFileSize = 2 * 1024 * 1024; // 2MB in bytes

        // Handle profile image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $profileImageFile = $profileImageDir . basename($_FILES['profile_image']['name']);
            if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $profileImageFile)) {
                echo "Failed to upload profile image.";
                exit;
            }
        } else {
            $profileImageFile = null;
        }

        // Handle resume upload
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
            if ($_FILES['resume']['size'] > $maxFileSize) {
                echo "File size exceeds maximum limit (2MB).";
                exit;
            }
            $resumeFile = $resumeDir . basename($_FILES['resume']['name']);
            if (!move_uploaded_file($_FILES['resume']['tmp_name'], $resumeFile)) {
                echo "Failed to upload resume document.";
                exit;
            }
        } else {
            $resumeFile = null;
        }

        // Handle CNIC upload
        if (isset($_FILES['cnic']) && $_FILES['cnic']['error'] === UPLOAD_ERR_OK) {
            if ($_FILES['cnic']['size'] > $maxFileSize) {
                echo "File size exceeds maximum limit (2MB).";
                exit;
            }
            $cnicFile = $cnicDir . basename($_FILES['cnic']['name']);
            if (!move_uploaded_file($_FILES['cnic']['tmp_name'], $cnicFile)) {
                echo "Failed to upload CNIC document.";
                exit;
            }
        } else {
            $cnicFile = null;
        }

        // Handle degree upload
        if (isset($_FILES['degree']) && $_FILES['degree']['error'] === UPLOAD_ERR_OK) {
            if ($_FILES['degree']['size'] > $maxFileSize) {
                echo "File size exceeds maximum limit (2MB).";
                exit;
            }
            $degreeFile = $degreeDir . basename($_FILES['degree']['name']);
            if (!move_uploaded_file($_FILES['degree']['tmp_name'], $degreeFile)) {
                echo "Failed to upload degree document.";
                exit;
            }
        } else {
            $degreeFile = null;
        }

        // Handle experience letter upload
        if (isset($_FILES['experienceletter']) && $_FILES['experienceletter']['error'] === UPLOAD_ERR_OK) {
            if ($_FILES['experienceletter']['size'] > $maxFileSize) {
                echo "File size exceeds maximum limit (2MB).";
                exit;
            }
            $experienceFile = $experienceDir . basename($_FILES['experienceletter']['name']);
            if (!move_uploaded_file($_FILES['experienceletter']['tmp_name'], $experienceFile)) {
                echo "Failed to upload experience letter.";
                exit;
            }
        } else {
            $experienceFile = null;
        }

        // Handle other documents upload
        $filePaths = [];
        if (isset($_FILES['otherdoc']) && is_array($_FILES['otherdoc']['name'])) {
            $otherdocFiles = $_FILES['otherdoc'];
            for ($i = 0; $i < count($otherdocFiles['name']); $i++) {
                if ($otherdocFiles['error'][$i] === UPLOAD_ERR_OK) {
                    $otherdocFile = $otherDir . basename($otherdocFiles['name'][$i]);
                    if (!move_uploaded_file($otherdocFiles['tmp_name'][$i], $otherdocFile)) {
                        echo "Failed to upload file " . basename($otherdocFiles['name'][$i]) . ".";
                        exit();
                    } else {
                        $filePaths[] = basename($otherdocFiles['name'][$i]);
                    }
                } else {
                    echo "Error uploading file " . basename($otherdocFiles['name'][$i]) . ".";
                    exit();
                }
            }
            // Encode only the file names into JSON
            $filePathsJson = json_encode($filePaths);
        } else {
            $filePathsJson = json_encode([]);
        }

        // Debugging: Check the $filePathsJson value
        echo "File paths JSON: " . $filePathsJson;

        // SQL insert statement
        $sql = "INSERT INTO Employee (userid, fname, bloodgroup, bdate, marital, gender, mnumber, Emnumber, temp_address, permanent_address, country, dept, joindate, email, position, accountnumber, reporting, Hire, experience, profile_image, resume, cnic, degree, experienceletter, otherdoc)
                VALUES (:userid, :fname, :bloodgroup, :bdate, :marital, :gender, :mnumber, :Emnumber, :temp_address, :permanent_address, :country, :dept, :joindate, :email, :position, :accountnumber, :reporting, :Hire, :experience, :profile_image, :resume, :cnic, :degree, :experienceletter, :otherdoc)";

        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':userid', $userid);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':bloodgroup', $bloodgroup);
        $stmt->bindParam(':bdate', $bdate);
        $stmt->bindParam(':marital', $marital);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':mnumber', $mnumber);
        $stmt->bindParam(':Emnumber', $Emnumber);
        $stmt->bindParam(':temp_address', $temp_address);
        $stmt->bindParam(':permanent_address', $permanent_address);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':dept', $dept);
        $stmt->bindParam(':joindate', $joindate);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':accountnumber', $accountnumber);
        $stmt->bindParam(':reporting', $reporting);
        $stmt->bindParam(':Hire', $Hire);
        $stmt->bindParam(':experience', $experience);
        $stmt->bindParam(':profile_image', $profileImageFile);
        $stmt->bindParam(':resume', $resumeFile);
        $stmt->bindParam(':cnic', $cnicFile);
        $stmt->bindParam(':degree', $degreeFile);
        $stmt->bindParam(':experienceletter', $experienceFile);
        $stmt->bindParam(':otherdoc', $filePathsJson);

        // Execute the statement
        $stmt->execute();

        // Redirect to dashboard after adding
        header("Location: userdashboard.php?success=Employee added successfully");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
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
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="dist/img/vault.png" alt="AdminLTELogo" height="60" width="60">
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
                            <h5><i class="nav-icon fas fa-share"></i> ADD Employee Form</h5>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">ADD Employee Form</li>
                            </ol>
                        </div>
                    </div>
                    <script>
                    function clearForm() {
                        document.getElementById("empform").reset();

                    }
                    </script>
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

            .form-row {
                margin-bottom: 15px;
                margin-inline-start: auto;
                /* Adjust the margin as needed */
            }

            .form-group {
                margin-bottom: 10px;
                /* Reset the default margin */
            }

            .input-group {
                width: 100%;
                /* Ensure the input group spans the full width */
            }

            .attachment-section {
                margin-top: 36px;
            }

            .attachment {
                margin-bottom: 10px;
            }

            .attachment-label {
                display: inline-block;
                margin-bottom: 5px;
                font-weight: bold;
            }

            input[type="file"] {
                display: none;
            }

            .file-upload-message {
                position: relative;
                /* Added to position the cross icon */
                font-size: 0.9em;

                display: inline-block;
                margin-left: 10px;
                padding: 5px 30px 5px 5px;
                /* Adjusted for cross icon space */


                transition: all 0.3s ease;
            }

            .file-upload-message:hover {
                color: #333;
                border-color: #ccc;
                background-color: #fff;
            }

            .remove-file {
                position: absolute;
                /* Positioned inside the file-upload-message */
                top: 50%;
                /* Vertically centered */
                right: 0px;
                /* Distance from the right border */
                transform: translateY(-50%);
                /* Centered vertically */
                display: inline-block;
                cursor: pointer;
                color: #fff;
                font-weight: bold;
                border: 1px solid #d9534f;
                padding: 4px 8px;
                border-radius: 50%;
                background-color: #d9534f;
                font-size: 1em;
                line-height: 1.0;
                transition: background-color 0.3s ease, border-color 0.3s ease;
            }

            .remove-file:hover {
                background-color: #c9302c;
                border-color: #ac2925;
            }

            /* Custom styling for the file input */
            .custom-file-input {
                cursor: pointer;
                width: 100%;
                height: 30px;
                background-color: #f7f7f7;
                border: 1px solid #ccc;
                border-radius: 5px;
                padding: 5px 10px;
            }

            .custom-file-input:hover {
                background-color: #e0e0e0;
            }

            .custom-file-input:focus {
                outline: none;
                border-color: #66afe9;
                box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
            }
            </style>

            <?php
            $update = '';
            // Initialize variables
            $profileImageName = '';
            $cnicName = '';
            $resumeName = '';
            $degreeName = '';
            $experienceName = '';
            $fileNames = '';

            // Check if $update is set to avoid undefined variable warnings
            if ($update) {
                // Handle profile image
                if (isset($update['profileImage']) && !empty($update['profileImage'])) {
                    $profileImageName = basename($update['profileImage']);
                }

                // Handle CNIC
                if (isset($update['cnic']) && !empty($update['cnic'])) {
                    $cnicName = basename($update['cnic']);
                }

                // Handle resume file
                if (isset($update['resume']) && !empty($update['resume'])) {
                    $resumeName = basename($update['resume']);
                }

                // Handle degree file
                if (isset($update['degree']) && !empty($update['degree'])) {
                    $degreeName = basename($update['degree']);
                }

                // Handle experience letter file
                if (isset($update['experienceletter']) && !empty($update['experienceletter'])) {
                    $experienceName = basename($update['experienceletter']);
                }

                // Handle other documents
                if (isset($update['otherdoc']) && !empty($update['otherdoc'])) {
                    $fileNames = implode(', ', json_decode($update['otherdoc'], true));
                }
            }
            ?>


            <section class="content">
                <div class="container-fluid">
                    <form action="" method="post" onsubmit="return clearform()" id="empform"
                        enctype="multipart/form-data">

                        <!-- left column -->

                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Basic Information</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-user" aria-hidden="true"></i>
                                            </span>
                                            <label class="control-label">User Name*</label>
                                            <div class="input-group">
                                                <select name="userid" class="form-control" tabindex="1" required>
                                                    <option value="">Select user</option>


                                                    <?php
                                                    $pdo = new PDO($dsn, $username, $password);
                                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                                    $sql = "SELECT * FROM user";
                                                    $stmt = $pdo->prepare($sql);
                                                    $stmt->execute();
                                                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($users as $row) {
                                                        echo "<option value='{$row['uid']}'>{$row['user_name']}</option>
                                                    ";
                                                    } ?>

                                                </select>

                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-user" aria-hidden="true"></i>
                                            </span>
                                            <label class="control-label">Full Name*</label>
                                            <div class="input-group">

                                                <input type="text" name="fname" title="Full Name" class="form-control"
                                                    placeholder="Full Name" required="">

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-medical" aria-hidden="true"></i>
                                            </span>
                                            <label class="control-label">Blood Group*</label>
                                            <div class="input-group">

                                                <input type="text" name="bloodgroup" title="Blood Group"
                                                    class="form-control" placeholder="Blood Group" required="">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </span>
                                            <label class="control-label">Birth Date*</label>
                                            <div class="input-group">
                                                <input type="date" id="Birthdate" title="Birth Date" name="bdate"
                                                    placeholder="Birth Date" class="form-control" required="">

                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user" aria-hidden="true"></i>
                                                </span>
                                                <label class="control-label">Marital*</label>
                                                <div class="input-group">
                                                    <select name="marital" title="Marital" required=""
                                                        class="form-control" style="text-transform: capitalize;">
                                                        <option value="">-- Select Marital --</option>
                                                        <option value="Married"
                                                            <?php echo isset($marital) && $marital == "Married" ? "selected" : ""; ?>>
                                                            Married</option>
                                                        <option value="UnMarried"
                                                            <?php echo isset($marital) && $marital == "UnMarried" ? "selected" : ""; ?>>
                                                            UnMarried
                                                        </option>
                                                        <!-- Add marital options here -->
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user" aria-hidden="true"></i>
                                                </span>
                                                <label class="control-label">Gender*</label>
                                                <div class="input-group">
                                                    <select name="gender" title="Gender" class="form-control"
                                                        required="" style=" text-transform: capitalize;">
                                                        <option value="">-- Select Gender --</option>
                                                        <option value="Male"
                                                            <?php echo isset($gender) && $gender == "Male" ? "selected" : ""; ?>>
                                                            Male</option>
                                                        <option value="Female"
                                                            <?php echo isset($gender) && $gender == "Female" ? "selected" : ""; ?>>
                                                            Female</option>
                                                        <!-- Add gender options here -->
                                                        <!-- Add gender options here -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="attachment-section">
                                            <div class="attachment">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                </span>
                                                <label for="ProfileAttachment" class="attachment-label">profile
                                                    Attachment:</label>
                                                <input type="file" id="ProfileAttachment" name="profile_image"
                                                    accept=".jpeg, .PNG" multiple
                                                    onchange="updateFileNameDisplay(this)">
                                                <span
                                                    class="file-upload-message"><?php echo htmlspecialchars($profileImageName ? $profileImageName :'Upload JPEG or PNG files');?>
                                                    <?php if ($profileImageName): ?>
                                                    <span class="remove-file"
                                                        onclick="removeUploadedFile('profile_image')">×</span>
                                                    <?php endif; ?>
                                                </span>
                                                </span>
                                            </div>
                                        </div>


                                    </div>




                                </div>






                                <div class="col-md-6">


                                    <div class="card-header">
                                        <h3 class="card-title" style="margin-top: -44px; color: white;">Contact Details
                                        </h3>
                                    </div>


                                    <div class="card-body" style="margin-top: -24px;">
                                        <div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-mobile" aria-hidden="true"></i>
                                            </span>
                                            <label class="control-label">Mobile Number*</label>
                                            <div class="input-group">

                                                <input type="number" name="mnumber" title="Mobile Number"
                                                    class="form-control" placeholder="Mobile Number" min="10"
                                                    maxlength="10"
                                                    onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                                    required="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-mobile" aria-hidden="true"></i>
                                            </span>
                                            <label class="control-label">Emergency Contact Number*</label>
                                            <div class="input-group">

                                                <input type="number" name="Emnumber" title=" Emergency Mobile Number"
                                                    class="form-control" placeholder=" Emergency Mobile Number" min="10"
                                                    maxlength="10"
                                                    onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                                    required="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-pencil " aria-hidden="true"></i>
                                            </span>
                                            <label class="control-label"> Temporary Address*</label>
                                            <div class="input-group">

                                                <input type="text" name="temp address" title=" temp Address "
                                                    class="form-control" placeholder=" Temp Address Line " required="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-pencil " aria-hidden="true"></i>
                                            </span>
                                            <label class="control-label"> Permanent Address*</label>
                                            <div class="input-group">

                                                <input type="text" name="permanent address" title=" permanent Address "
                                                    class="form-control" placeholder=" permananet Address Line "
                                                    required="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-globe" aria-hidden="true"></i>
                                            </span>
                                            <label class="control-label">Country / Province *</label>
                                            <div class="input-group">
                                                <input type="text" name="country" title=" country " class="form-control"
                                                    placeholder=" Country / Province " required="">



                                            </div>
                                        </div>

                                        <div class="attachment-section">
                                            <div class="attachment">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                </span>
                                                <label for="cnicAttachment" class="attachment-label">CNIC
                                                    Attachment:</label>
                                                <input type="file" id="cnicAttachment" name="cnic" accept=".pdf,.doc"
                                                    multiple onchange="updateFileNameDisplay(this)">
                                                <span class="file-upload-message"><?php echo htmlspecialchars($cnicName ? $cnicName :'Upload PDF or DOC files (Max
                                                    2MB)');?>
                                                    <?php if ($cnicName): ?>
                                                    <span class="remove-file"
                                                        onclick="removeUploadedFile('cnic')">×</span>
                                                    <?php endif; ?>
                                                </span>
                                                </span>
                                            </div>
                                        </div>




                                    </div>




                                </div>
                            </div>



                            <!-- general form elements -->
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Employement Details</h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- /.card-header -->
                                        <!-- form start -->

                                        <div class="card-body">
                                            <div class="form-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-building" aria-hidden="true"></i>
                                                </span>
                                                <label class="control-label"> Department*</label>
                                                <div class="input-group">

                                                    <input type="text" name="dept" title=" dept " class="form-control"
                                                        placeholder=" dept " required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </span>
                                                <label class="control-label">Joining Date*</label>

                                                <div class="input-group">

                                                    <input type="date" id="JoinDate" title="Join Date" name="joindate"
                                                        placeholder="Join Date" class="form-control" required="">
                                                </div>



                                            </div>
                                            <div class="form-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                                </span>
                                                <label class="control-label">Email*</label>
                                                <div class="input-group">

                                                    <input type="email" name="email" title="Email" class="form-control"
                                                        placeholder="Email Address" required="">
                                                </div>

                                            </div>

                                            <div class="form-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-language" aria-hidden="true"></i>
                                                </span>

                                                <label class="control-label">Position*</label>
                                                <div class="input-group">

                                                    <input type="text" name="position" title=" Position "
                                                        class="form-control" placeholder=" Position " required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-bank" aria-hidden="true"></i>
                                                </span>

                                                <label class="control-label">Account Number*</label>
                                                <div class="input-group">

                                                    <input type="text" name="accountnumber" title=" Account Number "
                                                        class="form-control" placeholder=" Account Number " required="">
                                                </div>
                                            </div>



                                        </div>


                                    </div>


                                    <div class="col-md-6">
                                        <!-- general form elements -->

                                        <div class="card-header">
                                            <h3 class="card-title" style="margin-top: -44px; color: white;">Hirarchy
                                                Information</h3>
                                        </div>
                                        <div class="card-body" style="margin-top: -24px;">
                                            <div class="form-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user" aria-hidden="true"></i>
                                                </span>
                                                <label class="control-label"> Reporting Manager*</label>
                                                <div class="input-group">

                                                    <input type="text" name="reporting" title=" Reporting "
                                                        class="form-control" placeholder="Reporting Manager"
                                                        required="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-briefcase" aria-hidden="true"></i>
                                                </span>
                                                <label class="control-label"> Source of Hire*</label>
                                                <div class="input-group">

                                                    <input type="text" name="Hire" title="Hire " class="form-control"
                                                        placeholder="Source of Hire" required="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                </span>
                                                <label class="control-label"> Experience*</label>
                                                <div class="input-group">

                                                    <input type="text" name="experience" title=" Experience "
                                                        class="form-control" placeholder="Experience" required="">
                                                </div>
                                            </div>


                                            <div class="attachment-section">

                                                <div class="attachment">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                    </span>
                                                    <label for="resumeAttachment" class="attachment-label">Resume
                                                        Attachment:</label>
                                                    <input type="file" id="resumeAttachment" name="resume"
                                                        accept=".pdf,.doc" onchange="updateFileNameDisplay(this)">
                                                    <span class="file-upload-message"><?php echo htmlspecialchars($resumeName ? $resumeName :'Upload PDF or DOC files (Max
                                                        2MB)'); ?>
                                                        <?php if ($resumeName): ?>
                                                        <span class="remove-file"
                                                            onclick="removeUploadedFile('cnic')">×</span>
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="attachment-section">

                                                <div class="attachment">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                    </span>
                                                    <label for="degreeAttachment" class="attachment-label">Degree
                                                        Attachment:</label>
                                                    <input type="file" id="degreeAttachment" name="degree"
                                                        accept=".pdf,.doc" onchange="updateFileNameDisplay(this)">
                                                    <span class="file-upload-message"><?php echo htmlspecialchars($degreeName ? $degreeName : 'Upload PDF or DOC files (Max
                                                        2MB)'); ?>

                                                        <?php if ($degreeName): ?>
                                                        <span class="remove-file"
                                                            onclick="removeUploadedFile('degree')">×</span>
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="attachment-section">

                                                <div class="attachment">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                    </span>
                                                    <label for="experienceAttachment"
                                                        class="attachment-label">Experience
                                                        Letter:</label>
                                                    <input type="file" id="experienceAttachment" name="experienceletter"
                                                        accept=".pdf,.doc" onchange="updateFileNameDisplay(this)">
                                                    <span class="file-upload-message"><?php echo htmlspecialchars($experienceName ? $experienceName :'Upload PDF or DOC files (Max
                                                        2MB)');?>
                                                        <?php if ($experienceName): ?>
                                                        <span class="remove-file"
                                                            onclick="removeUploadedFile('experience')">×</span>
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="attachment-section">
                                                <div class="attachment">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                                    </span>
                                                    <label for="otherAttachment" class="attachment-label">Other's
                                                        Attachment:</label>
                                                    <input type="file" id="otherAttachment" name="otherdoc[]"
                                                        accept=".pdf,.doc" multiple
                                                        onchange="updateFileNameDisplay(this)">
                                                    <span
                                                        class="file-upload-message"><?php echo htmlspecialchars($fileNames ? $fileNames :'Upload PDF, DOC files');?>
                                                        <?php if ($fileNames): ?>
                                                        <span class="remove-file"
                                                            onclick="removeUploadedFile('otherdoc')">×</span>
                                                        <?php endif; ?>

                                                    </span>
                                                </div>
                                            </div>

                                            <div class="form-group" style="text-align: right;">
                                                <button type="reset" class="btn btn-default">Reset</button>
                                                <button type="submit" name="submit" value="save" class="btn btn-primary"
                                                    style="margin: 9px;">Submit</button>
                                            </div>

                                        </div>



                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </section>




        </div>


        <!-- /.card -->
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

        setTimeout(function() {
            copyIcon.setAttribute('data-original-title', 'Copy to clipboard');
        }, 1000);
    }
    $(document).ready(function() {
        setTimeout(function() {
            $("#dangerAlert").alert('close');
        }, 3000);
    });
    </script>
    <script>
    var OneStepBack;

    function nmac(val, e) {
        if (e.keyCode != 8) {
            if (val.length == 2)
                document.getElementById("mac").value = val + "-";
            if (val.length == 5)
                document.getElementById("mac").value = val + "-";
            if (val.length == 8)
                document.getElementById("mac").value = val + "-";
            if (val.length == 11)
                document.getElementById("mac").value = val + "-";
            if (val.length == 14) {
                document.getElementById("mac").value = val + "-";
            }
        }
    }

    function nmac1(val, e) {
        if (e.keyCode == 32) {
            return false;
        }

        if (e.keyCode != 8) {

            if (val.length == 2)
                document.getElementById("mac").value = val + "-";
            if (val.length == 5)
                document.getElementById("mac").value = val + "-";
            if (val.length == 8)
                document.getElementById("mac").value = val + "-";
            if (val.length == 11)
                document.getElementById("mac").value = val + "-";
            if (val.length == 14) {
                document.getElementById("mac").value = val + "-";
            }

            if (val.length == 17) {
                return false;
            }
        }
    }
    </script>




    <script>
    function updateFileNameDisplay(input) {
        var files = input.files;
        var fileNames = [];
        for (var i = 0; i < files.length; i++) {
            fileNames.push(files[i].name);
        }
        var siblingSpan = input.nextElementSibling;
        if (fileNames.length > 0) {
            siblingSpan.textContent = fileNames.join(', ');

            // Create and append the remove button if it doesn't exist
            if (!siblingSpan.querySelector('.remove-file')) {
                var removeButton = document.createElement('span');
                removeButton.className = 'remove-file';
                removeButton.textContent = '×';
                removeButton.onclick = function() {
                    removeUploadedFile(input.id);
                };
                siblingSpan.appendChild(removeButton);
            }
        } else {
            siblingSpan.textContent = 'Upload PDF or DOC files (Max 2MB)';

            // Remove the remove button if no files are selected
            var removeButton = siblingSpan.querySelector('.remove-file');
            if (removeButton) {
                siblingSpan.removeChild(removeButton);
            }
        }
    }

    function removeUploadedFile(inputId) {
        var input = document.getElementById(inputId);
        var siblingSpan = input.nextElementSibling;

        // Clear the file input
        input.value = '';

        // Reset the file name display
        siblingSpan.textContent = 'Upload PDF or DOC files (Max 2MB)';

        // Remove the remove button
        var removeButton = siblingSpan.querySelector('.remove-file');
        if (removeButton) {
            siblingSpan.removeChild(removeButton);
        }
    }
    </script>

    <?php require ('includes/footer.php') ?>
    <?php require ('includes/footer_lib.php') ?>
    <script src="plugins/jquery/jquery.min.js"></script>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
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
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
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