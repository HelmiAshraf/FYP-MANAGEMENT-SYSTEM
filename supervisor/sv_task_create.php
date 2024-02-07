<?php

include 'includes/sv_sidebar.php';
include '../db_credentials.php';


// Initialize variables
$Message = '';

try {
    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define the directory where files will be stored
    $uploadDirectory = '../file/task/';

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if any files were uploaded
        if (isset($_FILES["files"]["name"]) && !empty($_FILES["files"]["name"])) {
            // Upload the file to the server directory and store the file path in the database
            $uploadSuccessful = true; // Flag to track successful file upload

            $file_name = $_FILES["files"]["name"];
            $file_path = $uploadDirectory . $file_name;

            // Proceed with inserting task details into the database
            // Extract task details from the form
            $task_title = $_POST["task_title"];
            $task_description = $_POST["task_description"];
            $date_create = date("Y-m-d H:i:s"); // Use the current date and time for task creation
            $task_date_due = $_POST["task_date_due"];
            $task_sv_id = $_SESSION["user_id"];
            $uploader_id = $_SESSION["user_id"];

            // Insert task details into the task table
            $sqlTask = "INSERT INTO task (task_title, task_description, task_date_create, task_date_due, task_sv_id)
                    VALUES (?, ?, ?, ?, ?)";
            $stmtTask = $pdo->prepare($sqlTask);
            $stmtTask->bindParam(1, $task_title);
            $stmtTask->bindParam(2, $task_description);
            $stmtTask->bindParam(3, $date_create);
            $stmtTask->bindParam(4, $task_date_due);
            $stmtTask->bindParam(5, $task_sv_id);

            if ($stmtTask->execute()) {
                // Get the task_id that was just inserted
                $task_id = $pdo->lastInsertId();

                $file_type = 'tsv'; // task = t
                $file_name = $file_type . '_' . $task_id . '_' . $_FILES["files"]["name"];

                // Insert file data into the file_path table using the retrieved task_id and updated file name
                $sqlFile = "INSERT INTO file_path (file_name, type_id, file_type, file_uploader_id, file_path)
                        VALUES (?, ?, ?, ?, ?)";
                $stmtFile = $pdo->prepare($sqlFile);
                $stmtFile->bindParam(1, $file_name);
                $stmtFile->bindParam(2, $task_id);
                $stmtFile->bindParam(3, $file_type);
                $stmtFile->bindParam(4, $uploader_id);
                $stmtFile->bindParam(5, $file_path);

                // Move the uploaded file to the server directory with the file name
                if (move_uploaded_file($_FILES["files"]["tmp_name"], $uploadDirectory . $file_name)) {
                    if (!$stmtFile->execute()) {
                        $uploadSuccessful = false;
                        $Message = 'Error: Failed to insert file details into the database.';
                    } else {
                        // Insert task-student relationships into the task_student table
                        if (isset($_POST["student_ids"])) {
                            $student_ids = $_POST["student_ids"];
                            foreach ($student_ids as $student_id) {
                                $sql = "INSERT INTO task_student (task_id, student_id)
                                        VALUES (?, ?)";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(1, $task_id);
                                $stmt->bindParam(2, $student_id);
                                $stmt->execute();
                            }
                        }
                    }
                } else {
                    $uploadSuccessful = false;
                    $Message = 'Error: Failed to move the uploaded file to the server directory.';
                }
            } else {
                $uploadSuccessful = false;
                $Message = 'Error: Failed to insert task details into the database.';
            }
        } else {
            // No files were uploaded, proceed with task creation without files
            $uploadSuccessful = true;

            // Extract task details from the form
            $task_title = $_POST["task_title"];
            $task_description = $_POST["task_description"];
            $date_create = date("Y-m-d H:i:s"); // Use the current date and time for task creation
            $task_date_due = $_POST["task_date_due"];
            $task_sv_id = $_SESSION["user_id"];
            $uploader_id = $_SESSION["user_id"];

            // Insert task details into the task table without file information
            $sqlTask = "INSERT INTO task (task_title, task_description, task_date_create, task_date_due, task_sv_id)
                    VALUES (?, ?, ?, ?, ?)";
            $stmtTask = $pdo->prepare($sqlTask);
            $stmtTask->bindParam(1, $task_title);
            $stmtTask->bindParam(2, $task_description);
            $stmtTask->bindParam(3, $date_create);
            $stmtTask->bindParam(4, $task_date_due);
            $stmtTask->bindParam(5, $task_sv_id);

            if ($stmtTask->execute()) {
                // Get the task_id that was just inserted
                $task_id = $pdo->lastInsertId();

                // Insert task-student relationships into the task_student table
                if (isset($_POST["student_ids"])) {
                    $student_ids = $_POST["student_ids"];
                    foreach ($student_ids as $student_id) {
                        $sql = "INSERT INTO task_student (task_id, student_id)
                                VALUES (?, ?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(1, $task_id);
                        $stmt->bindParam(2, $student_id);
                        $stmt->execute();
                    }
                }
            } else {
                $uploadSuccessful = false;
                $Message = 'Error: Failed to insert task details into the database.';
            }
        }

        // Display success message if file upload was successful or no files were uploaded
        if ($uploadSuccessful) {
            $Message = 'Successfully created a task.';
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}



$supervisor_id = $_SESSION["user_id"];


$sql = "SELECT s.st_id, s.st_name, s.st_batch, b.batch_category
        FROM student s
        INNER JOIN supervise ON s.st_id = supervise.student_id
        INNER JOIN batches b ON s.st_batch = b.batch_id
        WHERE supervise.supervisor_id = ? AND b.batch_category IN (?, ?)";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $CSP600_ID = 'CSP600';
    $CSP650_ID = 'CSP650';

    $stmt->bind_param("iss", $supervisor_id, $CSP600_ID, $CSP650_ID);
    $stmt->execute();
    $result = $stmt->get_result();


?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Task</title>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    </head>

    <div class="error-message">
        <?php if (!empty($Message)) : ?>
            <script>
                alert("<?php echo $Message; ?>");
            </script>
        <?php endif; ?>
    </div>

    <div class="flex justify-between items-center">
        <div>
            <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: Supervisor</p>
        </div>
        <div class="ml-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="sv_supervisee.php" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-gray-600 hover:font-bold ">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Supervise
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="sv_task.php" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                                Task
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                                Post Task
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

    <h1 class="text-3xl font-bold mb-4">Post Task</h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
        <div class="p-4 bg-gray-800">
            <form action="sv_task_create.php" method="POST" enctype="multipart/form-data">
                <div class="mb-6">
                    <label for="title" class="block mb-2 text-sm font-medium text-white">Task Title</label>
                    <input type="text" required name="task_title" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Your final year project title" required>
                </div>
                <div class="mb-6">
                    <label for="message" class="block mb-2 text-sm font-medium text-white">Task Description</label>
                    <textarea name="task_description" rows="4" class="block p-2.5 w-full text-sm rounded-lg border bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Write some project description"></textarea>
                </div>
                <div class="mb-6">
                    <label for="files" class="block mb-2 text-sm font-medium text-white">Upload Files</label>
                    <input type="file" name="files" id="fileInput" multiple onchange="displaySelectedFile(this)" class="block w-full text-sm border border-gray-600 rounded-lg cursor-pointer text-gray-400 bg-gray-700 placeholder-gray-400" aria-describedby="user_avatar_help">
                    <p class="mt-1 text-sm text-gray-300" id="file_input_help">Only PDF files are accepted.</p>
                    <!-- Display selected files -->
                    <div id="selected-files" class="mt-2 text-gray-300 " required></div>
                </div>
                <div class="mb-6">
                    <label for="dueDate" class="block mb-2 text-sm font-medium text-white">Due Date</label>
                    <input onkeydown="return false" required type="datetime-local" name="task_date_due" class="border text-sm rounded-lg block  p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" required min="<?php echo date('Y-m-d\TH:i', strtotime('+1 minute')); ?>">
                </div>


                <div class="mb-6">
                    <div class="flex items-center">
                        <label for="part" class="block mb-2 text-sm font-medium text-white mr-4">Select Students</label>
                        <select id="batchFilter" class="mb-2 text-sm rounded-md block p-2 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="CSP600">CSP600</option>
                            <option value="CSP650">CSP650</option>
                        </select>
                    </div>

                    <!-- Students table -->
                    <table id="students_table" class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3 items-center">
                                    Course
                                </th>
                                <th scope="col" class="p-4 text-center ">
                                    Select
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                            ?>
                                    <tr class="student-row">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <?php echo $row['st_name']; ?>
                                        </td>
                                        <td class="batch-cell px-6 py-4">
                                            <?php echo $row['batch_category']; ?>
                                        </td>
                                        <td class="p-4 items-center ">
                                            <div class="flex items-center">
                                                <input type="checkbox" name="student_ids[]" value="<?php echo $row['st_id']; ?>" class="w-4 h-4 text-blue-600 bg-gray-100 mx-auto rounded bg-gray-700 border-gray-600">
                                            </div>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                                echo "<td colspan='3' class='px-6 py-4 text-center'>No tasks found</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <input type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus-ring-blue-800" value="Create Task">
            </form>
        </div>
    </div>

<?php

} else {
    die("Error preparing the statement.");
}
?>



<!-- Other HTML code -->

<script>
    const batchFilter = document.getElementById('batchFilter');
    const tableRows = document.querySelectorAll('.student-row');

    batchFilter.addEventListener('change', () => {
        const selectedStatus = batchFilter.value;

        tableRows.forEach(row => {
            const batchCell = row.querySelector('.batch-cell');

            if (selectedStatus === 'all' || batchCell.textContent.trim() === selectedStatus) {
                row.style.display = ''; // Show the row
            } else {
                row.style.display = 'none'; // Hide the row
            }
        });
    });

    // Set default value to 'CSP600'
    batchFilter.value = 'CSP600';

    // Trigger change event after setting the default value
    batchFilter.dispatchEvent(new Event('change'));
</script>

<!-- Other scripts -->

<!-- The rest of your HTML code -->




<script>
    function displaySelectedFile(fileInput) {
        var displayDiv = document.getElementById("selected-files");
        displayDiv.innerHTML = ""; // Clear previous selection

        var file = fileInput.files[0];

        if (file) {
            // Add the allowed file types
            var allowedTypes = [".pdf"];
            var fileType = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();

            if (allowedTypes.indexOf(fileType) !== -1) {
                // var fileName = file.name;
                // var fileSize = (file.size / 1024).toFixed(2) + " KB"; // Display file size in KB

                // var fileInfo = document.createElement("p");
                // fileInfo.textContent = " " + fileName + " (" + fileSize + ")";
                // displayDiv.appendChild(fileInfo);

                // // Optionally, you can store the selected file in a hidden input field
                // var hiddenInput = document.createElement("input");
                // hiddenInput.type = "hidden";
                // hiddenInput.name = "selected_file";
                // hiddenInput.value = fileName;
                // displayDiv.appendChild(hiddenInput);
            } else {
                // Display an error message for an invalid file type
                var errorInfo = document.createElement("p");
                errorInfo.textContent = "Invalid file type. Allowed types are .pdf";
                errorInfo.style.color = "red";
                displayDiv.appendChild(errorInfo);

                // Clear the file input
                fileInput.value = "";
            }
        }
    }
</script>