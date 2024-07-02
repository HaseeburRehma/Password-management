<!-- Server settings
$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 2; // Enable verbose debug output
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'jhaseeb718@gmail.com';
$mail->Password = 'ganl vutw ymhd jrac';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

// Recipients
$mail->setFrom('jhaseeb718@gmail.com', 'Password Vault');
$mail->addAddress($email); -->


<?php
require 'connection.php';
require 'vendor/autoload.php';
require 'mailer/phpmailer.php';
require 'mailer/credential.php';
require 'mailer/Exception.php';

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

            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->CharSet = 'UTF-8';
            $mail->Host = 'smtp.office365.com';
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->SMTPAuth = true;
            $mail->Username = EMAIL;
            $mail->Password = PASS;
            $mail->setFrom(EMAIL, 'ENCS Networks');
            $mail->addAddress($email);
            $mail->isHTML(true);

            // Content
            $notereceivingLink = 'http://localhost/passwordvault/notereceiving.php';
            $mail->Subject = 'Shared Note';
            $mail->Body = 'A note has been shared with you. <br><br>' .
                'Click <a href="' . $notereceivingLink . '">here</a> to view the note.<br><br>' .
                'Your unique identifier is: ' . $uniqueIdentifier;

            // Send the email
            $mail->send();
            $_SESSION['success_message'] = "Note is shared successfully and email is sent.";
        } catch (Exception $e) {
            $_SESSION['success_message'] = "Note is shared successfully but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            error_log("Mailer Error: " . $mail->ErrorInfo); // Log the error for debugging
        }

        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>



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

    .form-title {
        font-family: Poppins-Bold;
        font-size: 24px;
        color: #fff;
        line-height: 1.2;
        text-align: center;

        width: 100%;
        display: block;
        padding-bottom: 54px;
        /* background-image: url("dist/img/encs-logo.png");
        background-color: #fff;
        background-size: contain;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent; */

    }

    #letter-orange {
        color: #f58634;
    }

    #letter-lightblue {
        color: #5881c3;
    }

    #letter-yellow {
        color: #fd6349;
    }

    #letter-green {
        color: #0a7740;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
            transform: translateY(50px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 1s ease-in-out;
    }

    .word {
        display: inline-block;
        opacity: 0;
        transition: opacity 0.5s, transform 0.5s;
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
                <div class="login100-pic js-tilt" style=" width: 400px; height: auto;" data-tilt>
                    <img src="dist/img/vault.png" alt="IMG">
                </div>
                <div id="formCard">

                    <form class="login100-form validate-form" method='post'>

                        <span id="formTitle" class="form-title">
                            <span id="letter-orange" class="word">E</span>
                            <span id="letter-lightblue" class="word">N</span>
                            <span id="letter-yellow" class="word">C</span>
                            <span id="letter-green" class="word">S</span>
                            <span class="word">Networks</span><br>
                            <span class="word">Note</span>
                            <span class="word">Sharing</span>
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

    <script src="Login/vendor/jquery/jquery-3.2.1.min.js"></script>

    <script src="Login/vendor/bootstrap/js/popper.js"></script>
    <script src="Login/vendor/bootstrap/js/bootstrap.min.js"></script>

    <script src="Login/vendor/select2/select2.min.js"></script>

    <script src="Login/vendor/tilt/tilt.jquery.min.js"></script>
    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        })

        setTimeout(function () {
            document.getElementById('alertDiv').style.display = 'none';
            history.replaceState(null, null, window.location.pathname);
        }, 3000);

        document.addEventListener('DOMContentLoaded', function () {
            var formTitle = document.getElementById('formTitle');
            var words = formTitle.querySelectorAll('.word');

            // Trigger animation for each word with a delay
            words.forEach(function (word, index) {
                setTimeout(function () {
                    word.style.opacity = 1;
                    word.classList.add('animate-fade-in');
                }, index * 500);
            });
        });
    </script>




    <script src="Login/js/main.js"></script>


</body>

</html>