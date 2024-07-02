<?php
$dsn = "mysql:host=localhost;dbname=password_vault;charset=UTF8";
$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file_paths'])) {
        $filePathsJson = $_POST['file_paths'];
        $filePathsArray = json_decode($filePathsJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo 'Invalid JSON data.';
            exit;
        }

        // Log received file paths for debugging
        error_log('Received file_paths: ' . implode(', ', $filePathsArray));

        foreach ($filePathsArray as $filePath) {
            $baseFileName = basename(trim($filePath)); // Get the base name of the file

            // Determine the column based on file path
            $columnName = '';
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

                    if (($key = array_search($baseFileName, $filePaths)) !== false) {
                        unset($filePaths[$key]);

                        // Attempt to delete the file from the server
                        $fullFilePath = "C:/xampp/htdocs/passwordvault/uploadotherdocuments/" . $baseFileName;
                        if (file_exists($fullFilePath)) {
                            if (unlink($fullFilePath)) {
                                error_log("Deleted file from server: $fullFilePath");
                            } else {
                                error_log("Failed to delete file from server: $fullFilePath");
                            }
                        } else {
                            error_log("File not found on server: $fullFilePath");
                        }
                    } else {
                        error_log("File $baseFileName not found in JSON data");
                    }

                    $newFilePathsJson = json_encode(array_values($filePaths));

                    // Update the database with the new JSON array
                    $stmt = $pdo->prepare("UPDATE employee SET $columnName = :new_file_paths WHERE JSON_CONTAINS($columnName, :file_name)");
                    $stmt->bindParam(':new_file_paths', $newFilePathsJson);
                    $stmt->bindValue(':file_name', json_encode([$baseFileName]));
                    $stmt->execute();

                    error_log("Updated $columnName in database for file: $baseFileName");
                } else {
                    error_log("No matching record found for file: $baseFileName");
                    http_response_code(400);
                    echo 'No matching record found.';
                    exit;
                }
            } else {
                // Handle deletion of other single files stored as full paths
                $stmt = $pdo->prepare("UPDATE employee SET $columnName = NULL WHERE $columnName = :file_path");
                $stmt->bindParam(':file_path', $filePath);
                $stmt->execute();

                // Attempt to delete the file from the server
                if (file_exists($filePath)) {
                    if (unlink($filePath)) {
                        error_log("Deleted file from server: $filePath");
                    } else {
                        error_log("Failed to delete file from server: $filePath");
                    }
                } else {
                    error_log("File not found on server: $filePath");
                }
            }
        }

        http_response_code(200);
        echo 'Files deleted from database and server.';
    } else {
        http_response_code(400);
        echo 'Invalid request. Missing file_paths.';
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Database error: ' . $e->getMessage();
    error_log('Database error: ' . $e->getMessage());
}
?>