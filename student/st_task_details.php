<?php
// Check if 'task_id' is set in the URL
if (isset($_GET['task_id'])) {
    // Get the task_id from the URL
    $task_id = $_GET['task_id'];

    include 'includes/st_sidebar.php';

    // Assuming you have a 'task' table with columns 'task_id', 'task_title', 'task_description', 'date_create'
    // and a 'file' table with columns 'file_id', 'file_name', 'file_content', 'uploader_id', and 'task_id'
    $sql = "SELECT t.task_title, t.task_description
            FROM task t
            WHERE t.task_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $task_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $task_title = $row['task_title'];
            $task_description = $row['task_description'];
?>
            <h1 class="text-2xl font-bold mb-4">Task Details</h1>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-gray-800 text-gray-400">
                <ul class="flex text-sm">
                    <li class="mr-2">
                        <p class="text-base font-semibold text-white">
                            Task Title:
                        </p>
                    </li>
                    <li>
                        <p class="mb-3 text-base text-gray-200">
                            <?php echo $task_title; ?>
                        </p>
                    </li>
                </ul>
                <ul class="text-sm">
                    <li class="mr-2">
                        <p class="text-base font-semibold text-white">
                            Task Description:
                        </p>
                    </li>
                    <li>
                        <p class="mb-3 text-base text-gray-200">
                            <?php echo $task_description; ?>
                        </p>
                    </li>
                </ul>
                <?php
                // Now, let's retrieve and display all associated files for this task
                $sql_files = "SELECT f.file_name, f.uploader_id, f.file_id
                          FROM file f
                          WHERE f.task_id = ?";

                $stmt_files = $conn->prepare($sql_files);
                $stmt_files->bind_param("i", $task_id);

                if ($stmt_files->execute()) {
                    $result_files = $stmt_files->get_result();

                    if ($result_files->num_rows > 0) {
                ?>
                        <ul class="text-sm">
                            <li class="mr-2">
                                <p class="text-base font-semibold text-white">
                                    Task Files:
                                </p>
                            </li>
                            <li>
                                <ul>
                                    <?php
                                    while ($row_files = $result_files->fetch_assoc()) {
                                        $file_name = $row_files['file_name'];
                                        $uploader_id = $row_files['uploader_id'];
                                        $file_id = $row_files['file_id'];
                                    ?>
                                        <li class="mb-3 text-base text-gray-200">
                                            <a href="function/download.php?file_id=<?php echo $file_id; ?>" class="text-blue-500 hover:underline" download>
                                                <?php echo $file_name; ?>
                                            </a>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </li>
                        </ul>
                <?php
                    } else {
                        // Handle the case where no files are associated with the task
                        echo "<p class='text-base font-semibold text-white'>No files associated with this task.</p>";
                    }
                } else {
                    echo "Error fetching files: " . $stmt_files->error;
                }
                ?>
            </div>
<?php
        } else {
            // Handle the case where no task with the specified task_id is found
            echo "Task not found.";
        }
    } else {
        echo "Error fetching task: " . $stmt->error;
    }
} else {
    // Handle the case where 'task_id' is not set in the URL
    echo "Task ID not provided.";
}
?>

<h1 class="text-2xl font-bold mb-4 mt-4">Submit Task</h1>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="p-4 bg-gray-800">
        <form action="function/submit_task.php" method="POST" enctype="multipart/form-data">
            <!-- Add an input field for task_id -->
            <input type="hidden" name="task_id" value="<?php echo $_GET['task_id']; ?>">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            <div class="mb-6">
                <label for="files" class="block mb-2 text-sm font-medium text-white">Upload Files</label>
                <input type="file" name="files[]" id="fileInput" multiple onchange="displaySelectedFiles(this.files)" class="block w-full text-sm border rounded-lg cursor-pointer text-gray-400 focus:outline-none bg-gray-700 border-gray-600 placeholder-gray-400" aria-describedby="user_avatar_help">
                <!-- Display selected files -->
                <div id="selected-files"></div>
            </div>
            <!-- Add an input field for tag -->
            <input type="hidden" name="tag" value="6">
            <div>
                <!-- Use a button for form submission -->
                <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus-ring-blue-800">Submit Task</button>
            </div>
        </form>

    </div>
</div>
<script>
    function displaySelectedFiles(files) {
        var displayDiv = document.getElementById("selected-files");

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var fileName = file.name;
            var fileSize = (file.size / 1024).toFixed(2) + " KB"; // Display file size in KB

            var fileInfo = document.createElement("p");
            fileInfo.textContent = "Selected File " + (i + 1) + ": " + fileName + " (" + fileSize + ")";
            displayDiv.appendChild(fileInfo);
        }
    }
</script>


<?php




// $student_id = $_SESSION['user_id'];

// if (isset($_REQUEST["tag"])) {
// $tag = $_REQUEST["tag"];
// if ($tag == 10) {
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
// // Define your database credentials
// $db_host = 'localhost';
// $db_name = 'fypms';
// $db_user = 'root';
// $db_pass = '';

// try {
// // Create a PDO database connection
// $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
// // Set PDO to throw exceptions on error
// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// // Get task_id from the form (assuming it's named 'task_id' in the form)
// $task_id = $_POST['task_id'];

// // Retrieve supervisor_id associated with the student from the 'supervise' table
// $sql_supervisor = "SELECT supervisor_id FROM supervise WHERE student_id = ?";
// $stmt_supervisor = $pdo->prepare($sql_supervisor);
// $stmt_supervisor->bindParam(1, $student_id);
// $stmt_supervisor->execute();

// $row_supervisor = $stmt_supervisor->fetch(PDO::FETCH_ASSOC);

// if ($row_supervisor) {
// $supervisor_id = $row_supervisor['supervisor_id'];
// $submission_date = date("Y-m-d");

// // Insert submission details into the 'task_submission' table
// $sql_submission = "INSERT INTO task_submission (task_id, student_id, supervisor_id, submissiondate)
// VALUES (?, ?, ?, ?)";
// $stmt_submission = $pdo->prepare($sql_submission);
// $stmt_submission->bindParam(1, $task_id);
// $stmt_submission->bindParam(2, $student_id);
// $stmt_submission->bindParam(3, $supervisor_id);
// $stmt_submission->bindParam(4, $submission_date);
// $stmt_submission->execute();

// // Handle file uploads
// if (!empty($_FILES['files']['name'])) {
// // Iterate through uploaded files
// foreach ($_FILES['files']['name'] as $key => $file_name) {
// $tmp_name = $_FILES['files']['tmp_name'][$key];
// $file_content = file_get_contents($tmp_name);

// // Insert file data into the 'file' table
// $sql_file = "INSERT INTO file (file_name, file_content, task_id, uploader_id)
// VALUES (?, ?, ?, ?)";
// $stmt_file = $pdo->prepare($sql_file);
// $stmt_file->bindParam(1, $file_name);
// $stmt_file->bindParam(2, $file_content, PDO::PARAM_LOB);
// $stmt_file->bindParam(3, $task_id, PDO::PARAM_INT);
// $stmt_file->bindParam(4, $student_id, PDO::PARAM_INT);
// $stmt_file->execute();
// }
// }

// // Redirect or show a success message
// echo "Task submitted successfully.";
// } else {
// echo "Supervisor not found for this student.";
// }
// } catch (PDOException $e) {
// echo "Error: " . $e->getMessage();
// die();
// }
// }
// }
// }




?>