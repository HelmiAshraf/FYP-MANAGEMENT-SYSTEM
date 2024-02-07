<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css" rel="stylesheet" />
<?php
include 'db.php';
if (isset($_REQUEST["tag"])) {
    $tag = $_REQUEST["tag"];
    if ($tag == 1) {
        if (isset($_REQUEST["task_id"])) {
            $task_id = $_REQUEST["task_id"];

            // Retrieve associated files for this task
            $sql_files = "SELECT id, file_path FROM file_path WHERE type_id = ? AND file_type = 't'";

            // Prepare the statement for retrieving files
            $stmt_files = $conn->prepare($sql_files);
            $stmt_files->bind_param("i", $task_id);

            if ($stmt_files->execute()) {
                // Fetch the result of the query
                $result_files = $stmt_files->get_result();

                // Loop through the files and delete each one
                while ($row_files = $result_files->fetch_assoc()) {
                    // Delete the file from the server
                    unlink($row_files['file_path']);

                    // Delete the file record from the database
                    $sql_delete_file = "DELETE FROM file_path WHERE id = ?";
                    $stmt_delete_file = $conn->prepare($sql_delete_file);
                    $stmt_delete_file->bind_param("i", $row_files['id']);

                    if (!$stmt_delete_file->execute()) {
                        // Error in file deletion
                        echo "Error deleting file: " . $stmt_delete_file->error;
                    }

                    $stmt_delete_file->close();
                }

                // Delete the task record from the database
                $sql_delete_task = "DELETE FROM task WHERE task_id = ?";
                $stmt_delete_task = $conn->prepare($sql_delete_task);
                $stmt_delete_task->bind_param("i", $task_id);

                if ($stmt_delete_task->execute()) {
                    // Task and associated files successfully deleted

                    // Delete the directory from the server
                    $directory_path = __DIR__ . '/../file/task/tsv_' . $task_id;
                    if (is_dir($directory_path)) {
                        rmdir($directory_path);
                    }

                    echo '<script>';
                    echo 'window.location.href = "sv_task.php";'; // Redirect to sv_task.php
                    echo '</script>';
                } else {
                    // Error in task deletion
                    echo "Error deleting task: " . $stmt_delete_task->error;
                }

                $stmt_delete_task->close();
            } else {
                // Error in retrieving files
                echo "Error retrieving files: " . $stmt_files->error;
            }

            $stmt_files->close();
        }
    } elseif ($tag == 2) {
        // rename operation for tag 2

    } elseif ($tag == 3) {
        // Delete operation

    }
}

function getBatchIdByCategory($batchCategory, $conn)
{
    $sql = "SELECT batch_id FROM batches WHERE batch_category = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("s", $batchCategory);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        return $row['batch_id'];
    }

    return null;
}
// echo 'SVid ' . $supervisor_id . ' ';
// echo 'BATCH_ID ' . $selected_option;

?>

<div class="flex justify-between items-center">
    <div>
        <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: Supervisr</p>
    </div>
    <div class="ml-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="sv_supervisee.php" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-gray-600 hover:font-bold ">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                            Task
                        </a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>
<div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

<h1 class="text-4xl font-bold mb-4">Tasks</h1>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
    <div class="relative p-4 bg-gray-900 flex justify-between">
        <div class="bg-gray-900 flex items-center">
            <label for="batchForm" class="block text-xl font-sm text-gray-300 mr-4">Course:</label>
            <form id="batchForm" method="GET">
                <select name="batch_category" id="countries" class="text-sm rounded-md block p-2 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500">
                    <option value="CSP600">CSP600</option>
                    <option value="CSP650">CSP650</option>
                </select>
            </form>
            <?php
            if (isset($_GET['batch_category'])) {
                $batchC = $_GET['batch_category'];
            }
            ?>
        </div>
        <!-- <script>
            function submitForm() {
                document.getElementById("batchForm").submit();
            }
        </script> -->
        <div>
            <a href="sv_task_create.php" class="ml-auto text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 block">
                Post Task
            </a>
        </div>
    </div>
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Task Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Created Date
                </th>
                <th scope="col" class="px-6 py-3">
                    Due Date
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Assuming you have established a database connection ($conn)
            $supervisor_id = $_SESSION["user_id"];

            // Get the default batch_id for CSP600
            $defaultBatchId = getBatchIdByCategory('CSP600', $conn);

            // If no batch_category is selected, set the default to CSP600 batch_id
            $selected_option = isset($_GET['batch_category']) ? $_GET['batch_category'] : $defaultBatchId;

            $sql = "SELECT t.task_id, t.task_title, t.task_date_create, t.task_date_due, s.st_name, s.st_batch
                    FROM task t
                    JOIN task_student ts ON t.task_id = ts.task_id
                    JOIN student s ON ts.student_id = s.st_id
                    WHERE t.task_sv_id = ? AND s.st_batch = ?
                    GROUP BY t.task_id";

            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ii", $supervisor_id, $selected_option);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                die("Error preparing the statement.");
            }
            // Check if there are rows in the result set
            if ($result->num_rows > 0) {
                // Fetch and display data
                while ($row = $result->fetch_assoc()) {
                    // Format task_date_create
                    $task_date_create_formatted = date("d-m-Y h:i A", strtotime($row['task_date_create']));
                    $task_date_due_formatted = date("d-m-Y h:i A", strtotime($row['task_date_due']));
            ?>
                    <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                        <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>
                            <a href="sv_task_details.php?task_id=<?php echo $row['task_id']; ?>" class='font-medium text-blue-500 hover:underline hover:text-blue-400'>
                                <?php echo $row['task_title']; ?>
                            </a>
                        </td>
                        <td class='px-6 py-4'><?php echo $task_date_create_formatted; ?></td>
                        <td class='px-6 py-4'><?php echo $task_date_due_formatted; ?></td>
                        <td class='px-6 py-4 text-center' onclick=' return confirm("Are you sure you want to delete this task?")'>
                            <form action='function/task_del.php' method='POST'>
                                <input type='hidden' name='task_id' value='<?php echo $row["task_id"]; ?>'>
                                <button type='submit' class='font-medium text-blue-500 hover:text-blue-600'>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
            <?php
                }
            } else {
                // No tasks found
                echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                echo "<td colspan='5' class='px-6 py-4 text-center'>No tasks found</td>";
                echo "</tr>";
            }

            // Close the result set
            $result->close();

            // Close the statement
            $stmt->close();

            // Close the connection
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
</div>

