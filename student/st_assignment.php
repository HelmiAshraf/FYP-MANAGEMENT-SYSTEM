<?php
include 'includes/st_sidebar.php';

$user_id = $_SESSION["user_id"]; // Assuming you have stored the logged-in user's ID in a session variable

$batch_id = $_SESSION['batch_id'];

$sql = "SELECT
a.*,
fl.fl_name AS creator_name
FROM
assignment a
JOIN
fyp_lecturer fl ON a.ass_fl_id = fl.fl_id
WHERE 
a.batch_id = ?;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $batch_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table-search').on('input', function() {
                var searchValue = $(this).val().toLowerCase();

                $('tbody tr').each(function() {
                    var documentTitle = $(this).find('td:nth-child(1)').text().toLowerCase();

                    if (documentTitle.includes(searchValue)) {
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
            <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: Student</p>
        </div>
        <div class="ml-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="st_dashboard.php" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-gray-600 hover:font-bold ">
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
                            <a href="st_assignment.php" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                                Assignment
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

    <h1 class="text-4xl font-bold mb-4">Assignment</h1>
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
                <input type="text" id="table-search" class="block p-2 pl-10 text-sm border  rounded-lg w-80 focus:ring-blue-500 focus:border-blue-500 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Search for items">
            </div>
        </div>

        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="w-2/5 px-6 py-3">
                        Assignment
                    </th>
                    <th scope="col" class="w-2/5 px-6 py-3 ">
                        Owner
                    </th>
                    <th scope="col" class="w-1/5 px-6 py-3 ">
                        Submit Due
                    </th>
                </tr>
            </thead>
            <?php

            if ($result->num_rows > 0) {

                while ($row = mysqli_fetch_assoc($result)) {
                    // Format ass_date_create, ass_date_due, and ass_time_due
                    $ass_date_create_formatted = date("d-m-Y h:i A", strtotime($row['ass_date_create']));
                    $ass_date_due_formatted = date("d-m-Y h:i A", strtotime($row['ass_date_due']));

            ?>

                    <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                        <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>
                            <a href="st_assignment_details.php?ass_id=<?php echo $row['ass_id']; ?>" class='font-medium text-blue-500 hover:underline hover:text-blue-400'><?php echo $row['ass_title']; ?></a>
                        </td>
                        <td class='px-6 py-4 '><?php echo $row['creator_name']; ?></td>
                        <td class='px-6 py-4 '><?php echo $ass_date_due_formatted; ?></td>
                    </tr>
        <?php
                }
            } else {
                echo "<tbody>";
                echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                echo "<td colspan='3' class='px-6 py-4 text-center'>No task found</td>";
                echo "</tr>";
                echo "</tbody>";
            }
        } else {
            echo "Error: " . $stmt->error;
        }
        mysqli_close($conn);
        ?>

        </table>
    </div>



    <!-- content end -->
    </div>
    </div>
    </div>
    </body>

    </html>