<?php
if (isset($_GET['file_id'])) {
    $file_id = $_GET['file_id'];

    // Replace with your actual database connection details
    $servername = "localhost"; // Database server hostname or IP address
    $dbusername = "root"; // Your database username
    $dbpassword = ""; // Your database password
    $dbname = "fypms"; // Name of your database

    // Establish a database connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve the file data from your database based on $file_id
    $sql = "SELECT file_name, file_content FROM file WHERE file_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $file_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $file_name = $row['file_name'];
            $file_content = $row['file_content'];

            // Set response headers based on the file type
            if (strpos($file_name, '.pdf') !== false) {
                header("Content-Type: application/pdf");
            } elseif (strpos($file_name, '.docx') !== false) {
                header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
            }

            header("Content-Disposition: inline; filename=$file_name");

            // Output the file content
            echo $file_content;
            exit();
        }
    }

    // Close the database connection
    $conn->close();
} else {
    // Invalid file parameter
    echo "Invalid file parameter";
}
?>
