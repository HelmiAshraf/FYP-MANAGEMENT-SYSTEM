<?php
// Check if 'form_id' is set in the URL
if (isset($_GET['form_id'])) {
    // Get the form_id from the URL
    $form_id = $_GET['form_id'];

    include 'includes/st_sidebar.php';

    // Assuming you have a 'form' table with columns 'form_id', 'Form_title', 'form_date_create', 'form_date_due'
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

            <!-- print form detail -->

            <h1 class="text-3xl font-bold mb-4">Form Details</h1>
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
                <ul class="text-sm">
                    <li class="mr-2">
                        <p class="text-base font-semibold text-white">
                            Form Files:
                        </p>
                    </li>
                    <?php
                    // Now, fetch and display related files from the 'file' table
                    $sql = "SELECT file_name, file_content, type_id, file_type, file_uploader_id, file_id
    FROM file
    WHERE type_id = ?";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $form_id);

                    if ($stmt->execute()) {
                        $result = $stmt->get_result();

                        while ($file_row = $result->fetch_assoc()) {
                            $file_name = $file_row['file_name'];
                            $file_content = $file_row['file_content'];
                            $file_id = $file_row['file_id'];

                            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                            if ($file_extension === 'pdf') {
                    ?>
                                <li>
                                    <a href="#" class="mb-3 text-base text-gray-200 view-file-link" data-file-extension="<?php echo $file_extension; ?>" data-file-content="<?php echo base64_encode($file_content); ?>" data-file-name="<?php echo $file_name; ?>">
                                        <?php echo $file_name; ?>
                                    </a>
                                </li>
                            <?php
                            } elseif ($file_extension === 'docx') {
                            ?>
                                <li>
                                    <a href="function/download.php?file_id=<?php echo $file_id; ?>" download="<?php echo $file_name; ?>" class="mb-3 text-base text-gray-200">
                                        <?php echo $file_name; ?>
                                    </a>
                                </li>
                            <?php
                            } else {
                                // For other file types, provide links to download
                            ?>
                                <li>
                                    <a href="function/download.php?file_id=<?php echo $file_id; ?>" target="_blank" class="mb-3 text-base text-gray-200">
                                        <?php echo $file_name; ?>
                                    </a>
                                </li>
        <?php
                            }
                        }
                    }
                } else {
                    // Handle the case where no form with the specified form_id is found
                    echo "Form not found.";
                }
            } else {
                echo "Error fetching form: " . $stmt->error;
            }
        } else {
            // Handle the case where 'form_id' is not set in the URL
            echo "Form ID not provided.";
        }
        ?>
                </ul>
            </div>

            <!-- JavaScript to handle the file popup -->
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const viewFileLinks = document.querySelectorAll(".view-file-link");
                    viewFileLinks.forEach(function(link) {
                        link.addEventListener("click", function(event) {
                            event.preventDefault();
                            const fileExtension = this.getAttribute("data-file-extension");
                            const fileContent = this.getAttribute("data-file-content");
                            const fileName = this.getAttribute("data-file-name");

                            if (fileExtension === 'pdf') {
                                // Open the PDF in a new tab within the same window
                                const pdfWindow = window.open("", "_blank");
                                pdfWindow.document.open();
                                pdfWindow.document.write(`<iframe src="data:application/pdf;base64,${fileContent}" width="100%" height="100%"></iframe`);
                                pdfWindow.document.close();
                            } else if (fileExtension === 'docx') {
                                // Open the DOCX using Google Docs Viewer in a new tab within the same window
                                const encodedFileContent = encodeURIComponent("data:application/vnd.openxmlformats-officedocument.wordprocessingml.document;base64," + fileContent);
                                const viewerUrl = `https://docs.google.com/gview?url=${encodedFileContent}&embedded=true`;
                                const docxWindow = window.open(viewerUrl, "_blank");
                            }
                        });
                    });
                });
            </script>


            <?php
            // Prepare and execute the SQL query to check if there are any form submissions for the user
            $submission_sql = "SELECT COUNT(*) AS submission_count FROM form_submission WHERE form_id = ? AND student_id = ?";

            $submission_stmt = $conn->prepare($submission_sql);
            $submission_stmt->bind_param("ii", $form_id, $user_id); // Assuming $user_id holds the user's ID and $form_id holds the form ID

            if ($submission_stmt->execute()) {
                $submission_result = $submission_stmt->get_result();
                $row = $submission_result->fetch_assoc();
                $submission_count = $row['submission_count'];

                if ($submission_count > 0) {
                    // If there are form submissions, display the table
            ?>
                    <h1 class="text-3xl font-bold mb-4 mt-4">Submit Form Detail</h1>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-gray-800">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        File Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Date Submit
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // print submit form
                                // Prepare and execute the SQL query to retrieve submission files
                                $sql = "SELECT fs.form_submission_id, fs.submissiondate, f.file_name
                                FROM form_submission fs
                                LEFT JOIN file f ON fs.form_submission_id = f.type_id
                                WHERE fs.form_id = ? AND fs.student_id = ? ";

                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ii", $form_id, $user_id); // Assuming $form_id holds the form ID and $user_id holds the user's ID

                                if ($stmt->execute()) {
                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                ?>
                            <tbody>
                                <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                                    <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-black'>
                                        <a href='st_form_details.php?form_id=<?php echo $form_id; ?>' class='font-medium text-blue-500 hover:underline hover:text-blue-400'><?php echo $row['file_name']; ?></a>
                                    </td>
                                    <td class='px-6 py-4 '><?php echo $row['submissiondate']; ?></td>
                                    <td class='px-6 py-4 '><?php echo $row['form_submission_id']; ?></td>
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

                    <!-- Submit form -->

                    <h1 class="text-3xl font-bold mb-4 mt-4">Submit Form</h1>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-gray-800">
                        <form action="function/submit_form.php" method="POST" enctype="multipart/form-data">
                            <!-- Add an input field for form_id -->
                            <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                            <input type="hidden" name="student_id" value="<?php echo $_SESSION['user_id']; ?>">
                            <div class="mb-6">
                                <label for="files" class="block mb-2 text-sm font-medium text-white">Upload Files</label>
                                <input type="file" name="files[]" id="fileInput" multiple onchange="displaySelectedFiles(this.files)" class="block w-full text-sm border rounded-lg cursor-pointer text-gray-400 focus:outline-none bg-gray-700 border-gray-600 placeholder-gray-400" aria-describedby="user_avatar_help">
                                <!-- Display selected files -->
                                <div id="selected-files"></div>
                            </div>
                            <!-- Add an input field for tag -->
                            <input type="hidden" name="tag" value="6">
                            <div>
                                <!-- Use a button for form submission -->
                                <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus-ring-blue-800">Submit Form</button>
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
                function displaySelectedFiles(files) {
                    var displayDiv = document.getElementById("selected-files");

                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        var fileName = file.name;
                        var fileSize = (file.size / 1024).toFixed(2) + " KB"; // Display file size in KB

                        var fileInfo = document.createElement("p");
                        fileInfo.textContent = "Selected File " + (i + 1) + ": " + fileName + " (" + fileSize + ")";
                        displayDiv.appendChild(fileInfo);
                    }
                }
            </script>

            <!-- content end -->
            </div>
            </div>
            </div>
            </body>

            </html>