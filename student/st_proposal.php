<?php
include 'includes/st_sidebar.php';

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
    </div>

    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
            <tr>
                <th scope="col" class="w-4/5 px-6 py-3">
                    Proposal Name
                </th>
                <th scope="col" class="w-1/5 px-6 py-3 ">
                    Submit Due
                </th>
            </tr>
        </thead>
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
                    <a href="st_proposal_details.php?proposal_id=<?php echo $row['proposal_id']; ?>&proposal_title=<?php echo urlencode($row['proposal_title']); ?>" class='font-medium text-blue-500 hover:underline hover:text-blue-400'><?php echo $row['proposal_title']; ?></a>
                </td>

                <td class='px-6 py-4 '><?php echo $proposal_date_due_formatted; ?></td>
            </tr>
        <?php
        }
        mysqli_close($conn);
        ?>

    </table>
</div>



<!-- content end -->
</div>
</div>
</div>
</body>

</html>