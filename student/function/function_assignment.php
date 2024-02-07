<?php
include '../../db_credentials.php';
$tag = $_REQUEST["tag"];

if ($tag == 1) {

    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES["files"]["name"])) {
        $uploadDirectory = "../../file/assignment/"; // Specify your upload directory

        try {
            // Create a PDO connection
            $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);

            // Set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $ass_id = $_POST['ass_id'];
            $student_id = $_POST['student_id'];
            $submission_date = date("Y-m-d H:i:s");
            $uploader_id = $student_id;

            // Get the supervisor_id from the 'supervise' table
            $sql_supervisor = "SELECT supervisor_id FROM supervise WHERE student_id = ?";
            $stmt_supervisor = $conn->prepare($sql_supervisor);
            $stmt_supervisor->execute([$student_id]);

            if ($stmt_supervisor->rowCount() > 0) {
                $supervisor_id = $stmt_supervisor->fetchColumn();
                $stmt_supervisor->closeCursor();

                // Insert data into 'ass_submission' table
                $sql_submission = "INSERT INTO assignment_submission (ass_id, ass_student_id, ass_supervisor_id, ass_submissiondate) 
                                    VALUES (?, ?, ?, ?)";
                $stmt_submission = $conn->prepare($sql_submission);
                $stmt_submission->bindParam(1, $ass_id, PDO::PARAM_INT);
                $stmt_submission->bindParam(2, $student_id, PDO::PARAM_INT);
                $stmt_submission->bindParam(3, $supervisor_id, PDO::PARAM_INT);
                $stmt_submission->bindParam(4, $submission_date);

                // Execute the statement
                $stmt_submission->execute();

                if ($stmt_submission->rowCount() > 0) {
                    // Get the ass_submission_id that was just inserted
                    $ass_submission_id = $conn->lastInsertId();

                    // File upload configuration
                    $file_name = $_FILES["files"]["name"];
                    $file_type = $_FILES["files"]["type"];
                    $file_size = $_FILES["files"]["size"];

                    $file_type = 'as'; // as = assignment submission
                    $file_name = $file_type . '_' . $ass_submission_id . '_' . $uploader_id . '_' . $_FILES["files"]["name"];

                    // Insert file data into the 'file_path' table using the retrieved ass_submission_id and updated file name
                    $sqlFile = "INSERT INTO file_path (file_name, type_id, file_type, file_uploader_id, file_path)
                        VALUES (?, ?, ?, ?, ?)";
                    $stmtFile = $conn->prepare($sqlFile);
                    $stmtFile->bindParam(1, $file_name);
                    $stmtFile->bindParam(2, $ass_submission_id, PDO::PARAM_INT);
                    $stmtFile->bindParam(3, $file_type);
                    $stmtFile->bindParam(4, $uploader_id, PDO::PARAM_INT);
                    $stmtFile->bindParam(5, $uploadDirectory); // Ensure this is the correct file path

                    // Move the uploaded file to the server directory with the updated file name
                    if (move_uploaded_file($_FILES["files"]["tmp_name"], $uploadDirectory . $file_name)) {
                        // Execute the statement
                        if ($stmtFile->execute()) {
                            // Redirect to st_assignment_details.php with a success message and ass_id
                            header("Location: ../st_assignment_details.php?ass_id=" . $ass_id);
                            exit;
                        } else {
                            echo "Error inserting file: " . $stmtFile->errorInfo()[2];
                        }
                    } else {
                        echo "Error moving uploaded file.";
                    }
                }
            } else {
                echo "Supervisor not found for this student.";
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    } else {
        echo "Invalid or missing ass_id or no file uploaded.";
    }
} elseif ($tag == 2)
{
   
    
}
