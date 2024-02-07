<?php

include 'includes/sv_sidebar.php';

?>

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
                        <a href="sv_assignment.php" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                            Assignment
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                            Assignment Details
                        </a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>
<div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

<h1 class="text-3xl font-bold mb-4">Student Proposal</h1>
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
                    File Name
                </th>
                <th scope="col" class="w-1/5 px-6 py-3 text-center">
                    Proposal Name
                </th>
                <th scope="col" class="w-1/5 px-6 py-3 text-center">
                    Date Submit
                </th>
                <th scope="col" class="w-1/5 px-6 py-3 text-center">
                    Comment
                </th>
            </tr>
        </thead>
        <?php
        if (isset($_GET['st_id'])) {
            $supervisor_id = $_SESSION["user_id"];
            $st_id = $_GET["st_id"];

            // SQL query to retrieve student proposal submissions
            $sql = "SELECT f.file_id, f.file_name, p.proposal_title, ps.submissiondate, ps.proposal_submission_id
            FROM proposal p
            JOIN proposal_submission ps ON p.proposal_id = ps.proposal_id
            JOIN file f ON ps.proposal_submission_id = f.type_id AND f.file_type = 'proposal_submission'
            WHERE ps.student_id = $st_id";

            $result = mysqli_query($conn, $sql);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
        ?>
                    <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                        <td class='px-6 py-4 font-medium whitespace-nowrap text-white'>
                            <a href="function/view_pdf.php?file_id=<?php echo $row['file_id']; ?>" target="_blank" class='text-blue-500 hover:underline hover:text-blue-400'>
                                <?php echo $row['file_name']; ?>
                            </a>
                        </td>
                        <td class='px-6 py-4 text-center'>
                            <?php echo $row['proposal_title']; ?>
                        </td>
                        <td class='px-6 py-4 text-center'>
                            <?php echo $row['submissiondate']; ?>
                        </td>
                        <td class='px-6 py-4 text-center'>
                            <a href="sv_proposal_details.php?st_id=<?php echo $row['proposal_submission_id']; ?>" class='text-blue-500 hover:underline hover:text-blue-400'>
                                Add Comment
                            </a>
                        </td>
                    </tr>
        <?php
                }
                mysqli_free_result($result);
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Invalid st_id parameter";
        }
        mysqli_close($conn);
        ?>



</div>



<!-- content end -->
</div>
</div>
</div>
</body>

</html>