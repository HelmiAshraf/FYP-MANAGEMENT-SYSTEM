<?php
//database connection
include '../db.php';

// Retrieve the image based on student_id
if (isset($_GET['st_id'])) {
    $st_id = $_GET['st_id'];
    $sql = "SELECT st_image FROM student WHERE st_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $st_id);
    $stmt->execute();

    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($imageData);
        $stmt->fetch();

        // Set appropriate headers for an image (JPEG in this case)
        header("Content-Type: image/jpeg");

        // Output the image data
        echo $imageData;
    } else {
        // Image not found
        echo "Image not found.";
    }

    $stmt->close();
} else {
    // No st_id provided
    echo "No st_id provided.";
}

$conn->close();


?>
