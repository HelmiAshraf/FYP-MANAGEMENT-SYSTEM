<?php
// Retrieve user data based on the user's login ID
$sidebar_user_id = $_SESSION['user_id']; // Assuming you store the login ID in a session variable

// Prepare and execute a query to retrieve user details
$sidebar_sql = "SELECT fl_name, fl_email, TO_BASE64(fl_image) AS fl_image_base64 FROM fyp_lecturer WHERE fl_id = ?";
$sidebar_stmt = $conn->prepare($sidebar_sql);
$sidebar_stmt->bind_param("i", $sidebar_user_id);

if ($sidebar_stmt->execute()) {
    // Bind the results to variables
    $sidebar_stmt->bind_result($sidebar_user_name, $sidebar_user_email, $sidebar_fl_image_base64);

    // Fetch the data
    $sidebar_stmt->fetch();

    // Close the statement
    $sidebar_stmt->close();
} else {
    echo "Error executing the query: " . $conn->error;
}

// Close the database connection
