<?php
include 'includes/st_sidebar_choose_sv.php';

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_title = $_POST['title'];
    $project_description = $_POST['description'];
    $student_id = $_SESSION['user_id']; // You'll need to set the student_id from your authentication mechanism
    $sv_id_from_form = $_POST['sv_id']; // Capture sv_id from the form

    // Validate and sanitize user inputs as needed

    // Get the current date
    $current_date = date('Y-m-d');

    // Insert data into the 'project' table
    $sql = "INSERT INTO project (project_title, project_description, student_id, supervisor_id, project_submit_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Check if the statement was prepared successfully
    if ($stmt) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ssiss", $project_title, $project_description, $student_id, $sv_id_from_form, $current_date);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to st_sv_status.php after successful submission
            echo '<script>alert("You have successfully propose your project."); window.location = "st_sv_status.php";</script>';
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
<div class="flex justify-between items-center">
    <div>
        <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: Student</p>
    </div>
    <div class="ml-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="st_available_sv.php" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-gray-600 hover:font-bold ">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Available Supervisor
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                        Propose Project
                        </a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>
<div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

<h1 class="text-3xl font-bold mb-4">Propose Project</h1>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
    <div class="p-4 bg-gray-800">
        <form action="st_propose_project.php" method="POST">
            <div class="mb-6">
                <label for="title" class="block mb-2 text-sm font-medium text-white">Project Title</label>
                <input type="text" id="title" name="title" class="block p-2.5 w-full text-sm rounded-lg border focus:border-blue-500 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Your final year project title" required>
            </div>
            <div class="mb-6">
                <label for="message" class="block mb-2 text-sm font-medium text-white">Description</label>
                <textarea id="description" name="description" rows="8" class="block p-2.5 w-full text-sm rounded-lg border focus:border-blue-500 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Write some project description"></textarea>
            </div>
            <input type="hidden" name="sv_id" value="<?php echo isset($_GET['sv_id']) ? $_GET['sv_id'] : ''; ?>">
            <button type="submit" class="text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">Submit</button>
        </form>
    </div>
</div>

<!-- content end -->
</div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>
</body>

</html>