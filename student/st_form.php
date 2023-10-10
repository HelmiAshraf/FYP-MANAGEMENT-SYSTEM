<?php
include 'includes/st_sidebar.php';

$user_id = $_SESSION["user_id"]; // Assuming you have stored the logged-in user's ID in a session variable

// Corrected SQL query to fetch form details
$sql = "SELECT
    form_id,
    Form_title,
    form_date_create,
    form_date_due
FROM
    form;"; // Adjust this query to fetch forms associated with the user

$result = mysqli_query($conn, $sql);
?>

<h1 class="text-4xl font-bold mb-4">Form</h1>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
    <div class="p-4 bg-white dark:bg-gray-900 flex justify-between">
        <!-- Left side with search input -->
        <div class="relative">
            <label for="table-search" class="sr-only">Search</label>
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input type="text" id="table-search" class="block p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for items">
        </div>
    </div>

    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Form Name
                </th>
                <th scope="col" class="px-6 py-3">
                    Date Created
                </th>
                <th scope="col" class="px-6 py-3">
                    Due Date
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600'>
                    <td scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white'><?php echo $row['Form_title']; ?></td>
                    <td class='px-6 py-4'><?php echo $row['form_date_create']; ?></td>
                    <td class='px-6 py-4'><?php echo $row['form_date_due']; ?></td>
                    <td class='px-6 py-4'>
                        <a href='st_form_details.php?form_id=<?php echo $row["form_id"]; ?>' class='font-medium text-blue-600 dark:text-blue-500 hover:underline'>View</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>

</html>

<?php
mysqli_close($conn);
?>




<!-- content end -->
</div>
</div>
</div>
</body>

</html>