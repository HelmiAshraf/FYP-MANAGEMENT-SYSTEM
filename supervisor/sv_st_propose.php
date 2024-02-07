<?php

include 'includes/sv_sidebar.php';

$user_id = $_SESSION['user_id'];


$sql = "SELECT
s.st_id,
s.st_name AS student_name,
s.st_image AS student_image_path,
p.project_id,
p.project_title,
p.project_status,
p.project_submit_date
FROM
project p
INNER JOIN
student s ON p.student_id = s.st_id
WHERE
p.supervisor_id = ? AND p.project_status IN (2, 0)
ORDER BY
p.project_id DESC;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table-search').on('input', function() {
            var searchValue = $(this).val().toLowerCase();

            $('tbody tr').each(function() {
                var supervisorName = $(this).find('td:nth-child(2)').text().toLowerCase();
                var supervisorExpertise = $(this).find('td:nth-child(3)').text().toLowerCase();

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
        <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: Supervisor</p>
    </div>
    <div class="ml-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="sv_supervisee.php" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-gray-600 hover:font-bold ">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Supervise
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                            Propose Project
                        </a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>
<div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

<h1 class="text-4xl font-bold mb-4">Propose Project</h1>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
    <div class="p-4 bg-gray-900">
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
                <th scope="col" class="px-6 py-3 ">
                    student image
                </th>
                <th scope="col" class="px-6 py-3">
                    name
                </th>
                <th scope="col" class="px-6 py-3">
                    title
                </th>
                <th scope="col" class="px-6 py-3">
                    date request
                </th>
                <th scope="col" class="px-6 py-3 text-center">
                    status
                </th>
                <th scope="col" class="px-6 py-3 text-center">
                    action
                </th>
            </tr>
        </thead>
        <tbody>

            <?php


            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $student_image_base64 = '';

                    // Check if the image path exists
                    if (!empty($row["student_image_path"]) && file_exists($row["student_image_path"])) {
                        // Read the image content and convert it to base64
                        $student_image_base64 = base64_encode(file_get_contents($row["student_image_path"]));
                    }
            ?>
                    <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                        <td class='px-6 py-3'>
                            <img class='h-12 w-12 rounded' src='data:image/jpeg;base64,<?php echo $student_image_base64; ?>' alt='Student Image' />
                        </td>
                        <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'><?php echo $row['student_name']; ?></td>
                        <td class='px-6 py-4'><?php echo $row['project_title']; ?></td>
                        <td class='px-6 py-4'><?php echo $row['project_submit_date']; ?></td>
                        <td class='px-6 py-4 text-center'>
                            <?php
                            $statusClass = ($row['project_status'] == 1) ? 'text-green-700' : (($row['project_status'] == 0) ? 'text-red-500' : 'text-yellow-500');
                            $statusText = ($row['project_status'] == 1) ? 'Accept' : (($row['project_status'] == 0) ? 'Reject' : 'Pending');
                            ?>
                            <span class="inline-block <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                        </td>


                        <td class='px-6 py-4 text-center'>
                            <a href='sv_st_acceptance.php?project_id=<?php echo $row["project_id"]; ?>&st_id=<?php echo $row["st_id"]; ?>' class='font-medium text-blue-500 hover:underline'>View</a>
                        </td>
                    </tr>
            <?php }
            } else {
                // No propose found
                echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                echo "<td colspan='6' class='px-6 py-4 text-center'>No propose project found found</td>";
                echo "</tr>";
            }

            // Close the result set
            $result->close();

            // Close the statement
            $stmt->close();

            // Close the connection
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
</div>

<!-- content end -->
</div>
</div>
</div>
</body>

</html>