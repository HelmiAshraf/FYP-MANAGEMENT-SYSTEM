<?php
// This file should be named, for example, download.php

$fileUrl = $_GET['url'] ?? '';
$fileName = $_GET['name'].'.pdf';

// Set appropriate Content-Type header for the file type
header('Content-Type: application/pdf');

// Set the appropriate Content-Disposition header
header('Content-Disposition: inline; filename="' . $fileName . '"');

// Output the file content
readfile($fileUrl);
?>