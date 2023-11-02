<?php

include 'includes/sv_sidebar.php';

?>
<h1 class="text-4xl font-bold mb-4">Your Supervisee</h1>
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
                <th scope="col" class="w-3/5 px-6 py-3">
                    Student Name
                </th>
                <th scope="col" class="w-1/5 px-6 py-3 text-center">
                    Group
                </th>
                <th scope="col" class="w-1/5 px-6 py-3 text-center">
                    View Progress
                </th>
            </tr>
        </thead>
        <?php
        // Replace {supervisor_id} with the actual ID of the logged-in supervisor
        $supervisor_id = $_SESSION["user_id"];

        // SQL query to retrieve students supervised by the specified supervisor
        $sql = "SELECT s.st_id, s.st_name, s.st_email, s.st_phnum, s.st_part, s.st_image, s.st_status, s.st_class 
    FROM student s INNER JOIN supervise su ON s.st_id = su.student_id WHERE su.supervisor_id = 2015900028";

        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
        ?>

            <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>
                    <a href="sv_supervisee_detail.php?st_id=<?php echo $row['st_id']; ?>" class='font-medium text-blue-500 hover:underline hover:text-blue-400'>
                        <?php echo $row['st_name']; ?>
                    </a>
                </td>
       
                <td class='px-6 py-4 text-center'>student/st_proposal_details.php></td>
                <td class='px-6 py-4 text-center'>
                    <a href="sv_supervisee_detail.php?st_id=<?php echo $row['st_id']; ?>" class='text-blue-500 hover:underline hover:text-blue-400'>
                        View
                    </a>
                </td>

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