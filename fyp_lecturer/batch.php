<?php
include 'includes/sidebar_batch.php';

$user_id = $_SESSION["user_id"]; // Assuming you have stored the logged-in user's ID in a session variable


?>

<div class="flex justify-between items-center">
    <div>
        <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: FYP Course Lecturer</p>
    </div>
    <div class="ml-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="insight.php" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-gray-600 hover:font-bold ">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Insight
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="#" class="ms-1 text-sm font-medium hover:text-gray-600 hover:font-bold md:ms-2 text-gray-400">
                            Batch
                        </a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</div>
<div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

<div class="flex justify-between items-center mb-4">
    <div class="relative mt-1">
        <h1 class="text-4xl font-bold">Batch</h1>
    </div>
    <div>
        <?php
        // Check the user's role
        $role_query = "SELECT fypl_role FROM fyp_lecturer WHERE fl_id = $user_id";
        $role_result = mysqli_query($conn, $role_query);
        $role_row = mysqli_fetch_assoc($role_result);

        // If the user is fypl_admin, show the "Update Batch" button
        if ($role_row['fypl_role'] == 'fypl_admin') {
        ?>
            <a href="batch_update.php" class="text-white font-medium rounded-lg text-sm px-5 py-3 bg-blue-600 hover:bg-blue-700">
                Update Batch
            </a>
        <?php
        }
        ?>
    </div>
</div>




<div class=" relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="py-3 px-4 bg-gray-900 flex justify-between items-center">
        <!-- Left side with search input -->
        <div class="relative">
            <h2 class="text-2xl text-white font-semibold text-center">
                Course: CSP600
            </h2>
        </div>
    </div>
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Batch
                </th>
                <th scope="col" class="px-6 py-3">
                    Start Batch
                </th>
                <th scope="col" class="px-6 py-3">
                    End Batch
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT *
            FROM batches 
            WHERE batch_category = 'CSP600' ";

            $result = mysqli_query($conn, $sql);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                        <td class='px-6 py-4 font-medium whitespace-nowrap text-white'><?php echo $row['batch_name']; ?></td>
                        <td class='px-6 py-4'>
                            <?php echo date("j F Y", strtotime($row['batch_start_date'])); ?>
                        </td>
                        <td class='px-6 py-4'>
                            <?php echo date("j F Y", strtotime($row['batch_end_date'])); ?>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
            ?>
        </tbody>
    </table>
</div>



<div class="mt-3 relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="py-3 px-4 bg-gray-900 flex justify-between items-center">
        <!-- Left side with search input -->
        <div class="relative">
            <h2 class="text-2xl text-white font-semibold text-center">
                Course: CSP650
            </h2>
        </div>
    </div>
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Batch
                </th>
                <th scope="col" class="px-6 py-3">
                    Start Batch
                </th>
                <th scope="col" class="px-6 py-3">
                    End Batch
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT *
            FROM batches 
            WHERE batch_category = 'CSP650' ";

            $result = mysqli_query($conn, $sql);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                        <td class='px-6 py-4 font-medium whitespace-nowrap text-white'><?php echo $row['batch_name']; ?></td>
                        <td class='px-6 py-4'>
                            <?php echo date("j F Y", strtotime($row['batch_start_date'])); ?>
                        </td>
                        <td class='px-6 py-4'>
                            <?php echo date("j F Y", strtotime($row['batch_end_date'])); ?>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
            ?>
        </tbody>
    </table>
</div>


<div class="mt-3 relative overflow-x-auto shadow-md sm:rounded-lg -lg">
    <div class="py-3 px-4 bg-gray-900 flex justify-between items-center">
        <!-- Left side with search input -->
        <div class="relative">
            <h2 class="text-2xl text-white font-semibold text-center">
                Graduated Batch
            </h2>
        </div>
    </div>
    <table class="w-full text-sm text-left text-gray-400">
        <thead class="text-xs uppercase bg-gray-700 text-gray-400">
            <tr class="">

                <th scope="col" class="px-6 py-3">
                    Batch
                </th>
                <th scope="col" class="px-6 py-3">
                    Start Batch
                </th>
                <th scope="col" class="px-6 py-3">
                    End Batch
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Corrected SQL query to fetch form details
            $sql = "SELECT *
            FROM batches 
            WHERE batch_category = 'Graduate' "; // Adjust this query to fetch forms associated with the user

            $result = mysqli_query($conn, $sql);

            // Check if the query was successful
            if (!$result) {
                // Handle the error, for example, log it or display an error message
                echo "Error: " . mysqli_error($conn);
            } else {
                // Initialize a counter variable

                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>

                        <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'><?php echo  $row['batch_name']; ?></td>
                        <td class='px-6 py-4'>
                            <?php
                            $formattedStartDate = date("j F Y", strtotime($row['batch_start_date']));
                            echo $formattedStartDate;
                            ?>
                        </td>
                        <td class='px-6 py-4'>
                            <?php
                            $formattedEndDate = date("j F Y", strtotime($row['batch_end_date']));
                            echo $formattedEndDate;
                            ?>
                        </td>
                    </tr>
            <?php

                }
            }
            ?>
        </tbody>
    </table>
</div>


<?php
mysqli_close($conn);
?>




<!-- content end -->
</div>
</div>
</div>
</body>

</html>