<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tag"])) {
    $tag = $_POST["tag"];

    if ($tag == 1) {
        // Define your database credentials
        include '../../db_credentials.php';

        try {
            // Create a PDO database connection
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            // Set PDO to throw exceptions on error
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Extract data from the form
            $form_id = $_POST['form_id'];
            $student_id = $_POST["user_id"];
            $submission_date = date("Y-m-d H:i:s");

            // Retrieve form_sv_id from the 'form' table
            $sql_form_sv = "SELECT form_sv_id FROM form WHERE form_id = ?";
            $stmt_form_sv = $pdo->prepare($sql_form_sv);
            $stmt_form_sv->bindParam(1, $form_id);
            $stmt_form_sv->execute();

            $row_form_sv = $stmt_form_sv->fetch(PDO::FETCH_ASSOC);

            if ($row_form_sv) {
                $form_sv_id = $row_form_sv['form_sv_id'];

                // Insert data into the 'form_submission' table
                $sql_submission = "INSERT INTO form_submission (form_id, student_id, supervisor_id, submissiondate)
                                    VALUES (?, ?, ?, ?)";
                $stmt_submission = $pdo->prepare($sql_submission);
                $stmt_submission->bindParam(1, $form_id);
                $stmt_submission->bindParam(2, $student_id);
                $stmt_submission->bindParam(3, $form_sv_id); // Use form_sv_id as supervisor_id
                $stmt_submission->bindParam(4, $submission_date);

                if ($stmt_submission->execute()) {
                    // Retrieve the inserted form_submission_id
                    $form_submission_id = $pdo->lastInsertId();

                    // Upload files to the database
                    foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
                        $file_name = $_FILES["files"]["name"][$key];
                        $file_data = file_get_contents($tmp_name); // Get file content as binary data

                        // Insert file data into the 'file' table using the form_submission_id
                        $sql = "INSERT INTO file (file_name, file_content, type_id, file_type, file_uploader_id)
                                VALUES (?, ?, ?, ?, ?)";

                        $stmt = $pdo->prepare($sql);
                        $file_type = "form_submission";
                        $stmt->bindParam(1, $file_name);
                        $stmt->bindParam(2, $file_data, PDO::PARAM_LOB);
                        $stmt->bindParam(3, $form_submission_id); // Use form_submission_id
                        $stmt->bindParam(4, $file_type); // Make this = "form_submission"
                        $stmt->bindParam(5, $student_id); // Corrected index

                        if (!$stmt->execute()) {
                            echo "Error inserting file: " . $stmt->errorInfo()[2]; // Display SQL error message
                        }
                    }

                    // Redirect to st_form_details.php with a success message and form_id
                    header("Location: ../st_form_details.php?form_id=" . $form_id);
                    exit;
                } else {
                    echo "Error inserting form submission: " . $stmt_submission->errorInfo()[2]; // Display SQL error message
                }
            } else {
                echo "form supervisor not found for this form.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
        }
    } elseif ($tag == 2) {
    }
}
