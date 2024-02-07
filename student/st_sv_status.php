<?php include 'includes/st_sidebar_choose_sv.php';

// Check if the student ID is stored in the session
if (isset($_SESSION['user_id'])) {
    $student_id = $_SESSION['user_id'];
} else {
    // Handle the case where the student ID is not in the session
    echo "Student ID not found in the session.";
    exit(); // Exit the script
}


?>

<div class="flex justify-between items-center">
    <div>
        <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: Student</p>
    </div>
    <div class="ml-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="st_available_sv.php" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-gray-600 hover:font-bold ">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Available Supervisor
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                            Supervisor Result
                        </a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>
<div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

<h1 class="text-4xl font-bold mb-4">Supervisor Result</h1>

<?php


$sql = "SELECT 
p.project_id AS project_ID,
s.st_name AS student_name,
s.st_id AS student_id,
s.st_phnum AS student_phnum,
s.st_email AS student_email,
s.st_image AS student_image_path,
p.project_description AS project_description,
p.project_title AS project_title,
sv.sv_name AS supervisor_name,
sv.sv_image,
sv.sv_email,
sv.sv_phnum,
sv.sv_expertise,
p.project_status
FROM
student s
JOIN
project p ON s.st_id = p.student_id
JOIN
supervisor sv ON p.supervisor_id = sv.sv_id
WHERE
s.st_id = ?
ORDER BY
project_ID DESC
LIMIT 1;

            ";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $student_image_base64 = '';

        // Check if the image path exists
        if (!empty($row["student_image_path"]) && file_exists($row["student_image_path"])) {
            // Read the image content and convert it to base64
            $student_image_base64 = base64_encode(file_get_contents($row["student_image_path"]));
        }

        $projectStatusLabel = "";
        $statusColorClass = "";

        switch ($row['project_status']) {
            case 0:
                $projectStatusLabel = "Reject";
                $statusColorClass = "text-red-500";
                break;
            case 1:
                $projectStatusLabel = "Accept";
                $statusColorClass = "text-green-500";
                break;
            case 2:
            default:
                $projectStatusLabel = "Pending";
                $statusColorClass = "text-yellow-500";
                break;
        }
?>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-gray-800 text-gray-400 mb-4">
            <div class="">
                <div class="relative overflow-x-auto">
                    <div class="px-3 py-2 bg-gray-900 flex justify-between">
                        <div class="relative flex items-center justify-center">
                            <h1 class="text-2xl font-bold text-white p-2">Supervisor Details</h1>
                        </div>

                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-white">
                        <tbody>
                            <tr class="border-b bg-gray-800 border-gray-700">
                                <td rowspan="4" class="px-6 py-4 border-r bg-gray-800 border-gray-700 text-center">
                                    <img class='h-36 w-36 rounded-lg inline-block' src='<?php echo $row['sv_image']; ?>' alt="Supervisor">
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold"> Name:
                                        <span class="font-normal text-gray-300">
                                            <?php echo $row['supervisor_name']; ?>
                                        </span>
                                    </p>
                                </td>
                                <td rowspan="4" class="px-6 py-4 border-l border-gray-700">
                                    <p class="font-semibold text-white" style="vertical-align: top;">Application Status:
                                        <span class="<?php echo $statusColorClass; ?> font-semibold">
                                            <?php echo $projectStatusLabel; ?>
                                        </span>
                                    </p>
                                </td>
                            </tr>
                            <tr class=" border-b bg-gray-800 border-gray-700">
                                <td class="px-6 py-4">
                                    <p class="font-semibold">Email:
                                        <span class="font-normal text-gray-300">
                                            <?php echo $row['sv_email']; ?>
                                        </span>
                                    </p>
                                </td>
                            </tr>
                            <tr class=" border-b bg-gray-800 border-gray-700">
                                <td class="px-6 py-4">
                                    <p class="font-semibold">Phone Number:
                                        <span class="font-normal text-gray-300">
                                            <?php echo $row['sv_phnum']; ?>
                                        </span>
                                    </p>
                                </td>
                            </tr>
                            <tr class=" border-b bg-gray-800 border-gray-700">
                                <td class="px-6 py-4">
                                    <p class="font-semibold">Expertise:
                                        <span class="font-normal text-gray-300">
                                            <?php echo $row['sv_expertise']; ?>
                                        </span>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="relative overflow-x-auto">
                    <div class="px-3 py-2 bg-gray-900 flex justify-between">
                        <div class="relative flex items-center justify-center">
                            <h1 class="text-2xl font-bold text-white p-2">Propose Project Details</h1>
                        </div>

                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-white">
                        <tbody>
                            <tr class="border-b bg-gray-800 border-gray-700">
                                <td class="px-6 py-4 border-r bg-gray-700 border-gray-700">
                                    <p class="font-semibold">Project Title:
                                    </p>
                                </td>
                            </tr>
                            <tr class=" border-b bg-gray-800 border-gray-700">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-300 mb-">
                                        <?php echo $row['project_title']; ?>
                                    </p>
                                </td>
                            </tr>
                            <tr class=" border-b bg-gray-800 border-gray-700">
                                <td class="px-6 py-4 bg-gray-700 border-gray-700">
                                    <p class="font-semibold">Project Description:
                                    </p>
                                </td>
                            </tr>
                            <tr class=" border-b bg-gray-800 border-gray-700">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-300 ">
                                        <?php echo nl2br($row['project_description']); ?>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <?php
                $sql1 = "SELECT
                            s.sv_id,
                            s.sv_name,
                            s.sv_email,
                            s.sv_expertise,
                            TO_BASE64(s.sv_image) AS sv_image_base64,
                            s.sv_status,
                            s.sv_quota,
                            s.sv_phnum,
                            p.project_status
                            FROM
                                project p
                            JOIN
                                supervisor s ON p.supervisor_id = s.sv_id
                            WHERE
                                p.student_id = ?
                            LIMIT 1
                                ";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param("i", $student_id); // Assuming student_id is an integer

                // Execute the query
                $stmt1->execute();

                // Get the result set
                $result1 = $stmt1->get_result();
                // Check the project status for the button to appear
                if ($result1->num_rows > 0) {
                    while ($row1 = $result1->fetch_assoc()) {
                        if ($row['project_status'] == 1) {
                ?>
                            <!-- Change Supervisor and Confirm Supervisor Buttons -->
                            <div class="w-full flex justify-end p-4">
                                <a href="st_dashboard.php" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 block flex items-center space-x-2">
                                    <span>Student Dashboard</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 ">
                                        <path fill-rule="evenodd" d="M12.97 3.97a.75.75 0 011.06 0l7.5 7.5a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 11-1.06-1.06l6.22-6.22H3a.75.75 0 010-1.5h16.19l-6.22-6.22a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                <?php
                        }
                    }
                }
                ?>
            </div>
        </div>
    <?php
    }
} else { ?>
    <div class="w-full p-4 text-center border rounded-lg shadow sm:p-8 bg-gray-800 border-gray-700">
        <h5 class="mb-2 text-3xl font-bold text-white">Oops!! you dont propose your project yet</h5>
        <p class="mb-5 text-base sm:text-lg text-gray-400">Please choose your favorite supervisor and propose your idea or project, provide them very details about your project and come back here.</p>
        <div class="items-center justify-center space-y-4 sm:flex sm:space-y-0 sm:space-x-4 rtl:space-x-reverse">
            <a href="st_available_sv.php" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 block ">
                Available Supervisor
            </a>
        </div>
    </div>
<?php
}

// Close the database connection and release resources
$stmt->close();
$conn->close();
?>


</div>
</div>

<!-- content end -->
</div>
</div>
</div>
</body>

</html>