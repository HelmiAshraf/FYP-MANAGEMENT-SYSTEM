<?php

?>

<!-- Your HTML for the modal remains unchanged, using the $task_name, $start_date, and $end_date variables to populate the form fields. -->



<div id="modal" class="modal fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto transform scale-0 opacity-0">
    <div class="modal-overlay bg-black bg-opacity-70 fixed inset-0"></div>
    <div class="bg-white w-96 rounded-lg shadow-lg p-6 transform scale-100 opacity-100 transition-transform duration-300">
        <h2 class="text-2xl font-bold mb-4">Update Timeline Task</h2>
        <form action="update_task.php" method="POST">
            <input type="hidden" name="gantt_chart_task_id" value="" id="modal-task-id">

            <div class="mb-4">
                <label for="Task name" class="block mb-2 text-sm font-medium">Task Name</label>
                <input type="text" required name="task_name" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" value="" id="modal-task-name" />

            </div>
            <div class="mb-4">
                <label for="Start Date" class="block mb-2 text-sm font-medium">Start Date</label>
                <input required name="start_date" type="date" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" value="<?php echo $start_date; ?>" id="modal-start-date" />
            </div>
            <div class="mb-4">
                <label for="End Date" class="block mb-2 text-sm font-medium">End Date</label>
                <input required name="end_date" type="date" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" value="<?php echo $end_date; ?>" id="modal-end-date" />
            </div>


            <div class="flex justify-end">
                <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover-bg-blue-700 focus-ring-blue-800">
                    Update
                </button>
            </div>
        </form>

        <button id="close-modal-button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    </div>
</div>


<div id="gantt-content" class="tabcontent">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
        <table class="w-full text-sm text-left text-gray-400">
            <caption class="py-3 px-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                Gantt Chart
                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Browse a list of Flowbite products designed to help you work and play, stay organized, get answers, keep in touch, grow your business, and more.</p>
            </caption>
            <?php
            // Assuming you have a database connection

            // Get student_id from the previous page using GET method
            $student_id = isset($_GET['st_id']) ? (int)$_GET['st_id'] : 0; // Ensure it's an integer

            if ($student_id > 0) {
                // Modify your SQL query to retrieve Gantt chart data for the specified student
                $sql = "SELECT gct.*, gctt.* FROM gantt_chart AS gct JOIN gantt_chart_task AS gctt ON gct.gantt_chart_id = gctt.gantt_chart_id WHERE gct.student_id = $student_id ORDER BY gctt.start_date ASC"; // Order by start_date in ascending order

                $result = mysqli_query($conn, $sql);

                if ($result) {
                    // Output the Gantt chart data
            ?>
                    <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                        <tr>
                            <th scope="col" class="w-1/9 px-6 py-3 text-center"> <!-- Change w-1/5 to w-1/8 -->
                                delete
                            </th>
                            <th scope="col" class="w-2/5 px-6 py-3 ">
                                Task Name
                            </th>
                            <th scope="col" class="w-1/5 px-6 py-3 ">
                                Start Date
                            </th>
                            <th scope="col" class="w-1/5 px-6 py-3 ">
                                End Date
                            </th>
                            <th scope="col" class="w-1/6 px-6 py-3 text-center">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $start_date = date("d F Y ", strtotime($row['start_date']));
                        $end_date = date("d F Y ", strtotime($row['end_date']));
                    ?>
                        <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900' data-task-id="<?php echo $row['gantt_chart_task_id']; ?>" data-task-name="<?php echo $row['task_name']; ?>" data-start-date="<?php echo $start_date; ?>" data-end-date="<?php echo $end_date; ?>">
                            <td class='px-6 py-4 text-center' data-task-id="<?php echo $row['gantt_chart_task_id']; ?>">
                                <?php echo $row['gantt_chart_task_id']; ?>
                            </td>
                            <td class='px-6 py-4 font-medium whitespace-nowrap text-white' data-task-name="<?php echo $row['task_name']; ?>">
                                <?php echo $row['task_name']; ?>
                            </td>
                            <td class='px-6 py-4' data-start-date="<?php echo $start_date; ?>">
                                <?php echo $start_date; ?>
                            </td>
                            <td class='px-6 py-4' data-end-date="<?php echo $end_date; ?>">
                                <?php echo $end_date; ?>
                            </td>
                            <td class='px-6 py-4 text-center'>
                                <a href="#" class="user_edit font-medium text-blue-600 dark:text-blue-500 hover:underline edit-link" data-task-id="<?php echo $row['gantt_chart_task_id']; ?>">Edit <?php echo $row['gantt_chart_task_id']; ?></a>
                            </td>
                        </tr>



                    <?php
                    }
                    ?>
        </table>


<?php
                } else {
                    echo "Error: " . mysqli_error($conn);
                }

                mysqli_close($conn);
            } else {
                echo "Invalid student ID.";
            }
?>
    </div>
</div>


<script>
    const editButtons = document.querySelectorAll(".user_edit");
    const modal = document.getElementById("modal");
    const closeModalButton = document.getElementById("close-modal-button");

    editButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            event.preventDefault(); // Prevent the default link behavior

            // Get the task details from the data attributes of the clicked button's parent row
            const row = button.closest('tr');
            const taskId = row.getAttribute("data-task-id");
            const taskName = row.getAttribute("data-task-name");
            const startDate = row.getAttribute("data-start-date");
            const endDate = row.getAttribute("data-end-date");

            // Update the modal form fields with the task details
            document.getElementById("modal-task-id").value = taskId;
            document.getElementById("modal-task-name").value = taskName;
            document.getElementById("modal-start-date").value = startDate;
            document.getElementById("modal-end-date").value = endDate;

            // Display the modal
            modal.style.transform = "scale(1)";
            modal.style.opacity = "1";
        });
    });

    // Close the modal when the close button is clicked
    closeModalButton.addEventListener("click", function() {
        modal.style.transform = "scale(0)";
        modal.style.opacity = "0";
    });
</script>