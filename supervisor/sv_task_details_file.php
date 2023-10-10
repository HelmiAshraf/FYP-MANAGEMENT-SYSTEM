<?php



include 'includes/sv_sidebar.php';

// Assuming you have established a database connection ($conn) and have the user_id of the logged-in supervisor
$user_id = $_SESSION['user_id']; // Replace with your authentication mechanism

$sql = "SELECT
        ts.task_id,
        s.st_name AS student_name,
        ts.submissiondate
        FROM
        task_submission ts
        INNER JOIN
        task t ON ts.task_id = t.task_id
        INNER JOIN
        student s ON ts.student_id = s.st_id
        INNER JOIN
        supervise su ON ts.student_id = su.student_id
        WHERE
        su.supervisor_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Assuming user_id is an integer

if ($stmt->execute()) {
    $result = $stmt->get_result();
?>
    <h1 class="text-2xl font-bold mb-4 mt-4">Submitted Tasks</h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
        <div class="p-4 bg-gray-900">
            <label for="table-search" class="sr-only">Search</label>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="table-search" class="block p-2 pl-10 text-sm border rounded-lg w-80 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Search for items">
            </div>
        </div>
        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Student Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Submission Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                        <td class='px-6 py-3 font-medium whitespace-nowrap text-white'><?php echo $row['student_name']; ?></td>
                        <td class='px-6 py-3'><?php echo $row['submissiondate']; ?></td>
                        <td class='px-6 py-3'>
                            <a href='sv_student_acceptance.php?task_id=<?php echo $row["task_id"]; ?>' class='font-medium text-blue-500 hover:underline'>View</a>
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