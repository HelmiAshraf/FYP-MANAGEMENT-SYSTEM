<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a PDO database connection
    include '../../db_credentials.php';

    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $doc_id = $_POST['doc_id'];
    $doc_title = $_POST['doc_title'];
    $doc_description = $_POST['doc_description'];
    $doc_date_due = $_POST['doc_date_due'];
    $file_uploader_id = $_POST['doc_fl_id'];


    // Check if a file is uploaded
    $doc_file = $_FILES['doc_file'] ?? null;
    $file_type = 'dfypl';

    // Fetch the existing file information from the database, including the file path
    $sql = "SELECT file_name, file_path FROM file_path WHERE type_id = ? AND file_type = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$doc_id, $file_type]);
    $existingFileData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Specify the directory path relative to your script
    $directoryPath = '../../file/document/';

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
    if ($doc_file && $doc_file['error'] === UPLOAD_ERR_OK) {
        $targetFilename = $file_type . '_' . $doc_id . '_' . $doc_file['name'];
        $targetPath = $directoryPath . $targetFilename;
        move_uploaded_file($doc_file['tmp_name'], $targetPath);

        if ($existingFileData) {
            // Update existing file information in the database
            $sql = "UPDATE file_path SET file_name = ?, file_path = ? WHERE type_id = ? AND file_type = ?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$targetFilename, $targetPath, $doc_id, $file_type]);
        } else {
            // Insert new file information into the database
            $sql = "INSERT INTO file_path (file_name, file_path, type_id, file_type, file_uploader_id) VALUES (?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$targetFilename, $targetPath, $doc_id, $file_type, $file_uploader_id]);
        }
    }

    // Update or insert other document information
    $sql = "UPDATE document SET 
            doc_title = ?, 
            doc_description = ?, 
            doc_date_due = ?
            WHERE doc_id = ?";


    $stmt = $pdo->prepare($sql);
    $stmt->execute([$doc_title, $doc_description, $doc_date_due, $doc_id]);

    header("Location: ../fypl_document_details.php?doc_id=" . $doc_id);
} else {
    echo "Invalid request method.";
}
