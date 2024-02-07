<?php
include 'includes/sv_sidebar.php';

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
                    echo '<script>alert("Password changed successfully."); window.location = "st_profile.php";</script>';
                    exit();
                } else {
                    echo '<script>alert("Failed to change password. Please try again.");</script>';
                }
            } else {
                echo '<script>alert("Incorrect current password."); window.location = "st_profile.php";</script>';
                exit();
            }
        } else {
            echo '<script>alert("User not found."); window.location = "st_profile.php";</script>';
            exit();
        }

        // Close connections
        $stmt->close();
        $update_stmt->close();
        $conn->close();
    }
}


$user_id = $_GET['st_id']; // Assuming you have stored the logged-in user's ID in a session variable

$sql = "SELECT DISTINCT
s.st_name AS student_name,
s.st_id AS student_id,
s.st_phnum AS student_phnum,
s.st_email AS student_email,
s.st_image AS student_image_path,
sv.sv_name AS supervisor_name,
p.project_id AS project_id,
p.project_title AS project_title,
p.research_area AS research_area,
p.domain AS domain,
p.end_product AS end_product,
p.objective AS objective,
p.scope AS scope,
p.significant AS significant
FROM
student s
JOIN
project p ON s.st_id = p.student_id
JOIN
supervise sp ON s.st_id = sp.student_id
JOIN
supervisor sv ON sp.supervisor_id = sv.sv_id
WHERE
s.st_id = ?
ORDER BY
p.project_id DESC
LIMIT 1;";

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
                            <a href="sv_supervisee.php" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-gray-600 hover:font-bold ">
                                <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                </svg>
                                Supervisee
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

      

        <h1 class="text-4xl font-bold mb-4">Student Profile</h1>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
            <div class="px-3 py-2 bg-gray-900 flex justify-between">
                <div class="relative flex items-center justify-center">
                    <h2 class="text-2xl font-bold text-gray-900 whitespace-nowrap dark:text-white">Student Details </h2>
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
                        <tr class='bg-gray-800 border-gray-700 '>
                            <td class='px-6 py-2' colspan="2">
                                <p class="font-bold text-white">Supervisor Name</p><?php echo $row['supervisor_name']; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-900 flex justify-between">
                <div class="relative p-3">
                    <h2 class="text-2xl font-bold whitespace-nowrap text-white">FYP Details</h2>
                </div>
            </div>
            <div>
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                        <tr>
                            <th scope="col" class="w-1/4 px-6 py-3">
                                maters
                            </th>
                            <th scope="col" class="w-3/4 px-6 py-3">
                                details
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td scope='row' class='w-1/4 px-6 py-4 font-medium whitespace-nowrap text-white'>Project Title</td>
                            <td class='w-3/4 px-6 py-4'><?php echo $row['project_title']; ?> </td>
                        </tr>
                        </tr>
                        <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>Research Area</td>
                            <td class='px-6 py-4'><?php echo nl2br($row['research_area']); ?></td>
                        </tr>
                        <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>Domain</td>
                            <td class='px-6 py-4'><?php echo nl2br($row['domain']); ?></td>
                        </tr>
                        <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>End Product</td>
                            <td class='px-6 py-4 text-justify'><?php echo nl2br($row['end_product']); ?></td>
                        </tr>
                        <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>Objective</td>
                            <td class='px-6 py-4 text-justify'><?php echo nl2br($row['objective']); ?></td>
                        </tr>
                        <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>Scope</td>
                            <td class='px-6 py-4 text-justify'><?php echo nl2br($row['scope']); ?></td>
                        </tr>
                        <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>Significant</td>
                            <td class='px-6 py-4 text-justify'><?php echo nl2br($row['significant']); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        </body>

        </html>

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

    const fypEditButtons = document.querySelectorAll(".fyp_edit");
    const fypEditModal = document.getElementById("editFyp");
    const fypCloseEditModalButton = document.getElementById("close-fyp-edit-modal-button");

    profileEditButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            event.preventDefault();

            // Show the profile edit modal
            profileEditModal.style.transform = "scale(1)";
            profileEditModal.style.opacity = "1";
        });
    });

    fypEditButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            event.preventDefault();

            // Show the FYP edit modal
            fypEditModal.style.transform = "scale(1)";
            fypEditModal.style.opacity = "1";
        });
    });

    // Close the profile edit modal
    profileCloseEditModalButton.addEventListener("click", function() {
        profileEditModal.style.transform = "scale(0)";
        profileEditModal.style.opacity = "0";
    });

    // Close the FYP edit modal
    fypCloseEditModalButton.addEventListener("click", function() {
        fypEditModal.style.transform = "scale(0)";
        fypEditModal.style.opacity = "0";
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