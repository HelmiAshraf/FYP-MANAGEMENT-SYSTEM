<?php
include 'includes/sv_sidebar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sv_id = $_POST["sv_id"];
    $tag = $_POST["tag"];

    if ($tag == "change_password") {
        // Get user input
        $current_password = $_POST["current_password"];
        $new_password = $_POST["new_password"];

        include '../db.php';

        // Retrieve the hashed password from the database
        $sql = "SELECT password FROM user WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $sv_id);
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
                $update_stmt->bind_param("si", $new_hashed_password, $sv_id);

                if ($update_stmt->execute()) {
                    echo '<script>alert("Password changed successfully."); window.location = "sv_profile.php";</script>';
                    exit();
                } else {
                    echo '<script>alert("Failed to change password. Please try again.");</script>';
                }
            } else {
                echo '<script>alert("Incorrect current password."); window.location = "sv_profile.php";</script>';
                exit();
            }
        } else {
            echo '<script>alert("User not found."); window.location = "sv_profile.php";</script>';
            exit();
        }

        // Close connections
        $stmt->close();
        $update_stmt->close();
        $conn->close();
    }
}

$user_id = $_SESSION['user_id']; // You can change this based on how you store the user ID in your session

$sql = "SELECT
s.sv_id,
s.sv_name,
s.sv_email,
s.sv_expertise,
s.sv_quota,
s.sv_image AS sv_image_path,
s.sv_phnum,
u.password
FROM
supervisor s
JOIN
user u ON s.sv_id = u.user_id
WHERE
s.sv_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Assuming user_id is an integer
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $sv_image_path = '';

        // Check if the image path exists
        if (!empty($row["sv_image_path"]) && file_exists($row["sv_image_path"])) {
            // Read the image content and convert it to base64
            $sv_image_path = base64_encode(file_get_contents($row["sv_image_path"]));
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
                                <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                                    Profile
                                </a>
                            </div>
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
                <form action="sv_profile.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="sv_id" value="<?php echo $row['sv_id']; ?>">
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
            <div class="bg-white w-2/6 rounded-lg shadow-lg p-6 transform scale-100 opacity-100 transition-transform duration-300">
                <h2 class="text-2xl font-bold mb-4">Edit Profile</h2>
                <form action="../global_class/sv_profile_form.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="sv_id" value="<?php echo $row['sv_id']; ?>">
                    <!-- Add hidden input fields for data -->
                    <input type="hidden" name="field_to_update" id="field_to_update">
                    <div class="mb-4">

                        <div class="mb-2 flex items-center">
                            <img id="image_preview" src='data:image/jpeg;base64,<?php echo $sv_image_path; ?>' alt="Profile Preview" class="w-20">
                        </div>
                        <!-- Upload Button -->
                        <div class="">
                            <input type="file" name="sv_image" id="fileInput" onchange="previewImage()" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="new_data" class="block mb-2 text-sm font-medium">Expertise</label>
                        <textarea name="sv_expertise" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" maxlength="300"><?php echo $row['sv_expertise']; ?></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="new_data" class="block mb-2 text-sm font-medium">Phone Number</label>
                        <input type="text" name="sv_phnum" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" value="<?php echo $row['sv_phnum']; ?>" />
                    </div>
                    <div class="mb-4">
                        <label for="doc_description" class="block mb-2 text-sm font-medium">Email</label>
                        <input type="text" name="sv_email" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" value="<?php echo $row['sv_email']; ?>" />
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">
                            Update
                        </button>
                    </div>
                </form>
                <button id="close-edit-modal-button" class=" absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        </div>

        <h1 class="text-4xl font-bold mb-4"> My Profile</h1>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
            <div class="px-3 py-2 bg-gray-900 flex justify-between">
                <div class="relative flex items-center justify-center">
                    <h2 class="text-2xl font-bold whitespace-nowrap text-white">My Details</h2>
                </div>
                <div class="flex items-center justify-center">
                    <a href="#" class="change_password text-sm hover:underline hover:text-blue-500 text-gray-100 font-light mr-4">Change Password</a>
                    <a href="#" class="sv_edit text-white font-medium rounded-lg text-sm px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-blue-800">Edit Profile</a>
                </div>
            </div>

            <div>
                <table class="table-fixed w-full text-sm text-left text-gray-400">
                    <tbody class="">
                        <tr class='bg-gray-800 :border-gray-700'>
                            <td scope='row' rowspan="4" class=' text-center w-1/4 px-2 py-3  '>
                                <img class='mx-auto mb-3 w-16 md:w-32 lg:w-48' src='data:image/jpeg;base64,<?php echo $sv_image_path; ?>' alt='Supervisor Image' />
                            </td>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Name</p><?php echo $row['sv_name']; ?>
                            </td>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Email</p><?php echo $row['sv_email']; ?>
                            </td>
                        </tr>
                        <tr class='bg-gray-800 border-gray-700'>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Supervisor ID</p><?php echo $row['sv_id']; ?>
                            </td>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Phone Number</p><?php echo $row['sv_phnum']; ?>
                            </td>
                        </tr>
                        <!-- <tr class=' bg-gray-800  border-gray-700 '>
                            <td rowspan="1" colspan="2" class='px-6 py-2' style='vertical-align: top;'>
                                <p class="font-bold text-white">Expertise</p> 
                            </td>
                        </tr> -->
                        <tr class='bg-gray-800 border-gray-700'>
                            <td class='px-6 py-2 ' style="vertical-align: top;">
                                <p class="font-bold text-white">Expertise</p><?php echo $row['sv_expertise']; ?>
                            </td>
                            <td class='px-6 py-2 ' style="vertical-align: top;">
                                <p class="font-bold text-white">Quota</p><?php echo $row['sv_quota']; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
<?php
    }
} else {
    echo "No supervisor found for the given user ID.";
}

$stmt->close();
$conn->close();
?>

<script>
    const editButtons = document.querySelectorAll(".sv_edit");
    const editModal = document.getElementById("editProfile");
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