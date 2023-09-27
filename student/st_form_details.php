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

            <h1 class="text-2xl font-bold mb-4">Form Details</h1>
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
            // Create a form for students to submit their forms
            // Ensure that the action attribute points to the submission handling script
            ?>
            <h1 class="text-2xl font-bold mb-4 mt-4">Submit Form</h1>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-gray-800">
                <form action="function/submit_form.php" method="POST" enctype="multipart/form-data">
                    <!-- Add an input field for form_id -->
                    <input type="text" name="form_id" value="<?php echo $form_id; ?>">
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
<?php
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




<!-- content end -->
</div>
</div>
</div>
</body>

</html>