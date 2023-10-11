<?php
include 'includes/sv_sidebar.php';

// Define your database credentials
$db_host = 'localhost';  // Your database host
$db_name = 'fypms'; // Your database name
$db_user = 'root'; // Your database username
$db_pass = ''; // Your database password

$successMessage = ''; // Initialize an empty success message

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
        // Generate the date in Y-m-d format
        $date_create = date("Y-m-d");
        $task_sv_id = $_SESSION["user_id"]; // You should have a session variable for supervisor_id
        $uploader_id = $_SESSION["user_id"]; // Assuming you have a session variable for user_id
        $task_part = $_POST["task_part"]; // Get the selected task part

        // Insert task details into the task table
        $sql = "INSERT INTO task (task_title, task_description, task_date_create, task_sv_id, task_part)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $task_title);
        $stmt->bindParam(2, $task_description);
        $stmt->bindParam(3, $date_create);
        $stmt->bindParam(4, $task_sv_id);
        $stmt->bindParam(5, $task_part);
        $stmt->execute();

        // Get the task_id that was just inserted
        $task_id = $pdo->lastInsertId();

        // Upload files to the database
        // Upload files to the database
        foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
            $file_name = $_FILES["files"]["name"][$key];
            $file_data = file_get_contents($tmp_name); // Get file content as binary data

            // Insert file data into the file table using the retrieved task_id
            $sql = "INSERT INTO file (file_name, file_content, type_id, file_type, file_uploader_id)
            VALUES (?, ?, ?, ?, ?)";

            // Use prepared statements to insert binary data
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(1, $file_name);
            $stmt->bindValue(2, $file_data, PDO::PARAM_LOB); // Use bindValue for binary data
            $stmt->bindParam(3, $task_id, PDO::PARAM_INT); // Use the retrieved task_id as file_type_id
            $stmt->bindValue(4, 'task', PDO::PARAM_STR); // Set file_type to 'task'
            $stmt->bindParam(5, $uploader_id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>


<h1 class="text-2xl font-bold mb-4">Create Task</h1>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
    <div class="p-4 bg-gray-800">
        <form action="sv_task_create.php" method="POST" enctype="multipart/form-data">
            <div class="mb-6">
                <label for="title" class="block mb-2 text-sm font-medium text-white">Task Title</label>
                <input type="text" required name="task_title" class=" border text-sm rounded-lg block w-full p-2.5 bg-gray-700 dborder-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Your final year project title" required>
            </div>
            <div class="mb-6">
                <label for="message" class="block mb-2 text-sm font-medium text-white">Task Description</label>
                <textarea name="task_description" rows="4" class="block p-2.5 w-full text-sm rounded-lg border bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Write some project description"></textarea>
            </div>
            <div class="mb-6">
                <label for="files" class="block mb-2 text-sm font-medium text-white">Upload Files</label>
                <input type="file" name="files[]" id="fileInput" multiple onchange="displaySelectedFiles(this.files)" class="block w-full text-sm border rounded-lg cursor-pointer text-gray-400 focus:outline-none bg-gray-700 border-gray-600 placeholder-gray-400" aria-describedby="user_avatar_help">
                <!-- Display selected files -->
                <div id="selected-files"></div>
            </div>
            <div class="mb-6">
                <label for="part" class="block mb-2 text-sm font-medium text-white">Part</label>
                <select name="task_part" id="part" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500">
                    <option value="5">Part 5</option>
                    <option value="6">Part 6</option>
                </select>
            </div>
            <input type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark-bg-blue-600 dark:hover-bg-blue-700 dark:focus-ring-blue-800" value="Create Task">
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