<div id="form-content" class="tabcontent">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
        <table class="w-full text-sm text-left text-gray-400">
            <caption class="py-3 px-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                Form
                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Browse a list of Flowbite products designed to help you work and play, stay organized, get answers, keep in touch, grow your business, and more.</p>
            </caption>
            <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="w-4/5 px-6 py-3">
                        Task Name
                    </th>
                    <th scope="col" class="w-1/5 px-6 py-3">
                        Start Date
                    </th>
                    <th scope="col" class="w-1/5 px-6 py-3">
                        End Date
                    </th>
                </tr>
            </thead>
            <?php
            // Retrieve Gantt chart tasks related to the student
            $student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
            if ($student_id > 0) {
                $sql = "SELECT * FROM ganttchart_task 
                INNER JOIN proposal ON ganttchart_task.proposal_id = proposal.proposal_id 
                WHERE proposal.student_id = $student_id";

                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    // Format task-related date fields
                    $taskName = $row['task_name'];
                    $startDate = date("d-m-Y h:i A", strtotime($row['start_date']));
                    $endDate = date("d-m-Y h:i A", strtotime($row['end_date']));

                    // Display task details in the table
                    echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                    echo "<td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>";
                    echo "<a href='st_proposal_details.php?proposal_id={$row['proposal_id']}&proposal_title=" . urlencode($row['proposal_title']) . "' class='font-medium text-blue-500 hover:underline hover:text-blue-400'>$taskName</a>";
                    echo "</td>";
                    echo "<td class='px-6 py-4'>$startDate</td>";
                    echo "<td class='px-6 py-4'>$endDate</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
    </div>
</div>