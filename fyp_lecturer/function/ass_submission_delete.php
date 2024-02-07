<?php
// Include the necessary files
require_once '../../db_credentials.php'; // Ensure this file contains your database credentials

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ass_submission_id'])) {
    // Get the assignment submission ID from the URL parameter
    $ass_submission_id = $_GET['ass_submission_id'];
    $ass_id = $_GET['ass_id'];

    try {
        // Create a PDO database connection
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        // Set PDO to throw exceptions on error
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update ass_status to 3
        $sqlUpdateStatus = "UPDATE assignment_submission SET ass_status = 3 WHERE ass_submission_id = ?";
        $stmtUpdateStatus = $pdo->prepare($sqlUpdateStatus);
        $stmtUpdateStatus->execute([$ass_submission_id]);

        // Redirect after successful update
        header("Location: ../fypl_assignment_details.php?ass_id=$ass_id");
        exit(); // Make sure to exit after redirect
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method or missing ass_submission_id.";
}
?>
