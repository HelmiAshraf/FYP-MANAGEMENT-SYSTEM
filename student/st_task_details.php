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
            <div class="mb-6">
                <label for="files" class="block mb-2 text-sm font-medium text-white">Upload Files</label>
                <input type="file" name="files[]" id="fileInput" multiple onchange="displaySelectedFiles(this.files)" class="block w-full text-sm border rounded-lg cursor-pointer text-gray-400 focus:outline-none bg-gray-700 border-gray-600 placeholder-gray-400" aria-describedby="user_avatar_help">
                <!-- Display selected files -->
                <div id="selected-files"></div>
            </div>
            <input type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover-bg-blue-700 focus-ring-blue-800" value="Submit Task">
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