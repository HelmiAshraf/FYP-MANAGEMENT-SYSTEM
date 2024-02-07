<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a PDO database connection
    include '../db_credentials.php';

    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $st_id = $_POST['st_id'];
    $st_phnum = $_POST['st_phnum'];
    $st_email = $_POST['st_email'];

    // Check if an image is uploaded
    $imageFile = $_FILES['st_image'] ?? null;

    // Fetch the existing image path from the database
    $sql = "SELECT st_image FROM student WHERE st_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$st_id]);
    $existingImagePath = $stmt->fetchColumn();

    // Specify the directory path relative to your script
    $directoryPath = '../file/image/';

    // Delete the existing image
    if ($existingImagePath) {
        $imagePath = $directoryPath . basename($existingImagePath);
        if (file_exists($imagePath)) {
            $deleted = unlink($imagePath);
            if (!$deleted) {
                echo "Error deleting the existing image.";
            }
        }
    }

    // Upload the new image
    if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
        // Move the uploaded image to the 'uploads' directory with st_id prefix
        $targetFilename = $st_id . '_' . $imageFile['name'];
        $targetPath = $directoryPath . $targetFilename;
        move_uploaded_file($imageFile['tmp_name'], $targetPath);

        // Update st_image in the database with the new path
        $newImagePath = '../file/image/' . $targetFilename;
        $sql = "UPDATE student SET st_image = ? WHERE st_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$newImagePath, $st_id]);
    }

    // Update other profile information
    $sql = "UPDATE student SET 
            st_phnum = ?, 
            st_email = ?
            WHERE st_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$st_phnum, $st_email, $st_id]);

    header("Location: ../student/st_profile.php");    

} else {
    echo "Invalid request method.";
}

?>
