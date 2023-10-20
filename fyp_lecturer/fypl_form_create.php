<?php
include 'includes/sidebar.php';

// Define your database credentials
$db_host = 'localhost';
$db_name = 'fypms';
$db_user = 'root';
$db_pass = '';

$successMessage = '';

try {
    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Extract form details from the POST data
        $form_title = $_POST["form_title"];
        $form_part = $_POST["form_part"];
        $uploader_id = $_SESSION["user_id"];
        $form_date_create = date("Y-m-d H:i:s");
        $form_date_due = $_POST["form_date_due"]; // Set your due date here, if applicable

        // Insert form details into the form table
        $sql = "INSERT INTO form (form_title, form_date_create, form_date_due, form_part, form_fl_id	)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $form_title);
        $stmt->bindParam(2, $form_date_create);
        $stmt->bindParam(3, $form_date_due);
        $stmt->bindParam(4, $form_part);
        $stmt->bindParam(5, $uploader_id);
        $stmt->execute();

        // Get the form_id that was just inserted
        $type_id = $pdo->lastInsertId();

        // Handle file uploads
        foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
            $file_name = $_FILES["files"]["name"][$key];
            $file_data = file_get_contents($tmp_name);

            // Insert file data into the file_form table
            $sql = "INSERT INTO file (file_name, file_content, type_id, file_type, file_uploader_id)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $file_type = "form";
            $stmt->bindParam(1, $file_name);
            $stmt->bindParam(2, $file_data, PDO::PARAM_LOB);
            $stmt->bindParam(3, $type_id, PDO::PARAM_INT);
            $stmt->bindParam(4, $file_type); // Make this = "form"
            $stmt->bindParam(5, $uploader_id, PDO::PARAM_INT);
            $stmt->execute();
        }

        $successMessage = 'Form submitted successfully.';
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>


<!-- Your HTML form -->
<div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
    <?php
    echo $_SESSION["user_id"];
    ?>
    <div class="p-4 bg-gray-800">
        <form action="fypl_form_create.php" method="POST" enctype="multipart/form-data">
            <div class="mb-6">
                <label for="title" class="block mb-2 text-sm font-medium text-white">Form Title</label>
                <input type="text" required name="form_title" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Your final year project title" required>
            </div>
            <div class="mb-6">
                <label for="form_date_due" class="block mb-2 text-sm font-medium text-white">Due Date</label>
                <input type="datetime-local" required name="form_date_due" class="border text-sm rounded-lg block p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-6">
                <label for="files" class="block mb-2 text-sm font-medium text-white">Upload Files</label>
                <input type="file" name="files[]" id="fileInput" multiple onchange="displaySelectedFiles(this.files)" class="block w-full text-sm border rounded-lg cursor-pointer text-gray-400 focus:outline-none bg-gray-700 border-gray-600 placeholder-gray-400" aria-describedby="user_avatar_help">
                <!-- Display selected files -->
                <div id="selected-files"></div>
            </div>
            <div class="mb-6">
                <label for="part" class="block mb-2 text-sm font-medium text-white">Part</label>
                <select name="form_part" id="part" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500">
                    <option value="5">Part 5</option>
                    <option value="6">Part 6</option>
                </select>
            </div>

            <input type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark-bg-blue-600 dark:hover-bg-blue-700 dark:focus-ring-blue-800" value="Upload Task">
        </form>


        <!-- Display success message if needed -->
        <?php if (!empty($successMessage)) : ?>
            <div class="text-green-500"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        </form>
    </div>
</div>

<script>
    // JavaScript function to display selected files (same as before)
    function displaySelectedFiles(files) {
        var displayDiv = document.getElementById("selected-files");

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var fileName = file.name;
            var fileSize = (file.size / 1024).toFixed(2) + " KB";

            var fileInfo = document.createElement("p");
            fileInfo.textContent = "Selected File " + (i + 1) + ": " + fileName + " (" + fileSize + ")";
            displayDiv.appendChild(fileInfo);
        }
    }
</script>