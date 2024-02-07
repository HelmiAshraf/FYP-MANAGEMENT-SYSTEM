<?php
include 'includes/sidebar.php';

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tag"])) {
    $tag = $_REQUEST["tag"];

    // Insert operation
    if ($tag == 1) {

        // Retrieve form data
        $ass_title = $_POST["ass_title"];
        $ass_description = $_POST["ass_description"];
        $ass_date_due = $_POST["ass_date_due"]; // Contains both date and time as "YYYY-MM-DDTHH:MM" format
        $ass_fl_id = $_SESSION["user_id"];
        $batch_id = $_POST["batch_id"];

        // Convert the input date and time format to a MySQL datetime format
        $ass_date_due_mysql = date("Y-m-d H:i:s", strtotime($ass_date_due));

        // Get the current create date including both date and time
        $ass_date_create = date("Y-m-d H:i:s"); // Format it as needed

        // Insert query (replace with your actual table and column names)
        $sql = "INSERT INTO assignment (ass_title, ass_description, ass_date_due, ass_date_create, ass_fl_id, batch_id) VALUES (?, ?, ?, ?, ?, ?)";

        // Prepare the statement
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $ass_title, $ass_description, $ass_date_due_mysql, $ass_date_create, $ass_fl_id, $batch_id);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // ass successfully inserted
                // Use JavaScript to show a success popup and then redirect
                echo '<script>';
                echo 'alert("Assignment created successfully!");';
                echo 'window.location.href = "fypl_assignment.php";'; // Redirect to fypl_ass.php
                echo '</script>';
            } else {
                // Error in execution
                echo "Error: " . mysqli_error($conn);
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            // Error in preparing the statement
            echo "Error: " . mysqli_error($conn);
        }
    } elseif ($tag == 2) {
        // rename operation for tag 2

    } elseif ($tag == 3) {
        // Delete operation
        if (isset($_REQUEST["ass_id"])) {
            $ass_id = $_REQUEST["ass_id"];

            // Delete query (replace with your actual table and column names)
            $sql = "DELETE FROM assignment WHERE ass_id = ?";

            // Prepare the statement
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("i", $ass_id);

                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    // ass successfully deleted
                    echo '<script>';
                    // echo 'alert("ass deleted successfully!");';
                    echo 'window.location.href = "fypl_assignment.php";'; // Redirect to your_page.php
                    echo '</script>';
                } else {
                    // Error in execution
                    echo "Error: " . $stmt->error;
                }

                // Close statement
                $stmt->close();
            } else {
                // Error in preparing the statement
                echo "Error: " . $conn->error;
            }
        }
    }
}
?>
<!!-- insert form -->

    <div id="ass-modal" class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto transform scale-0 opacity-0 ">
        <div class="modal-overlay bg-black bg-opacity-70 fixed inset-0"></div>
        <div class="bg-white w-96 rounded-lg shadow-lg p-6 transform scale-100 opacity-100 transition-transform duration-300">
            <h2 class="text-2xl font-bold mb-4">Create Assignment</h2>
            <form action="fypl_assignment.php" method="POST" enctype="multipart/form-data">
                <!-- Your form content here -->
                <div class="mb-6">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900">
                        Title
                    </label>
                    <input type="text" required name="ass_title" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" placeholder="Assignment Chapter 2" />
                </div>
                <div class="mb-6">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900">
                        Description
                    </label>
                    <textarea id="ass_description" required name="ass_description" rows="4" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" placeholder="Write your Description here..."></textarea>
                </div>
                <div class="mb-6">
                    <label for="message" class="block mb-2 text-sm font-medium text-gray-900">
                        Submission due date and time
                    </label>
                    <input required onkeydown="return false" type="datetime-local" name="ass_date_due" type="datetime-local" class="border text-sm rounded-lg block w-full  p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" placeholder="Select date and time" min="<?php echo date('Y-m-d\TH:i', strtotime('+1 minute')); ?>" />
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
                ?>

                <div class="mb-6">
                    <label for="batch_id" class="block mb-2 text-sm font-medium text-gray-900">
                        This assignment for?
                    </label>
                    <select required name="batch_id" id="batch_id" class="border text-sm rounded-lg block p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 text-black ">
                        <option value="<?php echo $batchIdCSP600; ?>">CSP600</option>
                        <option value="<?php echo $batchIdCSP650; ?>">CSP650</option>
                    </select>
                </div>
                <input type="hidden" name="tag" value="1">

                <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover-bg-blue-700 focus-ring-blue-800">
                    Create Assignment
                </button>
            </form>

            <button id="close-modal-button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    </div>

    <?php
    function getBatchIdByCategory($batchCategory, $conn)
    {
        $sql = "SELECT batch_id FROM batches WHERE batch_category = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error preparing the statement: " . $conn->error);
        }

        $stmt->bind_param("s", $batchCategory);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['batch_id'];
        }

        return null;
    }

    // Assuming you have established a database connection ($conn)
    $supervisor_id = $_SESSION["user_id"];

    // Get the default batch_id for CSP600
    $defaultBatchId = getBatchIdByCategory('CSP600', $conn);

    // If no batch_category is selected, set the default to CSP600 batch_id
    $selected_option = isset($_GET['batch_category']) ? $_GET['batch_category'] : $defaultBatchId;

    $sql = "SELECT
    a.*
FROM
    assignment a
JOIN
fyp_lecturer fl ON a.ass_fl_id = fl.fl_id
 WHERE a.batch_id = ? AND fl.fl_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $selected_option, $supervisor_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
    ?>

        <div class="flex justify-between items-center">
            <div>
                <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: Final Year Project Lecturer</p>
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
                                <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                                    Assignment
                                </a>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

        <h1 class="text-4xl font-bold mb-4">Assignment</h1>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
            <div class="p-4 bg-gray-900 flex justify-between">
                <div class="bg-gray-900 flex items-center">
                    <label for="batchForm" class="block text-xl font-sm text-gray-300 mr-4">Course:</label>
                    <form id="batchForm" method="GET">
                        <select name="batch_category" id="countries" class="text-sm rounded-md block p-2 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" onchange="submitForm()">
                            <?php
                            // Fetch batch_id based on batch_category
                            $csp600BatchId = getBatchIdByCategory('CSP600', $conn);
                            $csp650BatchId = getBatchIdByCategory('CSP650', $conn);
                            ?>
                            <option value="<?php echo $csp600BatchId; ?>" <?php echo ($selected_option == $csp600BatchId) ? 'selected' : ''; ?>>CSP600</option>
                            <option value="<?php echo $csp650BatchId; ?>" <?php echo ($selected_option == $csp650BatchId) ? 'selected' : ''; ?>>CSP650</option>
                        </select>
                    </form>
                </div>
                <script>
                    function submitForm() {
                        document.getElementById("batchForm").submit();
                    }
                </script>
                <div>
                    <button id="open-modal-button" class="ml-auto text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-700 hover:bg-blue-800">
                        Create Assignment
                    </button>
                </div>
            </div>

            <!!-- print data -->

                <table class="w-full text-sm text-left text-gray-400">
                    <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                        Official Submission Hub
                        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">An area for submitting official project components like reports and poster</p>
                    </caption>
                    <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                        <tr>
                            <th scope="col" class="w-3/7 px-6 py-3">
                                Assignment Name
                            </th>
                            <th scope="col" class="w-1/7 px-6 py-3">
                                Date Create
                            </th>
                            <th scope="col" class="w-1/7 px-6 py-3 ">
                                Submit Due
                            </th>
                            <th scope="col" class="w-1/7 px-6 py-3 text-center">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        if ($result->num_rows > 0) {

                            while ($row = $result->fetch_assoc()) {
                                // Format ass_date_create, ass_date_due, and ass_time_due
                                $ass_date_create_formatted = date("d-m-Y h:i A", strtotime($row['ass_date_create']));
                                $ass_date_due_formatted = date("d-m-Y h:i A", strtotime($row['ass_date_due']));
                        ?>
                                <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                                    <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>
                                        <a href="fypl_assignment_details.php?ass_id=<?php echo $row['ass_id']; ?>" class='font-medium text-blue-500 hover:underline hover:text-blue-400'><?php echo $row['ass_title']; ?></a>
                                    </td>
                                    <td class='px-6 py-4'><?php echo $ass_date_create_formatted; ?></td>
                                    <td class='px-6 py-4 '><?php echo $ass_date_due_formatted; ?></td>
                                    <td class='px-6 py-4 text-center' onclick=' return confirm("Are you sure you want to proceed? Deleting this assignment submission will remove all associated submissions")'>
                                        <a href='function/ass_del.php?ass_id=<?php echo $row["ass_id"]; ?>' class='font-medium text-blue-500 hover:text-blue-600'>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                    <?php
                            }
                        } else {
                            echo "<tbody>";
                            echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                            echo "<td colspan='5' class='px-6 py-4 text-center'>No Assignment found</td>";
                            echo "</tr>";
                            echo "</tbody>";
                        }
                    } else {
                        echo "Error executing the query: " . $stmt->error;
                    }
                    ?>
                    </tbody>
                </table>
        </div>
        <?php
        $stmt->close();
        mysqli_close($conn);
        ?>

        <!-- content end -->
        </div>
        </div>
        </div>

        <script>
            const openModalButton = document.getElementById("open-modal-button");
            const closeModalButton = document.getElementById("close-modal-button");
            const assModal = document.getElementById("ass-modal");

            openModalButton.addEventListener("click", () => {
                assModal.classList.remove("scale-0", "opacity-0");
                assModal.classList.add("scale-100", "opacity-100");
            });

            closeModalButton.addEventListener("click", () => {
                assModal.classList.remove("scale-100", "opacity-100");
                assModal.classList.add("scale-0", "opacity-0");
            });
        </script>

        </body>

        </html>