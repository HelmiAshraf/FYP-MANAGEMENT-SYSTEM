<?php
// Include the necessary files
require_once '../../db_credentials.php'; // Ensure this file contains your database credentials

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $doc_id = $_POST['doc_id'];

    // 1. Find all doc submissions based on the doc_id
    $sqlSubmissions = "SELECT doc_submission_id, doc_student_id FROM document_submission WHERE doc_id = ?";
    $stmtSubmissions = $pdo->prepare($sqlSubmissions);
    $stmtSubmissions->execute([$doc_id]);
    $submissions = $stmtSubmissions->fetchAll(PDO::FETCH_ASSOC);

    // Loop through each submission
    foreach ($submissions as $submission) {
        // 1. Find doc file based on the file_path table
        $sqlFile = "SELECT file_name FROM file_path WHERE type_id = ? AND file_type = 'ds'";
        $stmtFile = $pdo->prepare($sqlFile);
        $stmtFile->execute([$submission['doc_submission_id']]);
        $fileInfo = $stmtFile->fetch(PDO::FETCH_ASSOC);

        // 2. Delete the student file from the directory
        $studentFilePath = '../../file/document/' . $fileInfo['file_name'];
        if (file_exists($studentFilePath)) {
            $deleted = unlink($studentFilePath);
            if (!$deleted) {
                echo "Error deleting the student file for doc_submission_id: ";
            }
        }

        // 3. Delete the file path based on the doc_submission_id
        $sqlDeleteFilePath = "DELETE FROM file_path WHERE type_id = ? AND file_type = 'ds'";
        $stmtDeleteFilePath = $pdo->prepare($sqlDeleteFilePath);
        $stmtDeleteFilePath->execute([$submission['doc_submission_id']]);
    }

    // 1. Find document file based on the file_path table
    $sql = "SELECT file_name FROM file_path WHERE type_id = ? AND file_type = 'dfypl'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$doc_id]);
    $fileInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($fileInfo) {
        // 2. Delete the file from the server or directory
        $filePath = '../../file/document/' . $fileInfo['file_name'];
        if (file_exists($filePath)) {
            $deleted = unlink($filePath);
            if (!$deleted) {
                echo "Error deleting the document file.";
            }
        }

        // 3. Delete the file path based on the doc_id
        $sqlDeleteFilePath = "DELETE FROM file_path WHERE type_id = ? AND file_type = 'dfypl'";
        $stmtDeleteFilePath = $pdo->prepare($sqlDeleteFilePath);
        $stmtDeleteFilePath->execute([$doc_id]);
    }

    // 4. Delete all doc submissions based on the doc_id
    $sqlDeleteSubmissions = "DELETE FROM document_submission WHERE doc_id = ?";
    $stmtDeleteSubmissions = $pdo->prepare($sqlDeleteSubmissions);
    $stmtDeleteSubmissions->execute([$doc_id]);

    // 4. Delete document details
    $sqlDeleteDocument = "DELETE FROM document WHERE doc_id = ?";
    $stmtDeleteDocument = $pdo->prepare($sqlDeleteDocument);
    $stmtDeleteDocument->execute([$doc_id]);

    header("Location: ../fypl_document.php");
} else {
    echo "Invalid request method.";
}
