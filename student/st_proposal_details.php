<?php
include 'includes/st_sidebar.php';

$proposal_title = $_GET['proposal_title'];

?>


<h1 class="text-4xl font-bold mb-4"><?php echo $proposal_title; ?></h1>

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
            <input type="text" id="table-search" class="block p-2 pl-10 text-sm border rounded-lg w-80 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Search for items">
        </div>
        <div>
            <button id="open-modal-button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">
                Upload Proposal
            </button>
        </div>
    </div>


    <!!-- insert form -->

        <div id="proposal-modal" class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto transform scale-0 opacity-0 ">
            <div class="modal-overlay bg-black bg-opacity-70 fixed inset-0"></div>
            <div class="bg-white w-96 rounded-lg shadow-lg p-6 transform scale-100 opacity-100 transition-transform duration-300">
                <h2 class="text-2xl font-bold mb-4">Proposal</h2>
                <form action="st_proposal_details.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-6">
                        <label for="proposal_file" class="block mb-2 text-sm font-medium text-gray-900">
                            Upload Proposal File
                        </label>
                        <input name="proposal_file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none " aria-describedby="file_input_help" id="file_input" type="file">
                        <p class="mt-1 text-xs text-gray-500" id="file_input_help">PDF and Word files only (MAX. 15MB).</p>
                    </div>
                    <div class="flex justify-end">
                        <input type="hidden" name="tag" value="1">
                        <!-- Add an input field for proposal_id -->
                        <input type="hidden" name="proposal_id" value="<?php echo $_GET['proposal_id']; ?>">
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

        <?php
        // Assuming you have established a database connection ($conn) and have the user_id of the logged-in student
        $student_id = $_SESSION['user_id']; // Replace with your authentication mechanism


        if (isset($_REQUEST["tag"])) {
            $tag = $_REQUEST["tag"];
            if ($tag == 1) {
                // Insert operation
                // Check if the form is submitted
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Check if proposal_id is valid and not null
                    $proposal_id = $_POST['proposal_id']; // Get proposal_id from URL parameter
                    if (!empty($proposal_id)) {
                        // Check if a submission with the same proposal_id and student_id already exists
                        $check_submission_sql = "SELECT proposal_submission_id FROM proposal_submission WHERE proposal_id = ? AND student_id = ?";
                        $stmt_check_submission = $conn->prepare($check_submission_sql);
                        $stmt_check_submission->bind_param("ii", $proposal_id, $student_id);
                        $stmt_check_submission->execute();
                        $stmt_check_submission->store_result();

                        if ($stmt_check_submission->num_rows > 0) {
                            echo '<script>alert("You have already submitted a proposal for this project.");</script>';
                        } else {
                            $proposal_id = $_POST['proposal_id']; // Get proposal_id from URL parameter
                            if (!empty($proposal_id)) {
                                // Get the supervisor_id from the 'supervise' table
                                $sql_supervisor = "SELECT supervisor_id FROM supervise WHERE student_id = ?";
                                $stmt_supervisor = $conn->prepare($sql_supervisor);
                                $stmt_supervisor->bind_param("i", $student_id);

                                if ($stmt_supervisor->execute()) {
                                    $stmt_supervisor->bind_result($supervisor_id);
                                    if ($stmt_supervisor->fetch()) {
                                        // Supervisor ID found, now close the first statement
                                        $stmt_supervisor->close();

                                        // Insert data into 'proposal_submission' table
                                        $submission_date = date("Y-m-d H:i:s"); // Current timestamp

                                        $sql_submission = "INSERT INTO proposal_submission (proposal_id, student_id, supervisor_id, submissiondate) VALUES (?, ?, ?, ?)";
                                        $stmt_submission = $conn->prepare($sql_submission);
                                        $stmt_submission->bind_param("iiis", $proposal_id, $student_id, $supervisor_id, $submission_date);

                                        if ($stmt_submission->execute()) {
                                            // Get the proposal_submission_id of the inserted record
                                            $proposal_submission_id = $stmt_submission->insert_id;

                                            // Handle file upload and insertion into 'file' table
                                            if ($_FILES['proposal_file']['error'] === UPLOAD_ERR_OK) {
                                                $file_name = $_FILES['proposal_file']['name'];
                                                $file_content = file_get_contents($_FILES['proposal_file']['tmp_name']);
                                                $file_type_id = $proposal_submission_id; // You can use proposal_submission_id as the file_type_id
                                                $file_type = "proposal submission"; // Assuming the type is 'proposal'
                                                $file_uploader_id = $student_id; // User's ID

                                                $sql_file = "INSERT INTO file (file_name, file_content, file_type_id, file_type, file_uploader_id) VALUES (?, ?, ?, ?, ?)";
                                                $stmt_file = $conn->prepare($sql_file);
                                                $stmt_file->bind_param("ssiss", $file_name, $file_content, $file_type_id, $file_type, $file_uploader_id);

                                                if ($stmt_file->execute()) {
                                                    echo "File uploaded successfully!";
                                                } else {
                                                    echo "Error uploading file: " . $stmt_file->error;
                                                }
                                            } else {
                                                echo "File upload error: " . $_FILES['proposal_file']['error'];
                                            }
                                        } else {
                                            echo "Error inserting into proposal_submission: " . $stmt_submission->error;
                                        }
                                    } else {
                                        echo "Supervisor not found for this student.";
                                    }
                                } else {
                                    echo "Error fetching supervisor: " . $stmt_supervisor->error;
                                }
                            } else {
                                echo "Invalid or missing proposal_id.";
                            }
                        }
                    }
                }
            }
        } 


        // Rest of your HTML and form
        ?>




        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="w-3/6 px-6 py-3">
                        Proposal Name
                    </th>
                    <th scope="col" class="w-2/6 px-6 py-3">
                        Owner
                    </th>
                    <th scope="col" class="w-1/6 px-6 py-3">
                        Date Submit
                    </th>
                </tr>
            </thead>
            <?php
            // Assuming you have established a database connection ($conn)
            $proposal_id = $_GET['proposal_id']; // Get proposal_id from URL parameter

            // Prepare and execute the SQL query
            $sql = "SELECT
        p.proposal_title AS proposal_title,
        f.file_name AS file_name,
        s.st_name AS student_name,
        ps.submissiondate AS submission_date
    FROM
        proposal p
    INNER JOIN
        proposal_submission ps ON p.proposal_id = ps.proposal_id
    INNER JOIN
        file f ON ps.proposal_submission_id = f.file_type_id
    INNER JOIN
        student s ON f.file_uploader_id = s.st_id
    WHERE
        p.proposal_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $proposal_id);

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
            ?>
                        <tbody>
                            <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                                <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>
                                    <a href='st_proposal_details.php?proposal_id=<?php echo $row["proposal_id"]; ?>' class='font-medium text-blue-500 hover:underline hover:text-blue-400'><?php echo $row['file_name']; ?></a>
                                </td>
                                <td class='px-6 py-4 '><?php echo $row['student_name']; ?></td>
                                <td class='px-6 py-4 '><?php echo $row['submission_date']; ?></td>
                            </tr>
                        </tbody>
            <?php
                    }
                } else {
                    echo "<tbody>";
                    echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                    echo "<td colspan='4' class='px-6 py-4 text-center'>No proposal found</td>";
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
    const openModalButton = document.getElementById("open-modal-button");
    const closeModalButton = document.getElementById("close-modal-button");
    const proposalModal = document.getElementById("proposal-modal");

    openModalButton.addEventListener("click", () => {
        proposalModal.classList.remove("scale-0", "opacity-0");
        proposalModal.classList.add("scale-100", "opacity-100");
    });

    closeModalButton.addEventListener("click", () => {
        proposalModal.classList.remove("scale-100", "opacity-100");
        proposalModal.classList.add("scale-0", "opacity-0");
    });
</script>

</body>

</html>