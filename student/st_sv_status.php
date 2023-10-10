<?php include 'includes/st_sidebar_choose_sv.php';

echo $_SESSION['user_id'];
// Check if the student ID is stored in the session
if (isset($_SESSION['user_id'])) {
    $student_id = $_SESSION['user_id'];
} else {
    // Handle the case where the student ID is not in the session
    echo "Student ID not found in the session.";
    exit(); // Exit the script
}

// Your database connection code here

// SQL query to retrieve supervisor data for the student's project
$sql = "SELECT
            s.sv_id,
            s.sv_name,
            s.sv_email,
            s.sv_expertise,
            TO_BASE64(s.sv_image) AS sv_image_base64,
            s.sv_status,
            s.sv_quota,
            s.sv_phnum,
            p.project_status
        FROM
            project p
        JOIN
            supervisor s ON p.supervisor_id = s.sv_id
        WHERE
            p.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id); // Assuming student_id is an integer

// Execute the query
$stmt->execute();

// Get the result set
$result = $stmt->get_result();

// Check if there are supervisors for the student's project
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Map project_status values to corresponding labels
        $projectStatusLabel = "";
        switch ($row['project_status']) {
            case 0:
                $projectStatusLabel = "Reject";
                break;
            case 1:
                $projectStatusLabel = "Accept";
                break;
            case 2:
            default:
                $projectStatusLabel = "Pending";
                break;
        }
?>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 bg-white dark:bg-gray-800 dark:text-gray-400">
            <div class="grid gap-4">
                <h2 class="text-xl font-semibold mt-2 text-white">Supervisor Details</h2>
                <!-- Chosen Supervisor and Status -->
                <div class="grid grid-cols-3 gap-4">
                    <!-- Chosen Supervisor Details -->
                    <div class="border p-6 rounded-lg -md shadow-md col-span-2">
                        <div class="flex items-center mb-6">
                            <img class='h-12 w-12' src='data:image/jpeg;base64,<?php echo $row["sv_image_base64"]; ?>' alt="Supervisor" class="w-32 h-32 rounded-lg -full mr-4">
                            <div>
                                <div class="ml-4">
                                    <p class="font-semibold text-white text-lg"><?php echo $row['sv_name']; ?></p>
                                    <p class="text-gray-200">Email: <?php echo $row['sv_email']; ?></p>
                                    <p class="text-gray-200">Phone Number: <?php echo $row['sv_phnum']; ?></p>
                                </div>
                                <div class="ml-4 mt-2">
                                    <p class="font-semibold text-white text-lg">Expertise</p>
                                    <p class="text-gray-200"><?php echo $row['sv_expertise']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status of Application -->
                    <div class="border p-6 rounded-lg -md shadow-md">
                        <p class="font-semibold text-white text-lg">Application Status</p>
                        <p class="text-green-500 font-semibold"><?php echo $projectStatusLabel; ?></p>
                    </div>
            <?php
        }
    } else {
        echo "No supervisors found for this student's project.";
    }

    // Close the database connection and release resources
    $stmt->close();
    $conn->close();
            ?>
                </div>

                <!-- Change Supervisor and Confirm Supervisor Buttons -->
                <div class="flex justify-end space-x-4 mt-4 mb-14">
                    <a href="st_proposal.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg -md hover:bg-blue-600 focus:outline-none">
                        Go To FYP System
                    </a>
                </div>
            </div>
        </div>

        <!-- Confirmation Popup -->
        <div id="confirmationModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold mb-2">Confirmation</h2>
                <p id="confirmationText" class="text-gray-700"></p>
                <div class="flex justify-end mt-4">
                    <button id="confirmButton" class="bg-blue-500 text-white px-2 py-1 rounded-lg -md hover:bg-blue-700 focus:outline-none mr-2" onclick="confirmSupervisor()">Confirm</button>
                    <button class="bg-gray-500 text-white px-2 py-1 rounded-lg -md hover:bg-gray-700 focus:outline-none" onclick="closeConfirmation()">Cancel</button>
                </div>
            </div>
        </div>

        <script>
            function showConfirmation(description) {
                document.getElementById('confirmationText').innerText = description;
                document.getElementById('confirmationModal').classList.remove('hidden');
            }

            function closeConfirmation() {
                document.getElementById('confirmationModal').classList.add('hidden');
            }

            function confirmSupervisor() {
                // TODO: Add code to confirm the supervisor
                window.location.href = "st_home.php";
            }
        </script>




        <!-- content end -->
        </div>
        </div>
        </div>
        </body>

        </html>