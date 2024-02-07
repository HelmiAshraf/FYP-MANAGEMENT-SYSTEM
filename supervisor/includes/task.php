<div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
    <table class="w-full text-sm text-left text-gray-400">
        <caption class="py-3 px-5 text-lg font-semibold text-left text-white bg-gray-800">
            Task
            <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Browse a list of Flowbite products designed to help you work and play, stay organized, get answers, keep in touch, grow your business, and more.</p>
        </caption>
        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
            <tr>
                <th scope="col" class="w-3/5 px-6 py-3">
                    Task Name
                </th>
                <th scope="col" class="w-1/5 px-6 py-3 ">
                    Submit Due
                </th>
                <th scope="col" class="w-1/5 px-6 py-3 ">
                    Status
                </th>
            </tr>
        </thead>
        <?php
        $sql = "SELECT * FROM task";

        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            // Format taskdate_create, taskdate_due, and tasktime_due
            $taskdate_create_formatted = date("d-m-Y h:i A", strtotime($row['task_date_create']));
            $taskdate_due_formatted = date("d-m-Y h:i A", strtotime($row['task_date_due']));
        ?>
            <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>

                <td class='px-6 py-4 '><?php echo $row['task_title']; ?></td>
                <td class='px-6 py-4 '><?php echo $taskdate_due_formatted; ?></td>

                <td class='px-6 py-4 '><?php echo $taskdate_due_formatted; ?></td>
            </tr>
        <?php
        }

        ?>
    </table>
</div>