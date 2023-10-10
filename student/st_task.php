<?php
include 'includes/st_sidebar.php';
?>

<h1 class="text-2xl font-bold mb-4">Tasks</h1>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
    <div class="p-4 bg-gray-900 flex justify-between">
        <!-- Left side with search input -->
        <div class="relative">
            <label for="table-search" class="sr-only">Search</label>
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input type="text" id="table-search" class="block p-2 pl-10 rounded-lg w-80 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Search for items">
        </div>

    </div>
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Task Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Date Created
                </th>
                <th scope="col" class="px-6 py-3">
                    Part
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Replace 'your_supervisor_id' with the actual supervisor's user_id
            $student_id = $_SESSION['user_id'];
            // Assuming you have a 'tasks' table with columns 'task_id', 'task_name', and 'date_created'
            $sql = "SELECT t.task_id, t.task_title, t.date_create, t.task_part
            FROM task t
            JOIN supervise s ON t.task_sv_id = s.supervisor_id
            JOIN student st ON s.student_id = st.st_id
            WHERE s.student_id = ?
            AND t.task_part = st.st_part;
            ";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $student_id);

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $task_id = $row['task_id'];
                    $task_name = $row['task_title'];
                    $date_created = $row['date_create'];
                    $task_part = $row['task_part'];
            ?>
                    <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                        <td class='px-6 py-4 font-medium whitespace-nowrap text-white'><?php echo $task_name; ?></td>
                        <td class='px-6 py-4'><?php echo $date_created; ?></td>
                        <td class='px-6 py-4'><?php echo $task_part; ?></td>
                        <td class='px-6 py-4'>
                            <a href='st_task_details.php?task_id=<?php echo $task_id; ?>' class='font-medium text-blue-500 hover:underline'>View</a>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
            ?>
        </tbody>
    </table>
</div>