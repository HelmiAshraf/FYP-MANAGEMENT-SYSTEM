<?php
include 'includes/sidebar.php';

$user_id = $_GET["st_id"]; // Assuming you have stored the logged-in user's ID in a session variable

$sql = "SELECT
    s.st_name AS student_name,
    s.st_id AS student_id,
    s.st_phnum AS student_phnum,
    s.st_email AS student_email,
    s.st_image AS student_image_path,
    p.objective AS project_objective,
    p.significant AS project_significant,
    p.scope AS project_scope,
    p.project_title AS project_title,
    sv.sv_name AS supervisor_name
FROM
    student s
JOIN
    project p ON s.st_id = p.student_id
JOIN
    supervise sp ON s.st_id = sp.student_id
JOIN
    supervisor sv ON sp.supervisor_id = sv.sv_id
WHERE
    s.st_id = ?
ORDER BY
p.project_id DESC
LIMIT 1;";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $student_image_base64 = '';

        // Check if the image path exists
        if (!empty($row["student_image_path"]) && file_exists($row["student_image_path"])) {
            // Read the image content and convert it to base64
            $student_image_base64 = base64_encode(file_get_contents($row["student_image_path"]));
        }

?>

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
                                <a href="fypl_student.php" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                                    Student
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                                    Student Details
                                </a>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

        <h1 class="text-4xl font-bold mb-4">Student Profile</h1>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
            <div class="p-3 bg-gray-900">
                <h2 class="text-2xl font-bold whitespace-nowrap text-white">Student Details </h2>
            </div>
            <div>
                <table class="table-fixed w-full text-sm text-left text-gray-400">
                    <tbody>
                        <tr class='bg-gray-800 border-gray-700'>
                            <td scope='row' rowspan="4" class='text-center w-1/4 px-2 py-3'>
                                <div class="flex justify-center items-center h-36 "> <!-- Adjust the height as needed -->
                                    <img class='rounded max-w-full max-h-full' src='data:image/jpeg;base64,<?php echo $student_image_base64; ?>' alt='Student Image' />
                                </div>
                            </td>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Name</p><?php echo $row['student_name']; ?>
                            </td>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Phone Number</p><?php echo $row['student_phnum']; ?>
                            </td>
                        </tr>
                        <tr class='bg-gray-800 border-gray-700 '>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Student ID</p><?php echo $row['student_id']; ?>
                            </td>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Email</p><?php echo $row['student_email']; ?>
                            </td>
                        </tr>
                        <tr class='bg-gray-800 border-gray-700'>
                            <td class='px-6 py-2' colspan="2">
                                <p class="font-bold text-white">Supervisor Name</p><?php echo $row['supervisor_name']; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="p-3 bg-gray-900">
                <!-- Search input -->
                <h2 class="text-2xl font-bold whitespace-nowrap text-white">Student FYP Details </h2>
            </div>
            <div>
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                        <tr>
                            <th scope="col" class="w-1/4 px-6 py-3">
                                maters
                            </th>
                            <th scope="col" class="w-3/4 px-6 py-3">
                                details
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>Project Title</td>
                            <td class='px-6 py-4'><?php echo $row['project_title']; ?></td>
                        </tr>
                        <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>Objective</td>
                            <td class='px-6 py-4'><?php echo $row['project_objective']; ?></td>
                        </tr>
                        <tr class='border-b bg-gray-800 border-gray-700  hover:bg-gray-900'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>Scope</td>
                            <td class='px-6 py-4'><?php echo $row['project_scope']; ?></td>
                        </tr>
                        <tr class='border-b bg-gray-800 border-gray-700 h hover:bg-gray-900'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>Significant</td>
                            <td class='px-6 py-4'><?php echo $row['project_significant']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        </body>

        </html>

<?php
    }
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>

<!-- content end -->
</div>
</div>
</div>
</body>

</html