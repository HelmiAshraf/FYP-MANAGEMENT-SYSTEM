<?php
// Check if 'task_id' is set in the URL
if (isset($_GET['task_id'])) {
    // Get the task_id from the URL
    $task_id = $_GET['task_id'];

    include 'includes/sv_sidebar.php';

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
                $sql_files = "SELECT f.file_name, f.uploader_id
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
                                    ?>
                                        <li class="mb-3 text-base text-gray-200">
                                            <?php echo $file_name; ?> (Uploader ID: <?php echo $uploader_id; ?>)
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

    // Close the statement and database connection
    // $stmt->close();
    // $conn->close();
} else {
    // Handle the case where 'task_id' is not set in the URL
    echo "Task ID not provided.";
}
?>

<?php

// Assuming you have established a database connection ($conn) and have the user_id of the logged-in supervisor
$user_id = $_SESSION['user_id']; // Replace with your authentication mechanism

$sql = "SELECT
        s.st_id,
        s.st_name AS student_name,
        TO_BASE64(s.st_image) AS st_image_base64,
        p.project_title,
        p.project_submit_date
        FROM
        project p
        INNER JOIN
        student s ON p.student_id = s.st_id
        WHERE
        p.supervisor_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Assuming user_id is an integer

if ($stmt->execute()) {
    $result = $stmt->get_result();

?>
    <h1 class="text-2xl font-bold mb-4 mt-4">Submited Task</h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="p-4 bg-gray-900">
            <label for="table-search" class="sr-only">Search</label>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="table-search" class="block p-2 pl-10 text-sm border  rounded-lg w-80 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Search for items">
            </div>
        </div>
        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        student image
                    </th>
                    <th scope="col" class="px-6 py-3">
                        name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        project title
                    </th>
                    <th scope="col" class="px-6 py-3">
                        date request
                    </th>
                    <th scope="col" class="px-6 py-3">
                        action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                        <td class='px-6 py-3'>
                            <img class='h-12 w-12' src='data:image/jpeg;base64,<?php echo $row["st_image_base64"]; ?>' alt='Student Image' />
                        </td>
                        <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'><?php echo $row['student_name']; ?></td>
                        <td class='px-6 py-4'><?php echo $row['project_title']; ?></td>
                        <td class='px-6 py-4'><?php echo $row['project_submit_date']; ?></td>
                        <td class='px-6 py-4'>
                            <a href='sv_student_acceptance.php?st_id=<?php echo $row["st_id"]; ?>' class='font-medium text-blue-500 hover:underline'>View</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
    ?>
    </div>

    <!-- content end -->
    </div>
    </div>
    </div>
    </body>

    </html>