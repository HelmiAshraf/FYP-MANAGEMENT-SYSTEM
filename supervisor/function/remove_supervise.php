<?php
include '../../db_credentials.php';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$st_id = $_GET['st_id'];

// Check if student ID is provided
if ($st_id) {
    try {
        // Deleting records from 'supervise' table
        $superviseDeleteQuery = "DELETE FROM supervise WHERE student_id = ?";
        $superviseStatement = $conn->prepare($superviseDeleteQuery);
        $superviseStatement->bind_param('s', $st_id);
        $superviseStatement->execute();

        // Deleting records from 'project' table
        $projectDeleteQuery = "DELETE FROM project WHERE student_id = ?";
        $projectStatement = $conn->prepare($projectDeleteQuery);
        $projectStatement->bind_param('s', $st_id);
        $projectStatement->execute();

        // Deleting records from 'gantt_chart_task' and 'gantt_chart' tables
        $ganttChartTaskDeleteQuery = "DELETE gct, gctt FROM gantt_chart gct
                                      LEFT JOIN gantt_chart_task gctt ON gct.gantt_chart_id = gctt.gantt_chart_id
                                      WHERE gct.student_id = ?";
        $ganttChartTaskStatement = $conn->prepare($ganttChartTaskDeleteQuery);
        $ganttChartTaskStatement->bind_param('s', $st_id);
        $ganttChartTaskStatement->execute();

        // Optionally, you can add additional logic or redirection after unlinking

        // Example: Redirecting to a success page
        header("Location: ../sv_supervisee.php");
        exit();
    } catch (Exception $e) {
        // Handle errors if necessary
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle the case when student ID is not provided
    echo "Student ID not provided.";
}
