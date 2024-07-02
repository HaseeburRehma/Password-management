<?php
require 'connection.php';
require 'includes/check_login.php';

// Establish database connection using PDO
$dsn = "mysql:host=localhost;dbname=new_pass;charset=UTF8";
$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error connecting to database: " . $e->getMessage();
    exit();
}

try {
    // Fetch unread notifications for the logged-in user
    $sql = "SELECT id, message, created_at FROM notifications WHERE recipient_id = :recipient_id AND is_read = 0 ORDER BY created_at DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['recipient_id' => $_SESSION['ID']]);
    $notification = $stmt->fetch(PDO::FETCH_ASSOC);

    // If there is a notification, mark it as read
    if ($notification) {
        // Calculate the time ago for the notification
        $createdTime = strtotime($notification['created_at']);
        $currentTime = time();
        $timeDiff = $currentTime - $createdTime;
        if ($timeDiff < 3600) {
            if ($timeDiff < 60) {
                $notification['time_ago'] = 'Just now';
            } elseif ($timeDiff < 120) {
                $notification['time_ago'] = '1 minute ago';
            } else {
                $notification['time_ago'] = floor($timeDiff / 60) . ' minutes ago';
            }
        } elseif ($timeDiff < 86400) {
            $notification['time_ago'] = floor($timeDiff / 3600) . 'h ago';
        } elseif ($timeDiff < 604800) {
            $notification['time_ago'] = floor($timeDiff / 86400) . 'd ago';
        } else {
            $notification['time_ago'] = floor($timeDiff / 604800) . 'w ago';
        }

        // Mark the notification as read
        $sql = "UPDATE notifications SET is_read = 1 WHERE id = :notification_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['notification_id' => $notification['id']]);
    }

    // Determine the count of unread notifications
    $count = ($notification) ? 1 : 0;

    $response = [
        'count' => $count,
        'messages' => ($notification) ? [$notification] : [],
    ];

} catch (PDOException $e) {
    $response = ['error' => 'Failed to fetch notifications. ' . $e->getMessage()];
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>