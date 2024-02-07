<?php
include '../../db_credentials.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tag"])) {
    $tag = $_POST["tag"];

    if ($tag == 1) {

        $uploadDirectory = ' ../../../../file/task/'; // Change this to your desired file path

        try {
            // Create a PDO database connection
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            // Set PDO to throw exceptions on error
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Extract data from the form
            $task_id = $_POST['task_id'];
            $student_id = $_POST["user_id"];
            $submission_date = date("Y-m-d H:i:s");
            $uploader_id = $_POST["user_id"];

            // Retrieve task_sv_id from the 'task' table
            $sql_task_sv = "SELECT task_sv_id FROM task WHERE task_id = ?";
            $stmt_task_sv = $pdo->prepare($sql_task_sv);
            $stmt_task_sv->bindParam(1, $task_id);
            $stmt_task_sv->execute();

            $row_task_sv = $stmt_task_sv->fetch(PDO::FETCH_ASSOC);

            if (isset($_FILES["files"]["name"]) && !empty($_FILES["files"]["name"])) {
                if ($row_task_sv) {
                    $task_sv_id = $row_task_sv['task_sv_id'];
                    $file_name = $_FILES["files"]["name"];
                    $file_type = $_FILES["files"]["type"];
                    $file_size = $_FILES["files"]["size"];
                    $file_path = $uploadDirectory;

                    // Insert data into the 'task_submission' table
                    $sql_submission = "INSERT INTO task_submission (task_id, student_id, supervisor_id, submissiondate)
                                    VALUES (?, ?, ?, ?)";
                    $stmt_submission = $pdo->prepare($sql_submission);
                    $stmt_submission->bindParam(1, $task_id);
                    $stmt_submission->bindParam(2, $student_id);
                    $stmt_submission->bindParam(3, $task_sv_id); // Use task_sv_id as supervisor_id
                    $stmt_submission->bindParam(4, $submission_date);

                    if ($stmt_submission->execute()) {
                        // Retrieve the inserted task_submission_id
                        $task_submission_id = $pdo->lastInsertId();

                        // File path configuration
                        $file_type = 'ts'; // task = t
                        $file_name =  $file_type . '_' . $task_submission_id . '_' . $uploader_id . '_' . $_FILES["files"]["name"];

                        // Insert file data into the 'file_path' table
                        $sqlFile = "INSERT INTO file_path (file_name, type_id, file_type, file_uploader_id, file_path)
                                VALUES (?, ?, ?, ?, ?)";
                        $stmtFile = $pdo->prepare($sqlFile);
                        $stmtFile->bindParam(1, $file_name);
                        $stmtFile->bindParam(2, $task_submission_id);
                        $stmtFile->bindParam(3, $file_type);
                        $stmtFile->bindParam(4, $uploader_id);
                        $stmtFile->bindParam(5, $file_path);

                        // Move the uploaded file to the server directory with the updated file name
                        if (move_uploaded_file($_FILES["files"]["tmp_name"], $file_path . $file_name)) {
                            // Insert file data into the 'file_path' table
                            if (!$stmtFile->execute()) {
                                echo "Error inserting file: " . $stmtFile->errorInfo()[2];
                            } else {
                                // Redirect to st_task_details.php with a success message and task_id
                                header("Location: ../st_task_details.php?task_id=" . $task_id);
                                exit;
                            }
                        } else {
                            echo "Error moving uploaded file.";
                        }
                    } else {
                        echo "Error inserting task submission: " . $stmt_submission->errorInfo()[2];
                    }
                } else {
                    echo "Task supervisor not found for this task.";
                }
            } else {
                echo "No files uploaded.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
        }
    } elseif ($tag == 2) {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                // Create a PDO connection
                $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);

                // Set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $task_id = $_POST['task_id'];
                $student_id = $_POST['user_id'];

                // Check if the student has already submitted the taskument
                $sql_check_submission = "SELECT task_submission_id, file_name FROM task_submission
                                        JOIN file_path ON task_submission.task_submission_id = file_path.type_id
                                        WHERE task_id = ? AND student_id = ? AND file_type = 'ts'";
                $stmt_check_submission = $conn->prepare($sql_check_submission);
                $stmt_check_submission->execute([$task_id, $student_id]);

                if ($stmt_check_submission->rowCount() > 0) {
                    $submission_data = $stmt_check_submission->fetch(PDO::FETCH_ASSOC);
                    $task_submission_id = $submission_data['task_submission_id'];
                    $file_name = $submission_data['file_name'];

                    // Delete the file from the server or directory
                    $uploadDirectory = "../../file/task/"; // Specify your upload directory
                    $file_path = $uploadDirectory . $file_name;

                    if (file_exists($file_path)) {
                        unlink($file_path); // Delete the file
                    }

                    // Delete the file path entry
                    $sql_delete_file_path = "DELETE FROM file_path WHERE type_id = ? AND file_type = 'ts' ";
                    $stmt_delete_file_path = $conn->prepare($sql_delete_file_path);
                    $stmt_delete_file_path->execute([$task_submission_id]);

                    // Delete the task submission entry
                    $sql_delete_submission = "DELETE FROM task_submission WHERE task_submission_id = ?";
                    $stmt_delete_submission = $conn->prepare($sql_delete_submission);
                    $stmt_delete_submission->execute([$task_submission_id]);

                    // Provide feedback or redirect to a page
                    header("Location: ../st_task_details.php?task_id=" . $task_id);
                } else {
                    echo "No submission found for the given task.";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        } else {
            echo "Invalid request method.";
        }
    }
}
