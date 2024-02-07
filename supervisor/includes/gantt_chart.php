<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<?php
$student_id = isset($_GET['st_id']) ? (int)$_GET['st_id'] : 0; // Ensure it's an integer

// Modify your SQL query to retrieve Gantt chart data for the specified student
$sql = "SELECT gct.*, gctt.* FROM gantt_chart AS gct JOIN gantt_chart_task AS gctt ON gct.gantt_chart_id = gctt.gantt_chart_id WHERE gct.student_id = $student_id ORDER BY gctt.start_date ASC"; // Order by start_date in ascending order

$result = mysqli_query($conn, $sql);

if ($result) {

?>

    <div id="proposal-content" class="tabcontent active">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
            <table class="w-full text-sm text-left text-gray-400">
                <div>
                    <caption class="py-3 px-5 text-lg font-semibold text-left text-white bg-gray-800">
                        Gantt Chart
                        <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Browse a list of Flowbite products designed to help you work and play, stay organized, get answers, keep in touch, grow your business, and more.</p>
                    </caption>
                </div>
                <div>

                </div>
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
                if ($result->num_rows > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {
                        $start_date = date("Y-m-d", strtotime($row['start_date']));
                        $end_date = date("Y-m-d", strtotime($row['end_date']));
                        $formattStartDate = date("d/m/Y", strtotime($row['start_date']));
                        $formattEndDate = date("d/m/Y", strtotime($row['end_date']));
                        $gantt_chart_task_id = $row["gantt_chart_task_id"];
                        $task_name = $row["task_name"];
                ?>
                        <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td class='px-6 py-4 text-center' onclick=' return confirm("Are you sure you want to delete this task?")'>
                                <form action='function/ganttchart_task.php' method='POST'>
                                    <input type='hidden' name='gantt_chart_task_id' value='<?php echo $gantt_chart_task_id ?>'>
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
                                <button data-modal-target="modal_<?php echo $gantt_chart_task_id; ?>" data-modal-toggle="modal_<?php echo $gantt_chart_task_id; ?>" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    Edit
                                </button>
                            </td>
                        </tr>


                        <div id="modal_<?php echo $gantt_chart_task_id; ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-md max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <!-- Modal header -->
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-600">
                                        <h3 class="text-lg font-semibold text-white">
                                            Tracking Number
                                        </h3>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modal_<?php echo $gantt_chart_task_id; ?>">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-4 md:p-5">
                                        <form action="function/ganttchart_task.php" method="POST">
                                            <input type="hidden" name="gantt_chart_task_id" value="" id="modal-task-id">
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
                                                        <input name="start_date" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $start_date; ?>" id="modal-start-date">
                                                    </div>
                                                    <span class="mx-4 text-gray-500">to</span>
                                                    <div class="relative">
                                                        <input name="end_date" type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $end_date; ?>" id="modal-end-date">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex justify-end">
                                                <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover-bg-blue-700 focus:ring-blue-800">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            // Get the button for the request modal
                            const Button = document.querySelector('[data-modal-toggle="modal_<?php echo $gantt_chart_task_id; ?>"]');
                            const Modal = document.getElementById('modal_<?php echo $gantt_chart_task_id; ?>');
                            const CloseButton = Modal.querySelector('[data-modal-hide="modal_<?php echo $gantt_chart_task_id; ?>"]');

                            const openModal = () => {
                                Modal.classList.remove('hidden');
                                Modal.setAttribute('aria-hidden', 'false');
                                // Additional actions for the request modal open state
                            };

                            const closeModal = () => {
                                Modal.classList.add('hidden');
                                Modal.setAttribute('aria-hidden', 'true');
                                // Additional actions for the request modal close state
                            };

                            Button.addEventListener('click', openModal);
                            CloseButton.addEventListener('click', closeModal);
                        </script>





                <?php
                    }
                } else {
                    // No tasks found
                    echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                    echo "<td colspan='5' class='px-6 py-4 text-center'>No tasks create from you for your supervise</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        <?php
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);

        ?>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>