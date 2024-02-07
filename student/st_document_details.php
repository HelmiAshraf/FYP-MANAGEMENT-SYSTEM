<?php
include 'includes/st_sidebar.php';
// Check if 'doc_id' is set in the URL
if (isset($_GET['doc_id'])) {
    // Get the doc_id from the URL
    $doc_id = $_GET['doc_id'];

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Assuming you have a 'document' table with columns 'doc_id', 'doc_title', 'doc_date_create', 'doc_date_due'
        $sql = "SELECT d.doc_title, d.doc_date_create, d.doc_date_due, d.doc_description
                FROM document d
                WHERE d.doc_id = :doc_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':doc_id', $doc_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $doc_title = $row['doc_title'];
            $doc_date_create = $row['doc_date_create'];
            $doc_date_due = $row['doc_date_due'];
            $doc_description = $row['doc_description'];

?>


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
                                    <a href="st_document.php" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                                        Document
                                    </a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                    <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                                        Document Details
                                    </a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

            <!-- Print document details -->
            <h1 class="text-3xl font-bold mb-4">Document Details</h1>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-gray-800 text-gray-400">
                <ul class="flex text-sm">
                    <li class="mr-2">
                        <p class="text-base font-semibold text-white">
                            Document:
                        </p>
                    </li>
                    <li>
                        <p class="mb-3 text-base text-gray-200">
                            <?php echo $doc_title; ?>
                        </p>
                    </li>
                </ul>
                <ul class="text-sm">
                    <li class="mr-2">
                        <p class="text-base font-semibold text-white">
                            Document Description:
                        </p>
                    </li>
                    <li>
                        <p class="mb-3 text-base text-gray-200">
                            <?php echo $doc_description; ?>
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
                            <?php echo $doc_date_create; ?>
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
                            <?php echo $doc_date_due; ?>
                        </p>
                    </li>
                </ul>
                <?php
                // Retrieve associated files for this task
                $sql_files = "SELECT f.file_name, f.file_id
                FROM file_path f
                WHERE f.type_id = :doc_id AND f.file_type = 'dfypl'";

                $stmt_files = $pdo->prepare($sql_files);
                $stmt_files->bindParam(':doc_id', $doc_id, PDO::PARAM_INT);
                $stmt_files->execute();
                $result_files = $stmt_files->fetchAll(PDO::FETCH_ASSOC);

                if (count($result_files) > 0) {
                ?>
                    <ul class="text-sm">
                        <li class="mr-2">
                            <p class="text-base font-semibold text-white">Document Files:</p>
                        </li>
                        <li>
                            <ul>
                                <?php foreach ($result_files as $row_files) { ?>
                                    <li class="mb-3 text-base text-gray-200">
                                        <?php
                                        $original_file_name_s = substr($row_files['file_name'], strpos($row_files['file_name'], '_', strpos($row_files['file_name'], '_') + 1) + 1);

                                        // Remove file extension
                                        $original_file_name_s_without_extension = pathinfo($original_file_name_s, PATHINFO_FILENAME);
                                        ?>

                                        <button class='text-blue-500 hover:underline hover:text-blue-400' onclick="openFileInNewTabLecturer()">
                                            <?php echo $original_file_name_s; ?>
                                        </button>

                                        <script>
                                            function openFileInNewTabLecturer() {
                                                // Construct the file URL
                                                var fileUrl = '../fyp_lecturer/view_pdf.php//?url=' + encodeURIComponent('../file/document/<?php echo $row_files['file_name']; ?>') + '&name=' + encodeURIComponent('<?php echo $original_file_name_s_without_extension; ?>');

                                                // Open the file link in a new tab
                                                var newTab = window.open(fileUrl, '_blank');

                                                // Set the title after a short delay
                                                if (newTab) {
                                                    setTimeout(function() {
                                                        newTab.document.title = <?php echo json_encode($original_file_name_s_without_extension, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
                                                    }, 1000); // Adjust the delay as needed
                                                }
                                            }
                                        </script>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                <?php
                } else {
                    echo "<p class='text-base font-semibold text-white'>No files associated with this task.</p>";
                }
                ?>
            </div>
<?php
        } else {
            echo "Document not found.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<?php
// Prepare and execute the SQL query to check if there are any doc submissions for the user
$submission_sql = "SELECT COUNT(*) AS submission_count FROM document_submission WHERE doc_id = ? AND doc_student_id = ?";

$submission_stmt = $conn->prepare($submission_sql);
$submission_stmt->bind_param("ii", $doc_id, $user_id); // Assuming $user_id holds the user's ID and $doc_id holds the doc ID

if ($submission_stmt->execute()) {
    $submission_result = $submission_stmt->get_result();
    $row = $submission_result->fetch_assoc();
    $submission_count = $row['submission_count'];

    if ($submission_count > 0) {
        // If there are doc submissions, display the table
?>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-4 mt-4">Submited Document</h1>
            </div>
            <div>
                <?php
                // print submit task
                // Prepare and execute the SQL query to retrieve submission files
                $sql = "SELECT ds.doc_submission_id, ds.doc_submissiondate, f.file_name
                FROM document_submission ds
                LEFT JOIN file_path f ON ds.doc_submission_id = f.type_id
                WHERE ds.doc_id = ? AND ds.doc_student_id = ? AND f.file_type = 'ds'";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $doc_id, $user_id); // Assuming $doc_id holds the doc ID and $user_id holds the user's ID

                if ($stmt->execute()) {
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {


                ?>
                            <form action="function/function_document.php" method="post" onsubmit="return confirm('Are you sure you want to unsubmit this document? Your file will disapear');">
                                <input type="hidden" name="tag" value="2">
                                <input type="hidden" name="doc_submission_id" value="<?php echo $row["doc_submission_id"]; ?>">
                                <input type="hidden" name="doc_id" value="<?php echo $doc_id; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">Unsubmit Document</button>
                            </form>


            </div>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-gray-800">
            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-2/3">
                            File Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date Submit
                        </th>
                    </tr>
                </thead>
                <tbody>

                <tbody>
                    <?php
                            $original_file_name = substr($row['file_name'], strpos($row['file_name'], '_', strpos($row['file_name'], '_', strpos($row['file_name'], '_') + 1) + 1) + 1); //potong 3

                            // Remove file extension
                            $original_file_name_without_extension = pathinfo($original_file_name, PATHINFO_FILENAME);

                    ?>
                    <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                        <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-black'>
                            <button class='text-blue-500 hover:underline hover:text-blue-400' onclick="openFileInNewTab()">
                                <?php echo $original_file_name; ?>
                            </button>

                            <script>
                                function openFileInNewTab() {
                                    // Construct the file URL
                                    var fileUrl = '../fyp_lecturer/view_pdf.php//?url=' + encodeURIComponent('../file/document/<?php echo $row['file_name']; ?>') + '&name=' + encodeURIComponent('<?php echo htmlspecialchars($original_file_name_without_extension); ?>');

                                    // Open the file link in a new tab
                                    var newTab = window.open(fileUrl, '_blank');

                                    // Set the title after a short delay
                                    if (newTab) {
                                        setTimeout(function() {
                                            newTab.document.title = <?php echo json_encode($original_file_name_without_extension, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
                                        }, 1000); // Adjust the delay as needed
                                    }
                                }
                            </script>
                        </td>
                        <td class='px-6 py-4 '><?php echo $row['doc_submissiondate']; ?></td>
                    </tr>
                </tbody>
    <?php
                        }
                    } else {
                        echo "<tbody>";
                        echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                        echo "<td colspan='4' class='px-6 py-4 text-center'>No submissions found</td>";
                        echo "</tr>";
                        echo "</tbody>";
                    }
                } else {
                    echo "Error executing the query: " . $stmt->error;
                }

                $stmt->close();

    ?>

    </tbody>
            </table>
        </div>
    <?php
    } else {
    ?>

        <!-- Submit doc -->

        <h1 class="text-3xl font-bold mb-4 mt-4">Submit Document</h1>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-gray-800">
            <form action="function/function_document.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                <!-- Add an input field for doc_id -->
                <input type="hidden" name="doc_id" value="<?php echo $doc_id; ?>">
                <input type="hidden" name="student_id" value="<?php echo $_SESSION['user_id']; ?>">
                <div class="mb-6">
                    <label for="fileInput" class="block mb-2 text-sm font-medium text-white">Upload File</label>
                    <input type="file" name="files" id="fileInput" onchange="displaySelectedFile(this)" class="block w-full text-sm border rounded-lg cursor-pointer text-gray-400 focus:outline-none bg-gray-700 border-gray-600 placeholder-gray-400" aria-describedby="user_avatar_help">
                    <p class="mt-1 text-sm text-gray-300" id="file_input_help">Submit your Document as a PDF file</p>
                    <!-- Display selected files -->
                    <div id="selected-files" class="mt-2"></div>
                </div>
                <!-- Add an input field for tag -->
                <input type="hidden" name="tag" value="1">
                <div>
                    <!-- Use a button for doc submission -->
                    <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus-ring-blue-800">Submit document</button>
                </div>
            </form>
        </div>
<?php
    }
} else {
    echo "Error executing the query: " . $submission_stmt->error;
}

$submission_stmt->close();
mysqli_close($conn);
?>


<script>
    function displaySelectedFile(fileInput) {
        var displayDiv = document.getElementById("selected-files");
        displayDiv.innerHTML = ""; // Clear previous selection

        var file = fileInput.files[0];

        if (file) {
            // Add the allowed file types
            var allowedTypes = [".pdf"];
            var fileType = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();

            if (allowedTypes.indexOf(fileType) !== -1) {
                // var fileName = file.name;
                // var fileSize = (file.size / 1024).toFixed(2) + " KB"; // Display file size in KB

                // var fileInfo = document.createElement("p");
                // fileInfo.textContent = " " + fileName + " (" + fileSize + ")";
                // displayDiv.appendChild(fileInfo);

                // // Optionally, you can store the selected file in a hidden input field
                // var hiddenInput = document.createElement("input");
                // hiddenInput.type = "hidden";
                // hiddenInput.name = "selected_file";
                // hiddenInput.value = fileName;
                // displayDiv.appendChild(hiddenInput);
            } else {
                // Display an error message for an invalid file type
                var errorInfo = document.createElement("p");
                errorInfo.textContent = "Invalid file type. Allowed types are .pdf";
                errorInfo.style.color = "red";
                displayDiv.appendChild(errorInfo);

                // Clear the file input
                fileInput.value = "";
            }
        }
    }

    function validateForm() {
        var fileInput = document.getElementById("fileInput");

        if (fileInput.files.length === 0) {
            alert("Please select a file before submitting the document.");
            return false; // Prevent form submission
        }

        // You can add more validation checks here if needed

        return true; // Allow form submission
    }
</script>


<!-- content end -->
</div>
</div>
</div>
</body>

</html>