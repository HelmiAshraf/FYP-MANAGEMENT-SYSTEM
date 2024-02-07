<?php
// Establish your database connection here

if (isset($_GET['file_id'])) {
    $file_id = $_GET['file_id'];
    
    // Replace the following with your database query to retrieve the BLOB content
    include '../../db_credentials.php';

    
    // Create a connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $file_id = $_GET['file_id'];

    // Replace "your_table_name" with the actual table name where the BLOBs are stored
    $query = "SELECT file_content FROM file WHERE file_id = $file_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pdfContent = $row['file_content'];

        // Set the content type and content disposition to display the PDF
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="file.pdf"');

        // Output the PDF content
        echo $pdfContent;
    } else {
        echo "PDF not found";
    }

    // Close the database connection
    $conn->close();
}
