<?php
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'includes/sidebar.php';

include 'db.php'; // Include your database connection file

// Get the sv_id from the URL parameter
$sv_id = $_GET['sv_id'];

// Query to fetch supervisor data
$sqlSupervisor = "SELECT
sv_id AS supervisor_id,
TO_BASE64(sv_image) AS supervisor_image,
sv_name AS supervisor_name,
sv_email AS supervisor_email,
sv_phnum AS supervisor_phnum,
sv_quota AS supervisor_quota,
sv_expertise AS supervisor_expertise,
COUNT(v.supervisor_id) AS current_students
FROM
supervisor s
LEFT JOIN
supervise v ON s.sv_id = v.supervisor_id
WHERE
s.sv_id = ?
GROUP BY
s.sv_id, s.sv_name, s.sv_email, s.sv_phnum, s.sv_expertise;";




$stmtSupervisor = $conn->prepare($sqlSupervisor);
$stmtSupervisor->bind_param("i", $sv_id);
$stmtSupervisor->execute();
$resultSupervisor = $stmtSupervisor->get_result();

if ($resultSupervisor && $supervisor = $resultSupervisor->fetch_assoc()) {
?>

    <h1 class="text-4xl font-bold mb-4">Supervisor / <?php echo $supervisor['supervisor_name']; ?></h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="p-3 bg-white dark:bg-gray-900">
            <!-- Search input -->
            <h2 class="text-2xl font-bold  text-gray-900 whitespace-nowrap dark:text-white">Supervisor Details </h2>
        </div>
        <div>
            <table class="table-fixed w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <tbody>
                    <tr class='bg-white dark:bg-gray-800 dark:border-gray-700'>
                        <td scope='row' rowspan="4" class=' text-center w-1/5 px-2 py-3  '>
                            <img class='mx-auto w-16 h-25 md:w-32 h-41 lg:w-48 h-57' src='data:image/jpeg;base64,<?php echo $supervisor["supervisor_image"]; ?>' alt='Student Image' />
                        </td>
                        <td class='px-6 py-2 w-2/5'>
                            <p class="font-bold text-white">Name</p><?php echo $supervisor['supervisor_name']; ?>
                        </td>
                        <td rowspan="4" style='vertical-align: top;' class='px-6 py-2 w-1/5'>
                            <p class="font-bold text-white">Current Student / Quota</p>
                            <?php echo $supervisor['current_students'] . " / " . $supervisor['supervisor_quota']; ?>
                        </td>
                        <td rowspan="4" style='vertical-align: top;' class='px-6 py-2 w-1/5'>
                            <button type="submit" name="reject" value="reject" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Edit Quota</button>
                        </td>
                    </tr>
                    <tr class='bg-white dark:bg-gray-800 dark:border-gray-700'>
                        <td class='px-6 py-2'>
                            <p class="font-bold text-white">Email</p><?php echo $supervisor['supervisor_email']; ?>
                        </td>
                    </tr>
                    <tr class='bg-white dark:bg-gray-800 dark:border-gray-700'>
                        <td class='px-6 py-2'>
                            <p class="font-bold text-white">Phone Number</p><?php echo $supervisor['supervisor_phnum']; ?>
                        </td>
                    </tr>
                    <tr class='bg-white dark:bg-gray-800 dark:border-gray-700 '>
                        <td class='px-6 py-2'>
                            <p class="font-bold text-white">Expertise</p><?php echo $supervisor['supervisor_expertise']; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="p-3 bg-white dark:bg-gray-900">
            <!-- Search input -->
            <h2 class="text-2xl font-bold  text-gray-900 whitespace-nowrap dark:text-white">Supervise</h2>
        </div>
        <div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="w-1/7 px-6 py-3">
                            Group
                        </th>
                        <th scope="col" class="w-1/7 px-6 py-3">
                            Student id
                        </th>
                        <th scope="col" class="w-2/7 px-6 py-3">
                            name
                        </th>
                        <th scope="col" class="w-3/6 px-6 py-3">
                            project title
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sqlStudents = "SELECT
                                s.st_id AS student_id,
                                s.st_name AS student_name,
                                s.st_class AS student_class,
                                p.project_title AS project_title
                            FROM
                                student s
                            JOIN
                                supervise sp ON s.st_id = sp.student_id
                            JOIN
                                project p ON s.st_id = p.student_id
                            WHERE
                                sp.supervisor_id = ?";

                    $stmtStudents = $conn->prepare($sqlStudents);
                    $stmtStudents->bind_param("i", $sv_id);
                    $stmtStudents->execute();
                    $resultStudents = $stmtStudents->get_result();

                    if ($resultStudents) {
                        while ($student = $resultStudents->fetch_assoc()) {

                    ?>
                            <tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600'>
                                <td class='px-6 py-4'><?php echo $student['student_class']; ?></td>
                                <td class='px-6 py-4'><?php echo $student['student_id']; ?></td>
                                <td class='px-6 py-4'><?php echo $student['student_name']; ?></td>
                                <td class='px-6 py-4'><?php echo $student['project_title']; ?></td>
                            </tr>
                <?php
                        }
                    } else {
                        echo "Error fetching students: " . $stmtStudents->error;
                    }
                } else {
                    echo "Error fetching supervisor: " . $stmtSupervisor->error;
                }

                $stmtSupervisor->close();
                $stmtStudents->close();
                $conn->close();
                ?>
                </tbody>
            </table>

        </div>
    </div>



    <!-- content end -->
    </div>
    </div>
    </div>
    </body>

    </html