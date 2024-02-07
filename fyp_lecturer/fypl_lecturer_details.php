<?php

include 'includes/sidebar.php';



// Get the sv_id from the URL parameter
$sv_id = $_GET['sv_id'];












// Query to fetch supervisor data
$sqlSupervisor = "SELECT
sv_id AS supervisor_id,
sv_image AS supervisor_image,
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
    $supervisor_image_base64 = '';

    if (!empty($supervisor["supervisor_image"]) && file_exists($supervisor["supervisor_image"])) {
        // Read the image content and convert it to base64
        $supervisor_image_base64 = base64_encode(file_get_contents($supervisor["supervisor_image"]));
    }
?>

    <div id="ass-modal" class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto transform scale-0 opacity-0 ">
        <div class="modal-overlay bg-black bg-opacity-70 fixed inset-0"></div>
        <div class="bg-white w-96 rounded-lg shadow-lg p-6 transform scale-100 opacity-100 transition-transform duration-300">
            <h2 class="text-2xl font-bold mb-4">Update Quota</h2>
            <form action="function/update_quota_specific.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="sv_id" value="<?php echo $sv_id ?>">
                <!-- Your form content here -->
                <div class="mb-6">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900">
                        Quota
                    </label>
                    <input type="text" required name="sv_quota" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" placeholder="Number of quota" />
                </div>
                <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover-bg-blue-700 focus-ring-blue-800">
                    Update
                </button>
            </form>
            <button id="close-modal-button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
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
                            <a href="fypl_lecturer.php" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                                Supervisor
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                                Supervisor Details
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

    <h1 class="text-4xl font-bold mb-4">Supervisor Profile<h1>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
                <div class="p-3 bg-gray-900">
                    <!-- Search input -->
                    <h2 class="text-2xl font-bold whitespace-nowrap text-white">Supervisor Details </h2>
                </div>
                <div>
                    <table class="table-fixed w-full text-sm text-left text-gray-400">
                        <tbody>
                            <tr class='bg-gray-800 border-gray-700'>
                                <td scope='row' rowspan="4" class=' text-center w-1/5 px-2 py-3  '>
                                    <img class='rounded max-w-full max-h-full' src='data:image/jpeg;base64,<?php echo $supervisor_image_base64; ?>' alt='supervisor Image' />
                                </td>
                                <td class='px-6 py-2 w-2/5'>
                                    <p class="font-bold text-white">Name</p><?php echo $supervisor['supervisor_name']; ?>
                                </td>
                                <td rowspan="4" style='vertical-align: top;' class='px-6 py-2 w-1/5'>
                                    <!-- //TODO EDIT QUOTA FOR SPECIFIC LECTURER -->
                                    <p class="font-bold text-white">Current Student / Quota</p>
                                    <?php echo $supervisor['current_students'] . " / " . $supervisor['supervisor_quota']; ?>
                                </td>
                                <td rowspan="4" style='vertical-align: top;' class='px-6 py-2 w-1/5'>
                                    <button id="open-modal-button" class="ml-auto text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-700 hover:bg-blue-800">
                                        Update Quota
                                    </button>
                                </td>
                            </tr>
                            <tr class='bg-gray-800 border-gray-700'>
                                <td class='px-6 py-2'>
                                    <p class="font-bold text-white">Email</p><?php echo $supervisor['supervisor_email']; ?>
                                </td>
                            </tr>
                            <tr class='bg-gray-800 border-gray-700'>
                                <td class='px-6 py-2'>
                                    <p class="font-bold text-white">Phone Number</p><?php echo $supervisor['supervisor_phnum']; ?>
                                </td>
                            </tr>
                            <tr class='bg-gray-800 border-gray-700'>
                                <td class='px-6 py-2'>
                                    <p class="font-bold text-white">Expertise</p><?php echo $supervisor['supervisor_expertise']; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="p-3 bg-gray-900">
                    <!-- Search input -->
                    <h2 class="text-2xl font-bold whitespace-nowrap text-white">Supervisee</h2>
                </div>
                <div>
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                            <tr>
                                <th scope="col" class="w-1/7 px-6 py-3">
                                    Student id
                                </th>
                                <th scope="col" class="w-3/7 px-6 py-3">
                                    name
                                </th>
                                <th scope="col" class="w-3/7 px-6 py-3">
                                    project title
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- //TODO TUNJUKAN SUPERVISE SUPERVISOR -->
                            <?php
                            $sqlStudents = "SELECT
                            s.st_id,
                            s.st_name,
                            gc.gantt_chart_id,
                            MAX(p.project_id) AS latest_project_id,
                            MAX(CASE WHEN p.project_status = 1 THEN p.project_title END) AS latest_project_title
                        FROM
                            student s
                        LEFT JOIN
                            project p ON p.student_id = s.st_id AND p.project_status = 1
                        INNER JOIN
                            supervise sp ON s.st_id = sp.student_id
                        LEFT JOIN
                            gantt_chart gc ON s.st_id = gc.student_id
                        WHERE
                            sp.supervisor_id = ?
                        GROUP BY
                            s.st_id, s.st_name, gc.gantt_chart_id
                        ORDER BY
                            gc.gantt_chart_id DESC;
                        ";

                            $stmtStudents = $conn->prepare($sqlStudents);
                            $stmtStudents->bind_param("i", $sv_id);
                            $stmtStudents->execute();
                            $resultStudents = $stmtStudents->get_result();

                            if ($resultStudents) {
                                if ($resultStudents->num_rows > 0) {

                                    while ($student = $resultStudents->fetch_assoc()) {

                            ?>
                                        <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                                            <td class='px-6 py-4'><?php echo $student['st_id']; ?></td>
                                            <td class='px-6 py-4'><?php echo $student['st_name']; ?></td>
                                            <td class='px-6 py-4'><?php echo $student['latest_project_title']; ?></td>
                                        </tr>
                        <?php
                                    }
                                } else {
                                    echo "<tbody>";
                                    echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                                    echo "<td colspan='4' class='px-6 py-4 text-center'>No student found</td>";
                                    echo "</tr>";
                                    echo "</tbody>";
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

            <script>
                const openModalButton = document.getElementById("open-modal-button");
                const closeModalButton = document.getElementById("close-modal-button");
                const assModal = document.getElementById("ass-modal");

                openModalButton.addEventListener("click", () => {
                    assModal.classList.remove("scale-0", "opacity-0");
                    assModal.classList.add("scale-100", "opacity-100");
                });

                closeModalButton.addEventListener("click", () => {
                    assModal.classList.remove("scale-100", "opacity-100");
                    assModal.classList.add("scale-0", "opacity-0");
                });
            </script>


            <!-- content end -->
            </div>
            </div>
            </div>
            </body>

            </html