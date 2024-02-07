<?php

include 'includes/sv_sidebar.php';

// Replace {supervisor_id} with the actual ID of the logged-in supervisor
$supervisor_id = $_SESSION["user_id"];

// Assuming you have a valid database connection ($conn) and the supervisor ID in $supervisor_id

$sql = "SELECT s.st_id, s.st_name, gc.gantt_chart_id, MAX(p.project_id) AS latest_project_id
        FROM student s
        LEFT JOIN project p ON p.student_id = s.st_id
        INNER JOIN supervise sp ON s.st_id = sp.student_id
        LEFT JOIN gantt_chart gc ON s.st_id = gc.student_id
        WHERE sp.supervisor_id = ?
        GROUP BY s.st_id, s.st_name, gc.gantt_chart_id
        ORDER BY gc.gantt_chart_id DESC;
        
        ;
        ";

if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $supervisor_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <style>
        #tooltip-top.tooltip {
            text-transform: lowercase;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table-search').on('input', function() {
                var searchValue = $(this).val().toLowerCase();

                $('tbody tr').each(function() {
                    var supervisorName = $(this).find('td:nth-child(1)').text().toLowerCase();
                    // var supervisorExpertise = $(this).find('td:nth-child(2)').text().toLowerCase();

                    if (supervisorName.includes(searchValue)) {
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
            <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: Supervisor</p>
        </div>
        <div class="ml-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="#" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-gray-600 hover:font-bold ">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Supervise
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

    <h1 class="text-4xl font-bold mb-4">Your Supervisee</h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
        <div class="p-4 bg-gray-900 flex justify-between">
            <!-- Search input -->
            <label for="table-search" class="sr-only">Search</label>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="table-search" class="block p-2 pl-10 text-sm border rounded-lg w-80 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Search for student?">
            </div>
        </div>

        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="w-3/5 px-6 py-3">
                        Student Name
                    </th>
                    <th scope="col" class="w-1/5 px-6 py-3 text-center">
                        Gantt Chart
                    </th>
                    <th scope="col" class="px-6 py-3 flex item-center justify-center text-center">
                        <div class="mr-2 flex items-center justify-center text-center ">
                            Unlink
                        </div>
                        <svg data-tooltip-target="tooltip-top" data-tooltip-placement="top" type="button" class="w-6 h-6 text-blue-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.6-8.5h0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <div id="tooltip-top" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-800 rounded-lg shadow-sm opacity-0 tooltip">
                            If you unlink, the student will no longer be under your supervision
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </th>
                </tr>
            </thead>
            <?php



            if ($result->num_rows > 0) {

                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                        <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap text-white">
                            <a href="student_profile.php?st_id=<?php echo $row['st_id']; ?>" class='text-blue-500 hover:underline hover:text-blue-400'>
                                <?php echo $row['st_name']; ?>
                            </a>
                        </td>
                        <td class='px-6 py-4 text-center'>
                            <a href="sv_supervisee_detail.php?st_id=<?php echo $row['st_id']; ?>&gantt_chart_id=<?php echo $row['gantt_chart_id']; ?>" class='text-blue-500 hover:underline hover:text-blue-400'>
                                View
                            </a>
                        </td>
                        <td class='px-6 py-4 text-center' onclick=' return confirm("If you choose to unlink this student, you will no longer be supervising them.")'>
                            <a href="function/remove_supervise.php?st_id=<?php echo $row['st_id']; ?>" class='text-orange-500 hover:underline hover:text-orange-600' onclick=' return confirm("Are you sure to unlike this student?")'>
                                Unlink
                            </a>
                        </td>
                    </tr>
        <?php
                }
            } else {
                // No supervise found
                echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                echo "<td colspan='5' class='px-6 py-4 text-center'>No supervise found</td>";
                echo "</tr>";
            }

            mysqli_stmt_close($stmt);
        }
        ?>




        </table>

    </div>



    <!-- content end -->
    </div>
    </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

    </body>

    </html>