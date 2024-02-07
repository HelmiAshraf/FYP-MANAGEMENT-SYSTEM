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
    $sv_id = $_POST["sv_id"];
    $sv_name = $_POST["sv_name"];
    $sv_email = $_POST["sv_email"];
    $sv_expertise = $_POST["sv_expertise"];
    $password = $_POST["password"];
    $role = 'supervisor';

    // Generate a unique token
    $verification_token = bin2hex(random_bytes(32)); // You can adjust the length as needed

    $uploadDirectory = '../file/image/';

    foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
        $file_name = $_FILES["files"]["name"][$key];
        $file_type = $_FILES["files"]["type"][$key];
        $file_size = $_FILES["files"]["size"][$key];

        // Generate a unique identifier and append it to the original file name
        $new_file_name = $sv_id . '_' . $file_name;

        $file_path = $uploadDirectory . $new_file_name;

        if (move_uploaded_file($tmp_name, $file_path)) {
            // File upload successful
        } else {
            // File upload failed
            echo '<script>alert("File upload failed. Please try again.");</script>';
            exit; // Abort the registration process
        }
    }

    $check_sql = "SELECT sv_id FROM supervisor WHERE sv_id = ? OR sv_email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("is", $sv_id, $sv_email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // sv_id ID already exists
        echo '<script>alert("Supervisor ID already exists. Please use a different ID."); window.location = "../register.php";</script>';
    } else {

        // Perform the SQL queries to insert data into 'sv_id' and 'user_accounts' tables
        $insert_account_sql = "INSERT INTO user (user_id, password, role, verify_email) VALUES (?, ?, ?, ?)";
        $insert_supervisor_sql = "INSERT INTO supervisor (sv_id, sv_name, sv_email, sv_expertise, sv_image) VALUES (?, ?, ?, ?, ?)";
        $insert_account_stmt = $conn->prepare($insert_account_sql);
        $insert_supervisor_stmt = $conn->prepare($insert_supervisor_sql);

        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

        $insert_account_stmt->bind_param("isss", $sv_id, $hashed_password, $role, $verification_token);
        $insert_supervisor_stmt->bind_param("issss", $sv_id, $sv_name, $sv_email, $sv_expertise, $file_path);

        if ($insert_account_stmt->execute() && $insert_supervisor_stmt->execute()) {
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
            $mail->addAddress($sv_email, $sv_name); // $st_email is the user's email
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
        $insert_supervisor_stmt->close();
        $insert_account_stmt->close();
    }
    // Close the database connection
    $check_stmt->close();
}
$conn->close();
?>
