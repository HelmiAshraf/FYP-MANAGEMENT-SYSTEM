<?php
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'includes/sv_sidebar.php';

$user_id = $_SESSION['user_id']; // You can change this based on how you store the user ID in your session

$sql = "SELECT
    sv_id,
    sv_name,
    sv_email,
    sv_expertise,
    TO_BASE64(sv_image) AS sv_image_base64,
    sv_phnum
FROM
    supervisor
WHERE
    sv_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Assuming user_id is an integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch and display the supervisor data
    while ($row = $result->fetch_assoc()) {
?>

        <h1 class="text-4xl font-bold mb-4"> My Profile</h1>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
            <div class="px-3 py-2 bg-gray-900 flex justify-between">
                <div class="relative flex items-center justify-center">
                    <h2 class="text-2xl font-bold text-gray-900 whitespace-nowrap text-white">My Details</h2>
                </div>
                <div class="flex items-center justify-center">
                    <button type="submit" name="reject" value="reject" class="text-white font-medium rounded-lg text-sm px-3.5 py-1.5  bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-blue-800">Edit
                        Profile</button>
                </div>
            </div>

            <div>
                <table class="table-fixed w-full text-sm text-left text-gray-400">
                    <tbody class="">
                        <tr class='bg-gray-800 :border-gray-700'>
                            <td scope='row' rowspan="4" class=' text-center w-1/4 px-2 py-3  '>
                                <img class='mx-auto w-16 md:w-32 lg:w-48' src='data:image/jpeg;base64,<?php echo $row["sv_image_base64"]; ?>' alt='Supervisor Image' />
                            </td>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Name</p><?php echo $row['sv_name']; ?>
                            </td>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Email</p><?php echo $row['sv_email']; ?>
                            </td>
                        </tr>
                        <tr class='bg-gray-800 border-gray-700'>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Supervisor ID</p><?php echo $row['sv_id']; ?>
                            </td>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Phone Number</p><?php echo $row['sv_phnum']; ?>
                            </td>
                        </tr>
                        <tr class=' bg-gray-800  border-gray-700 '>
                            <td rowspan="1" colspan="2" class='px-6 py-2' style='vertical-align: top;'>
                                <p class="font-bold text-white">Expertise</p> <?php echo $row['sv_expertise']; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
<?php
    }
} else {
    echo "No supervisor found for the given user ID.";
}

$stmt->close();
$conn->close();
?>

<!-- content end -->
</div>
</div>
</div>
</body>

</html