<?php
// Check if the path parameter is set

if (isset($_GET['path'])) {
    // Get the file path from the query parameter
    $filePath = $_GET['path'];

    // Check if the file exists
    if (file_exists($filePath)) {
        // Set the appropriate headers to indicate that this is a PDF file
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($filePath) . '"');

        // Read the file and output its contents
        readfile($filePath);
        exit;
    } else {
        // If the file does not exist, display an error message
        echo "File not found.";
    }
} else {
    // If the path parameter is not set, display an error message
    echo "File path not provided.";
}
?>