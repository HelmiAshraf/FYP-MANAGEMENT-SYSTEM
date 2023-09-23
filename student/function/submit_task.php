<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tag"])) {
    $tag = $_POST["tag"];

    if ($tag == 6) {
        // Define your database credentials
        $db_host = 'localhost';  // Your database host
        $db_name = 'fypms'; // Your database name
        $db_user = 'root'; // Your database username
        $db_pass = ''; // Your database password

        try {
            // Create a PDO database connection
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            // Set PDO to throw exceptions on error
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Extract data from the form
            $task_id = $_POST['task_id'];
            $student_id = $_POST["user_id"];
            $submission_date = date("Y-m-d");

            // Retrieve supervisor_id associated with the student from the 'supervise' table
            $sql_supervisor = "SELECT supervisor_id FROM supervise WHERE student_id = ?";
            $stmt_supervisor = $pdo->prepare($sql_supervisor);
            $stmt_supervisor->bindParam(1, $student_id);
            $stmt_supervisor->execute();

            $row_supervisor = $stmt_supervisor->fetch(PDO::FETCH_ASSOC);

            if ($row_supervisor) {
                $supervisor_id = $row_supervisor['supervisor_id'];

                // Insert data into the 'task_submission' table
                $sql_submission = "INSERT INTO task_submission (task_id, student_id, supervisor_id, submissiondate)
                                VALUES (?, ?, ?, ?)";
                $stmt_submission = $pdo->prepare($sql_submission);
                $stmt_submission->bindParam(1, $task_id);
                $stmt_submission->bindParam(2, $student_id);
                $stmt_submission->bindParam(3, $supervisor_id);
                $stmt_submission->bindParam(4, $submission_date);

                if ($stmt_submission->execute()) {
                    // Upload files to the database
                    foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
                        $file_name = $_FILES["files"]["name"][$key];
                        $file_data = file_get_contents($tmp_name); // Get file content as binary data

                        // Insert file data into the 'file' table
                        $sql = "INSERT INTO file (file_name, file_content, task_id, uploader_id)
                                VALUES (?, ?, ?, ?)";

                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(1, $file_name);
                        $stmt->bindParam(2, $file_data, PDO::PARAM_LOB);
                        $stmt->bindParam(3, $task_id);
                        $stmt->bindParam(4, $student_id);

                        if (!$stmt->execute()) {
                            echo "Error inserting file: " . $stmt->errorInfo()[2]; // Display SQL error message
                        }
                    }

                    // Redirect to st_task_details.php with a success message and task_id
                    header("Location: ../st_task_details.php?task_id=" . $task_id);
                    exit;
                } else {
                    echo "Error inserting task submission: " . $stmt_submission->errorInfo()[2]; // Display SQL error message
                }
            } else {
                echo "Supervisor not found for this student.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
        }
    }
}
