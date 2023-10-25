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
            $form_id = $_POST['form_id'];
            $student_id = $_POST["student_id"];
            $submission_date = date("Y-m-d h:i:s");

            // Insert data into the 'form_submission' table
            $sql_submission = "INSERT INTO form_submission (form_id, student_id, submissiondate)
                                VALUES (?, ?, ?)";
            $stmt_submission = $pdo->prepare($sql_submission);
            $stmt_submission->bindParam(1, $form_id);
            $stmt_submission->bindParam(2, $student_id);
            $stmt_submission->bindParam(3, $submission_date);

            if ($stmt_submission->execute()) {
                $form_submission_id = $pdo->lastInsertId();

                // Initialize type_id
                $type_id = $form_submission_id;

                // Upload files to the database
                foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
                    $file_name = $_FILES["files"]["name"][$key];
                    $file_data = file_get_contents($tmp_name);

                    // Insert file data into the 'file' table
                    $sql = "INSERT INTO file (file_name, file_content, type_id, file_type, file_uploader_id)
                            VALUES (?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $file_type = "form_submission";
                    $stmt->bindParam(1, $file_name);
                    $stmt->bindParam(2, $file_data, PDO::PARAM_LOB);
                    $stmt->bindParam(3, $type_id, PDO::PARAM_INT);
                    $stmt->bindParam(4, $file_type); // Make this = "form_submission"
                    $stmt->bindParam(5, $student_id, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        // Check if type_id is not set (first iteration), set it to the last inserted ID
                        if ($type_id === null) {
                            $type_id = $pdo->lastInsertId();
                        }
                    } else {
                        echo "Error inserting file: " . $stmt->errorInfo()[2]; // Display SQL error message
                    }
                }

                // Redirect to st_form_details.php with a success message and form_id
                header("Location: ../st_form_details.php?form_id=" . $form_id);
                exit;
            } else {
                echo "Error inserting form submission: " . $stmt_submission->errorInfo()[2]; // Display SQL error message
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            die();
        }
    }
}
