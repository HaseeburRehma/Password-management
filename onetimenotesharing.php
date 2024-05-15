<?php
require 'connection.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    $pdo = new PDO("mysql:host=localhost;dbname=password_vault", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $description = $_POST['description'];
    $uniqueIdentifier = uniqid();

    $sql = "INSERT INTO notes (unique_identifier, content, recipient_email, sender_id) VALUES (:unique_identifier, :content, :recipient_email, :sender_id)";
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':sender_id', $_SESSION['ID'], PDO::PARAM_INT);
    $stmt->bindParam(':unique_identifier', $uniqueIdentifier, PDO::PARAM_STR);
    $stmt->bindParam(':content', $description, PDO::PARAM_STR);
    $stmt->bindParam(':recipient_email', $email, PDO::PARAM_STR);

    try {
        // Execute the statement
        $stmt->execute();

        // Prepare and send the email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->SMTPDebug = 2; // Enable verbose debug output
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jhaseeb718@gmail.com';
            $mail->Password = 'ganl vutw ymhd jrac';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('jhaseeb718@gmail.com', 'Password Vault');
            $mail->addAddress($email);

            // Content
            $notereceivingLink = 'http://localhost/passwordvault/notereceiving.php?';
            $mail->isHTML(true);
            $mail->Subject = 'Shared Note';
            $mail->Body = 'A note has been shared with you. <br><br>' .
                'Click <a href="' . $notereceivingLink . '">here</a> to view the note.<br><br>' .
                'Your unique identifier is: ' . $uniqueIdentifier;



            // Send the email
            $mail->send();
            $_SESSION['success_message'] = "Data shared successfully and email sent.";
        } catch (Exception $e) {
            $_SESSION['success_message'] = "Data shared successfully but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            error_log("Mailer Error: " . $mail->ErrorInfo); // Log the error for debugging
        }


        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>



<!--
<!DOCTYPE html>
<html lang="en">
<title>ENCS</title>
<link rel="shortcut icon" href="dist/img/encs-logo.png">


<body class="hold-transition sidebar-mini layout-fixed">


    <div class="wrapper">

<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/encs-loader.png" alt="AdminLTELogo" height="60" width="60">
</div>


<section id="header">
    <div class="mainheader">
        <a href="#" class="brand-link">
            <img src="dist/img/vault.png" alt="AdminLTE Logo" class="rounded-circle"
                style="width: 90px; height: 90px; margin-top: -3px;">
            <span class="brand-text text-white" style="font-size: 18px;">ENCS Password Vault</span>
        </a>
    </div>
</section>
<div class="content-wrapper">



    <form method="POST" action="">
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

            .mainheader {
                background-color: #343a40;
                color: #ffffff;
                padding: 8px;
                width: 100%;
            }

            .main-header .brand-link {
                color: #ffffff;
            }



            .wrapper {
                display: flex;
                flex-direction: column;
                height: 100vh;
                width: 100%;
            }

            .content-wrapper {
                display: flex;
                flex-direction: column;
                flex: 1;
                padding: 0 15px;
            }

            .form-wrapper {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: 24px;
                height: 100%;
            }

            .main-header .container-fluid {
                padding-left: auto;
                padding-right: auto;
            }

            .content {
                flex: 2;
            }


            .card {
                width: 100%;
                max-width: 400px;

            }
        </style>
        <section class="content" style="margin-left: 130px;">
            <div class="container-fluid">
                <div class="alert alert-success" id="successMessage" style="display: none;">
                    Note is shared successfully!
                </div>

                <?php echo (isset($_SESSION['shared_success'])) ? '<div id="alertDiv" class="alert alert-success alert-dismissible fade show" role="alert">' . $_SESSION['success_message'] . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button></div>' : ''; ?>
                <div class="row">
                    <div class="col-md-10 mb-2">
                        <div class="card card-info">
                            <div class="card-header text-center">
                                <h3 class="card-title"> Shared Notes</h3>
                            </div>
                            <div class="card-body">



                                <div class="form-group">
                                    <label for="email">Recipient Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        required></textarea>
                                </div>

                                <div class="form-group  text-left">
                                    <button type="submit" class="btn btn-primary " id="shareButton">Share
                                        Note</button>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </section>

    </form>


</div>
</div>






</div>

</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var shareButton = document.getElementById("shareButton");
        var successMessage = document.getElementById("successMessage");

        shareButton.addEventListener("click", function () {
            successMessage.style.display = "block";

            setTimeout(function () {
                successMessage.style.display = "none";

                setTimeout(function () {
                    window.location.reload();
                }, 10000);
            }, 3000);
        });
    });


</script>

</div>
</section>
</div>





</body>

</html>
-->

<!DOCTYPE html>
<html lang="en">
<?php require 'includes/header_lib.php'; ?>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="shortcut icon" href="dist/img/encs-logo.png">

<head>
    <title>Note Sharing</title>
    <meta charset="UTF-8">

    <link rel="icon" type="image/png" href="Login/images/icons/favicon.ico" />

    <link rel="stylesheet" type="text/css" href="Login/vendor/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="Login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="Login/vendor/animate/animate.css">

    <link rel="stylesheet" type="text/css" href="Login/vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="Login/vendor/select2/select2.min.css">

    <link rel="stylesheet" type="text/css" href="Login/css/util.css">
    <link rel="stylesheet" type="text/css" href="Login/css/main.css">

</head>
<style>
    .input {
        width: 100%;
        min-height: 150px;
        max-height: 300px;
        resize: vertical;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f8f8f8;
        font-size: 16px;
        color: #333;
        transition: border-color 0.3s ease;
    }

    .input:focus {
        border-color: #6c63ff;
        outline: none;
    }

    .wrap-input1001 {
        position: relative;
        margin-bottom: 25px;
    }

    .wrap-input1001 .focus-input1001 {
        position: absolute;
        display: block;
        width: calc(100% - 20px);
        height: 2px;
        bottom: 0;
        left: 10px;
        background: #6c63ff;
        transition: all 0.4s;
    }

    .wrap-input1001 .input1001:focus+.focus-input1001 {
        transform: translateY(-7px);
    }

    .wrap-input1001 {
        position: relative;
        width: 100%;
    }

    .input1001 {
        width: calc(100% - 30px);
    }

    .entered-email {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        color: #999;
    }

    @keyframes flash {
        0% {
            background-color: transparent;
        }

        50% {
            background-color: #ffcc00;
        }

        100% {
            background-color: transparent;
        }
    }

    .flash {
        animation: flash 0.5s infinite;
    }
</style>

<body>

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="dist/img/vault.png" alt="IMG">
                </div>
                <div id="formCard">
                    <form class="login100-form validate-form" method='post'>
                        <span class="login100-form-title">
                            ENCS Networks <br> Note Sharing
                        </span>
                        <?php
                        // Check if success message is set in session and display it
                        if (isset($_SESSION['success_message'])) {
                            echo "<div id='alertDiv' class='alert alert-success'>" . $_SESSION['success_message'] . "</div>";
                            // Unset the session variable to clear the message after displaying it
                            unset($_SESSION['success_message']);
                        }

                        // Check if error message is set in session and display it
                        if (isset($_SESSION['error_message'])) {
                            echo "<div id='alertDiv' class='alert alert-danger'>" . $_SESSION['error_message'] . "</div>";
                            // Unset the session variable to clear the message after displaying it
                            unset($_SESSION['error_message']);
                        }
                        ?>





                        <div class="wrap-input100 validate-input" data-validate="email is required">
                            <input class="input100" type="email" name="email" placeholder="Receiver Email">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </span>
                        </div>
                        <div class="wrap-input1001 validate-input" data-validate="Description is required">
                            <label style="color: white;">Write a Note</label>
                            <textarea class="input" name="description" placeholder="Write your note here..."></textarea>
                            <span class="focus-input1001"></span>
                        </div>

                        <div class="container-login100-form-btn">
                            <ul>

                                <button type="submit" value="Save" name="submit" class=" btn btn-md pr-3 pl-3 btn-info">
                                    share</button>
                            </ul>
                        </div>





                    </form>
                </div>


            </div>
        </div>




    </div>

    <!--===============================================================================================-->
    <script src="Login/vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="Login/vendor/bootstrap/js/popper.js"></script>
    <script src="Login/vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="Login/vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="Login/vendor/tilt/tilt.jquery.min.js"></script>
    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        })

        setTimeout(function () {
            document.getElementById('alertDiv').style.display = 'none';
            history.replaceState(null, null, window.location.pathname);
        }, 3000);
    </script>



    <!--===============================================================================================-->
    <script src="Login/js/main.js"></script>


</body>

</html>