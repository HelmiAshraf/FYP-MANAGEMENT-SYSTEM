<?php
// Include the necessary files
require_once '../../db_credentials.php'; // Ensure this file contains your database credentials

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get form data
    $task_id = $_POST['task_id'];

    // 1. Find all task submissions based on the task_id
    $sqlSubmissions = "SELECT task_submission_id, student_id FROM task_submission WHERE task_id = ?";
    $stmtSubmissions = $pdo->prepare($sqlSubmissions);
    $stmtSubmissions->execute([$task_id]);
    $submissions = $stmtSubmissions->fetchAll(PDO::FETCH_ASSOC);

    // Loop through each submission
    foreach ($submissions as $submission) {
        // 1. Find task file based on the file_path table
        $sqlFile = "SELECT file_name FROM file_path WHERE type_id = ? AND file_type = 'ts'";
        $stmtFile = $pdo->prepare($sqlFile);
        $stmtFile->execute([$submission['task_submission_id']]);
        $fileInfo = $stmtFile->fetch(PDO::FETCH_ASSOC);

        // 2. Delete the student file from the directory
        $studentFilePath = '../../file/task/' . $fileInfo['file_name'];
        if (file_exists($studentFilePath)) {
            $deleted = unlink($studentFilePath);
            if (!$deleted) {
                echo "Error deleting the student file for task_submission_id: " . $submission['task_submission_id'];
            }
        }

        // 3. Delete the file path based on the task_submission_id
        $sqlDeleteFilePath = "DELETE FROM file_path WHERE type_id = ? AND file_type = 'ts'";
        $stmtDeleteFilePath = $pdo->prepare($sqlDeleteFilePath);
        $stmtDeleteFilePath->execute([$submission['task_submission_id']]);
    }

    // 1. Find task file based on the file_path table
    $sqlTaskFile = "SELECT file_name FROM file_path WHERE type_id = ? AND file_type = 'tfypl'";
    $stmtTaskFile = $pdo->prepare($sqlTaskFile);
    $stmtTaskFile->execute([$task_id]);
    $fileInfo = $stmtTaskFile->fetch(PDO::FETCH_ASSOC);

    if ($fileInfo) {
        // 2. Delete the file from the server or directory
        $filePath = '../../file/task/' . $fileInfo['file_name'];
        if (file_exists($filePath)) {
            $deleted = unlink($filePath);
            if (!$deleted) {
                echo "Error deleting the task file.";
            }
        }

        // 3. Delete the file path based on the doc_id
        $sqlDeleteFilePath = "DELETE FROM file_path WHERE type_id = ? AND file_type = 'tfypl'";
        $stmtDeleteFilePath = $pdo->prepare($sqlDeleteFilePath);
        $stmtDeleteFilePath->execute([$task_id]);
    }

    // 4. Delete all task submissions based on the task_id
    $sqlDeleteSubmissions = "DELETE FROM task_submission WHERE task_id = ?";
    $stmtDeleteSubmissions = $pdo->prepare($sqlDeleteSubmissions);
    $stmtDeleteSubmissions->execute([$task_id]);

    // 5. Delete task details
    $sqlDeleteTask = "DELETE FROM task WHERE task_id = ?";
    $stmtDeleteTask = $pdo->prepare($sqlDeleteTask);
    $stmtDeleteTask->execute([$task_id]);

    header("Location: ../fypl_task.php");
} else {
    echo "Invalid request method.";
}
