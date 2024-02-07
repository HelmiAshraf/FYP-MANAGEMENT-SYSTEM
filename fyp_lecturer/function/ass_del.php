<?php
// Include the necessary files
require_once '../../db_credentials.php'; // Ensure this file contains your database credentials

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ass_id'])) {
    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $ass_id = $_GET['ass_id'];

    // 1. Find all ass submissions based on the ass_id
    $sqlSubmissions = "SELECT ass_submission_id, ass_student_id FROM assignment_submission WHERE ass_id = ?";
    $stmtSubmissions = $pdo->prepare($sqlSubmissions);
    $stmtSubmissions->execute([$ass_id]);
    $submissions = $stmtSubmissions->fetchAll(PDO::FETCH_ASSOC);

    // Loop through each submission
    foreach ($submissions as $submission) {
        // 1. Find ass file based on the file_path table
        $sqlFile = "SELECT file_name FROM file_path WHERE type_id = ? AND file_type = 'as'";
        $stmtFile = $pdo->prepare($sqlFile);
        $stmtFile->execute([$submission['ass_submission_id']]);
        $fileInfo = $stmtFile->fetch(PDO::FETCH_ASSOC);

        // 2. Delete the student file from the directory
        $studentFilePath = '../../file/assignment/' . $fileInfo['file_name'];
        if (file_exists($studentFilePath)) {
            $deleted = unlink($studentFilePath);
            if (!$deleted) {
                echo "Error deleting the student file  ";
            }
        }

        // 3. Delete the file path based on the ass_submission_id
        $sqlDeleteFilePath = "DELETE FROM file_path WHERE type_id = ? AND file_type = 'as'";
        $stmtDeleteFilePath = $pdo->prepare($sqlDeleteFilePath);
        $stmtDeleteFilePath->execute([$submission['ass_submission_id']]);
    }

    // 4. Delete all ass submissions based on the ass_id
    $sqlDeleteSubmissions = "DELETE FROM assignment_submission WHERE ass_id = ?";
    $stmtDeleteSubmissions = $pdo->prepare($sqlDeleteSubmissions);
    $stmtDeleteSubmissions->execute([$ass_id]);

    // 4. Delete assument details
    $sqlDeleteassument = "DELETE FROM assignment WHERE ass_id = ?";
    $stmtDeleteassument = $pdo->prepare($sqlDeleteassument);
    $stmtDeleteassument->execute([$ass_id]);

    header("Location: ../fypl_assignment.php");
} else {
    echo "Invalid request method.";
}
