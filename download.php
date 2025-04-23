<?php
if (isset($_GET['path'])) {
    // Sanitize and prevent directory traversal
    $file_path = urldecode($_GET['path']);
    $file_path = str_replace('..', '', $file_path); // basic protection
    $full_path = __DIR__ . '/' . $file_path;

    if (file_exists($full_path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($full_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($full_path));
        readfile($full_path);
        exit;
    } else {
        echo "File not found.";
    }
} else {
    echo "No file specified.";
}
