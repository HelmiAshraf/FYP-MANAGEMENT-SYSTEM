<?php
include 'includes/sidebar.php';

$user_id = $_SESSION["user_id"];

$selected_option = isset($_GET['batch_category']) ? $_GET['batch_category'] : 'CSP600';

$sql = "SELECT
    s.st_id,
    s.st_batch,
    s.st_name AS student_name,
    p.project_title AS project_title,
    p.project_status,
    b.batch_category AS course
FROM
    student s
JOIN
    project p ON s.st_id = p.student_id
JOIN
    batches b ON s.st_batch = b.batch_id
WHERE
    b.batch_category = ? AND  p.project_status = 1";



$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $selected_option);

if ($stmt->execute()) {
    $result = $stmt->get_result();

?>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table-search').on('input', function() {
                var searchValue = $(this).val().toLowerCase();

                $('tbody tr').each(function() {
                    var studentName = $(this).find('td:nth-child(1)').text().toLowerCase();
                    var projectTitle = $(this).find('td:nth-child(2)').text().toLowerCase();

                    if (studentName.includes(searchValue) || projectTitle.includes(searchValue)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>


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
                            <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">Student</a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

    <h1 class="text-4xl font-bold mb-4">Students</h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
        <div class="p-4 bg-gray-900 flex justify-between">
            <div class="relative">
                <label for="table-search" class="sr-only">Search</label>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="table-search" class="block p-2 pl-10 text-sm border rounded-lg w-80 bg-gray-700 border-gray-600 placeholder-gray-400 text-white " placeholder="Search for Student?">
            </div>
            <div class="flex items-center">
                <label for="countries" class="block text-xl font-sm text-gray-300 mr-2">Course:</label>
                <form id="batchForm" method="GET">
                    <select name="batch_category" id="countries" class="text-sm rounded-md p-2 bg-gray-700 border-gray-600 placeholder-gray-700 text-white focus:ring-blue-500 focus:border-blue-500" onchange="submitForm()">
                        <option value="CSP600" <?php echo ($selected_option === 'CSP600') ? 'selected' : ''; ?>>CSP600</option>
                        <option value="CSP650" <?php echo ($selected_option === 'CSP650') ? 'selected' : ''; ?>>CSP650</option>
                    </select>
                </form>
            </div>
        </div>


        <script>
            function submitForm() {
                document.getElementById("batchForm").submit();
            }
        </script>
        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Student Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Project Title
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Course
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Student Details
                    </th>
                </tr>
            </thead>
            <tbody>

                <?php
                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_assoc()) {

                ?>
                        <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'><?= $row['student_name']; ?></td>
                            <td class='px-6 py-4'><?= $row['project_title']; ?></td>
                            <td class='px-6 py-4'><?= $row['course']; ?></td>
                            <td class='px-6 py-4'><a href='fypl_student_details.php?st_id=<?= $row["st_id"]; ?>' class='font-medium text-blue-500 hover:underline'>View</a></td>
                        </tr>
                    <?php
                    }
                    ?>
            </tbody>
    <?php
                } else {
                    echo "<tbody>";
                    echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                    echo "<td colspan='4' class='px-6 py-4 text-center'>No student found</td>";
                    echo "</tr>";
                    echo "</tbody>";
                }
            } else {
                echo "Error executing the query: " . $stmt->error;
            }
    ?>
        </table>
    </div>
    </body>

    </html>

    <?php
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    ?>

    <!-- content end -->
    </div>
    </div>
    </div>
    </body>

    </html>