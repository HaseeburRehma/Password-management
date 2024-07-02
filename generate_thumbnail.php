<?php
// Include FPDF library
require_once 'FPDF/fpdf.php';

// Include FPDI library
require_once 'FPDI/src/autoload.php';

// Use FPDI namespace
use setasign\Fpdi\Fpdi;

function generateThumbnailPDF($pdfPath)
{
    // Define thumbnail directory
    $thumbnailDir = 'C:/xampp/htdocs/passwordvault/thumbnail/';
    if (!is_dir($thumbnailDir)) {
        mkdir($thumbnailDir, 0755, true);
    }

    // Create the thumbnail path
    $thumbnailPath = $thumbnailDir . pathinfo($pdfPath, PATHINFO_FILENAME) . '.jpg';

    try {
        $pdf = new Fpdi();
        $pdf->setSourceFile($pdfPath);
        $tplIdx = $pdf->importPage(1);
        $pdf->AddPage();
        $pdf->useTemplate($tplIdx, 10, 10, 100);

        // Save first page as temporary PDF
        $tempPdfPath = $thumbnailDir . 'temp.pdf';
        $pdf->Output($tempPdfPath, 'F');

        // Convert the temporary PDF to a JPEG image using ImageMagick
        $command = "magick convert \"$tempPdfPath\"[0] -resize 200x \"$thumbnailPath\"";
        exec($command);

        // Clean up temporary PDF file
        if (file_exists($tempPdfPath)) {
            unlink($tempPdfPath);
        }

        return $thumbnailPath;
    } catch (Exception $e) {
        error_log("Error generating thumbnail: " . $e->getMessage());
        return false;
    }
}