<?php
// Get the file_id from the query parameter
$file_id = $_GET['file_id'];

// Fetch the file content from your database or file system based on the $file_id
// Replace the following code with your actual data retrieval logic

// Sample code assuming you have a 'files' directory where PDFs are stored
$file_path = "files/" . $file_id . ".pdf";

// Check if the file exists
if (file_exists($file_path)) {
    // Set the content type to PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="document.pdf"');

    // Output the PDF content
    readfile($file_path);
} else {
    // Handle the case where the file does not exist
    echo "File not found.";
}
?>
