<?php
include 'includes/st_sidebar.php';

// Assuming you have established a database connection ($conn) and have the user_id of the logged-in student
$student_id = $_SESSION['user_id']; // Replace with your authentication mechanism


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
                        <a href="st_assignment.php" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                            Assignment
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="st_assignment_details.php" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                            Assignment Details
                        </a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>
<div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

<!!-- insert modal form -->
    <div id="ass-modal" class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto transform scale-0 opacity-0 ">
        <div class="modal-overlay bg-black bg-opacity-70 fixed inset-0"></div>
        <div class="bg-white w-96 rounded-lg shadow-lg p-6 transform scale-100 opacity-100 transition-transform duration-300">
            <h2 class="text-2xl font-bold mb-4">assignment</h2>
            <form action="st_assignment_details.php" method="POST" enctype="multipart/form-data">
                <div class="mb-6">
                    <label for="ass_file" class="block mb-2 text-sm font-medium text-gray-900">
                        Upload assignment File
                    </label>
                    <input name="ass_file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none " aria-describedby="file_input_help" id="file_input" type="file">
                    <p class="mt-1 text-xs text-gray-500" id="file_input_help">PDF and Word files only (MAX. 15MB).</p>
                </div>
                <div class="flex justify-end">
                    <input type="hidden" name="tag" value="1">
                    <!-- Add an input field for ass_id -->
                    <input type="hidden" name="ass_id" value="<?php echo $_GET['ass_id']; ?>">
                    <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover-bg-blue-700 focus-ring-blue-800">
                        Upload
                    </button>
                </div>
            </form>

            <button id="close-modal-button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 class="text-3xl font-bold mb-4">Assignment Details</h1>
        </div>
    </div>

    <?php
    if (isset($_GET['ass_id'])) {
        $ass_id = $_GET['ass_id'];

        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT a.ass_title, a.ass_description, a.ass_date_due
                FROM assignment a
                WHERE a.ass_id = :ass_id";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':ass_id', $ass_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $ass_title = $row['ass_title'];
                $ass_description = $row['ass_description'];
                $ass_date_due = $row['ass_date_due'];
    ?>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-gray-800 text-gray-400">
                    <ul class="flex text-sm">
                        <li class="mr-2">
                            <p class="text-base font-semibold text-white">
                                Assignment:
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
                                Due Date:
                            </p>
                        </li>
                        <li>
                            <p class="mb-3 text-base text-gray-200">
                                <?php echo $ass_date_due; ?>
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

    <?php
    // Assuming $ass_id and $user_id are defined before this point

    // Display the submit form
    ?>



    <h1 class="text-3xl font-bold mb-4 mt-4">Submit Assignment</h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-gray-800">
        <form action="function/function_assignment.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <!-- Add an input field for ass_id -->
            <input type="hidden" name="ass_id" value="<?php echo $ass_id; ?>">
            <input type="hidden" name="student_id" value="<?php echo $_SESSION['user_id']; ?>">
            <div class="mb-6">
                <label for="fileInput" class="block mb-2 text-sm font-medium text-white">Upload File</label>
                <input require type="file" name="files" id="fileInput" onchange="displaySelectedFile(this)" class="block w-full text-sm border rounded-lg cursor-pointer text-gray-400 focus:outline-none bg-gray-700 border-gray-600 placeholder-gray-400" aria-describedby="user_avatar_help">
                <p class="mt-1 text-sm text-gray-300" id="file_input_help">Submit your assignment as a PDF file</p>

                <!-- Display selected files -->
                <div id="selected-files" class="mt-2"></div>
            </div>
            <!-- Add an input field for tag -->
            <input type="hidden" name="tag" value="1">
            <div>
                <!-- Use a button for ass submission -->
                <button type="submit" class="text-white font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700">Submit
                    Assignment</button>
            </div>
        </form>
    </div>


    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-4 mt-4">Submitted Assignments</h1>
        </div>
    </div>

    <?php
    // Prepare and execute the SQL query to retrieve submission files
    $sql = "SELECT ds.ass_submission_id, ds.ass_submissiondate, ds.ass_status, f.file_name
        FROM assignment_submission ds
        LEFT JOIN file_path f ON ds.ass_submission_id = f.type_id
        WHERE ds.ass_id = ? AND ds.ass_student_id = ? AND f.file_type = 'as' ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $ass_id, $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();


        // Display the list of submitted assignments
    ?>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-gray-800">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            File Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date Submit
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

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
                                            var fileUrl = '../fyp_lecturer/view_pdf.php//?url=' + encodeURIComponent('../file/assignment/<?php echo $row['file_name']; ?>') + '&name=' + encodeURIComponent('<?php echo htmlspecialchars($original_file_name_without_extension); ?>');

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
                                <td class='px-6 py-4 '>
                                    <?php echo $row['ass_submissiondate']; ?>
                                </td>
                                <td class='px-6 py-4 font-extrabold'>
                                    <?php
                                    $status = $row['ass_status'];
                                    $statusText = '';

                                    switch ($status) {
                                        case 0:
                                            $statusText = 'Reject';
                                            $colorClass = 'text-red-500';
                                            break;
                                        case 1:
                                            $statusText = 'Approve';
                                            $colorClass = 'text-green-500';
                                            break;
                                        case 2:
                                            $statusText = 'Pending';
                                            $colorClass = 'text-yellow-500';
                                            break;
                                        default:
                                            $statusText = 'FYP Lecturer Delete';
                                            $colorClass = 'text-yellow-500';
                                    }
                                    ?>
                                    <span class="inline-block px-2 py-1 rounded-full <?php echo $colorClass; ?>">
                                        <?php echo $statusText; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                </tbody>

        <?php
                    } else {
                        // Show a message indicating "No Submission found"
                        echo "<tbody>";
                        echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                        echo "<td colspan='5' class='px-6 py-4 text-center'>No Submission found</td>";
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






        <!-- content end -->
        </div>
        </div>
        </div>


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
                        // You can uncomment and add more code here if needed
                        // var fileName = file.name;
                        // var fileSize = (file.size / 1024).toFixed(2) + " KB";
                        // var fileInfo = document.createElement("p");
                        // fileInfo.textContent = " " + fileName + " (" + fileSize + ")";
                        // displayDiv.appendChild(fileInfo);
                    } else {
                        // Display an error message for an invalid file type
                        var errorInfo = document.createElement("p");
                        errorInfo.textContent = "Invalid file type. Allowed types are .pdf";
                        errorInfo.style.color = "red";
                        displayDiv.appendChild(errorInfo);

                        // Clear the file input
                        fileInput.value = "";
                    }
                } else {
                    // Display an error message if no file is selected
                    var errorInfo = document.createElement("p");
                    errorInfo.textContent = "Please select a file.";
                    errorInfo.style.color = "red";
                    displayDiv.appendChild(errorInfo);

                    // Clear the file input
                    fileInput.value = "";
                }
            }

            // Additional function to prevent form submission without a file
            function validateForm() {
                var fileInput = document.getElementById("fileInput");

                if (fileInput.files.length === 0) {
                    alert("Please select a file before submitting the assignment.");
                    return false; // Prevent form submission
                } else {
                    var confirmation = confirm("Are you sure you want to submit this file? You can't unsubmit after submission.");
                    return confirmation; // If 'OK' is clicked, allow form submission; otherwise, prevent it
                }
            }
        </script>



        </body>

        </html>