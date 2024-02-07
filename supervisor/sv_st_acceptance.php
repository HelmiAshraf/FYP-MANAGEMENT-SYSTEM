<?php

include 'includes/sv_sidebar.php';


// Retrieve project_id from the URL parameter
$project_id = $_GET['project_id'];
$student_id = $_GET['st_id'];

// SQL query to retrieve project details for the specific project_id
$sql = "SELECT project_id, project_title, project_submit_date, project_description, project_status FROM project WHERE project_id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $project_id); // Assuming project_id is an integer

// Execute the query
$stmt->execute();

// Get the result set
$result = $stmt->get_result();

// Check if there is a row in the result set
if ($result->num_rows > 0) {
    // Fetch the project details
    $row = $result->fetch_assoc();
    // Display project details in HTML template
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
                            <a href="sv_st_propose.php" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                                Propose Project
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                                Propose Details
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

    <h1 class="text-4xl font-bold mb-4">Supervisor Result</h1>

    <div class="relative overflow-x-auto rounded-lg  bg-gray-900 ">
        <table class="w-full text-sm text-left rtl:text-right text-white">
            <tbody>
                <tr class="border-b bg-gray-800 border-gray-700">
                    <td class="px-6 py-4 border-r bg-gray-700 border-gray-700">
                        <p class="font-semibold">Project Title:
                        </p>
                    </td>
                </tr>
                <tr class=" border-b bg-gray-800 border-gray-700">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-300 mb-">
                            <?php echo $row['project_title']; ?>
                        </p>
                    </td>
                </tr>
                <tr class=" border-b bg-gray-800 border-gray-700">
                    <td class="px-6 py-4 bg-gray-700 border-gray-700">
                        <p class="font-semibold">Project Description:
                        </p>
                    </td>
                </tr>
                <tr class=" border-b bg-gray-800 border-gray-700">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-300 ">
                            <?php echo nl2br($row['project_description']); ?>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php
        // Check if the project status is not equal to 0 before displaying the buttons
        if ($row['project_status'] != 0) {
        ?>
            <div class="flex justify-end p-3 mt-1">
                <form action="function/accept_reject_st.php" method="post">
                    <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                    <input type="hidden" name="supervisor_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <!-- "Accept" button -->
                    <button type="submit" name="accept" value="accept" onclick="confirmAction('accept')"  class="focus:outline-none text-white focus:ring-4 font-medium rounded-md text-sm px-5 py-2.5 mr-2 mb-2 bg-green-700 hover:bg-green-600">Accept</button>

                    <!-- "Reject" button -->
                    <button type="submit" name="reject" value="reject" onclick="confirmAction('reject')"  class="focus:outline-none text-white focus:ring-4 font-medium rounded-md text-sm px-5 py-2.5 mr-2 mb-2 bg-red-700 hover:bg-red-600 ">Reject</button>
                </form>
            </div>
            <script>
                function confirmAction(action) {
                    var confirmation = confirm("Are you sure you want to " + action + " this request?");
                    if (confirmation) {
                        document.getElementById('acceptRejectForm').submit();
                    } else {
                        // If user clicks cancel, do nothing or add any other action you want.
                    }
                }
            </script>
    </div>
<?php
        }
    } else {
        // Handle the case where no projects are found for the student
        echo "No projects found for this student.";
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
?>




<!-- content end -->
</div>
</div>
</div>
</body>

</html>