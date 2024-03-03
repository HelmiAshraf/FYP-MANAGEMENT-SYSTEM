<?php
include 'includes/sidebar.php';
include '../db_credentials.php';


$user_id = $_SESSION["user_id"]; // Assuming you have stored the logged-in user's ID in a session variable


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the new quota value from the form
    $newQuota = $_POST['sv_quota'];

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update sv_quota for all supervisors
        $query = "UPDATE supervisor SET sv_quota = :newQuota";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':newQuota', $newQuota, PDO::PARAM_INT);
        $stmt->execute();

        echo '<script>alert("Quota updated successfully for all supervisors.");</script>';
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}




$sql = "SELECT
sv_id,
sv_name AS supervisor_name,
sv_expertise AS supervisor_expertise,
sv_quota AS supervisor_quota
FROM
supervisor;
";

$stmt = $conn->prepare($sql);

if ($stmt->execute()) {
    $result = $stmt->get_result();
?>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table-search').on('input', function() {
                var searchValue = $(this).val().toLowerCase();

                $('tbody tr').each(function() {
                    var supervisorName = $(this).find('td:nth-child(1)').text().toLowerCase();
                    var supervisorExpertise = $(this).find('td:nth-child(2)').text().toLowerCase();

                    if (supervisorName.includes(searchValue) || supervisorExpertise.includes(searchValue)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>

    <div id="ass-modal" class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto transform scale-0 opacity-0 ">
        <div class="modal-overlay bg-black bg-opacity-70 fixed inset-0"></div>
        <div class="bg-white w-96 rounded-lg shadow-lg p-6 transform scale-100 opacity-100 transition-transform duration-300">
            <h2 class="text-2xl font-bold mb-4">Update All Supervisor Quota</h2>
            <form action="fypl_lecturer.php" method="POST" enctype="multipart/form-data">
                <!-- Your form content here -->
                <div class="mb-6">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900">
                        Quota
                    </label>
                    <input type="text" required name="sv_quota" class="border text-sm rounded-lg block w-full p-2.5 bg-gray-100 border-gray-300 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500" placeholder="Number of quota" />
                </div>
                <button type="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center bg-blue-600 hover-bg-blue-700 focus-ring-blue-800">
                    Update
                </button>
            </form>
            <button id="close-modal-button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    </div>

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
                                Supervisor
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>

    <h1 class="text-4xl font-bold mb-4">Supervisor</h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">

        <div class="p-4 bg-gray-900 flex justify-between">
            <!-- Left side with search input -->
            <div class="relative">
                <label for="table-search" class="sr-only">Search</label>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="table-search" class="block p-2 pl-10 text-sm border rounded-lg w-80 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Search for Supervisor?">
            </div>
            <div>
                <button id="open-modal-button" class="ml-auto text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-700 hover:bg-blue-800">
                    Update Quota
                </button>
            </div>
        </div>

        <table class="w-full text-sm text-left text-gray-400">
            <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        supervisor Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        expertise
                    </th>
                    <th scope="col" class="px-6 py-3">
                        quota
                    </th>
                    <th scope="col" class="px-6 py-3">
                        supervisor details
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_assoc()) {
                ?>
                        <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'><?php echo $row['supervisor_name']; ?></td>
                            <td class='px-6 py-4'><?php echo $row['supervisor_expertise']; ?></td>
                            <td class='px-6 py-4'><?php echo $row['supervisor_quota']; ?></td>
                            <td class='px-6 py-4'>
                                <a href='fypl_lecturer_details.php?sv_id=<?php echo $row["sv_id"]; ?>' class='font-medium text-blue-500 hover:underline'>View</a>
                            </td>
                        </tr>
                    <?php } ?>
            </tbody>
    <?php
                } else {
                    echo "<tbody>";
                    echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                    echo "<td colspan='4' class='px-6 py-4 text-center'>No supervisor found</td>";
                    echo "</tr>";
                    echo "</tbody>";
                }
            } else {
                echo "Error executing the query: " . $stmt->error;
            }
    ?>
        </table>
    </div>
    </body>

    </html>

    <?php
    mysqli_close($conn);
    ?>

    <script>
        const openModalButton = document.getElementById("open-modal-button");
        const closeModalButton = document.getElementById("close-modal-button");
        const assModal = document.getElementById("ass-modal");

        openModalButton.addEventListener("click", () => {
            assModal.classList.remove("scale-0", "opacity-0");
            assModal.classList.add("scale-100", "opacity-100");
        });

        closeModalButton.addEventListener("click", () => {
            assModal.classList.remove("scale-100", "opacity-100");
            assModal.classList.add("scale-0", "opacity-0");
        });
    </script>

    <!-- content end -->
    </div>
    </div>
    </div>
    </body>

    </html>