<?php
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'includes/sidebar.php';


include 'db.php'; // Include your database connection file

$user_id = $_SESSION["user_id"]; // Assuming you have stored the logged-in user's ID in a session variable

$sql = "SELECT
            s.st_id,
            s.st_name AS student_name,
            p.project_title AS project_title
        FROM
            student s
        JOIN
            project p ON s.st_id = p.student_id
        WHERE
            s.st_id IN (SELECT student_id FROM supervise)";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Project List</title>
    <!-- Add your CSS file here -->
</head>

<body>
    <h1 class="text-4xl font-bold mb-4">Students</h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="p-4 bg-white dark:bg-gray-900">
            <!-- Search input -->
            <label for="table-search" class="sr-only">Search</label>
            <div class="relative mt-1">
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
                        Student Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Project Title
                    </th>
                    <th scope="col" class="px-6 py-3">
                        student details
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600'>
                        <td scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white'><?php echo $row['student_name']; ?></td>
                        <td class='px-6 py-4'><?php echo $row['project_title']; ?></td>
                        <td class='px-6 py-4'>
                            <a href='fypl_student_details.php?st_id=<?php echo $row["st_id"];?>' class='font-medium text-blue-600 dark:text-blue-500 hover:underline'>View</a>
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