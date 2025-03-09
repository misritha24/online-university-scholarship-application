<?php
if (isset($_GET['file'])) {
    $fileName = basename($_GET['file']);
    $filePath = "uploads/" . $fileName;

    if (file_exists($filePath)) {
        // Force the file to download
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        echo "Error: File not found.";
    }
} else {
    echo "Invalid request.";
}
?>
