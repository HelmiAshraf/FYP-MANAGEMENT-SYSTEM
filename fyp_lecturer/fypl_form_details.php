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
$sql_students = "SELECT
    s.st_name AS student_name,
    ff.file_content,
    fs.SubmissionDate
    FROM
    student AS s
    INNER JOIN
    form_submit AS fs ON s.st_id = fs.student_id
    INNER JOIN
    file_form AS ff ON fs.form_id = ff.form_id
    WHERE
    fs.form_id = ?";

$stmt_students = $conn->prepare($sql_students);
$stmt_students->bind_param("i", $form_id);

if ($stmt_students->execute()) {
    $result_students = $stmt_students->get_result();
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
                        Student Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Submission Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result_students->fetch_assoc()) { ?>
                    <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                        <td class='px-6 py-3 font-medium whitespace-nowrap text-white'><?php echo $row['student_name'] ?></td>
                        <td class='px-6 py-3'><?php echo $row['SubmissionDate']  ?></td>
                        <td class='px-6 py-3'>
                            <!-- You can add your action here -->
                            <!-- For example: <a href='view_task.php?task_id=<?php echo $row["task_id"]; ?>' class='font-medium text-blue-500 hover:underline'>View</a> -->
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php
} else {
    // Handle the case where no students have submitted the form
    echo "<p class='text-base font-semibold text-white'>No students have submitted this form.</p>";
}

$stmt_students->close();
$conn->close();
?>



</div>


<!-- content end -->
</div>
</div>
</div>
</body>

</html>