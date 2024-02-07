<?php
include 'includes/sidebar.php';

// Define your database credentials
include '../../db_credentials.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $proposal_title = $_POST["proposal_title"];
    $proposal_date_due_input = $_POST["proposal_date_due"];

    // Perform data validation here if needed

    // Convert the input date format (dd/mm/yyyy) to the database format (yyyy-mm-dd)
    $date_parts = explode('/', $proposal_date_due_input);
    if (count($date_parts) === 3) {
        $proposal_date_due = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    } else {
        // Handle invalid date format
        echo "Invalid date format. Please use dd/mm/yyyy.";
        exit; // Exit the script
    }

    // Get the current create date
    $proposal_date_create = date("Y-m-d H:i:s"); // Format it as needed

    // Database connection code (replace with your actual code)
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    // Check the connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Insert query (replace with your actual table and column names)
    $sql = "INSERT INTO proposal (proposal_title, proposal_date_due, proposal_date_create) VALUES (?, ?, ?)";

    // Prepare the statement
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sss", $proposal_title, $proposal_date_due, $proposal_date_create);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Proposal successfully inserted
            // Use JavaScript to show a success popup and then redirect
            echo '<script>';
            echo 'alert("Proposal created successfully!");';
            echo 'window.location.href = "../fypl_proposal.php";'; // Redirect to fypl_proposal.php
            echo '</script>';
        } else {
            // Error in execution
            echo "Error: " . mysqli_error($conn);
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        // Error in preparing the statement
        echo "Error: " . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
}
