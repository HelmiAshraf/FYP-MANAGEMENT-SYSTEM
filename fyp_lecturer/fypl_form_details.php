<?php
// Check if 'form_id' is set in the URL
if (isset($_GET['form_id'])) {
    // Get the form_id from the URL
    $form_id = $_GET['form_id'];

    include 'includes/sidebar.php';

    // Assuming you have a 'form' table with columns 'form_id', 'Form_title', 'form_date_create', 'form_date_due', and a 'form_submit' table with columns 'submission_id', 'form_id', 'student_id', 'SubmissionDate'
    // Corrected SQL query to fetch form details
    $sql = "SELECT f.Form_title, f.form_date_create, f.form_date_due
            FROM form f
            WHERE f.form_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $form_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $form_title = $row['Form_title'];
            $form_date_create = $row['form_date_create'];
            $form_date_due = $row['form_date_due'];
?>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-gray-800 text-gray-400">
                <ul class="flex text-sm">
                    <li class="mr-2">
                        <p class="text-base font-semibold text-white">
                            Form Title:
                        </p>
                    </li>
                    <li>
                        <p class="mb-3 text-base text-gray-200">
                            <?php echo $form_title; ?>
                        </p>
                    </li>
                </ul>
                <ul class="text-sm">
                    <li class="mr-2">
                        <p class="text-base font-semibold text-white">
                            Date Created:
                        </p>
                    </li>
                    <li>
                        <p class="mb-3 text-base text-gray-200">
                            <?php echo $form_date_create; ?>
                        </p>
                    </li>
                </ul>
                <ul class="text-sm">
                    <li class="mr-2">
                        <p class="text-base font-semibold text-white">
                            Due Date:
                        </p>
                    </li>
                    <li>
                        <p class="mb-3 text-base text-gray-200">
                            <?php echo $form_date_due; ?>
                        </p>
                    </li>
                </ul>
            </div>
<?php
        } else {
            // Handle the case where no form with the specified form_id is found
            echo "Form not found.";
        }
    } else {
        echo "Error fetching form: " . $stmt->error;
    }
}
?>

<!-- Display Submitted Tasks -->
<?php

?>
    <h1 class="text-2xl font-bold mb-4 mt-4">Submitted Tasks</h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
        <div class="p-4 bg-gray-900">
            <label for="table-search" class="sr-only">Search</label>
            <!-- ... Rest of the search input code ... -->
        </div>
        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        File Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Owner
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Submission Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <?php

            // Prepare and execute the SQL query
            $sql = "SELECT
            s.st_name AS student_name,
            f.file_content,
            f.file_name,
            fs.submissiondate
            FROM
            student AS s
            INNER JOIN
            form_submission AS fs ON s.st_id = fs.student_id
            INNER JOIN
            file AS f ON fs.form_id = f.type_id
            WHERE
            fs.form_id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $form_id);

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
            ?>
                        <tbody>
                            <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                                <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-black'>
                                    <a href='st_proposal_details.php?proposal_id=<?php echo $row["file_name"]; ?>' class='font-medium text-blue-500 hover:underline hover:text-blue-400'><?php echo $row['file_name']; ?></a>
                                </td>
                                <td class='px-6 py-4 '><?php echo $row['student_name']; ?></td>
                                <td class='px-6 py-4 '><?php echo $row['submissiondate']; ?></td>
                                <td class='px-6 py-4 text-center' onclick=' return confirm("Are you sure you want to delete?")'>
                                    <a href='fypl_proposal_details.php?tag=2&proposal_submission_id=<?php echo $row["proposal_submission_id"]; ?>' class='font-medium text-blue-500 hover:text-blue-600'>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
            <?php
                    }
                } else {
                    echo "<tbody>";
                    echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                    echo "<td colspan='4' class='px-6 py-4 text-center'>No form found</td>";
                    echo "</tr>";
                    echo "</tbody>";
                }
            } else {
                echo "Error executing the query: " . $stmt->error;
            }
            $stmt->close();
            mysqli_close($conn);
            ?>
        </table>
    </div>
</div>


<!-- content end -->
</div>
</div>
</div>
</body>

</html>