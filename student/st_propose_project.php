<?php include 'includes/st_sidebar_choose_sv.php';


// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_title = $_POST['title'];
    $project_description = $_POST['description'];
    $student_id = $_SESSION['user_id']; // You'll need to set the student_id from your authentication mechanism
    $sv_id_from_form = $_POST['sv_id']; // Capture sv_id from the form

    // Validate and sanitize user inputs as needed

    // Insert data into the 'project' table
    $sql = "INSERT INTO project (project_title, project_description, student_id, supervisor_id, project_submit_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiss", $project_title, $project_description, $student_id, $sv_id_from_form, date('Y-m-d'));

    //!! pergi ke st_sv_status.php tak
    if ($stmt->execute()) {
        // Redirect to st_sv_status.php after successful submission
        echo "successfull propose";
    } else {
        echo "Error: " . $stmt->error;
    }
    // Close the database connection
    $stmt->close();
    $conn->close();
}

?>
<h1 class="text-2xl font-bold mb-4">Propose Project</h1>
<?php echo $_SESSION['user_id']; ?>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
    <div class="p-4 bg-white dark:bg-gray-800">
        <form action="st_propose_project.php" method="POST">
            <div class="mb-6">
                <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Project Title</label>
                <input type="text" id="title" name="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your final year project title" required>
            </div>
            <div class="mb-6">
                <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write some project description"></textarea>
            </div>
            <input type="text" name="sv_id" value="<?php echo isset($_GET['sv_id']) ? $_GET['sv_id'] : ''; ?>">
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
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