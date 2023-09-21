<?php
// Check if 'student_id' is set in the session (assuming you have a session variable for student_id)
if (isset($_SESSION['student_id'])) {
    // Get the student_id from the session
    $student_id = $_SESSION['student_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Define your database credentials
        $db_host = 'localhost';
        $db_name = 'fypms';
        $db_user = 'root';
        $db_pass = '';

        try {
            // Create a PDO database connection
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            // Set PDO to throw exceptions on error
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Get task_id from the form (assuming it's named 'task_id' in the form)
            $task_id = $_POST['task_id'];

            // Retrieve supervisor_id associated with the student from the 'supervise' table
            $sql_supervisor = "SELECT supervisor_id FROM supervise WHERE student_id = ?";
            $stmt_supervisor = $pdo->prepare($sql_supervisor);
            $stmt_supervisor->bindParam(1, $student_id);
            $stmt_supervisor->execute();

            $row_supervisor = $stmt_supervisor->fetch(PDO::FETCH_ASSOC);

            if ($row_supervisor) {
                $supervisor_id = $row_supervisor['supervisor_id'];
                $submission_date = date("Y-m-d");

                // Insert submission details into the 'task_submission' table
                $sql_submission = "INSERT INTO task_submission (task_id, student_id, supervisor_id, submissiondate)
                                   VALUES (?, ?, ?, ?)";
                $stmt_submission = $pdo->prepare($sql_submission);
                $stmt_submission->bindParam(1, $task_id);
                $stmt_submission->bindParam(2, $student_id);
                $stmt_submission->bindParam(3, $supervisor_id);
                $stmt_submission->bindParam(4, $submission_date);
                $stmt_submission->execute();

                // Handle file uploads
                if (!empty($_FILES['files']['name'])) {
                    // Iterate through uploaded files
                    foreach ($_FILES['files']['name'] as $key => $file_name) {
                        $tmp_name = $_FILES['files']['tmp_name'][$key];
                        $file_content = file_get_contents($tmp_name);

                        // Insert file data into the 'file' table
                        $sql_file = "INSERT INTO file (file_name, file_content, task_id, uploader_id)
                                     VALUES (?, ?, ?, ?)";
                        $stmt_file = $pdo->prepare($sql_file);
                        $stmt_file->bindParam(1, $file_name);
                        $stmt_file->bindParam(2, $file_content, PDO::PARAM_LOB);
                        $stmt_file->bindParam(3, $task_id, PDO::PARAM_INT);
                        $stmt_file->bindParam(4, $student_id, PDO::PARAM_INT);
                        $stmt_file->execute();
                    }
                }

                // Redirect or show a success message
                echo "Task submitted successfully.";
            } else {
                echo "Supervisor not found for this student.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
        }
    }
}
