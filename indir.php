<?php
$file_url = $_GET['file'];
$file_name = $_GET['title'];
$uzanti = ".mp3";
if (filter_var($file_url, FILTER_VALIDATE_URL)) {
    if (empty($file_name)) {
        $file_name = basename($file_url);
    }
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $file_name . $uzanti);
    readfile($file_url);
    exit;
} else {
    echo 'Geçersiz dosya URL\'si.';
}
?>
