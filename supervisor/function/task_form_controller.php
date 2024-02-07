<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a PDO database connection
    include '../../db_credentials.php';

    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $task_id = $_POST['task_id'];
    $task_title = $_POST['task_title'];
    $task_description = $_POST['task_description'];
    $task_date_due = $_POST['task_date_due'];
    $file_uploader_id = $_POST['task_sv_id'];


    // Check if a file is uploaded
    $task_file = $_FILES['task_file'] ?? null;
    $file_type = 'tsv';

    // Fetch the existing file information from the database, including the file path
    $sql = "SELECT file_name, file_path FROM file_path WHERE type_id = ? AND file_type = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$task_id, $file_type]);
    $existingFileData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Specify the directory path relative to your script
    $directoryPath = '../../file/task/';

    // Delete the existing file from the server
    if ($existingFileData) {
        $filePath = $directoryPath . $existingFileData['file_name'];

        if (file_exists($filePath)) {
            $deleted = unlink($filePath);

            if (!$deleted) {
                echo "Error deleting the existing file.";
            }
        }
    }

    // Upload the new file to the server
    if ($task_file && $task_file['error'] === UPLOAD_ERR_OK) {
        $targetFilename = $file_type . '_' . $task_id . '_' . $task_file['name'];
        $targetPath = $directoryPath . $targetFilename;
        move_uploaded_file($task_file['tmp_name'], $targetPath);

        if ($existingFileData) {
            // Update existing file information in the database
            $sql = "UPDATE file_path SET file_name = ?, file_path = ? WHERE type_id = ? AND file_type = ?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$targetFilename, $targetPath, $task_id, $file_type]);
        } else {
            // Insert new file information into the database
            $sql = "INSERT INTO file_path (file_name, file_path, type_id, file_type, file_uploader_id) VALUES (?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$targetFilename, $targetPath, $task_id, $file_type, $file_uploader_id]);
        }
    }

    // Update or insert other task information
    $sql = "UPDATE task SET 
            task_title = ?, 
            task_description = ?, 
            task_date_due = ?
            WHERE task_id = ?";


    $stmt = $pdo->prepare($sql);
    $stmt->execute([$task_title, $task_description, $task_date_due, $task_id]);

    header("Location: ../sv_task_details.php?task_id=" . $task_id);
} else {
    echo "Invalid request method.";
}
