<?php
session_start();
include '../../db.php';


// Create a database connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check which button was clicked
if (isset($_POST['accept'])) {
    // Handle the "Accept" action
    $supervisor_id = $_POST['supervisor_id']; // Get supervisor_id from the form
    $student_id = $_POST['student_id'];       // Get student_id from the form
    $project_id = $_POST['project_id']; 

    // Insert a record into the supervise table
    $insertSql = "INSERT INTO supervise (supervisor_id, student_id) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("ii", $supervisor_id, $student_id);

    $sqlSelect = "SELECT * FROM student WHERE st_id =?";
    $stmtSelect = $conn->prepare($sqlSelect);
    $stmtSelect->bind_param("i", $student_id); // Assuming student_id is an integer
    $stmtSelect->execute();
    $resultSelect = $stmtSelect->get_result();

    if ($resultSelect->num_rows > 0) {
        $rowS = $resultSelect->fetch_assoc();
        $batch_id = $rowS['st_batch'];
    }

    $sqlInsertGantt = "INSERT INTO gantt_chart (student_id, supervisor_id, batch_id) VALUES (?, ?, ?)";
    $stmtInsertGantt = $conn->prepare($sqlInsertGantt);
    $stmtInsertGantt->bind_param("isi", $student_id, $supervisor_id, $batch_id);

    // Execute the gantt_chart INSERT query
    $stmtInsertGantt->execute();

    if ($insertStmt->execute() && $stmtInsertGantt->affected_rows > 0) {
        // Update the project_status to 1 (Accepted) in the project table
        $updateSql = "UPDATE project SET project_status = 1 WHERE student_id = ? AND project_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ii", $student_id, $project_id);

        if ($updateStmt->execute()) {
            // Redirect to a success page or display a success message
            header("Location: ../sv_st_propose.php");
            exit();
        } else {
            // Handle the update error
            echo "Error updating project status: " . $conn->error;
        }
    }
} elseif (isset($_POST['reject'])) {
    // Handle the "Reject" action
    $student_id = $_POST['student_id']; // Get student_id from the form

    // Delete the project data related to the student_id
    $deleteSql = "UPDATE project SET project_status = 0 WHERE student_id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $student_id);

    if ($deleteStmt->execute()) {
        // Redirect to a success page or display a success message
        header("Location: ../sv_st_propose.php");
        exit();
    } else {
        // Handle the delete error
        echo "Error deleting project data: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
