<?php
require 'connection.php';

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

$error = '';
$note = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipientEmail = $_POST['recipient_email'];
    $uniqueIdentifier = $_POST['unique_identifier'];

    $sql = "SELECT n.id, n.unique_identifier, n.content, n.status, n.read_at, u.full_name AS sender_name
            FROM notes n
            INNER JOIN user u ON n.sender_id = u.uid
            WHERE n.unique_identifier = :unique_identifier
            AND n.recipient_email = :recipient_email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'unique_identifier' => $uniqueIdentifier,
        'recipient_email' => $recipientEmail
    ]);
    $note = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($note) {
        if ($note['status'] !== 'unread') {
            $error = 'If you didn t read this note, then someone else did it. If you read it, but forgot to write down the contents, ask the sender to send you a note again.';
            $note = null;
        } else {
            $updateSql = "UPDATE notes SET status = 'received', read_at = NOW() WHERE id = :id";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute(['id' => $note['id']]);
        }
    } else {
        $error = 'No note found with the provided credentials.';
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<?php require 'includes/header_lib.php'; ?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="shortcut icon" href="dist/img/encs-logo.png">

<head>
    <title>Note Receiving</title>
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

    #errorDiv {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #f44336;
        border-radius: 5px;
        background-color: #f8d7da;
        color: #721c24;
    }
</style>

<body>

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" style=" width: 400px; height: auto;" data-tilt>
                    <img src="dist/img/vault.png" alt="IMG">
                </div>

                <form class="login100-form validate-form" method='post' id="formCard"
                    style="<?php echo $note ? 'display:none;' : ''; ?>">
                    <span class="login100-form-title">
                        ENCS Networks <br> Note Sharing
                    </span>
                    <?php echo (isset($error)) ? "<div id='errorDiv' class='alert alert-danger'>" . htmlspecialchars($error) . "</div>" : ""; ?>


                    <div class="wrap-input100 validate-input" data-validate="email is required">
                        <input class="input100" type="email" name="recipient_email" placeholder="User Email">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="id is required">
                        <input class="input100" type="password" name="unique_identifier" placeholder="Id">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <ul>

                            <button type="submit" value="Save" name="submit" class=" btn btn-md pr-3 pl-3 btn-info">
                                submit</button>
                        </ul>
                        <!-- <a type="button" href="register.php" class="btn btn-md pr-3 pl-3 btn-success">reset</a> -->
                    </div>





                </form>

                <form class="login100-form validate-form" id="notedetail" method=''>
                    <?php if ($note && $note['status'] !== 'read'): ?>

                        <span class="login100-form-title">
                            ENCS Networks <br> Note Details
                        </span>

                        <div class="wrap-input100 validate-input">
                            <input class="input100" type="text" name="senderName"
                                value="<?php echo htmlspecialchars($note['sender_name']); ?>" readonly>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </span>
                        </div>

                        <div class="wrap-input1001 validate-input" data-validate="Description is required">
                            <label style="color: white;">Received Note</label>
                            <textarea class="input" name="description" placeholder="Write your note here..."
                                readonly><?php echo htmlspecialchars($note['content']); ?></textarea>
                            <span class="focus-input1001"></span>
                        </div>
                        <div class="container-login100-form-btn">
                            <ul>

                                <button id="receivedButton" class=" btn btn-md pr-3 pl-3 btn-info">
                                    Received</button>
                            </ul>
                            <!-- <a type="button" href="register.php" class="btn btn-md pr-3 pl-3 btn-success">reset</a> -->
                        </div>



                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                var receivedButton = document.getElementById('receivedButton');
                                receivedButton.addEventListener('click', function () {
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('POST', 'update_note_status.php', true);
                                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                    xhr.onreadystatechange = function () {
                                        if (xhr.readyState === 4 && xhr.status === 200) {
                                            document.getElementById('notedetail').style.display = 'none';
                                        }
                                    };
                                    xhr.send('note_id=<?php echo $note['id']; ?>');
                                });
                            });
                        </script>
                    <?php endif; ?>
                </form>
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
            document.getElementById('errorDiv').style.display = 'none';
            history.replaceState(null, null, window.location.pathname);
        }, 3000);
    </script>
    <!--===============================================================================================-->
    <script src="Login/js/main.js"></script>

</body>

</html>