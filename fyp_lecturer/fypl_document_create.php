<?php
include 'includes/sidebar.php';

// Define your database credentials
include '../db_credentials.php';


$Message = '';

$uploadDirectory = '../file/document/';

try {
    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $uploadSuccessful = true; // Flag to track successful file upload

        foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
            $file_name = $_FILES["files"]["name"][$key];
            $file_type = $_FILES["files"]["type"][$key];
            $file_size = $_FILES["files"]["size"][$key];


            // Extract document details from the form
            $doc_title = $_POST["doc_title"];
            $doc_description = $_POST["doc_description"];
            $uploader_id = $_SESSION["user_id"];
            $doc_date_create = date("Y-m-d H:i:s");
            $doc_date_due = $_POST["doc_date_due"];
            $batch_id = $_POST["batch_id"];

            // Insert doc details into the doc table
            $sqldocument = "INSERT INTO document (doc_title, doc_date_create, doc_date_due, doc_fl_id, batch_id, doc_description)
                VALUES (?, ?, ?, ?, ?, ?)";
            $stmtDocument = $pdo->prepare($sqldocument);
            $stmtDocument->bindParam(1, $doc_title);
            $stmtDocument->bindParam(2, $doc_date_create);
            $stmtDocument->bindParam(3, $doc_date_due);
            $stmtDocument->bindParam(4, $uploader_id);
            $stmtDocument->bindParam(5, $batch_id);
            $stmtDocument->bindParam(6, $doc_description);

            if ($stmtDocument->execute()) { // Fix condition
                // Get the doc_id that was just inserted
                $doc_id = $pdo->lastInsertId();

                $file_type = 'dfypl'; // dfypl = document FYP Course Lecturer
                $file_name = $file_type . '_' . $doc_id . '_' . $_FILES["files"]["name"][$key];

                // Insert file data into the file_path table using the retrieved doc_id and updated file name
                $sqlFile = "INSERT INTO file_path (file_name, type_id, file_type, file_uploader_id, file_path)
                    VALUES (?, ?, ?, ?, ?)";
                $stmtFile = $pdo->prepare($sqlFile);
                $stmtFile->bindParam(1, $file_name);
                $stmtFile->bindParam(2, $doc_id);
                $stmtFile->bindParam(3, $file_type);
                $stmtFile->bindParam(4, $uploader_id);
                $stmtFile->bindParam(5, $file_path);

                $file_path = $uploadDirectory . $file_name;

                // Move the uploaded file to the server directory with the updated file name
                if (move_uploaded_file($tmp_name, $file_path)) {
                    if (!$stmtFile->execute()) {
                        $uploadSuccessful = false;
                        $Message = 'Error: Failed to insert file details into the database.';
                        break; // Stop processing further files
                    }
                } else {
                    $uploadSuccessful = false;
                    $Message = 'Error: Failed to move the uploaded file to the server directory.';
                    break; // Stop processing further files
                }
            } else {
                $uploadSuccessful = false;
                $Message = 'Error: Failed to insert document details into the database.';
                break; // Stop processing further files
            }
        }

        // Display success message if file upload was successful
        if ($uploadSuccessful) {
            $Message = 'Successfully upload a document.';
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

?>

<!-- //TODO UPLOAD DOCUMENT, FILE BELUM BERFUNGSI -->
<!-- Your HTML doc -->

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
                        <a href="fypl_document.php" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
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
                            Create Document
                        </a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>
<div class="w-full border-b mt-2 border-gray-400 mb-2"></div>

<?php if (!empty($Message)) : ?>
    <script>
        alert("<?php echo $Message; ?>");
    </script>
<?php endif; ?>

<h1 class="text-3xl font-bold mb-4">Upload Document</h1>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
    <div class="p-4 bg-gray-800">
        <form action="fypl_document_create.php" method="POST" enctype="multipart/form-data">
            <div class="mb-6">
                <label for="title" class="block mb-2 text-sm font-medium text-white">Document Title</label>
                <input type="text" required name="doc_title" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Document One">
            </div>
            <div class="mb-6">
                <label for="doc_description	" class="block mb-2 text-sm font-medium text-white">Document Description</label>
                <input type="text" name="doc_description" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="description Document One">
            </div>
            <div class="mb-6">
                <label for="doc_date_due" class="block mb-2 text-sm font-medium text-white">Due Date</label>
                <input onkeydown="return false" type="datetime-local" required name="doc_date_due" class="border text-sm rounded-lg block p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" min="<?php echo date('Y-m-d\TH:i', strtotime('+1 minute')); ?>">
            </div>

            <div class="mb-6">
                <label for="fileInput" class="block mb-2 text-sm font-medium text-white">Upload File</label>
                <input type="file" name="files[]" id="fileInput" multiple onchange="displaySelectedFile(this)" class="block w-full text-sm border rounded-lg cursor-pointer text-gray-400 focus:outline-none bg-gray-700 border-gray-600 placeholder-gray-400" aria-describedby="user_avatar_help" required>
                <p class="mt-1 text-sm text-gray-300" id="file_input_help">Only PDF files are accepted</p>
                <!-- Display selected files -->
                <div id="selected-files" class="mt-2 text-gray-300 "></div>
            </div>
            <?php
            // Fetch batch_id for CSP600
            $sqlCSP600 = "SELECT batch_id FROM batches WHERE batch_category = 'CSP600'";
            $resultCSP600 = $conn->query($sqlCSP600);
            $rowCSP600 = $resultCSP600->fetch_assoc();
            $batchIdCSP600 = $rowCSP600['batch_id'];

            // Fetch batch_id for CSP650
            $sqlCSP650 = "SELECT batch_id FROM batches WHERE batch_category = 'CSP650'";
            $resultCSP650 = $conn->query($sqlCSP650);
            $rowCSP650 = $resultCSP650->fetch_assoc();
            $batchIdCSP650 = $rowCSP650['batch_id'];

            // Close the database connection
            $conn->close();
            ?>

            <div class="mb-6">
                <label for="batch_id" class="block mb-2 text-sm font-medium text-white">Select course for this document</label>
                <select required name="batch_id" id="batch_id" class="border text-sm rounded-lg block p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500">
                    <option value="<?php echo $batchIdCSP600; ?>">CSP600</option>
                    <option value="<?php echo $batchIdCSP650; ?>">CSP650</option>
                </select>
            </div>

            <input type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark-bg-blue-600 dark:hover-bg-blue-700 dark:focus-ring-blue-800" value="Upload">
        </form>


        <!-- Display success message if needed -->

        </form>
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
</script>