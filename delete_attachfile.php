<?php
$dsn = "mysql:host=localhost;dbname=password_vault;charset=UTF8";
$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file_path'])) {
        $filePath = $_POST['file_path'];
        $filePathsArray = explode(',', $filePath);

        // Log received file path for debugging
        error_log('Received file_path: ' . $filePath);

        // Determine the column based on file path
        $columnName = '';
        $baseFileName = basename(trim($filePathsArray[0])); // Only use the first file name for column determination

        if (strpos($filePath, 'uploadimage') !== false) {
            $columnName = 'profile_image';
        } elseif (strpos($filePath, 'uploadresume') !== false) {
            $columnName = 'resume';
        } elseif (strpos($filePath, 'uploadcnic') !== false) {
            $columnName = 'cnic';
        } elseif (strpos($filePath, 'uploaddegree') !== false) {
            $columnName = 'degree';
        } elseif (strpos($filePath, 'uploadexperience') !== false) {
            $columnName = 'experienceletter';
        } elseif (strpos($filePath, 'uploadotherdocuments') !== false) {
            $columnName = 'otherdoc';
        } else {
            http_response_code(400);
            echo 'Invalid file path.';
            exit;
        }

        if ($columnName === 'otherdoc') {
            // Handle deletion of otherdoc files stored in JSON format
            $stmt = $pdo->prepare("SELECT $columnName FROM employee WHERE JSON_CONTAINS($columnName, :file_name)");
            $stmt->bindValue(':file_name', json_encode([$baseFileName]));
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $filePaths = json_decode($result[$columnName], true);

                // Process each file name in the comma-separated list
                foreach ($filePathsArray as $file) {
                    $file = basename(trim($file));

                    if (($key = array_search($file, $filePaths)) !== false) {
                        unset($filePaths[$key]);

                        // Attempt to delete the file from the server
                        $fullFilePath = "C:/xampp/htdocs/passwordvault/uploadotherdocuments/" . $file;
                        if (file_exists($fullFilePath)) {
                            unlink($fullFilePath);
                        }
                    }
                }

                $newFilePathsJson = json_encode(array_values($filePaths));

                // Update the database with the new JSON array
                $stmt = $pdo->prepare("UPDATE employee SET $columnName = :new_file_paths WHERE JSON_CONTAINS($columnName, :file_name)");
                $stmt->bindParam(':new_file_paths', $newFilePathsJson);
                $stmt->bindValue(':file_name', json_encode([$baseFileName]));
                $stmt->execute();

                http_response_code(200);
                echo 'Files deleted from database and server.';
            } else {
                http_response_code(400);
                echo 'No matching record found.';
            }
        } else {
            // Handle deletion of other single files stored as full paths
            $stmt = $pdo->prepare("UPDATE employee SET $columnName = NULL WHERE $columnName = :file_path");
            $stmt->bindParam(':file_path', $filePath);
            $stmt->execute();

            // Attempt to delete the file from the server
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            http_response_code(200);
            echo 'File deleted from database and server.';
        }
    } else {
        http_response_code(400);
        echo 'Invalid request. Missing file_path.';
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Database error: ' . $e->getMessage();
}
?>