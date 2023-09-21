<?php
// Check if 'file_id' is set in the URL
if (isset($_GET['file_id'])) {
    // Get the file_id from the URL
    $file_id = $_GET['file_id'];

    // Include your database connection code here
    include 'includes/db_connection.php';

    // Assuming you have a 'file' table with columns 'file_id', 'file_name', 'file_content'
    $sql = "SELECT file_name, file_content FROM file WHERE file_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $file_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $file_name = $row['file_name'];
            $file_content = $row['file_content'];

            // Set the appropriate headers for file download
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            header('Content-Length: ' . strlen($file_content));

            // Output the file content
            echo $file_content;
        } else {
            // Handle the case where no file with the specified file_id is found
            echo "File not found.";
        }
    } else {
        echo "Error fetching file: " . $stmt->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle the case where 'file_id' is not set in the URL
    echo "File ID not provided.";
}
?>
