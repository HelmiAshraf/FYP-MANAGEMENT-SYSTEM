<?php

session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include 'includes/sv_sidebar.php';
echo $_SESSION['user_id'];


//! 


// Check if st_id is provided in the URL
if (isset($_GET['st_id'])) {
    $student_id = $_GET['st_id'];
} else {
    // Handle the case where st_id is not provided in the URL
    echo "Student ID not provided in the URL.";
    exit(); // Exit the script
}

// Your database connection code here

// SQL query to retrieve all project details for the student
$sql = "SELECT project_title, project_submit_date, project_description FROM project WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id); // Assuming student_id is an integer

// Execute the query
$stmt->execute();

// Get the result set
$result = $stmt->get_result();

// Check if there are projects for the student
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Display project details in HTML template
?>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-gray-800 text-gray-400">
            <ul class="flex text-sm">
                <li class="mr-2">
                    <p class="text-base font-semibold text-white">
                        Project Title :
                    </p>
                </li>
                <li>
                    <p class=" mb-3 text-base text-gray-200">
                        <?php echo $row['project_title']; ?>
                    </p>
                </li>
            </ul>
            <ul class="text-sm">
                <li class="mr-2">
                    <p class="text-base font-semibold text-white">
                        Project Description :
                    </p>
                </li>
                <li>
                    <p class=" mb-3 text-base text-gray-200">
                        <?php echo $row['project_description']; ?>
                    </p>
                </li>
            </ul>
            <div class="flex justify-end ">
                <form action="function/accept_reject_st.php" method="post">
                    <!-- Hidden input fields to send student_id and supervisor_id -->
                    <input type="text" name="student_id" value="<?php echo $student_id; ?>">
                    <input type="text" name="supervisor_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <!-- "Accept" button -->
                    <button type="submit" name="accept" value="accept" class="focus:outline-none text-white focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 bg-green-700 hover:bg-green-600 focus:ring-green-800">Accept</button>

                    <!-- "Reject" button -->
                    <button type="submit" name="reject" value="reject" class="focus:outline-none text-white focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 bg-red-700 hover:bg-red-600 focus:ring-red-800">Reject</button>
                </form>
            </div>
        </div>

<?php
    }
} else {
    // Handle the case where no projects are found for the student
    echo "No projects found for this student.";
}

// Close the statement and database connection
$stmt->close();
$conn->close();
?>



<!-- content end -->
</div>
</div>
</div>
</body>

</html>
