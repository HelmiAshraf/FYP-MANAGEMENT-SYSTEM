<?php
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: fypl_login.php");
    exit();
}
include 'includes/sidebar.php';

?>

<h1 class="text-2xl font-bold mb-4">Proposal</h1>
<?php echo $_SESSION['user_id']; ?>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="p-4 bg-white dark:bg-gray-900">
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
                    proposal name
                </th>
                <th scope="col" class="px-6 py-3">
                    date create
                </th>
                <th scope="col" class="px-6 py-3">
                    submit due
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT
                s.sv_id,
                s.sv_name,
                s.sv_email,
                s.sv_phnum,
                s.sv_expertise,
                s.sv_image,
                s.sv_status,
                s.sv_quota,
                COUNT(v.supervisor_id) AS current_students
            FROM
                supervisor s
            LEFT JOIN
                supervise v ON s.sv_id = v.supervisor_id
            GROUP BY
                s.sv_id, s.sv_name, s.sv_email, s.sv_phnum, s.sv_expertise, s.sv_image, s.sv_status, s.sv_quota
            HAVING
                current_students <= s.sv_quota;";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600'>";

                    // Display the image using an <img> tag
                    echo "<td class='px-6 py-4'><img class='h-12 w-12' src='function/path_to_image_sv.php?sv_id=" . $row["sv_id"] . "' alt='Supervisor Image' /></td>";

                    echo "<td scope='row' class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white'>" . $row["sv_name"] . "</td>";
                    echo "<td class='px-6 py-4'>" . $row["sv_phnum"] . "</td>";
                    echo "<td class='px-6 py-4'>" . $row["sv_email"] . "</td>";
                    echo "<td class='px-6 py-4'>" . $row["sv_expertise"] . "</td>";

                    // Display current_students and sv_quota as a fraction
                    echo "<td class='px-6 py-4'>" . $row["current_students"] . " / " . $row["sv_quota"] . "</td>";

                    echo "<td class='px-6 py-4'>
                    <a href='st_propose_project.php?sv_id=" . $row["sv_id"] . "' class='font-medium text-blue-600 dark:text-blue-500 hover:underline'>Choose</a>
                </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='py-2 px-4 text-center'>No supervisors found.</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>



    </table>
</div>


<!-- content end -->
</div>
</div>
</div>
</body>

</html>