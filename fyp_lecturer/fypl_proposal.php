<?php
include 'includes/sidebar.php';

$user_id = $_SESSION["user_id"]; // Assuming you have stored the logged-in user's ID in a session variable
?>


<h1 class="text-4xl font-bold mb-4">Proposal</h1>
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
                Create Proposal
            </button>
        </div>
    </div>


    <!!-- insert form -->

        <div id="proposal-modal" class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto transform scale-0 opacity-0 ">
            <div class="modal-overlay bg-black bg-opacity-70 fixed inset-0"></div>
            <div class="bg-white w-96 rounded-lg shadow-lg p-6 transform scale-100 opacity-100 transition-transform duration-300">
                <h2 class="text-2xl font-bold mb-4">Create Proposal</h2>
                <form action="fypl_proposal.php" method="POST" enctype="multipart/form-data">
                    <!-- Your form content here -->
                    <div class="mb-6">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900">
                            Proposal Title
                        </label>
                        <input type="text" required name="proposal_title" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" placeholder="Proposal Chapter 2" />
                    </div>
                    <div class="mb-6">
                        <label for="message" class="block mb-2 text-sm font-medium text-gray-900">
                            Submission due date and time
                        </label>
                        <input required name="proposal_datetime_due" type="datetime-local" class="border text-sm rounded-lg block w-full  p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" placeholder="Select date and time" />
                    </div>

                    <input type="hidden" name="tag" value="1">
                    <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover-bg-blue-700 focus-ring-blue-800">
                        Create Proposal
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

        //  tag code

        // Check if a tag is set (for insert or delete actions)
        if (isset($_REQUEST["tag"])) {
            $tag = $_REQUEST["tag"];
            if ($tag == 1) {
                // Insert operation
                // Check if the form is submitted
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Retrieve form data
                    $proposal_title = $_POST["proposal_title"];
                    $proposal_datetime_due = $_POST["proposal_datetime_due"]; // Contains both date and time as "YYYY-MM-DDTHH:MM" format

                    // Perform data validation here if needed

                    // Convert the input date and time format to a MySQL datetime format
                    $proposal_datetime_due_mysql = date("Y-m-d H:i:s", strtotime($proposal_datetime_due));

                    // Get the current create date including both date and time
                    $proposal_date_create = date("Y-m-d H:i:s"); // Format it as needed

                    // Insert query (replace with your actual table and column names)
                    $sql = "INSERT INTO proposal (proposal_title, proposal_datetime_due, proposal_date_create) VALUES (?, ?, ?)";

                    // Prepare the statement
                    if ($stmt = mysqli_prepare($conn, $sql)) {
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "sss", $proposal_title, $proposal_datetime_due_mysql, $proposal_date_create);

                        // Attempt to execute the prepared statement
                        if (mysqli_stmt_execute($stmt)) {
                            // Proposal successfully inserted
                            // Use JavaScript to show a success popup and then redirect
                            echo '<script>';
                            echo 'alert("Proposal created successfully!");';
                            echo 'window.location.href = "fypl_proposal.php";'; // Redirect to fypl_proposal.php
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
                }
            } elseif ($tag == 2) {
                // rename operation for tag 2

            } elseif ($tag == 3) {
                // Delete operation
                if (isset($_REQUEST["proposal_id"])) {
                    $proposal_id = $_REQUEST["proposal_id"];

                    // Delete query (replace with your actual table and column names)
                    $sql = "DELETE FROM proposal WHERE proposal_id = ?";

                    // Prepare the statement
                    $stmt = $conn->prepare($sql);

                    if ($stmt) {
                        // Bind variables to the prepared statement as parameters
                        $stmt->bind_param("i", $proposal_id);

                        // Attempt to execute the prepared statement
                        if ($stmt->execute()) {
                            // Proposal successfully deleted
                            echo '<script>';
                            // echo 'alert("Proposal deleted successfully!");';
                            echo 'window.location.href = "fypl_proposal.php";'; // Redirect to your_page.php
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


        <!!-- print data -->


            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                    <tr>
                        <th scope="col" class="w-3/5 px-6 py-3">
                            Proposal Name
                        </th>
                        <th scope="col" class="w-1/5 px-6 py-3">
                            Date Create
                        </th>
                        <th scope="col" class="w-1/5 px-6 py-3 ">
                            Submit Due
                        </th>
                        <th scope="col" class="w-1/5 px-6 py-3 ">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM proposal";

                    $result = mysqli_query($conn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        // Format proposal_date_create, proposal_date_due, and proposal_time_due
                        $proposal_date_create_formatted = date("d-m-Y h:i A", strtotime($row['proposal_date_create']));
                        $proposal_date_due_formatted = date("d-m-Y h:i A", strtotime($row['proposal_datetime_due']));
                    ?>
                        <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>
                                <a href="fypl_proposal_details.php?proposal_id=<?php echo $row['proposal_id']; ?>&proposal_title=<?php echo urlencode($row['proposal_title']); ?>" class='font-medium text-blue-500 hover:underline hover:text-blue-400'><?php echo $row['proposal_title']; ?></a>
                            </td>
                            <td class='px-6 py-4'><?php echo $proposal_date_create_formatted; ?></td>
                            <td class='px-6 py-4 '><?php echo $proposal_date_due_formatted; ?></td>
                            <td class='px-6 py-4 text-center' onclick=' return confirm("Are you sure you want to delete?")'>
                                <a href='fypl_proposal.php?tag=3&proposal_id=<?php echo $row["proposal_id"]; ?>' class='font-medium text-blue-500 hover:text-blue-600'>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                    mysqli_close($conn);
                    ?>
                </tbody>

            </table>
</div>

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