<?php
include 'includes/sidebar_batch.php';

$user_id = $_SESSION["user_id"]; // Assuming you have stored the logged-in user's ID in a session variable

// Include the database connection code
include '../db.php'; // Include your database connection file


// Check if form data is set before accessing
if (isset($_POST['batch_name'], $_POST['start_date'], $_POST['end_date'])) {
    // Retrieve form data
    $batch_name = $_POST['batch_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Update existing batches based on category
    $update_sql_csp650 = "UPDATE batches SET batch_category = 'graduate' WHERE batch_category = 'CSP650'";
    $update_sql_csp600 = "UPDATE batches SET batch_category = 'CSP650' WHERE batch_category = 'CSP600'";

    if ($conn->query($update_sql_csp650) && $conn->query($update_sql_csp600)) {
        // Insert new batch with category CSP600
        $insert_sql = "INSERT INTO batches (batch_name, batch_start_date, batch_end_date, batch_category) VALUES (?, ?, ?, 'CSP600')";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sss", $batch_name, $start_date, $end_date);

        // If the new batch is inserted successfully, proceed with updates
        if ($insert_stmt->execute()) {
            echo "Batch updated and inserted successfully.";
        } else {
            echo "Error inserting new batch: " . $insert_stmt->error;
        }

        // Close the prepared statement
        $insert_stmt->close();
    } else {
        echo "Error updating batches: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<div class="flex justify-between items-center">
    <div>
        <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: FYP Course Lecturer</p>
    </div>
    <div class="ml-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="insight.php" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-gray-600 hover:font-bold ">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Insight
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="batch.php" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                            Batch
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                            Batch Update
                        </a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>
<div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

<!-- HTML content starts here -->
<h1 class="text-4xl font-bold mb-4">Batch Update</h1>

<div class="relative w-1/2 overflow-x-auto shadow-md sm:rounded-lg -lg">
    <div class="p-4 bg-gray-800">
        <form action="batch_update.php" method="POST" onsubmit="return confirm('Are you sure you want to update the batch?');">
            <div class="mb-6">
                <label for="batch_name" class="block mb-2 text-sm font-medium text-white">Batch Name</label>
                <input type="text" required name="batch_name" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Mac22-Feb23" required>
            </div>
            <div class="mb-6">
                <label for="start_date" class="block mb-2 text-sm font-medium text-white">Start Date</label>
                <input type="date" required name="start_date" class="border text-sm rounded-lg block p-2.5 pr-4 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-6">
                <label for="end_date" class="block mb-2 text-sm font-medium text-white">End Date</label>
                <input type="date" required name="end_date" class="border text-sm rounded-lg block p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500">
            </div>
            <input type="submit" class="text-white font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700" value="Update Batch">
        </form>
    </div>

    <?php
    // You may include other PHP code or HTML content here
    ?>

    <!-- content end -->
</div>
</div>
</div>
</body>

</html>