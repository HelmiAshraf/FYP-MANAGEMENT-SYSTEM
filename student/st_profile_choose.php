<?php
include 'includes/st_sidebar_choose_sv.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $st_id = $_POST["st_id"];
    $tag = $_POST["tag"];

    if ($tag == "change_password") {
        // Get user input
        $current_password = $_POST["current_password"];
        $new_password = $_POST["new_password"];

        include '../db.php';

        // Retrieve the hashed password from the database
        $sql = "SELECT password FROM user WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $st_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];

            // Verify the current password
            if (password_verify($current_password, $hashed_password)) {
                // Current password is correct, update the password with the new hashed password
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $update_sql = "UPDATE user SET password = ? WHERE user_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $new_hashed_password, $st_id);

                if ($update_stmt->execute()) {
                    echo '<script>alert("Password changed successfully."); window.location = "st_profile_choose.php";</script>';
                    exit();
                } else {
                    echo '<script>alert("Failed to change password. Please try again.");</script>';
                }
            } else {
                echo '<script>alert("Incorrect current password."); window.location = "st_profile_choose.php";</script>';
                exit();
            }
        } else {
            echo '<script>alert("User not found."); window.location = "st_profile_choose.php";</script>';
            exit();
        }

        // Close connections
        $stmt->close();
        $update_stmt->close();
        $conn->close();
    } else if ($tag == "edit_profile") {

        include '../db_credentials.php';

        // Create a PDO database connection
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        // Set PDO to throw exceptions on error
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get form data
        $st_id = $_POST['st_id'];
        $st_phnum = $_POST['st_phnum'];
        $st_email = $_POST['st_email'];

        // Check if an image is uploaded
        $imageFile = $_FILES['st_image'] ?? null;

        // Fetch the existing image path from the database
        $sql = "SELECT st_image FROM student WHERE st_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$st_id]);
        $existingImagePath = $stmt->fetchColumn();

        // Specify the directory path relative to your script
        $directoryPath = '../file/image/';

        // Delete the existing image
        if ($existingImagePath) {
            $imagePath = $directoryPath . basename($existingImagePath);
            if (file_exists($imagePath)) {
                $deleted = unlink($imagePath);
                if (!$deleted) {
                    echo "Error deleting the existing image.";
                }
            }
        }

        // Upload the new image
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK) {
            // Move the uploaded image to the 'uploads' directory with st_id prefix
            $targetFilename = $st_id . '_' . $imageFile['name'];
            $targetPath = $directoryPath . $targetFilename;
            move_uploaded_file($imageFile['tmp_name'], $targetPath);

            // Update st_image in the database with the new path
            $newImagePath = '../file/image/' . $targetFilename;
            $sql = "UPDATE student SET st_image = ? WHERE st_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$newImagePath, $st_id]);
        }

        // Update other profile information
        $sql = "UPDATE student SET 
                st_phnum = ?, 
                st_email = ?
                WHERE st_id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$st_phnum, $st_email, $st_id]);

        echo '<script>
        alert("Profile updated successfully.");
        window.location.href = "st_profile_choose.php";
    </script>';
    } else {
        echo "Invalid request method.";
    }
}

$user_id = $_SESSION['user_id']; // Assuming you have stored the logged-in user's ID in a session variable

$sql = "SELECT
            st_name AS student_name,
            st_id AS student_id,
            st_phnum AS student_phnum,
            st_email AS student_email,
            st_image AS student_image_path
        FROM student
        WHERE st_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


if ($result) {
    while ($row = $result->fetch_assoc()) {
        $student_image_path = '';

        // Check if the image path exists
        if (!empty($row["student_image_path"]) && file_exists($row["student_image_path"])) {
            // Read the image content and convert it to base64
            $student_image_path = base64_encode(file_get_contents($row["student_image_path"]));
        }
?>

        <?php
        // Check if the session variable is set
        if (isset($_SESSION['update_success_message'])) {
            // Output JavaScript to show a popup message
            echo '<script>alert("' . $_SESSION['update_success_message'] . '");</script>';

            // Unset the session variable to avoid showing the message on subsequent visits
            unset($_SESSION['update_success_message']);
        }
        ?>

        <div class="flex justify-between items-center">
            <div>
                <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: Student</p>
            </div>
            <div class="ml-4">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                            <a href="st_available_sv.php" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-gray-600 hover:font-bold ">
                                <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                </svg>
                                Available Supervisor
                            </a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

        <div id="change_password" class="modal fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto transform scale-0 opacity-0">
            <div class="modal-overlay bg-black bg-opacity-70 fixed inset-0"></div>
            <div class="bg-white w-2/6 rounded-lg shadow-lg p-6 transform scale-100 opacity-100 transition-transform duration-300">
                <h2 class="text-2xl font-bold mb-4">Change Password</h2>
                <form action="st_profile_choose.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="st_id" value="<?php echo $row['student_id']; ?>">
                    <input type="hidden" name="tag" value="change_password">
                    <!-- Add hidden input fields for data -->
                    <div class="mb-4">
                        <label for="password" class="block mb-2 text-sm font-medium">Current Password</label>
                        <input type="text" name="current_password" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block mb-2 text-sm font-medium">New Password (at least 6 characters)</label>
                        <input type="text" name="new_password" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" required minlength="6" />
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">
                            Change
                        </button>
                    </div>
                </form>
                <button id="close-change_password" class=" absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="editProfile" class="modal fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto transform scale-0 opacity-0">
            <div class="modal-overlay bg-black bg-opacity-70 fixed inset-0"></div>
            <div class="bg-white w-96 rounded-lg shadow-lg p-6 transform scale-100 opacity-100 transition-transform duration-300">
                <h2 class="text-2xl font-bold mb-4">Edit Profile</h2>
                <form action="st_profile_choose.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="st_id" value="<?php echo $row['student_id']; ?>">
                    <input type="hidden" name="tag" value="edit_profile">


                    <!-- Add hidden input fields for data -->
                    <input type="hidden" name="field_to_update" id="field_to_update">
                    <div class="mb-4">
                        <div class="mb-2 flex items-center">
                            <img id="image_preview" src='data:image/jpeg;base64,<?php echo $student_image_path; ?>' alt="Profile Preview" class="w-20">
                        </div>
                        <!-- Upload Button -->
                        <div class="">
                            <input type="file" name="st_image" id="fileInput" onchange="previewImage()" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="new_data" class="block mb-2 text-sm font-medium">Phone Number</label>
                        <input type="text" name="st_phnum" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" value="<?php echo $row['student_phnum']; ?>" />
                    </div>
                    <div class="mb-4">
                        <label for="doc_description" class="block mb-2 text-sm font-medium">Email</label>
                        <input type="text" name="st_email" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" value="<?php echo $row['student_email']; ?>" />
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">
                            Update
                        </button>
                    </div>
                </form>
                <button id="close-profile-edit-modal-button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        </div>

        <h1 class="text-4xl font-bold mb-4">My Profile</h1>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
            <div class="px-3 py-2 bg-gray-900 flex justify-between">
                <div class="relative flex items-center justify-center">
                    <h2 class="text-2xl font-bold text-gray-900 whitespace-nowrap dark:text-white">Student Details </h2>
                </div>
                <div class="flex items-center justify-center">
                    <a href="#" class="change_password text-sm hover:underline hover:text-blue-500 text-gray-100 font-light mr-4">Change Password</a>
                    <a href="#" class="profile_edit text-white font-medium rounded-lg text-sm px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-blue-800">Edit Profile</a>

                </div>
            </div>
            <div>
                <table class="table-fixed w-full text-sm text-left text-gray-400">
                    <tbody>
                        <tr class='bg-gray-800 border-gray-700'>
                            <td scope='row' rowspan="4" class='text-center w-1/4 px-2 py-3'>
                                <div class="flex justify-center items-center h-36 "> <!-- Adjust the height as needed -->
                                    <img class='rounded max-w-full max-h-full' src='data:image/jpeg;base64,<?php echo $student_image_path; ?>' alt='Student Image' />
                                </div>
                            </td>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Name</p><?php echo $row['student_name']; ?>
                            </td>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Phone Number</p><?php echo $row['student_phnum']; ?>
                            </td>
                        </tr>
                        <tr class='bg-gray-800 border-gray-700 '>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Student ID</p><?php echo $row['student_id']; ?>
                            </td>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Email</p><?php echo $row['student_email']; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

<?php
    }
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>



<script>
    const profileEditButtons = document.querySelectorAll(".profile_edit");
    const profileEditModal = document.getElementById("editProfile");
    const profileCloseEditModalButton = document.getElementById("close-profile-edit-modal-button");

    profileEditButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            event.preventDefault();

            // Show the profile edit modal
            profileEditModal.style.transform = "scale(1)";
            profileEditModal.style.opacity = "1";
        });
    });


    // Close the profile edit modal
    profileCloseEditModalButton.addEventListener("click", function() {
        profileEditModal.style.transform = "scale(0)";
        profileEditModal.style.opacity = "0";
    });
</script>

<script>
    const change_passwordButtons = document.querySelectorAll(".change_password");
    const change_passwordModal = document.getElementById("change_password");
    const change_passwordCloseModalButton = document.getElementById("close-change_password");

    change_passwordButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            event.preventDefault();

            // Show the modal
            change_passwordModal.style.transform = "scale(1)";
            change_passwordModal.style.opacity = "1";
        });
    });

    // Close the change password modal
    change_passwordCloseModalButton.addEventListener("click", function() {
        change_passwordModal.style.transform = "scale(0)";
        change_passwordModal.style.opacity = "0";
    });
</script>

<script>
    function previewImage() {
        var preview = document.getElementById('image_preview');
        var fileInput = document.getElementById('fileInput');
        var file = fileInput.files[0];
        var reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "default_profile_picture.jpg";
        }
    }
</script>


<!-- content end -->
</div>
</div>
</div>
</body>

</html