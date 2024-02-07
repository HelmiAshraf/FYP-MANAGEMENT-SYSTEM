<?php
include 'includes/sidebar_batch.php';

$user_id = $_SESSION["user_id"]; // Assuming you have stored the logged-in user's ID in a session variable


if (isset($_POST['update_course'])) {
    $lecturer_id = $_POST['lecturer_id'];
    $new_course = $_POST['course'];

    $update_query = "UPDATE fyp_lecturer SET fypl_course = '$new_course' WHERE fl_id = $lecturer_id";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        echo "Course updated successfully.";
    } else {
        echo "Error updating course: " . mysqli_error($conn);
    }
}

?>


<h1 class="text-4xl font-bold">FYPL Lecturer Course</h1>

<div class="mt-3 relative overflow-x-auto shadow-md sm:rounded-lg">
    <?php
    // Check fypl_role
    $role_query = "SELECT fypl_role FROM fyp_lecturer WHERE fl_id = $user_id";
    $role_result = mysqli_query($conn, $role_query);
    $role_row = mysqli_fetch_assoc($role_result);

    if ($role_row['fypl_role'] == 'fypl_admin') {
    ?>
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        FYP Lecturer Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Course
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Update
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM fyp_lecturer ";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white'><?php echo $row['fl_name']; ?></td>
                            <td class='px-6 py-4'>
                                <form action="fypl_course.php" method="post">
                                    <select name="course" class="border text-sm rounded-lg block px-4 py-2 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500">
                                        <?php
                                        // Get the enum values from the database
                                        $enum_query = "SHOW COLUMNS FROM fyp_lecturer WHERE Field = 'fypl_course'";
                                        $enum_result = mysqli_query($conn, $enum_query);
                                        $enum_row = mysqli_fetch_assoc($enum_result);
                                        $enum_values = explode("','", preg_replace("/(enum|set)\('(.+?)'\)/", "\\2", $enum_row['Type']));

                                        // Dynamically generate dropdown options and select the current fypl_course
                                        foreach ($enum_values as $value) {
                                            $selected = ($row['fypl_course'] == $value) ? 'selected' : '';
                                            echo "<option value=\"$value\" $selected>$value</option>";
                                        }
                                        ?>
                                    </select>
                            </td>
                            <td class='px-6 py-4'>
                                <input type="hidden" name="lecturer_id" value="<?php echo $row['fl_id']; ?>">
                                <button type="submit" name="update_course" class="text-white font-medium rounded-lg text-sm px-4 py-2 bg-blue-600 hover:bg-blue-700">Update</button>
                                </form>
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
    <?php
    } else {
        // Display table with lecturer name and course
    ?>
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        FYP Lecturer Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Course
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT fl_name, fypl_course FROM fyp_lecturer ";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white'><?php echo $row['fl_name']; ?></td>
                            <td class='px-6 py-4'><?php echo $row['fypl_course']; ?></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
                ?>
            </tbody>
        </table>
    <?php
    }
    ?>
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