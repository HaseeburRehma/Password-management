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
// Directory where profile images will be uploaded
$profileImageDir = 'C:/xampp/htdocs/passwordvault/uploadimage/';
// Directory where resumes will be uploaded
$resumeDir = 'C:/xampp/htdocs/passwordvault/uploadresume/';
// Directory where CNICs will be uploaded
$cnicDir = 'C:/xampp/htdocs/passwordvault/uploadcnic/';


$degreeDir = 'C:/xampp/htdocs/passwordvault/uploaddegree/';

$experienceDir = 'C:/xampp/htdocs/passwordvault/uploadexperience/';

$otherDir = 'C:/xampp/htdocs/passwordvault/uploadotherdocuments/';

// Fetch users from the user table

// Check if empid parameter is set

if (isset($_GET['id'])) {
    $ID = $_GET['id'];

    // Ensure $ID is a valid integer
    if (!ctype_digit($ID)) {
        echo "You are not authorized to access this page";
        exit();
    }

    // Check if employee exists in the database
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM Employee WHERE empid = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $ID);
        $stmt->execute();

        $update = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$update) {
            echo "Employee not found";
            exit();
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "Employee ID not provided.";
    exit();
}

// Process form submission
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
            // Check if the 'profile_image' key exists in the $update array
            $profileImageFile = isset($update['profile_image']) ? $update['profile_image'] : null; // Keep the existing image
        }

        // Handle resume upload
        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
            if ($_FILES['cnic']['size'] > $maxFileSize) {
                echo "File size exceeds maximum limit (2MB).";
                exit;
            }
            $resumeFile = $resumeDir . basename($_FILES['resume']['name']);
            if (!move_uploaded_file($_FILES['resume']['tmp_name'], $resumeFile)) {
                echo "Failed to upload resume document.";
                exit;
            }
        } else {
            // Check if the 'profile_image' key exists in the $update array
            $resumeFile = isset($update['resume']) ? $update['resume'] : null; // Keep the existing image
        }

        // Handle CNIC upload
        if (isset($_FILES['cnic']) && $_FILES['cnic']['error'] === UPLOAD_ERR_OK) {
            if ($_FILES['cnic']['size'] > $maxFileSize) {
                echo "File size exceeds maximum limit (2MB).";
                exit;
            }
            $cnicFile = $cnicDir . basename($_FILES['cnic']['name']);
            if (!move_uploaded_file($_FILES['cnic']['tmp_name'], $cnicFile)) {
                echo "Failed to upload cnic document.";
                exit;
            }
        } else {
            // Check if the 'profile_image' key exists in the $update array
            $cnicFile = isset($update['cnic']) ? $update['cnic'] : null; // Keep the existing image
        }
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
            // Check if the 'profile_image' key exists in the $update array
            $degreeFile = isset($update['degree']) ? $update['degree'] : null; // Keep the existing image
        }

        if (isset($_FILES['experienceletter']) && $_FILES['experienceletter']['error'] === UPLOAD_ERR_OK) {
            if ($_FILES['experienceletter']['size'] > $maxFileSize) {
                echo "File size exceeds maximum limit (2MB).";
                exit;
            }
            $experienceFile = $experienceDir . basename($_FILES['experienceletter']['name']);
            if (!move_uploaded_file($_FILES['experienceletter']['tmp_name'], $experienceFile)) {
                echo "Failed to upload cnic document.";
                exit;
            }
        } else {
            // Check if the 'profile_image' key exists in the $update array
            $experienceFile = isset($update['experienceletter']) ? $update['experienceletter'] : null; // Keep the existing image
        }

        $filePaths = [];

        if (isset($_FILES['otherdoc']) && !empty($_FILES['otherdoc']['name'][0])) {
            $otherdocFiles = $_FILES['otherdoc'];

            for ($i = 0; $i < count($otherdocFiles['name']); $i++) {
                if ($otherdocFiles['error'][$i] === UPLOAD_ERR_OK) {
                    $otherdocFile = $otherDir . basename($otherdocFiles['name'][$i]);
                    if (!move_uploaded_file($otherdocFiles['tmp_name'][$i], $otherdocFile)) {
                        echo "Failed to upload file " . basename($otherdocFiles['name'][$i]) . ".";
                        exit();
                    } else {
                        // Replace '\/' with '/' in the file path
                        $filePath = str_replace('\/', '/', $otherdocFile);
                        $filePaths[] = $filePath;
                    }
                } else {
                    echo "Error uploading file " . basename($otherdocFiles['name'][$i]) . ".";
                    exit();
                }
            }

            // Encode only the file names into JSON with correct file paths
            $fileNames = [];
            foreach ($filePaths as $path) {
                $fileNames[] = basename($path); // Extract only the file name without path
            }
            $filePathsJson = json_encode($fileNames);
        } else {
            $filePathsJson = isset($update['otherdoc']) ? $update['otherdoc'] : null;
        }



        // SQL update statement
        $sql = "UPDATE Employee SET 
            userid = :userid,
            fname = :fname,
            bloodgroup = :bloodgroup,
            bdate = :bdate,
            marital = :marital,
            gender = :gender,
            mnumber = :mnumber,
            Emnumber = :Emnumber,
            temp_address = :temp_address,
            permanent_address = :permanent_address,
            country = :country,
            dept = :dept,
            joindate = :joindate, 
            email = :email,
            position = :position,
            accountnumber = :accountnumber,
            reporting = :reporting,
            Hire = :Hire,
            experience = :experience,
            profile_image = :profile_image,
            resume = :resume,
            cnic = :cnic,
            degree = :degree,
            experienceletter = :experienceletter,
            otherdoc = :otherdoc

        WHERE empid = :id";


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
        $stmt->bindParam(':id', $ID);


        // Execute the statement
        $stmt->execute();

        // Redirect to dashboard after updating
        header("Location: userdashboard.php?id=$ID&success=Record is updated Successfully");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
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


    <style>
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
                            <h1><i class="nav-icon fas fa-edit"></i> Edit Employee Details</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Edit Employee Details</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>


            <?php echo (isset($_GET['wall'])) ? "<div class='alert alert-success'>" . $_GET['wall'] . "</div>" : ""; ?>
            <?php echo (isset($_GET['wallet'])) ? "<div class='alert alert-success col-md-12'>" . $_GET['wallet'] . "</div>" : ""; ?>


            <section class="content">
                <div class="container-fluid">
                    <form action="" method="POST" onsubmit="return clearform()" id="empform"
                        enctype="multipart/form-data">

                        <!-- left column -->

                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Basic Information</h3>
                            </div>
                            <!-- /.card-header <option value="userid" <?php echo ($update['userid'] == 'userid') ? 'selected' : ''; ?>> -->
                            <!-- form start -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-user" aria-hidden="true"></i>
                                            </span>
                                            <label class="control-label">Employee ID*</label>
                                            <div class="input-group">
                                                <select name="userid" class="form-control" tabindex="1" required>
                                                    <?php
                                                    // Fetch user_name for the given userid
                                                    $stmt = $pdo->prepare("SELECT user_name FROM user WHERE uid = ?");
                                                    $stmt->execute([$update['userid']]);
                                                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                                                    // Check if user_name is fetched successfully
                                                    if ($user) {
                                                        echo "<option value='{$update['userid']}'>{$user['user_name']}</option>";
                                                    } else {
                                                        echo "<option value='{$update['userid']}'>Unknown</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-user" aria-hidden="true"></i>
                                            </span>
                                            <label class="control-label">Full Name*</label>
                                            <div class="input-group">

                                                <input type="text" name="fname" title="Full Name"
                                                    value="<?php echo $update['fname']; ?>" class=" form-control"
                                                    placeholder="Full Name" required="">

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-tint" aria-hidden="true"></i>
                                            </span>
                                            <label class="control-label">Blood Group*</label>
                                            <div class="input-group">

                                                <input type="text" name="bloodgroup" title="Full Name"
                                                    value="<?php echo $update['bloodgroup']; ?>" class=" form-control"
                                                    placeholder="Blood Group" required="">

                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                            </span>
                                            <label class="control-label">Birth Date*</label>
                                            <div class="input-group">
                                                <input type="date" id="Birthdate" title="Birth Date" name="bdate"
                                                    placeholder="Birth Date" class="form-control"
                                                    value="<?php echo $update['bdate']; ?>">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-user" aria-hidden="true"></i>
                                                </span>
                                                <label class="control-label">Marital*</label>
                                                <div class="input-group">
                                                    <select name="marital" class="form-control" title="Marital"
                                                        required="" style="text-transform: capitalize;">
                                                        <option value="">-- Select Marital --</option>
                                                        <option value="Married"
                                                            <?php echo ($update['marital'] == 'Married') ? 'selected' : ''; ?>>
                                                            Married</option>
                                                        <option value="UnMarried"
                                                            <?php echo ($update['marital'] == 'UnMarried') ? 'selected' : ''; ?>>
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
                                                    <select name="gender" class="form-control" title="Gender"
                                                        required="" style=" text-transform: capitalize;">
                                                        <option value="">-- Select Gender --</option>
                                                        <option value="Male"
                                                            <?php echo ($update['gender'] == 'Male') ? 'selected' : ''; ?>>
                                                            Male</option>
                                                        <option value="Female"
                                                            <?php echo ($update['gender'] == 'Female') ? 'selected' : ''; ?>>
                                                            Female</option>
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
                                                <label for="profile_imageAttachment" class="attachment-label">Profile
                                                    Attachment:</label>
                                                <?php
                                                $profileImageName = '';
                                                if ($update && isset($update['profile_image']) && !empty($update['profile_image'])) {
                                                    $profileImageName = basename($update['profile_image']);
                                                }
                                                ?>
                                                <input type="file" id="profile_imageAttachment" name="profile_image"
                                                    accept=".jpeg, .png"
                                                    onchange="updateFileNameDisplay(this, 'profile_image')">
                                                <span
                                                    class="file-upload-message"><?php echo htmlspecialchars($profileImageName ? $profileImageName : 'No file chosen'); ?>
                                                    <?php if ($profileImageName): ?>
                                                    <span class="remove-file"
                                                        onclick="removeUploadedFile('profile_imageAttachment', 'C:/xampp/htdocs/passwordvault/uploadimage/', '<?php echo $profileImageName; ?>')">×</span>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>

                                        <!--<div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-picture-o" aria-hidden="true"></i>
                                            </span>
                                            <label for="exampleInputFile">Upload Profile Image</label>
                                            <div class="custom-file">
                                                <?php


                                                $imagePath = '';
                                                if ($update && isset($update['profile_image']) && !empty($update['profile_image'])) {
                                                    $imagePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $update['profile_image']);

                                                    // Debugging: Print the image path
                                                    //echo "Image Path: " . $imagePath;
                                                
                                                    // Display the image
                                                    echo '<img src="' . $imagePath . '>';
                                                }

                                                ?>


                                                <input type="file" class="custom-file-input"
                                                    id="exampleInputFileProfile" name="profile_image"
                                                    onchange="updateFileName(this, 'fileInputLabelProfile')">
                                                <span class="focus-input100"></span>
                                                <label class="custom-file-label" id="fileInputLabelProfile"
                                                    for="exampleInputFile"><?php echo $imagePath; ?></label>

                                            </div>
                                        </div>
                                            -->
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
                                                    value="<?php echo $update['mnumber']; ?>" class="form-control"
                                                    placeholder="Mobile Number" min="10" maxlength="10"
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
                                                    value="<?php echo $update['Emnumber']; ?>" class="form-control"
                                                    placeholder=" Emergency Mobile Number" min="10" maxlength="10"
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
                                                    value="<?php echo $update['temp_address']; ?>" class="form-control"
                                                    placeholder=" Temp Address Line " required="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-pencil " aria-hidden="true"></i>
                                            </span>
                                            <label class="control-label"> Permanent Address*</label>
                                            <div class="input-group">

                                                <input type="text" name="permanent address" title=" permanent Address "
                                                    value="<?php echo $update['permanent_address']; ?>"
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

                                                <input type="text" name="country" title=" country "
                                                    value="<?php echo $update['country']; ?>" class="form-control"
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
                                                <?php
                                                $cnicName = '';
                                                if ($update && isset($update['cnic']) && !empty($update['cnic'])) {
                                                    $cnicName = basename($update['cnic']);
                                                }
                                                ?>
                                                <input type="file" id="cnicAttachment" name="cnic" accept=".pdf, .doc"
                                                    onchange="updateFileNameDisplay(this)">
                                                <span
                                                    class="file-upload-message"><?php echo htmlspecialchars($cnicName ? $cnicName : 'No file chosen'); ?>
                                                    <?php if ($cnicName): ?>
                                                    <span class="remove-file"
                                                        onclick="removeUploadedFile('cnicAttachment', 'C:/xampp/htdocs/passwordvault/uploadcnic/', '<?php echo $cnicName; ?>')">×</span>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>

                                        <!--
                                         <div class="form-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-picture-o" aria-hidden="true"></i>
                                            </span>
                                            <label for="exampleInputFile">Upload CNIC</label>
                                            <div class="custom-file">
                                                <?php


                                                $cnicPath = '';
                                                if ($update && isset($update['cnic'])) {
                                                    $cnicPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $update['cnic']);

                                                    // Debugging: Print the image path
                                                    //echo "Image Path: " . $imagePath;
                                                
                                                    // Display the image
                                                    echo '<img src="' . $cnicPath . '>';
                                                }

                                                ?>


                                                <input type="file" class="custom-file-input" id="exampleInputFileCnic"
                                                    name="cnic" onchange="updateFileName(this, 'fileInputLabelCnic')">
                                                <span class="focus-input100"></span>
                                                <label class="custom-file-label" id="fileInputLabelCnic"
                                                    for="exampleInputFile"><?php echo $cnicPath; ?></label>

                                            </div>
                                        </div>
                                        -->

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

                                                    <input type="text" name="dept" title=" dept "
                                                        value="<?php echo $update['dept']; ?>" class="form-control"
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
                                                        placeholder="Join Date"
                                                        value="<?php echo $update['joindate']; ?>" class="form-control"
                                                        required="">
                                                </div>



                                            </div>
                                            <div class="form-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                                </span>
                                                <label class="control-label">Email*</label>
                                                <div class="input-group">

                                                    <input type="email" name="email" title="Email"
                                                        value="<?php echo $update['email']; ?>" class="form-control"
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
                                                        value="<?php echo $update['position']; ?>" class="form-control"
                                                        placeholder=" Position " required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-bank" aria-hidden="true"></i>
                                                </span>

                                                <label class="control-label">Account Number*</label>
                                                <div class="input-group">

                                                    <input type="text" name="accountnumber" title="Accountnumber "
                                                        value="<?php echo $update['accountnumber']; ?>"
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
                                                        value="<?php echo $update['reporting']; ?>" class="form-control"
                                                        placeholder="Reporting Manager" required="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-briefcase" aria-hidden="true"></i>
                                                </span>
                                                <label class="control-label"> Source of Hire*</label>
                                                <div class="input-group">

                                                    <input type="text" name="Hire" title="Hire "
                                                        value="<?php echo $update['Hire']; ?>" class="form-control"
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
                                                        value="<?php echo $update['experience']; ?>"
                                                        class="form-control" placeholder="Experience" required="">
                                                </div>
                                            </div>
                                            <div class="attachment-section">
                                                <div class="attachment">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                                    </span>
                                                    <label for="resumeAttachment" class="attachment-label">Resume
                                                        Attachment:</label>
                                                    <?php
                                                    $resumeName = '';
                                                    if ($update && isset($update['resume']) && !empty($update['resume'])) {
                                                        $resumeName = basename($update['resume']);
                                                    }
                                                    ?>
                                                    <input type="file" id="resumeAttachment" name="resume"
                                                        accept=".pdf, .doc"
                                                        onchange="updateFileNameDisplay(this, 'resume')">
                                                    <span class="file-upload-message">
                                                        <?php echo htmlspecialchars($resumeName ? $resumeName : 'No file chosen'); ?>
                                                        <?php if ($resumeName): ?>
                                                        <span class="remove-file"
                                                            onclick="removeUploadedFile('resumeAttachment', 'C:/xampp/htdocs/passwordvault/uploadresume/', '<?php echo htmlspecialchars(addslashes($resumeName)); ?>')">×</span>
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                            </div>



                                            <div class="attachment-section">
                                                <div class="attachment">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                                    </span>
                                                    <label for="degreeAttachment" class="attachment-label">Degree
                                                        Attachment:</label>
                                                    <?php
                                                    $degreeName = '';
                                                    if ($update && isset($update['degree']) && !empty($update['degree'])) {
                                                        $degreeName = basename($update['degree']);
                                                    }
                                                    ?>
                                                    <input type="file" id="degreeAttachment" name="degree"
                                                        accept=".pdf, .doc" onchange="updateFileNameDisplay(this)">
                                                    <span
                                                        class="file-upload-message"><?php echo htmlspecialchars($degreeName ? $degreeName : 'No file chosen'); ?>
                                                        <?php if ($degreeName): ?>
                                                        <span class="remove-file"
                                                            onclick="removeUploadedFile('degreeAttachment', 'C:/xampp/htdocs/passwordvault/uploaddegree/', '<?php echo $degreeName; ?>')">×</span>
                                                        <?php endif; ?>
                                                    </span>

                                                </div>
                                            </div>
                                            <div class="attachment-section">
                                                <div class="attachment">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                                    </span>
                                                    <label for="experienceAttachment"
                                                        class="attachment-label">Experience Letter:</label>
                                                    <?php
                                                    $experienceName = '';
                                                    if ($update && isset($update['experienceletter']) && !empty($update['experienceletter'])) {
                                                        $experienceName = basename($update['experienceletter']);
                                                    }
                                                    ?>
                                                    <input type="file" id="experienceAttachment" name="experienceletter"
                                                        accept=".pdf, .doc" onchange="updateFileNameDisplay(this)">
                                                    <span
                                                        class="file-upload-message"><?php echo htmlspecialchars($experienceName ? $experienceName : 'No file chosen'); ?>
                                                        <?php if ($experienceName): ?>
                                                        <span class="remove-file"
                                                            onclick="removeUploadedFile('experienceAttachment', 'C:/xampp/htdocs/passwordvault/uploadexperience/', '<?php echo $experienceName; ?>')">×</span>
                                                        <?php endif; ?>
                                                    </span>

                                                </div>
                                            </div>
                                            <div class="attachment-section">
                                                <div class="attachment">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                                    </span>
                                                    <label for="otherdocAttachment" class="attachment-label">Other
                                                        Documents:</label>
                                                    <?php
                                                    $fileNames = '';
                                                    if ($update && isset($update['otherdoc']) && !empty($update['otherdoc'])) {
                                                        $filePaths = json_decode($update['otherdoc'], true); // Decode JSON to get array of paths
                                                        $fileNames = array_map(function ($filePath) {
                                                            return basename($filePath); // Extract file name from each path
                                                        }, $filePaths);
                                                        $fileNames = implode(', ', $fileNames); // Convert array of file names to comma-separated string
                                                    }
                                                    ?>
                                                    <input type="file" id="otherdocAttachment" name="otherdoc[]"
                                                        accept=".pdf,.doc" multiple
                                                        onchange="updateFileNameDisplay(this)">
                                                    <span
                                                        class="file-upload-message"><?php echo htmlspecialchars($fileNames ? $fileNames: 'No file chosen'); ?>
                                                        <?php if ($fileNames): ?>
                                                        <span class="remove-file"
                                                            onclick="removeotherdocFile('otherdocAttachment', 'C:/xampp/htdocs/passwordvault/uploadotherdocuments/', '<?php echo $fileNames; ?>')">×</span>
                                                        <?php endif; ?>
                                                    </span>

                                                </div>
                                            </div>
                                        </div>
                                        <!--
                                            <div class="form-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                </span>
                                                <label for="exampleInputFile">Upload Resume</label>
                                                <div class="custom-file">
                                                    <?php


                                                    $resumePath = '';
                                                    if ($update && isset($update['resume'])) {
                                                        $resumePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $update['resume']);

                                                        // Debugging: Print the image path
                                                        //echo "Image Path: " . $imagePath;
                                                    
                                                        // Display the image
                                                        echo '<img src="' . $resumePath . '" >';
                                                    }


                                                    ?>

                                                    <input type="file" class="custom-file-input"
                                                        id="exampleInputFileResume" name="resume"
                                                        onchange="updateFileName(this, 'fileInputLabelResume')">
                                                    <span class="focus-input100"></span>
                                                    <label class="custom-file-label" id="fileInputLabelResume"
                                                        for="exampleInputFile"><?php echo $resumePath; ?></label>
                                                </div>
                                            </div>
                                                -->
                                        <div class=" form-group" style="text-align: right;">

                                            <input type="hidden" name="empid" value="<?php echo $update['empid']; ?>">
                                            <a href="userdashboard.php" class="btn btn-link">Cancel</a>
                                            <button id="updateButton" type="submit" name="save" value="save"
                                                class="btn btn-success">Update Record</button>

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





    function removeotherdocFile(inputId, baseDir, fileName) {
        var input = document.getElementById(inputId);

        if (!input) {
            console.error('Element with id ' + inputId + ' not found.');
            return;
        }

        var siblingSpan = input.nextElementSibling;
        if (!siblingSpan) {
            console.error('No nextElementSibling found for the element with id ' + inputId);
            return;
        }

        input.value = '';
        siblingSpan.textContent = 'Upload PDF or DOC files (Max 2MB)';
        var removeButton = siblingSpan.querySelector('.remove-file');
        if (removeButton) {
            siblingSpan.removeChild(removeButton);
        }

        var fullPath = baseDir + fileName;
        console.log('Sending file_path: ' + fullPath);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_attachfile.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('File deleted from database and server.');
            } else {
                console.error('Failed to delete file: ' + xhr.statusText + ' - ' + xhr.responseText);
            }
        };
        xhr.onerror = function() {
            console.error('Error deleting file.');
        };

        xhr.send('file_path=' + encodeURIComponent(fullPath));
    }


    // Array to store files marked for deletion
    var filesToDelete = [];
    var otherdocFilesToDelete = [];

    function removeUploadedFile(inputId, baseDir, fileName) {
        var input = document.getElementById(inputId);

        if (!input) {
            console.error('Element with id ' + inputId + ' not found.');
            return;
        }

        var siblingSpan = input.nextElementSibling;
        if (!siblingSpan) {
            console.error('No nextElementSibling found for the element with id ' + inputId);
            return;
        }

        input.value = '';
        siblingSpan.textContent = 'Upload PDF or DOC files (Max 2MB)';
        var removeButton = siblingSpan.querySelector('.remove-file');
        if (removeButton) {
            siblingSpan.removeChild(removeButton);
        }

        var fullPath = baseDir + fileName;
        console.log('Marked for deletion: ' + fullPath);

        // Add file to the list of files to be deleted
        filesToDelete.push(fullPath);
        var otherdocfilename = fileName;
        console.log('Marked for deletion: ' + fileName); // Log only the file name

        // Add file name to the list of files to be deleted
        otherdocFilesToDelete.push(fileName); // Stor
    }

    // Attach to the update button click event
    document.getElementById('updateButton').addEventListener('click', function() {
        if (filesToDelete.length > 0) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'deletefiles.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status === 200) {

                    console.log('Files deleted successfully.');
                    // Clear the list of files to delete after successful deletion
                    filesToDelete = [];
                    otherdocFilesToDelete = [];
                } else {
                    console.error('Failed to delete files: ' + xhr.statusText + ' - ' + xhr.responseText);
                }
            };
            xhr.onerror = function() {
                console.error('Error deleting files.');
            };

            // Send the list of files to delete
            xhr.send('file_paths=' + encodeURIComponent(JSON.stringify(filesToDelete)));
        }
    });
    </script>







    <!--/.content-wrapper -->

    <?php require ('includes/footer.php') ?>
    <?php require ('includes/footer_lib.php') ?>
    </div>
</body>
</head>

</html>