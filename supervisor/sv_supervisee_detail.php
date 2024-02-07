<?php

include 'includes/sv_sidebar.php';

$student_id = $_GET['st_id'];
$gantt_chart_id = $_GET['gantt_chart_id'];

// Modify your SQL query to retrieve Gantt chart data for the specified student
$sql = "SELECT gct.*, gctt.* FROM gantt_chart AS gct JOIN gantt_chart_task AS gctt ON gct.gantt_chart_id = gctt.gantt_chart_id WHERE gct.student_id = ? ORDER BY gctt.start_date ASC";

// Assuming $student_id is the variable you want to bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);  // Assuming $student_id is an integer, change the "i" if it's a different type

$stmt->execute();

$result = $stmt->get_result();

if ($result) {
?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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
                                Supervise Details
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

    <!-- <div id="create_modal" class="modal fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto transform scale-0 opacity-0">
        <div class="modal-overlay bg-black bg-opacity-70 fixed inset-0"></div>
        <div class="bg-white w-96 rounded-lg shadow-lg p-6 transform scale-100 opacity-100 transition-transform duration-300">
            <h2 class="text-2xl font-bold mb-4">New Task</h2>
            <form action="function/ganttchart_form.php" method="POST">
                <input type="text" name="gantt_chart_id" value="<?php echo $gantt_chart_id; ?>">
                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                <input type="hidden" name="tag" value="3">
                <div class="mb-4">
                    <label for="task_name" class="block mb-2 text-sm font-medium">Task Name</label>
                    <input type="text" required name="task_name" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" id="modal-task-name">
                </div>
                <div class="mb-4">
                    <label for="start_date" class="block mb-2 text-sm font-medium">Development Time</label>
                    <div class="flex items-center">
                        <div class="relative">
                            <input required onkeydown="return false" name="start_date" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" id="modal-start-date">
                        </div>
                        <span class="mx-4 text-gray-500">to</span>
                        <div class="relative">
                            <input required onkeydown="return false" name="end_date" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" id="modal-end-date">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">
                        Create
                    </button>
                </div>
            </form>
            <button id="close-edit-modal-button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    </div> -->

    <div id="create_modal" class="modal fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto transform scale-0 opacity-0">
        <!-- ... (Your existing modal content) ... -->
        <div class="fixed inset-0 bg-black bg-opacity-60"></div>
        <div class="bg-white w-1/2 rounded-lg shadow-lg p-6 transform scale-100 opacity-100 transition-transform duration-300">
            <h2 class="text-2xl font-bold mb-4">New Task</h2>
            <form id="taskForm" action="function/ganttchart_form.php" method="POST">
                <!-- Initial Input fields for form data -->
                <input type="hidden" name="gantt_chart_id" value="<?php echo $gantt_chart_id; ?>">
                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                <input type="hidden" name="tag" value="3">

                <!-- Container to hold dynamically added input fields -->
                <div id="dynamicFieldsContainer" class="flex flex-col space-y-2">
                    <!-- First Task input fields -->
                    <div class="flex space-x-2 ">
                        <!-- No "Remove Task" button for the first input -->
                        <div class="flex flex-row space-x-2  w-full"> <!-- Set the container width to full -->
                            <div class="flex flex-col flex-1"> <!-- Make "Task Name" input take full width -->
                                <label for="task_name">Task Name</label>
                                <input type="text" name="task_name[0]" class="border text-sm rounded-lg p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 w-full" required placeholder="Task Name">
                            </div>

                            <div class="flex flex-col">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date[0]" id="start_date" class="border text-sm rounded-lg p-2.5 bg-gray-100 border-gray-300" required>
                            </div>

                            <div class="flex flex-col">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date[0]" id="end_date" class="border text-sm rounded-lg p-2.5 bg-gray-100 border-gray-300" required>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Plus and Add Task Buttons -->
                <div class="flex justify-between mt-4">
                    <button type="button" id="addTaskButton" class="text-white font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700">+ Add Task</button>
                    <button type="submit" class="text-white font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700">
                        Create
                    </button>
                </div>
            </form>
            <button id="close-edit-modal-button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    </div>





    <?php



    ?>

    <div class="flex justify-between items-center">
        <div class="relative "> <!-- Updated this line -->
            <h1 class="text-3xl font-bold ">Gantt Chart Details</h1>
        </div>
        <div>
            <a href="#" class="create_modal text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 focus:outline-none block">Create task</a>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-2">
        <table class="w-full text-sm text-left text-gray-400">
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
            </tbody>
            <?php
            if ($result->num_rows > 0) {

                while ($row = mysqli_fetch_assoc($result)) {
                    $start_date = date("Y-m-d", strtotime($row['start_date']));
                    $end_date = date("Y-m-d", strtotime($row['end_date']));
                    $formattStartDate = date("d/m/Y", strtotime($row['start_date']));
                    $formattEndDate = date("d/m/Y", strtotime($row['end_date']));
                    $gantt_chart_task_id = $row["gantt_chart_task_id"];
                    $gantt_chart_id = $row["gantt_chart_id"];
                    $task_name = $row["task_name"];
            ?>
                    <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                        <td class='px-6 py-4 text-center' onclick=' return confirm("Are you sure you want to delete this task?")'>
                            <form action='function/ganttchart_form.php' method='POST'>
                                <input type='hidden' name='gantt_chart_task_id' value='<?php echo $gantt_chart_task_id ?>'>
                                <input type='hidden' name='gantt_chart_id' value='<?php echo $gantt_chart_id ?>'>
                                <input type='hidden' name='student_id' value='<?php echo $student_id ?>'>
                                <input type='hidden' name='tag' value='2'>
                                <button type='submit' class='font-medium text-blue-500 hover:text-blue-600'>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                        <td class='px-6 py-4 font-medium whitespace-nowrap text-white'>
                            <?php echo $task_name; ?>
                        </td>
                        <td class='px-6 py-4' data-start-date="<?php echo $start_date; ?>">
                            <?php echo $start_date; ?>
                        </td>
                        <td class='px-6 py-4' data-end-date="<?php echo $end_date; ?>">
                            <?php echo $end_date; ?>
                        </td>
                        <td class='px-6 py-4 text-center'>
                            <button data-modal-target="modal_<?php echo $gantt_chart_task_id; ?>" data-modal-toggle="modal_<?php echo $gantt_chart_task_id; ?>" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                                Edit
                            </button>
                        </td>
                    </tr>
                    <!-- Main modal -->
                    <div id="modal_<?php echo $gantt_chart_task_id; ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full ">
                        <!-- Modal content -->

                        <div class="relative bg-white rounded-lg shadow ">
                            <!-- Modal header -->
                            <div class="flex items-center justify-between md:p-5 rounded">
                                <h3 class="text-lg font-semibold">
                                    Update Task
                                </h3>
                                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="modal_<?php echo $gantt_chart_task_id; ?>">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="bg-white w-96  px-6 transform scale-100 opacity-100 transition-transform duration-300 rounded-b">
                                <form action="function/ganttchart_form.php" method="POST">
                                    <input type="hidden" name="gantt_chart_task_id" value="<?php echo $gantt_chart_task_id; ?>" id="modal-task-id">
                                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                                    <input type="hidden" name="tag" value="1">

                                    <div class="mb-4">
                                        <label for="task_name" class="block mb-2 text-sm font-medium">Task Name</label>
                                        <input type="text" required name="task_name" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" value="<?php echo $task_name; ?>" id="modal-task-name">
                                    </div>
                                    <div class="mb-4">
                                        <label for="start_date" class="block mb-2 text-sm font-medium">Development Time</label>
                                        <div class="flex items-center">
                                            <div class="relative">
                                                <input onkeydown="return false" name="start_date" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $start_date; ?>" id="modal-start-date">
                                            </div>
                                            <span class="mx-4 text-gray-500">to</span>
                                            <div class="relative">
                                                <input onkeydown="return false" name="end_date" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $end_date; ?>" id="modal-end-date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-end mb-4">
                                        <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover-bg-blue-700 focus:ring-blue-800 ">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
    </div>

<?php
                }
            } else {
                // No tasks found
                echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                echo "<td colspan='5' class='px-6 py-4 text-center'>No tasks create from you for your supervise</td>";
                echo "</tr>";
            }
?>
</tbody>
</table>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
<?php
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);

?>
</div>
</div>

<!-- Add this script at the end of your HTML, just before the closing </body> tag -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get references to the modal, the "Create task" button, and the close button inside the modal
        var modal = document.getElementById('create_modal');
        var createTaskButton = document.querySelector('.create_modal');
        var closeModalButton = document.getElementById('close-edit-modal-button');

        // Function to toggle the modal's visibility
        function toggleModal() {
            modal.classList.toggle('scale-0');
            modal.classList.toggle('opacity-0');
        }

        // Event listener for the "Create task" button
        createTaskButton.addEventListener('click', function(event) {
            event.preventDefault();
            // Call the function to toggle the modal
            toggleModal();
        });

        // Event listener for the close button inside the modal
        closeModalButton.addEventListener('click', function() {
            // Call the function to toggle the modalx
            toggleModal();
        });
    });
</script>




<script>
    document.addEventListener("DOMContentLoaded", function() {
        const taskForm = document.getElementById("taskForm");
        const addTaskButton = document.getElementById("addTaskButton");
        const dynamicFieldsContainer = document.getElementById("dynamicFieldsContainer");

        let taskCounter = 0; // Initial task counter
        const maxTasks = 9; // Maximum number of tasks allowed

        // Event listener for the "Add Task" button
        addTaskButton.addEventListener("click", function() {
            if (taskCounter < maxTasks) {
                // Increment the counter when adding a new task
                taskCounter++;

                // Create a new container for each set of input fields
                const dynamicFields = document.createElement("div");
                dynamicFields.className = "flex space-x-2";

                // Create input fields
                const deleteButton = document.createElement("button");
                deleteButton.type = "button";
                deleteButton.className = "text-red-500 hover:text-red-400";
                deleteButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mx-auto">
                    <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                </svg>`;

                const taskNameInput = document.createElement("input");
                taskNameInput.type = "text";
                taskNameInput.name = `task_name[${taskCounter}]`;
                taskNameInput.placeholder = "Task Name";
                taskNameInput.className = "border text-sm rounded-lg p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 w-full flex-1";
                taskNameInput.required = true;

                const startDateInput = document.createElement("input");
                startDateInput.type = "date";
                startDateInput.name = `start_date[${taskCounter}]`;
                startDateInput.className = "border text-sm rounded-lg p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400";
                startDateInput.required = true;

                const endDateInput = document.createElement("input");
                endDateInput.type = "date";
                endDateInput.name = `end_date[${taskCounter}]`;
                endDateInput.className = "border text-sm rounded-lg p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400";
                endDateInput.required = true;

                // Append new input fields to the container
                dynamicFields.appendChild(deleteButton);
                dynamicFields.appendChild(taskNameInput);
                dynamicFields.appendChild(startDateInput);
                dynamicFields.appendChild(endDateInput);

                // Append the container to the main form
                dynamicFieldsContainer.appendChild(dynamicFields);

                // Event listener for the "remove" button
                deleteButton.addEventListener("click", function() {
                    dynamicFieldsContainer.removeChild(dynamicFields);
                    // Decrement the counter when removing a task
                    taskCounter--;

                    // If there's only one task left, remove the "Remove Task" button
                    if (taskCounter === 1) {
                        dynamicFieldsContainer.removeChild(dynamicFieldsContainer.firstChild);
                    }
                });
            } else {
                alert("Maximum number of tasks reached 10.");
            }
        });
    });
</script>




<!-- <div class="mb-4 border-b-2 border-gray-300 ">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
        <li class="me-2" role="presentation">
            <button class="inline-block p-4 border-b-1 border-gray-300  rounded-t-lg" id="Gantt-tab" data-tabs-target="#Gantt" type="button" role="tab" aria-controls="Gantt" aria-selected="false">Gantt Chart</button>
        </li>
        <li class="me-2" role="presentation">
            <button class="inline-block p-4 border-gray-300  rounded-t-lg hover:text-gray-600 hover:border-gray-400 " id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">Assignment</button>
        </li>
        <li class="me-2" role="presentation">
            <button class="inline-block p-4 border-gray-300  rounded-t-lg hover:text-gray-600 hover:border-gray-400 " id="Document-tab" data-tabs-target="#Document" type="button" role="tab" aria-controls="Document" aria-selected="false">Document</button>
        </li>
        <li class="me-2" role="presentation">
            <button class="inline-block p-4 border-gray-300  rounded-t-lg hover:text-gray-600 hover:border-gray-400 " id="Task-tab" data-tabs-target="#Task" type="button" role="tab" aria-controls="Task" aria-selected="false">Task</button>
        </li>
    </ul>
</div> -->

<!-- <div id="default-tab-content">
    <div class="hidden" id="Task" role="Task" aria-labelledby="Task-tab">

    </div>
    <div class="hidden " id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
  
    </div>
    <div class="hidden " id="Document" role="tabpanel" aria-labelledby="Document-tab">

    </div>
    <div class="hidden " id="Gantt" role="tabpanel" aria-labelledby="Gantt-tab">
    
    </div>

</div> -->





<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script> -->

<!-- content end -->
</div>
</div>
</div>
</body>

</html>