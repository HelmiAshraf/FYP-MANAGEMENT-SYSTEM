<?php
include 'includes/sidebar.php';

// Define your database credentials
include '../db_credentials.php';

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
            echo "Proposal created successfully!";
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
?>



<h1 class="text-2xl font-bold mb-4">Create New Proposal</h1>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
    <div class="p-4 bg-gray-800">
        <form action="fypl_proposal_create.php" method="POST" enctype="multipart/form-data">
            <div class="mb-6">
                <label for="title" class="block mb-2 text-sm font-medium text-white">Proposal Title</label>
                <input type="text" required name="proposal_title" class=" border text-sm rounded-lg block w-full p-2.5 bg-gray-700 dborder-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Your final year project title" required>
            </div>
            <div class="mb-6">
                <label for="message" class="block mb-2 text-sm font-medium text-white">Submission due </label>
                <div class="relative max-w-sm ">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                    </div>
                    <input required name="proposal_date_due" datepicker datepicker-format="dd/mm/yyyy" datepicker type="text" class="border text-sm rounded-lg  block w-full pl-10 p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Select date">
                </div>
            </div>
            <input type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover-bg-blue-700 focus-ring-blue-800" value="Create Proposal">
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/datepicker.min.js"></script>
<!-- content end -->
</div>
</div>
</div>
</body>

</html>