<?php
include 'includes/sv_sidebar.php';
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
        <!-- Right side with "Edit Quota" button -->
        <div>
            <a href="sv_task_create.php" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 focus:outline-none block">Post Task</a>
        </div>

    </div>
    <table class="w-full text-sm text-left text-gray-400">
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
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Assuming you have established a database connection ($conn)
                $supervisor_id = $_SESSION["user_id"];

                $sql = "SELECT task_id, task_title, task_date_create FROM task WHERE task_sv_id = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $supervisor_id);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                ?>
                                <?php
                                while ($row = $result->fetch_assoc()) {
                                    // Format task_date_create
                                    $task_date_create_formatted = date("d-m-Y h:i A", strtotime($row['task_date_create']));
                                ?>
                                    <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                                        <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>
                                            <a href="sv_task_details.php?task_id=<?php echo $row['task_id']; ?>&task_title=<?php echo urlencode($row['task_title']); ?>" class='font-medium text-blue-500 hover:underline hover:text-blue-400'>
                                                <?php echo $row['task_title']; ?>
                                            </a>
                                        </td>
                                        <td class='px-6 py-4'><?php echo $task_date_create_formatted; ?></td>
                                        <td class='px-6 py-4 text-center'>
                                            <a href='fypl_proposal.php?tag=3&task_id=<?php echo $row["task_id"]; ?>' class='font-medium text-blue-500 hover:text-blue-600' onclick='return confirm("Are you sure you want to delete this task?")'>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                <?php
                    } else {
                        echo "<tbody>";
                        echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                        echo "<td colspan='3' class='px-6 py-4 text-center'>No tasks found</td>";
                        echo "</tr>";
                        echo "</tbody>";
                    }
                } else {
                    echo "Error executing the query: " . $stmt->error;
                }

                $stmt->close();
                mysqli_close($conn);
                ?>

</div>