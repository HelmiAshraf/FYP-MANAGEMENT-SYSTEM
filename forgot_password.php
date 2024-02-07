<?php
include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    $email = $_POST["email"];

    // Check if the email exists in the student table
    $checkStudentQuery = "SELECT st_id, st_name FROM student WHERE st_email = ?";
    $checkStudentStmt = $conn->prepare($checkStudentQuery);
    $checkStudentStmt->bind_param("s", $email);
    $checkStudentStmt->execute();
    $studentResult = $checkStudentStmt->get_result();

    // Check if the email exists in the supervisor table
    $checkSupervisorQuery = "SELECT sv_id, sv_name FROM supervisor WHERE sv_email = ?";
    $checkSupervisorStmt = $conn->prepare($checkSupervisorQuery);
    $checkSupervisorStmt->bind_param("s", $email);
    $checkSupervisorStmt->execute();
    $supervisorResult = $checkSupervisorStmt->get_result();

    if ($studentResult->num_rows > 0) {
        // Email found in the student table
        $userType = 'student';
        $row = $studentResult->fetch_assoc();
        $userId = $row['st_id'];
        $userName = $row['st_name'];
    } elseif ($supervisorResult->num_rows > 0) {
        // Email found in the supervisor table
        $userType = 'supervisor';
        $row = $supervisorResult->fetch_assoc();
        $userId = $row['sv_id'];
        $userName = $row['sv_name'];
    } else {
        // Email not found
        echo '<script>alert("Email not found. Please check your email address.");</script>';
        exit();
    }

    // Generate a new password
    $newPassword = generateRandomPassword();

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the password in the user table
    $updatePasswordQuery = "UPDATE user SET password = ? WHERE user_id = ?";
    $updatePasswordStmt = $conn->prepare($updatePasswordQuery);
    $updatePasswordStmt->bind_param("ss", $hashedPassword, $userId);
    $updatePasswordStmt->execute();

    // Send email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'fypms.uitm@gmail.com';
        $mail->Password   = 'syasldvkvokucfij';
        // $mail->SMTPSecure = 'ssl'; // You can also use 'ssl'
        $mail->Port       = 587;    // You may need to change the port

        //Recipients
        $mail->setFrom('fypms.uitm@gmail.com', 'FYP Management System');
        $mail->addAddress($email, $userName);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        $mail->Body    = "Dear $userName,<br><br>Your password has been reset. Your new password is: $newPassword<br><br>Please log in and change your password immediately.";

        $mail->send();

        echo '<script>alert("Password reset successful. Check your email for the new password."); window.location.href = "login.php";</script>';
    } catch (Exception $e) {
        echo '<script>alert("Error sending email. Please try again later.");</script>';
    }
}

function generateRandomPassword($length = 10)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <div class="w-full rounded-lg shadow border md:mt-0 sm:max-w-md xl:p-0 bg-gray-800 border-gray-700">
            <div class="flex items-center justify-between px-8 pt-4">
                <div class="flex items-center">
                    <div>
                        <a href="login.php">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-gray-300 hover:text-blue-500">
                                <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                            </svg>

                        </a>
                    </div>
                    <div class="ml-4">
                        <a href="index.php" class="flex items-center text-2xl font-extrabold text-white">
                            <img src="assets/uitm.png" class="h-6 mr-2" alt="uitm Logo" />
                            FYPMS
                        </a>
                    </div>
                </div>
            </div>

            <div class="px-6 space-y-4 md:space-y-6 sm:p-8">
                <p class="text-gray-300 font-light">
                    Enter email address associated with your account, and we will promptly send your details.
                </p>
                <form class="space-y-4 md:space-y-6 " action="forgot_password.php" method="POST">
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-white">Email</label>
                        <input type="email" name="email" id="email" class=" border sm:text-sm rounded-lg  block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 " placeholder="FYPMS@gmail.com" required="">
                    </div>
                    <div class="flex flex-col items-center justify-center">
                        <button type="submit" class="flex w-full bg-blue-700 hover:bg-blue-800 justify-center rounded-lg -md px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>