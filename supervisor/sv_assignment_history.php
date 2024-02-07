<?php

include 'includes/sv_sidebar.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["tag"])) {
    $tag = $_GET["tag"];

    // Define your database credentials
    include '../db_credentials.php';

    try {
        // Create a PDO instance
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);

        // Set PDO to throw exceptions on errors
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($tag == 3) {
            // Assuming your form has input fields with names "ass_submission_id" and "ass_status"
            if (isset($_GET["ass_submission_id"]) && isset($_GET["ass_status"])) {
                $ass_submission_id = $_GET["ass_submission_id"];
                $ass_status = $_GET["ass_status"];

                // Update the assignment_submission table
                $sql = "UPDATE assignment_submission SET ass_status = :ass_status WHERE ass_submission_id = :ass_submission_id";
                $stmt = $conn->prepare($sql);

                // Bind parameters
                $stmt->bindParam(':ass_status', $ass_status, PDO::PARAM_INT);
                $stmt->bindParam(':ass_submission_id', $ass_submission_id, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    if ($stmt->rowCount() > 0) {
                        echo "Assignment status updated successfully.";
                        echo '<script>';
                        echo 'window.location.href = "sv_assignment.php";';
                        echo '</script>';
                    } else {
                        echo "No changes made. Assignment status might already be the same.";
                    }
                } else {
                    echo "Failed to update assignment status.";
                }
            } else {
                echo "Missing required parameters.";
            }
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        die();
    } finally {
        // Close the connection
        $conn = null;
    }
}




function getBatchIdByCategory($batchCategory, $conn)
{
    $sql = "SELECT batch_id FROM batches WHERE batch_category = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("s", $batchCategory);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        return $row['batch_id'];
    }

    return null;
}

// Assuming you have established a database connection ($conn)
$supervisor_id = $_SESSION["user_id"];

// Get the default batch_id for CSP600
$defaultBatchId = getBatchIdByCategory('CSP600', $conn);

// If no batch_category is selected, set the default to CSP600 batch_id
$selected_option = isset($_GET['batch_category']) ? $_GET['batch_category'] : $defaultBatchId;

// SQL query to retrieve students supervised by the specified supervisor
$sql = "SELECT
s.st_name,
a_s.ass_submission_id,
a_s.ass_submissiondate,
a_s.ass_status,
a.ass_title,
a.ass_date_due,
a.batch_id,
f.file_id,
f.file_type,
f.file_name,
f.file_path
FROM
student AS s
JOIN
supervise AS su ON s.st_id = su.student_id
JOIN
assignment_submission AS a_s ON s.st_id = a_s.ass_student_id
JOIN
assignment AS a ON a_s.ass_id = a.ass_id
JOIN
file_path AS f ON a_s.ass_submission_id = f.type_id
WHERE
su.supervisor_id = ?
AND a.batch_id = ?
AND a_s.ass_status IN (1, 0, 3)
AND f.file_type = 'as';
";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ii", $supervisor_id, $selected_option);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Error preparing the statement: " . $conn->error);
}

?>

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
                        <a href="sv_assignment.php" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                            Assignment
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                            Assignment History
                        </a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>
<div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

<h1 class="text-4xl font-bold mb-4">Student Assignment History</h1>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
    <div class="p-4 bg-gray-900 flex justify-between">
        <div class="bg-gray-900 flex items-center">
            <label for="batchForm" class="block text-xl font-sm text-gray-300 mr-4">Course:</label>
            <form id="batchForm" method="GET">
                <select name="batch_category" id="countries" class="text-sm rounded-md block p-2 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" onchange="submitForm()">
                    <?php
                    // Fetch batch_id based on batch_category
                    $csp600BatchId = getBatchIdByCategory('CSP600', $conn);
                    $csp650BatchId = getBatchIdByCategory('CSP650', $conn);
                    ?>
                    <option value="<?php echo $csp600BatchId; ?>" <?php echo ($selected_option == $csp600BatchId) ? 'selected' : ''; ?>>CSP600</option>
                    <option value="<?php echo $csp650BatchId; ?>" <?php echo ($selected_option == $csp650BatchId) ? 'selected' : ''; ?>>CSP650</option>
                </select>
            </form>
        </div>
        <script>
            function submitForm() {
                document.getElementById("batchForm").submit();
            }
        </script>
        <!-- //TODO BUTTON VIEW ASSIGNEMTN HISTORY -->
    </div>

    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
            <tr>
                <th scope="col" class="w-2/7 px-6 py-3">
                    Student Name
                </th>
                <th scope="col" class="w-2/7 px-6 py-3">
                    Assignment Name
                </th>
                <th scope="col" class="w-2/7 px-6 py-3">
                    View PDF
                </th>
                <th scope="col" class="w-2/7 px-6 py-3 text-center">
                    Due Date
                </th>
                <th scope="col" class="w-1/7 px-6 py-3 text-center">
                    Status
                </th>
            </tr>
        </thead>
        <tbody>
            <?php

            // Check if there are rows in the result set
            if ($result->num_rows > 0) {
                // Fetch and display data
                while ($row = $result->fetch_assoc()) {
                    // Format task_date_create

                    $original_file_name = substr($row['file_name'], strpos($row['file_name'], '_', strpos($row['file_name'], '_', strpos($row['file_name'], '_') + 1) + 1) + 1); //potong 3

                    // Remove file extension
                    $original_file_name_without_extension = pathinfo($original_file_name, PATHINFO_FILENAME);

                ?>
                    <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                        <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>
                            <?php echo $row['st_name']; ?>
                        </td>
                        <td class='px-6 py-4'><?php echo $row['ass_title']; ?> </td>
                        <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-black'>
                                <button class='text-blue-500 hover:underline hover:text-blue-400' onclick="openFileInNewTab('<?php echo $row['file_name']; ?>', '<?php echo htmlspecialchars($original_file_name_without_extension); ?>')">
                                    <?php echo htmlspecialchars($original_file_name); ?>
                                </button>

                                <script>
                                    function openFileInNewTab(file_name, file_name_without_extension) {
                                        // Construct the file URL
                                        var fileUrl = '../fyp_lecturer/view_pdf.php//?url=' + encodeURIComponent('../file/assignment/' + file_name) + '&name=' + encodeURIComponent(file_name_without_extension);

                                        // Open the file link in a new tab
                                        var newTab = window.open(fileUrl, '_blank');

                                        // Set the title after a short delay
                                        if (newTab) {
                                            setTimeout(function() {
                                                newTab.document.title = file_name_without_extension;
                                            }, 1000); // Adjust the delay as needed
                                        }
                                    }
                                </script>
                            </td>
                        <td class='px-6 py-4 text-center'><?php echo $row['ass_date_due']; ?> </td>
                        <td class='px-6 py-4 text-center'>
                            <?php
                            echo ($row['ass_status'] == 1) ? 'Approve' : (($row['ass_status'] == 0) ? 'Reject' : 'FYP Lecturer Delete');
                            ?>
                        </td>

                    </tr>
            <?php
                }
            } else {
                // No tasks found
                echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                echo "<td colspan='6' class='px-6 py-4 text-center'>No assignment history found</td>";
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