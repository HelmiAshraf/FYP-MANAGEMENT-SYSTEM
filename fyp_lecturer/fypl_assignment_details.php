<?php
include 'includes/sidebar.php';
// Check if 'ass_id' is set in the URL



if (isset($_GET['ass_id'])) {
    $ass_id = $_GET['ass_id'];

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT a.ass_title, a.ass_date_create, a.ass_date_due, a.ass_description
                FROM assignment a
                WHERE a.ass_id = :ass_id ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ass_id', $ass_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $ass_title = $row['ass_title'];
            $ass_date_create = $row['ass_date_create'];
            $ass_date_due = $row['ass_date_due'];
            $ass_description = $row['ass_description'];
?>

            <div id="editModal" class="modal fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto transform scale-0 opacity-0">
                <div class="modal-overlay bg-black bg-opacity-70 fixed inset-0"></div>
                <div class="bg-white w-96 rounded-lg shadow-lg p-6 transform scale-100 opacity-100 transition-transform duration-300">
                    <h2 class="text-2xl font-bold mb-4">Update Assignment</h2>
                    <form action="function/ass_form_controller.php" method="POST">
                        <!-- Input fields for form data -->
                        <input type="hidden" name="ass_id" value="<?php echo $ass_id; ?>">

                        <div class="mb-4">
                            <label for="new_data" class="block mb-2 text-sm font-medium">Title</label>
                            <input type="text" name="ass_title" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" value="<?php echo $ass_title; ?>" />
                        </div>

                        <div class="mb-4">
                            <label for="ass_description" class="block mb-2 text-sm font-medium">Description</label>
                            <input type="text" name="ass_description" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" value="<?php echo $ass_description; ?>" />
                        </div>
                        <div class="mb-4">
                            <label for="due_date" class="block mb-2 text-sm font-medium">Due Date</label>
                            <input onkeydown="return false" type="datetime-local" type="text" name="ass_date_due" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" value="<?php echo $ass_date_due; ?>" />
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">
                                Update
                            </button>
                        </div>
                    </form>
                    <button id="close-edit-modal-button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
            </div>


            <div class="flex justify-between items-center">
                <div>
                    <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: FYP Course Lecturer</p>
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
                                    <a href="fypl_assignment.php" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
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
                                        Assignment Details
                                    </a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold mb-4">Assignment Details</h1>
                </div>
                <div>
                    <a href="#" class="user_edit text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 focus:outline-none block">
                        Update Assignment
                    </a>
                </div>
            </div>


            <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-gray-800 text-gray-400">
                <ul class="flex text-sm">
                    <li class="mr-2">
                        <p class="text-base font-semibold text-white">
                            Title:
                        </p>
                    </li>
                    <li>
                        <p class="mb-3 text-base text-gray-200">
                            <?php echo $ass_title; ?>
                        </p>
                    </li>
                </ul>
                <ul class="text-sm">
                    <li class="mr-2">
                        <p class="text-base font-semibold text-white">
                            Description:
                        </p>
                    </li>
                    <li>
                        <p class="mb-3 text-base text-gray-200">
                            <?php echo $ass_description; ?>
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
                            <?php echo $ass_date_create; ?>
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
                            <?php echo $ass_date_due; ?>
                        </p>
                    </li>
                </ul>


            </div>
<?php
        } else {
            echo "Assignment not found.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- TODO DELETE SUBMISSION ASSIGNMENT -->
<!-- Display Submitted Tasks -->


<h1 class="text-2xl font-bold mb-4 mt-4">Approved Assignment</h1>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    #
                </th>
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

        $count = 0;
        // Prepare and execute the SQL query
        $sql = "SELECT
            s.st_name AS student_name,
            f.file_path,
            f.file_id,
            f.file_name,
            a_s.ass_submission_id,
            a_s.ass_submissiondate
            FROM
            student AS s
            INNER JOIN
            assignment_submission AS a_s ON s.st_id = a_s.ass_student_id
            INNER JOIN
            file_path AS f ON a_s.ass_submission_id = f.type_id
            WHERE
            a_s.ass_id = ? AND a_s.ass_status = 1 AND f.file_type = 'as' ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $ass_id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $count++;
        ?>
                    <tbody>
                        <?php

                        $original_file_name = substr($row['file_name'], strpos($row['file_name'], '_', strpos($row['file_name'], '_', strpos($row['file_name'], '_') + 1) + 1) + 1); //potong 3

                        // Remove file extension
                        $original_file_name_without_extension = pathinfo($original_file_name, PATHINFO_FILENAME);

                        ?>
                        <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td class='px-6 py-4 '>
                                <?php echo $count; ?>
                            </td>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-black'>
                                <button class='text-blue-500 hover:underline hover:text-blue-400' onclick="openFileInNewTab('<?php echo $row['file_name']; ?>', '<?php echo htmlspecialchars($original_file_name_without_extension); ?>')">
                                    <?php echo htmlspecialchars($original_file_name); ?>
                                </button>

                                <script>
                                    function openFileInNewTab(file_name, file_name_without_extension) {
                                        // Construct the file URL
                                        var fileUrl = 'view_pdf.php//?url=' + encodeURIComponent('../file/assignment/' + file_name) + '&name=' + encodeURIComponent(file_name_without_extension);

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
                            <td class='px-6 py-4 '>
                                <?php echo $row['student_name']; ?>
                            </td>
                            <td class='px-6 py-4 '>
                                <?php
                                $submission_date = date("j M Y | H:i", strtotime($row['ass_submissiondate']));
                                echo $submission_date;
                                ?>
                            </td>
                            <td class='px-6 py-4 text-center' onclick=' return confirm("Are you sure you want to delete?")'>
                                <a href='function/ass_submission_delete.php?ass_submission_id=<?php echo $row["ass_submission_id"]; ?>&ass_id=<?php echo $ass_id; ?>' class='font-medium text-blue-500 hover:text-blue-600'>
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
                echo "<td colspan='5' class='px-6 py-4 text-center'>No assignment found</td>";
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


<!-- JavaScript code to handle the modal and update -->
<script>
    const editButtons = document.querySelectorAll(".user_edit");
    const editModal = document.getElementById("editModal");
    const closeEditModalButton = document.getElementById("close-edit-modal-button");

    editButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            event.preventDefault();

            // Show the modal
            editModal.style.transform = "scale(1)";
            editModal.style.opacity = "1";
        });
    });

    // Close the edit modal
    closeEditModalButton.addEventListener("click", function() {
        editModal.style.transform = "scale(0)";
        editModal.style.opacity = "0";
    });
</script>

<!-- content end -->
</div>
</div>
</div>
</body>

</html>