<?php
include 'includes/sv_sidebar.php';

// Define your database credentials
$db_host = 'localhost';  // Your database host
$db_name = 'fypms'; // Your database name
$db_user = 'root'; // Your database username
$db_pass = ''; // Your database password

try {
    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Extract task details from the form
        $task_title = $_POST["task_title"];
        $task_description = $_POST["task_description"];
        $task_date = $_POST["task_date"];
        $task_sv_id = $_SESSION["user_id"]; // You should have a session variable for supervisor_id
        $uploader_id = $_SESSION["user_id"]; // Assuming you have a session variable for user_id

        // Upload files to the database
        foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
            $file_name = $_FILES["files"]["name"][$key];
            $file_data = file_get_contents($tmp_name); // Get file content as binary data

            // Insert file data into the file table
            $sql = "INSERT INTO file (file_name, file_content, task_id, uploader_id)
                    VALUES (?, ?, LAST_INSERT_ID(), ?)";

            // Use prepared statements to insert binary data
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(1, $file_name);
            $stmt->bindParam(2, $file_data, PDO::PARAM_LOB);
            $stmt->bindParam(3, $uploader_id, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Insert task details into the task table
        $sql = "INSERT INTO task (task_title, task_description, task_date, task_sv_id)
                VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $task_title);
        $stmt->bindParam(2, $task_description);
        $stmt->bindParam(3, $task_date);
        $stmt->bindParam(4, $task_sv_id);
        $stmt->execute();

        // Redirect or show a success message
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>



<?php
echo $_SESSION['user_id'];
?>
<form action="sv_task.php" method="post" enctype="multipart/form-data">
    <label for="task_title">Task Title:</label>
    <input type="text" name="task_title" required><br>

    <label for="task_description">Task Description:</label>
    <textarea name="task_description" required></textarea><br>

    <label for="task_date">Task Date:</label>
    <input type="date" name="task_date" required><br>

    <label for="files">Upload Files:</label>
    <input type="file" name="files[]" id="fileInput" multiple onchange="displaySelectedFiles(this.files)"><br>

    <!-- Display selected files -->
    <div id="selected-files"></div>

    <input type="submit" value="Create Task">
</form>

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






<!-- content end -->
</div>
</div>
</div>
</body>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-gray-800 text-gray-400">
            <ul class="flex text-sm">
                <li class="mr-2">
                    <p class="text-base font-semibold text-white">
                        Task Title :
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
                        Task Description :
                    </p>
                </li>
                <li>
                    <p class=" mb-3 text-base text-gray-200">
                        <?php echo $row['project_description']; ?>
                    </p>
                </li>
            </ul>
            <ul class="text-sm">
                <li class="mr-2">
                    <p class="text-base font-semibold text-white">
                        Task File :
                    </p>
                </li>
                <li>
                    <p class=" mb-3 text-base text-gray-200">
                        <?php echo $row['project_description']; ?>
                    </p>
                </li>
            </ul>
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


</html