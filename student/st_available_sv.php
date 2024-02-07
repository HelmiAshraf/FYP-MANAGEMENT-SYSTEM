<?php

include 'includes/st_sidebar_choose_sv.php';


$sql = "SELECT
s.sv_id,
s.sv_name,
s.sv_email,
s.sv_phnum,
s.sv_expertise,
s.sv_image AS sv_image_path,
s.sv_status,
s.sv_quota,
COUNT(v.supervisor_id) AS current_students
FROM
supervisor s
LEFT JOIN
supervise v ON s.sv_id = v.supervisor_id
GROUP BY
s.sv_id, s.sv_name, s.sv_email, s.sv_phnum, s.sv_expertise, s.sv_image, s.sv_status, s.sv_quota
HAVING
current_students < s.sv_quota
ORDER BY
s.sv_name ASC;
";

$result = $conn->query($sql);

if ($result) {
?>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table-search').on('input', function() {
                var searchValue = $(this).val().toLowerCase();

                $('tbody tr').each(function() {
                    var supervisorName = $(this).find('td:nth-child(2)').text().toLowerCase();
                    var supervisorExpertise = $(this).find('td:nth-child(5)').text().toLowerCase();

                    if (supervisorName.includes(searchValue) || supervisorExpertise.includes(searchValue)) {
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
                        <a href="#" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-gray-600 hover:font-bold ">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Available Supervisor
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

    <h1 class="text-4xl font-bold mb-4">Choose Supervisor</h1>
    <input type="hidden" value="<?php $student_id = $_SESSION['user_id'];
                                echo $student_id; ?>">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
        <div class="p-4 bg-gray-900">
            <label for="table-search" class="sr-only">Search</label>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="table-search" class="block p-2 pl-10 text-sm border rounded-lg w-80 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Search for supervisor?">
            </div>
        </div>
        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        image
                    </th>
                    <th scope="col" class="px-6 py-3">
                        name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        phone number
                    </th>
                    <th scope="col" class="px-6 py-3">
                        email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        expertise
                    </th>
                    <th scope="col" class="px-6 py-3">
                        current student / quota
                    </th>
                    <th scope="col" class="px-6 py-3">
                        action
                    </th>

                </tr>
            </thead>
            <tbody>
            <?php

            while ($row = $result->fetch_assoc()) {
                $sv_image_base64 = '';

                // Check if the image path exists
                if (!empty($row["sv_image_path"]) && file_exists($row["sv_image_path"])) {
                    // Read the image content and convert it to base64
                    $sv_image_base64 = base64_encode(file_get_contents($row["sv_image_path"]));
                }

                echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                echo "<td class='px-6 py-4'><img class='rounded max-h-12' src='data:image/jpeg;base64," . $sv_image_base64 . "' alt='Supervisor Image' /></td>";
                echo "<td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>" . $row["sv_name"] . "</td>";
                echo "<td class='px-6 py-4'>" . $row["sv_phnum"] . "</td>";
                echo "<td class='px-6 py-4'>" . $row["sv_email"] . "</td>";
                echo "<td class='px-6 py-4'>" . $row["sv_expertise"] . "</td>";


                // Display current_students and sv_quota as a fraction
                echo "<td class='px-6 py-4'>" . $row["current_students"] . " / " . $row["sv_quota"] . "</td>";

                $sql1 = "SELECT DISTINCT
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
                                p.student_id = ?";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param("i", $student_id); // Assuming student_id is an integer

                // Execute the query
                $stmt1->execute();

                // Get the result set
                $result1 = $stmt1->get_result();
                // Check the project status for the button to appear
                if ($result1->num_rows > 0) {
                    while ($row1 = $result1->fetch_assoc()) {
                        if ($row1['project_status'] == 2) {
                            echo "<td class='px-6 py-4'>
                                <a class='font-medium text-blue-500 hover:underline' role='link' aria-disabled='true' onclick='checkP()'>Choose</a>
                                </td>";
                        } else if ($row1['project_status'] == 1) {
                            echo "<td class='px-6 py-4'>
                                <a class='font-medium text-blue-500 hover:underline' role='link' aria-disabled='true' onclick='checkA()'>Choose</a>
                                </td>";
                        } else {
                            echo  "<td class='px-6 py-4'>
                            <a href='javascript:void(0);' onclick='chooseSupervisor(\"{$row['sv_id']}\")' class='font-medium text-blue-500 hover:underline'>Choose</a>
                        </td>";
                        }
                    }
                } else {
                    echo "<td class='px-6 py-4'>
                                <a href='javascript:void(0);' onclick='chooseSupervisor(\"{$row['sv_id']}\")' class='font-medium text-blue-500 hover:underline'>Choose</a>
                            </td>";

                    echo "</tr>";
                }
            }
        } else {
            echo "<tr><td colspan='7' class='py-2 px-4 text-center'>No supervisors found.</td></tr>";
        }
        $conn->close();
            ?>
            </tbody>



        </table>
    </div>



    <!-- content end -->
    </div>
    </div>
    </div>
    </body>
    <script type="text/javascript">
        function checkP() {

            var pending = "Your request is pending";

            alert(pending);
        }

        function checkA() {

            var accept = "Your request have been accepted. Please go to student Dashboard";

            alert(accept);
        }
    </script>
    <script>
        function chooseSupervisor(svId) {
            // Use AJAX to check the supervisor's quota in real-time
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'check_quota.php?sv_id=' + svId, true);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);

                            if (response.quotaFull) {
                                // Quota is full, refresh the page
                                alert("Supervisor's quota is full. Please choose another supervisor.");
                                location.reload();
                            } else {
                                // Quota is not full, proceed to st_propose_project.php
                                window.location.href = 'st_propose_project.php?sv_id=' + svId;
                            }
                        } catch (error) {
                            console.error('Error parsing JSON:', xhr.responseText);
                            alert('Error processing the response. Please try again.');
                        }
                    } else {
                        console.error('Request failed with status:', xhr.status);
                        alert('Failed to check supervisor quota. Please try again.');
                    }
                }
            };

            xhr.send();
        }
    </script>




    </html>