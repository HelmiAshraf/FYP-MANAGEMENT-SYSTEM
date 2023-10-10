<?php

include 'includes/st_sidebar.php';

$user_id = $_SESSION['user_id']; // Assuming you have stored the logged-in user's ID in a session variable

$sql = "SELECT
            s.st_name AS student_name,
            s.st_id AS student_id,
            s.st_phnum AS student_phnum,
            s.st_email AS student_email,
            TO_BASE64(s.st_image) AS student_image,
            s.st_class AS student_class,
            p.objective AS project_objective,
            p.significant AS project_significant,
            p.scope AS project_scope,
            p.project_title AS project_title,
            sv.sv_name AS supervisor_name
        FROM
            student s
        JOIN
            project p ON s.st_id = p.student_id
        JOIN
            supervise sp ON s.st_id = sp.student_id
        JOIN
            supervisor sv ON sp.supervisor_id = sv.sv_id
        WHERE
            s.st_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    while ($row = $result->fetch_assoc()) {

?>

        <h1 class="text-4xl font-bold mb-4"> My Profile</h1>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">

            <div class="px-3 py-2 bg-gray-900 flex justify-between">
                <div class="relative  flex items-center justify-center">
                    <h2 class="text-2xl font-bold text-gray-900 whitespace-nowrap text-white">My Details</h2>
                </div>
                <div class=" flex items-center justify-center">
                    <button type="submit" name="reject" value="reject" class="text-white font-medium rounded-lg text-sm px-3.5 py-1.5  bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-blue-800">Edit Profile</button>
                </div>
            </div>

            <div>
                <table class="table-fixed w-full text-sm text-left text-gray-400">
                    <tbody>
                        <tr class='bg-gray-800 border-gray-700'>
                            <td scope='row' rowspan="4" class=' text-center w-1/4 px-2 py-3  '>
                                <img class='mx-auto w-16 md:w-32 lg:w-48' src='data:image/jpeg;base64,<?php echo $row["student_image"]; ?>' alt='Student Image' />
                            </td>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Name</p><?php echo $row['student_name']; ?>
                            </td>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Email</p><?php echo $row['student_email']; ?>
                            </td>
                        </tr>
                        <tr class='bg-gray-800 border-gray-700'>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Student ID</p><?php echo $row['student_id']; ?>
                            </td>
                            <td class='px-6 py-2'>
                                <p class="font-bold text-white">Phone Number</p><?php echo $row['student_phnum']; ?>
                            </td>
                        </tr>
                        <tr class='bg-gray-800 border-gray-700 '>
                            <td colpan="2" class='px-6 py-2' style='vertical-align: top;'>
                                <p class="font-bold text-white">Supervisor Name</p><?php echo $row['supervisor_name']; ?>
                            </td>
                            <td rowspan="2" class='px-6 py-2' style='vertical-align: top;'>
                                <p class="font-bold text-white">Group</p> <?php echo $row['student_class']; ?>
                            </td>
                        </tr>
                        <tr class='bg-gray-800 border-gray-700 '>
                            <td class='px-6 py-2'>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2 bg-gray-900 flex justify-between">
                <div class="relative  flex items-center justify-center">
                    <h2 class="text-2xl font-bold text-gray-900 whitespace-nowrap text-white">My Fyp Details</h2>
                </div>
                <div class=" flex items-center justify-center">
                    <button type="submit" name="reject" value="reject" class="text-white font-medium rounded-lg text-sm px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-blue-800">Edit Fyp</button>
                </div>
            </div>
            <div>
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-700 text-gray-400">
                        <tr>
                            <th scope="col" class="w-1/4 px-6 py-3">
                                maters
                            </th>
                            <th scope="col" class="w-3/4 px-6 py-3">
                                details
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-600'>
                            <td scope='row' class='w-1/4 px-6 py-4 font-medium whitespace-nowrap text-white'>Project Title</td>
                            <td class='w-3/4 px-6 py-4'><?php echo $row['project_title']; ?></td>
                        </tr>
                        </tr>
                        <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-600'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>Objective</td>
                            <td class='px-6 py-4'><?php echo $row['project_objective']; ?></td>
                        </tr>
                        <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-600'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>Scope</td>
                            <td class='px-6 py-4'><?php echo $row['project_scope']; ?></td>
                        </tr>
                        <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-600'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>Significant</td>
                            <td class='px-6 py-4'><?php echo $row['project_significant']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        </body>

        </html>

<?php
    }
} else {
    echo "Error: " . $conn->error;
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