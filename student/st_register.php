<?php
include '../db.php';

// Include PHPMailer autoload file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $student_id = $_POST["student_id"];
    $name = $_POST["st_name"];
    $st_email = $_POST["st_email"];
    $password = $_POST["password"]; // Raw password from the form

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $role = 'student';

    // Generate a unique token
    $verification_token = bin2hex(random_bytes(32)); // You can adjust the length as needed

    $uploadDirectory = '../file/image/';

    foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
        $file_name = $_FILES["files"]["name"][$key];
        $file_type = $_FILES["files"]["type"][$key];
        $file_size = $_FILES["files"]["size"][$key];

        // Generate a unique identifier and append it to the original file name
        $new_file_name = $student_id . '_' . $file_name;

        $file_path = $uploadDirectory . $new_file_name;

        if (move_uploaded_file($tmp_name, $file_path)) {
            // File upload successful
        } else {
            // File upload failed
            echo '<script>alert("File upload failed. Please try again.");</script>';
            exit; // Abort the registration process
        }
    }

    $check_sql = "SELECT st_id FROM student WHERE st_id = ? OR st_email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $student_id, $st_email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Student ID or email already exists
        echo '<script>alert("Student ID or email already exists. Please use a different ID or email."); window.location = "../register.php";</script>';
    } else {
        // Get the batch_id for category 'csp600'
        $batch_id = getBatchId($conn, 'CSP600');
        // Perform the SQL queries to insert data into 'students' and 'user_accounts' tables
        $insert_account_sql = "INSERT INTO user (user_id, password, role, verify_email) VALUES (?, ?, ?, ?)";
        $insert_student_sql = "INSERT INTO student (st_id, st_name, st_email, st_batch, st_image) VALUES (?, ?, ?, ?, ?)";
        $insert_account_stmt = $conn->prepare($insert_account_sql);
        $insert_student_stmt = $conn->prepare($insert_student_sql);

        $insert_account_stmt->bind_param("isss", $student_id, $hashed_password, $role, $verification_token);
        $insert_student_stmt->bind_param("issis", $student_id, $name, $st_email, $batch_id, $file_path);

        if ($insert_account_stmt->execute() && $insert_student_stmt->execute()) {
            // Registration successful

            // Send verification email with $verification_token
            $verification_link = "http://localhost/FYPS/verify_email.php?token=$verification_token";

            // Use PHPMailer to send the verification email
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->Username = 'fypms.uitm@gmail.com';
            $mail->Password = 'syasldvkvokucfij';
            $mail->setFrom('fypms.uitm@gmail.com', 'FYPMS');
            $mail->addAddress($st_email, $name); // $st_email is the user's email
            $mail->Subject = 'Email Verification';
            $mail->Body = "Click on the following link to verify your email: <a href='$verification_link'>Verify Email</a>";
            $mail->isHTML(true);

            try {
                $mail->send();
                echo '<script>alert("Registration successful. Check your email for verification instructions."); window.location = "../login.php";</script>';
            } catch (Exception $e) {
                echo '<script>alert("Registration successful, but failed to send verification email. Please contact support.");</script>';
            }
        } else {
            // Registration failed
            echo '<script>alert("Registration failed. Please try again.");</script>';
        }

        // Close the prepared statements
        $insert_student_stmt->close();
        $insert_account_stmt->close();
    }

    // Close the database connection
    $check_stmt->close();
}

$conn->close();

// Function to get the batch_id for a given category
function getBatchId($conn, $category)
{
    $batch_id = null;
    $select_batch_sql = "SELECT batch_id FROM batches WHERE batch_category = ? LIMIT 1";
    $select_batch_stmt = $conn->prepare($select_batch_sql);
    $select_batch_stmt->bind_param("s", $category);
    $select_batch_stmt->execute();
    $result = $select_batch_stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $batch_id = $row['batch_id'];
    }

    $select_batch_stmt->close();
    return $batch_id;
}
?>
