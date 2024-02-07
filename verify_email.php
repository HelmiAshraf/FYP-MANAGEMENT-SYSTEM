<?php
include 'db.php';

// Get the token from the URL
$token = $_GET['token'];

// Check if the token exists in the database
$select_user_sql = "SELECT user_id FROM user WHERE verify_email = ?";
$select_user_stmt = $conn->prepare($select_user_sql);
$select_user_stmt->bind_param("s", $token);
$select_user_stmt->execute();
$result = $select_user_stmt->get_result();

if ($result->num_rows > 0) {
    // Token is valid, update verify_email to 1
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];

    $update_user_sql = "UPDATE user SET verify_email = 1 WHERE user_id = ?";
    $update_user_stmt = $conn->prepare($update_user_sql);
    $update_user_stmt->bind_param("i", $user_id);

    if ($update_user_stmt->execute()) {
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Email Verification Success</title>
            <script src="https://cdn.tailwindcss.com"></script>

        </head>
        <body class="bg-gray-900 text-white h-screen flex items-center justify-center">
            <div class="text-center">
                <!-- Add a tick icon (you can use an emoji or an SVG icon) -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-16 h-16 mx-auto mb-4 text-green-500">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <h1 class="text-2xl font-bold mb-4">Email Verified Successfully</h1>
                <p class="text-sm">You can now log in to your account.</p>
            </div>
        </body>
        </html>
        ';
    } else {
        echo "Error updating database. Please contact support.";
    }



    $update_user_stmt->close();
} else {
    echo "Invalid verification token.";
}

$select_user_stmt->close();
$conn->close();
