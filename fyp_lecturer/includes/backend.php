<?php
include '../db.php';

// Retrieve user data based on the user's login ID
$sidebar_user_id = $_SESSION['user_id']; // Assuming you store the login ID in a session variable

// Prepare and execute a query to retrieve user details
$sidebar_sql = "SELECT fl_name, fl_email, fl_image FROM fyp_lecturer WHERE fl_id = ?";
$sidebar_stmt = $conn->prepare($sidebar_sql);
$sidebar_stmt->bind_param("i", $sidebar_user_id);

if ($sidebar_stmt->execute()) {
    // Bind the results to variables
    $sidebar_stmt->bind_result($sidebar_user_name, $sidebar_user_email, $sidebar_fl_image);

    // Fetch the data
    $sidebar_stmt->fetch();

    // Read the image file content and convert it to base64
    if ($sidebar_fl_image && file_exists($sidebar_fl_image)) {
        $sidebar_fl_image_base64 = base64_encode(file_get_contents($sidebar_fl_image));
    } else {
        $sidebar_fl_image_base64 = null;
    }

    // Close the statement
    $sidebar_stmt->close();
} else {
    echo "Error executing the query: " . $conn->error;
}


