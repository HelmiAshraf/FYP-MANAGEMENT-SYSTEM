<?php
// Get the file_id from the query parameter
$file_id = $_GET['file_id'];

// Fetch the DOCX file content from your database or file system based on the $file_id
// Replace the following code with your actual data retrieval logic

// Sample code assuming you have a 'files' directory where DOCX files are stored
$file_path = "files/" . $file_id . ".docx";

// Check if the file exists
if (file_exists($file_path)) {
    // Read the DOCX content as binary data
    $docx_content = file_get_contents($file_path);

    // Send the binary data as a response with appropriate headers
    header('Content-Type: application/octet-stream'); // Set the content type
    header('Content-Disposition: inline; filename="document.docx"');

    // Output the DOCX content
    echo $docx_content;
} else {
    // Handle the case where the file does not exist
    echo "File not found.";
}
?>
