<div id="proposal-content" class="tabcontent active">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
        <table class="w-full text-sm text-left text-gray-400">
            <caption class="py-3 px-5 text-lg font-semibold text-left text-white bg-gray-800">
                Assignment
                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Browse a list of Flowbite products designed to help you work and play, stay organized, get answers, keep in touch, grow your business, and more.</p>
            </caption>
            <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="w-3/5 px-6 py-3">
                        Assignment Name
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
            $sql = "SELECT * FROM assignment";

            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                // Format ass_date_create, ass_date_due, and ass_time_due
                $ass_date_create_formatted = date("d-m-Y h:i A", strtotime($row['ass_date_create']));
                $ass_date_due_formatted = date("d-m-Y h:i A", strtotime($row['ass_date_due']));
            ?>
                <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                    <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>
                        <a href="st_assignment_details.php?ass_id=<?php echo $row['ass_id']; ?>&ass_title=<?php echo urlencode($row['ass_title']); ?>" class='font-medium text-blue-500 hover:underline hover:text-blue-400'><?php echo $row['ass_title']; ?></a>
                    </td>
                    <td class='px-6 py-4 '><?php echo $ass_date_due_formatted; ?></td>
                    <td class='px-6 py-4 '><?php echo $ass_date_due_formatted; ?></td>
                </tr>
            <?php
            }

            ?>
        </table>
    </div>
</div>