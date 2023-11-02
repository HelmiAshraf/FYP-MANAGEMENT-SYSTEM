<table class="w-full text-sm text-left text-gray-400">
    <thead class="text-xs uppercase bg-gray-700 text-gray-400">
        <tr>
            <th scope="col" class="w-1/9 px-6 py-3 text-center"> <!-- Change w-1/5 to w-1/8 -->
                delete
            </th>
            <th scope="col" class="w-2/5 px-6 py-3">
                Task Name
            </th>
            <th scope="col" class="w-1/5 px-6 py-3">
                Start Date
            </th>
            <th scope="col" class="w-1/5 px-6 py-3">
                End Date
            </th>
            <th scope="col" class="w-1/6 px-6 py-3 text-center">
                Action
            </th>
        </tr>
    </thead>
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        $start_date = date("d F Y", strtotime($row['start_date']));
        $end_date = date("d F Y", strtotime($row['end_date']));
        ?>
        <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
            <td class='px-6 py-4 text-center'>
                <?php echo $row['gantt_chart_task_id']; ?>
            </td>
            <td class='px-6 py-4 font-medium whitespace-nowrap text-white'>
                <?php echo $row['task_name']; ?>
            </td>
            <td class='px-6 py-4'>
                <?php echo $start_date; ?>
            </td>
            <td class='px-6 py-4 '>
                <?php echo $end_date; ?>
            </td>
            <td class='px-6 py-4 text-center'>
                <!-- Add a data attribute to store the gantt_chart_task_id -->
                <a href="#" class="user_edit font-medium text-blue-600 dark:text-blue-500 hover:underline edit-link" data-task-id="<?php echo $row['gantt_chart_task_id']; ?>">Edit <?php echo $row['gantt_chart_task_id']; ?></a>
            </td>
        </tr>
        <?php
    }
    ?>
</table>

<!-- Modal container -->
<div id="modal" class="modal">
    <div class="modal-content">
        <p id="modal-content">Gantt Chart Task ID: <span id="task-id"></span></p>
    </div>
</div>

<script>
    // JavaScript code to handle the button click event and pass the ID to the modal
    const editButtons = document.querySelectorAll(".user_edit");

    editButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent the default link behavior

            // Get the task ID from the data attribute
            const taskId = button.getAttribute("data-task-id");

            // Update the modal content with the retrieved ID
            document.getElementById("task-id").textContent = taskId;

            // Display the modal
            document.getElementById("modal").style.display = "block";
        });
    });
</script>
