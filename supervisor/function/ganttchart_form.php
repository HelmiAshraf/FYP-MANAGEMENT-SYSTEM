<?php
include '../../db_credentials.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tag"])) {
    $tag = $_POST["tag"];

    if ($tag == 1) {
        // update

        // Create a database connection
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $gantt_chart_task_id = $_POST['gantt_chart_task_id'];
        $task_name = $_POST['task_name'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $student_id = $_POST['student_id'];

        $sql = "SELECT gantt_chart_id FROM gantt_chart_task WHERE gantt_chart_task_id = $gantt_chart_task_id";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $gantt_chart_id = $row['gantt_chart_id'];
        }

        // Construct the SQL update query
        $sql = "UPDATE gantt_chart_task SET
                task_name = '$task_name',
                start_date = '$start_date',
                end_date = '$end_date',
                gantt_chart_id = '$gantt_chart_id'
                WHERE gantt_chart_task_id = $gantt_chart_task_id";

        // Execute the update query
        if ($conn->query($sql) === TRUE) {
            header("Location: ../sv_supervisee_detail.php?st_id=" . $student_id . "&gantt_chart_id=" . $gantt_chart_id);
        } else {
            echo "Error updating record: " . $conn->error;
        }

        // Close the database connection
        $conn->close();
    } elseif ($tag == 2) {
        // delete 

        // Create a database connection
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $gantt_chart_task_id = $_POST['gantt_chart_task_id'];
        $student_id = $_POST['student_id'];
        $gantt_chart_id = $_POST['gantt_chart_id'];


        // Construct the SQL delete query
        $sql = "DELETE FROM gantt_chart_task WHERE gantt_chart_task_id = $gantt_chart_task_id";

        // Execute the delete query
        if ($conn->query($sql) === TRUE) {
            header("Location: ../sv_supervisee_detail.php?st_id=" . $student_id . "&gantt_chart_id=" . $gantt_chart_id);
        } else {
            echo "Error deleting record: " . $conn->error;
        }

        // Close the database connection
        $conn->close();
    } elseif ($tag == 3) {

        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $student_id = $_POST['student_id'];
        $gantt_chart_id = $_POST['gantt_chart_id'];
        
        // Assume task_name, start_date, end_date, and gantt_chart_id are arrays
        $task_names = $_POST['task_name'];
        $start_dates = $_POST['start_date'];
        $end_dates = $_POST['end_date'];
        
        // Loop through the tasks and insert them into the database
        for ($i = 0; $i < count($task_names); $i++) {
            $task_name = $conn->real_escape_string($task_names[$i]);
            $start_date = $conn->real_escape_string($start_dates[$i]);
            $end_date = $conn->real_escape_string($end_dates[$i]);
        
            // Construct the SQL insert query
            $sql = "INSERT INTO gantt_chart_task (gantt_chart_id, task_name, start_date, end_date)
                    VALUES ('$gantt_chart_id', '$task_name', '$start_date', '$end_date')";
        
            // Execute the insert query
            if ($conn->query($sql) !== TRUE) {
                echo "Error creating record: " . $conn->error;
            }
        }
        
        // Redirect after processing all tasks
        header("Location: ../sv_supervisee_detail.php?st_id=" . $student_id . "&gantt_chart_id=" . $gantt_chart_id);
        
        // Close the database connection
        $conn->close();
    
        
    }
}
