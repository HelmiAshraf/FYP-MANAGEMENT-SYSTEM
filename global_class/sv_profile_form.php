<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a PDO database connection
    include '../db_credentials.php';

    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $sv_id = $_POST['sv_id'];
    $sv_expertise = $_POST['sv_expertise'];
    $sv_phnum = $_POST['sv_phnum'];
    $sv_email = $_POST['sv_email'];

    // Check if an image is uploaded
    $imageFile = $_FILES['sv_image'] ?? null;

    // Fetch the existing image path from the database
    $sql = "SELECT sv_image FROM supervisor WHERE sv_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$sv_id]);
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
        // Move the uploaded image to the 'uploads' directory with sv_id prefix
        $targetFilename = $sv_id . '_' . $imageFile['name'];
        $targetPath = $directoryPath . $targetFilename;
        move_uploaded_file($imageFile['tmp_name'], $targetPath);

        // Update sv_image in the database with the new path
        $newImagePath = '../file/image/' . $targetFilename;
        $sql = "UPDATE supervisor SET sv_image = ? WHERE sv_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$newImagePath, $sv_id]);
    }

    // Update other profile information
    $sql = "UPDATE supervisor SET 
            sv_expertise = ?, 
            sv_phnum = ?, 
            sv_email = ?
            WHERE sv_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$sv_expertise, $sv_phnum, $sv_email, $sv_id]);

    header("Location: ../supervisor/sv_profile.php");    // Redirect or do further processing as needed
} else {
    echo "Invalid request method.";
}

?>
