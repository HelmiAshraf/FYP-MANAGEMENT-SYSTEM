<?php
include '../../db_credentials.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tag"])) {
    $tag = $_POST["tag"];

    if ($tag == 1) {

        $uploadDirectory = "../../file/document/"; // Specify your upload directory

        try {
            // Create a PDO database connection
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            // Set PDO to throw exceptions on error
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Extract data from the form
            $doc_id = $_POST['doc_id'];
            $student_id = $_POST["student_id"];
            $submission_date = date("Y-m-d h:i:s");
            $uploader_id = $student_id;

            // Insert data into the 'document_submission' table
            $sql_submission = "INSERT INTO document_submission (doc_id, doc_student_id, doc_submissiondate)
                                VALUES (?, ?, ?)";
            $stmt_submission = $pdo->prepare($sql_submission);
            $stmt_submission->bindParam(1, $doc_id, PDO::PARAM_INT);
            $stmt_submission->bindParam(2, $student_id, PDO::PARAM_INT);
            $stmt_submission->bindParam(3, $submission_date);

            if ($stmt_submission->execute()) {
                // Get the doc_submission_id that was just inserted
                $doc_submission_id = $pdo->lastInsertId();

                // File upload configuration
                if (isset($_FILES["files"]["name"]) && !empty($_FILES["files"]["name"])) {
                    $file_name = $_FILES["files"]["name"];
                    $file_type = $_FILES["files"]["type"];
                    $file_size = $_FILES["files"]["size"];

                    $file_type = 'ds'; // ds = document submission
                    $file_name = $file_type . '_' . $doc_submission_id . '_' . $uploader_id . '_' . $_FILES["files"]["name"];

                    // Insert file data into the 'file_path' table using the retrieved doc_submission_id and updated file name
                    $sqlFile = "INSERT INTO file_path (file_name, type_id, file_type, file_uploader_id, file_path)
                                VALUES (?, ?, ?, ?, ?)";
                    $stmtFile = $pdo->prepare($sqlFile);
                    $stmtFile->bindParam(1, $file_name);
                    $stmtFile->bindParam(2, $doc_submission_id, PDO::PARAM_INT);
                    $stmtFile->bindParam(3, $file_type);
                    $stmtFile->bindParam(4, $uploader_id, PDO::PARAM_INT);
                    $stmtFile->bindParam(5, $uploadDirectory); // Ensure this is the correct file path

                    // Move the uploaded file to the server directory with the updated file name
                    if (move_uploaded_file($_FILES["files"]["tmp_name"], $uploadDirectory . $file_name)) {
                        // Insert file data into the 'file_path' table
                        if (!$stmtFile->execute()) {
                            echo "Error inserting file: " . $stmtFile->errorInfo()[2];
                        } else {
                            // Redirect to st_document_details.php with a success message and doc_id
                            header("Location: ../st_document_details.php?doc_id=" . $doc_id);
                            exit;
                        }
                    } else {
                        echo "Error moving uploaded file.";
                    }
                } else {
                    echo "No file uploaded.";
                }
            } else {
                echo "Error inserting Document submission: " . $stmt_submission->errorInfo()[2]; // Display SQL error message
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
        }
    } elseif ($tag == 2) {

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                // Create a PDO connection
                $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);

                // Set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $doc_id = $_POST['doc_id'];
                $student_id = $_POST['user_id'];

                // Check if the student has already submitted the document
                $sql_check_submission = "SELECT doc_submission_id, file_name FROM document_submission
                                        JOIN file_path ON document_submission.doc_submission_id = file_path.type_id
                                        WHERE doc_id = ? AND doc_student_id = ? AND file_type = 'ds'";
                $stmt_check_submission = $conn->prepare($sql_check_submission);
                $stmt_check_submission->execute([$doc_id, $student_id]);

                if ($stmt_check_submission->rowCount() > 0) {
                    $submission_data = $stmt_check_submission->fetch(PDO::FETCH_ASSOC);
                    $doc_submission_id = $submission_data['doc_submission_id'];
                    $file_name = $submission_data['file_name'];

                    // Delete the file from the server or directory
                    $uploadDirectory = "../../file/document/"; // Specify your upload directory
                    $file_path = $uploadDirectory . $file_name;

                    if (file_exists($file_path)) {
                        unlink($file_path); // Delete the file
                    }

                    // Delete the file path entry
                    $sql_delete_file_path = "DELETE FROM file_path WHERE type_id = ? AND file_type = 'ds' ";
                    $stmt_delete_file_path = $conn->prepare($sql_delete_file_path);
                    $stmt_delete_file_path->execute([$doc_submission_id]);

                    // Delete the document submission entry
                    $sql_delete_submission = "DELETE FROM document_submission WHERE doc_submission_id = ?";
                    $stmt_delete_submission = $conn->prepare($sql_delete_submission);
                    $stmt_delete_submission->execute([$doc_submission_id]);

                    // Provide feedback or redirect to a page
                    header("Location: ../st_document_details.php?doc_id=" . $doc_id);
                } else {
                    echo "No submission found for the given document.";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        } else {
            echo "Invalid request method.";
        }
    }
}
