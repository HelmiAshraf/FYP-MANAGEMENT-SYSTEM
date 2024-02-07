<!-- Include jQuery from CDN -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<?php


include 'includes/sidebar.php';
// Check if 'task_id' is set in the URL
if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    include '../db_credentials.php';

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT t.task_title, t.task_description, t.task_date_due, t.task_sv_id
                FROM task t
                WHERE t.task_id = :task_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $task_title = $row['task_title'];
            $task_description = $row['task_description'];
            $task_date_due = $row['task_date_due'];
            $task_sv_id = $row['task_sv_id'];

?>

            <div id="editModal" class="modal fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto transform scale-0 opacity-0">
                <div class="modal-overlay bg-black bg-opacity-70 fixed inset-0"></div>
                <div class="bg-white w-96 rounded-lg shadow-lg p-6 transform scale-100 opacity-100 transition-transform duration-300">
                    <h2 class="text-2xl font-bold mb-4">Update Task</h2>
                    <form action="function/task_form_controller.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
                        <input type="hidden" name="task_sv_id" value="<?php echo $task_sv_id; ?>">
                        <!-- Add hidden input fields for data -->
                        <input type="hidden" name="field_to_update" id="field_to_update">
                        <div class="mb-4">
                            <label for="new_data" class="block mb-2 text-sm font-medium">Title</label>
                            <input type="text" name="task_title" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" value="<?php echo $task_title; ?>" />
                        </div>
                        <div class="mb-4">
                            <label for="task_description" class="block mb-2 text-sm font-medium">Description</label>
                            <input type="text" name="task_description" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" value="<?php echo $task_description; ?>" />
                        </div>
                        <div class="mb-4">
                            <label for="due_date" class="block mb-2 text-sm font-medium">Due Date</label>
                            <input onkeydown="return false" type="datetime-local" name="task_date_due" class="border w-full text-sm rounded-lg block p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400  focus:ring-blue-500 focus:border-blue-500" min="<?php echo date('Y-m-d\TH:i', strtotime('+1 minute')); ?>" value="<?php echo $task_date_due; ?>" />
                        </div>
                        <div class="mb-4">
                            <label for="new_data" class="block mb-2 text-sm font-medium">Upload file</label>
                            <input type="file" name="task_file" id="fileInput" multiple onchange="displaySelectedFile(this)" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none" aria-describedby="user_avatar_help">
                            <p class="mt-1 text-sm text-gray-500" id="file_input_help">SVG, PNG, JPG, or GIF (MAX. 800x400px).</p>
                            <!-- Display selected files -->
                            <div id="selected-files" class="mt-2 text-gray-300 "></div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">
                                Update
                            </button>
                        </div>
                    </form>
                    <button id="close-edit-modal-button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div>
                    <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: Final Year Project Lecturer</p>
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
                                    <a href="fypl_task.php" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
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
                                        Task Details
                                    </a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

            <div class="flex justify-between align-items-center">
                <div>
                    <h1 class="text-2xl font-bold mb-4">Task Details</h1>
                </div>
                <div>
                    <a href="#" class="user_edit text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 focus:outline-none block">Update
                        task</a>
                </div>
            </div>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-gray-800 text-gray-400">
                <ul class="flex text-sm">
                    <li class="mr-2">
                        <p class="text-base font-semibold text-white">Title:</p>
                    </li>
                    <li>
                        <p class="mb-3 text-base text-gray-200">
                            <?php echo $task_title; ?>
                        </p>
                    </li>
                </ul>

                <ul class="text-sm">
                    <li class="mr-2">
                        <p class="text-base font-semibold text-white">Description:</p>
                    </li>
                    <li>
                        <p class="mb-3 text-base text-gray-200">
                            <?php echo $task_description; ?>
                        </p>
                    </li>
                </ul>
                <ul class="text-sm">
                    <li class="mr-2">
                        <p class="text-base font-semibold text-white">Due Date:</p>
                    </li>
                    <li>
                        <p class="mb-3 text-base text-gray-200">
                            <?php echo $task_date_due; ?>
                        </p>
                    </li>
                </ul>
                <?php
                // Retrieve associated files for this task
                $sql_files = "SELECT f.file_name, f.file_id
              FROM file_path f
              WHERE f.type_id = :task_id AND f.file_type = 'tfypl'";

                $stmt_files = $pdo->prepare($sql_files);
                $stmt_files->bindParam(':task_id', $task_id, PDO::PARAM_INT);
                $stmt_files->execute();
                $result_files = $stmt_files->fetchAll(PDO::FETCH_ASSOC);

                if (count($result_files) > 0) {
                ?>
                    <ul class="text-sm">
                        <li class="mr-2">
                            <p class="text-base font-semibold text-white">Task Files:</p>
                        </li>
                        <li>
                            <ul>
                                <?php foreach ($result_files as $row_files) { ?>
                                    <li class="mb-3 text-base text-gray-200">

                                        <?php
                                        $original_file_name_l = substr($row_files['file_name'], strpos($row_files['file_name'], '_', strpos($row_files['file_name'], '_') + 1) + 1);

                                        // Remove file extension
                                        $original_file_name_l_without_extension = pathinfo($original_file_name_l, PATHINFO_FILENAME);
                                        ?>

                                        <button class='text-blue-500 hover:underline hover:text-blue-400' onclick="openFileInNewTabLecturer()">
                                            <?php echo $original_file_name_l; ?>
                                        </button>

                                        <script>
                                            function openFileInNewTabLecturer() {
                                                // Construct the file URL
                                                var fileUrl = 'view_pdf.php//?url=' + encodeURIComponent('../file/task/<?php echo $row_files['file_name']; ?>') + '&name=' + encodeURIComponent('<?php echo $original_file_name_l_without_extension; ?>');

                                                // Open the file link in a new tab
                                                var newTab = window.open(fileUrl, '_blank');

                                                // Set the title after a short delay
                                                if (newTab) {
                                                    setTimeout(function() {
                                                        newTab.document.title = <?php echo json_encode($original_file_name_l_without_extension, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
                                                    }, 1000); // Adjust the delay as needed
                                                }
                                            }
                                        </script>


                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                <?php
                } else {
                    echo "<p class='text-base font-semibold text-white'>No files associated with this task.</p>";
                }
                ?>

            </div>
<?php
        } else {
            echo "Task not found.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<?php

// Assuming you have established a database connection ($conn) and have the user_id of the logged-in supervisor
$user_id = $_SESSION['user_id']; // Replace with your authentication mechanism

$sql = "SELECT
ts.task_id,
s.st_name AS student_name,
s.st_id,
ts.submissiondate,
f.file_id,
f.file_name,
f.file_path
FROM
task_submission ts
INNER JOIN
task t ON ts.task_id = t.task_id
INNER JOIN
student s ON ts.student_id = s.st_id
INNER JOIN
fyp_lecturer fypl ON ts.supervisor_id = fypl.fl_id 
INNER JOIN
file_path f ON ts.task_submission_id = f.type_id
WHERE
fypl.fl_id  = ? AND ts.task_id = ? AND f.file_type = 'ts';
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $task_id); // Assuming user_id is an integer


if ($stmt->execute()) {
    $result = $stmt->get_result();
}

// You should check if there are rows in the result outside of the first if statement

?>


<h1 class="text-2xl font-bold mb-4 mt-4">Submited Tasks</h1>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    File Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Student Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Submission Date
                </th>
            </tr>
        </thead>
        <?php if ($result->num_rows > 0) { ?>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) {
                    $original_file_name = substr($row['file_name'], strpos($row['file_name'], '_', strpos($row['file_name'], '_', strpos($row['file_name'], '_') + 1) + 1) + 1); //potong 3

                    // Remove file extension
                    $original_file_name_without_extension = pathinfo($original_file_name, PATHINFO_FILENAME);

                ?>
                    <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                        <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-black'>
                            <button class='text-blue-500 hover:underline hover:text-blue-400' onclick="openFileInNewTab('<?php echo $row['file_name']; ?>', '<?php echo htmlspecialchars($original_file_name_without_extension); ?>')">
                                <?php echo htmlspecialchars($original_file_name); ?>
                            </button>

                            <script>
                                function openFileInNewTab(file_name, file_name_without_extension) {
                                    // Construct the file URL
                                    var fileUrl = 'view_pdf.php//?url=' + encodeURIComponent('../file/task/' + file_name) + '&name=' + encodeURIComponent(file_name_without_extension);

                                    // Open the file link in a new tab
                                    var newTab = window.open(fileUrl, '_blank');

                                    // Set the title after a short delay
                                    if (newTab) {
                                        setTimeout(function() {
                                            newTab.document.title = file_name_without_extension;
                                        }, 1000); // Adjust the delay as needed
                                    }
                                }
                            </script>
                        </td>
                        <td class='px-6 py-3 '>
                            <?php echo $row['student_name']; ?>
                        </td>
                        <td class='px-6 py-3'>
                            <?php
                            $submission_date = date("g:i a | j M Y", strtotime($row['submissiondate']));
                            echo $submission_date;
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        <?php
        } else {
            // If no submissions are found, display a message
            echo "<tbody>";
            echo "<tr class='border-b bg-gray-800 border-gray-700 hover-bg-gray-900'>";
            echo "<td colspan='3' class='px-6 py-4 text-center'>No submissions found</td>";
            echo "</tr>";
            echo "</tbody>";
        }

        $stmt->close();
        $conn->close();
        ?>

    </table>

</div>

<!-- JavaScript code to handle the modal and update -->
<script>
    const editButtons = document.querySelectorAll(".user_edit");
    const editModal = document.getElementById("editModal");
    const closeEditModalButton = document.getElementById("close-edit-modal-button");

    editButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            event.preventDefault();

            // Show the modal
            editModal.style.transform = "scale(1)";
            editModal.style.opacity = "1";
        });
    });

    // Close the edit modal
    closeEditModalButton.addEventListener("click", function() {
        editModal.style.transform = "scale(0)";
        editModal.style.opacity = "0";
    });
</script>

<script>
    function displaySelectedFile(fileInput) {
        var displayDiv = document.getElementById("selected-files");
        displayDiv.innerHTML = ""; // Clear previous selection

        var file = fileInput.files[0];

        if (file) {
            // Add the allowed file types
            var allowedTypes = [".pdf", ".jpg", ".jpeg", ".png"];
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
                errorInfo.textContent = "Invalid file type. Allowed types are .pdf, .jpg, .jpeg, .png";
                errorInfo.style.color = "red";
                displayDiv.appendChild(errorInfo);

                // Clear the file input
                fileInput.value = "";
            }
        }
    }
</script>
<!-- content end -->
</div>
</div>
</div>
</body>


</html>