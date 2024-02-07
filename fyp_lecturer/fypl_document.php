<?php
include 'includes/sidebar.php';

function getBatchIdByCategory($batchCategory, $conn)
{
    $sql = "SELECT batch_id FROM batches WHERE batch_category = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("s", $batchCategory);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        return $row['batch_id'];
    }

    return null;
}

// Assuming you have established a database connection ($conn)
$supervisor_id = $_SESSION["user_id"];

// Get the default batch_id for CSP600
$defaultBatchId = getBatchIdByCategory('CSP600', $conn);

// If no batch_category is selected, set the default to CSP600 batch_id
$selected_option = isset($_GET['batch_category']) ? $_GET['batch_category'] : $defaultBatchId;

// Corrected SQL query to fetch doc details
$sql = "SELECT
d.doc_id,
d.doc_title,
d.doc_date_create,
d.doc_date_due
FROM
document d
JOIN
fyp_lecturer f ON d.doc_fl_id = f.fl_id
WHERE
d.batch_id = ? AND f.fl_id = ?;
"; // Adjust this query to fetch docs associated with the user

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $selected_option, $supervisor_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();

?>

    <div class="flex justify-between items-center">
        <div>
            <p class="inline-flex items-center text-sm font-medium text-gray-400">Login as: Final Year Project Lecturer</p>
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
                                Document
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="w-full border-b mt-1 border-gray-400 mb-2"></div>


    <h1 class="text-4xl font-bold mb-4">Documents</h1>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
        <div class="p-4 bg-gray-900 flex justify-between">
            <div class="bg-gray-900 flex items-center">
                <label for="batchForm" class="block text-xl font-sm text-gray-300 mr-4">Course:</label>
                <form id="batchForm" method="GET">
                    <select name="batch_category" id="countries" class="text-sm rounded-md block p-2 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" onchange="submitForm()">
                        <?php
                        // Fetch batch_id based on batch_category
                        $csp600BatchId = getBatchIdByCategory('CSP600', $conn);
                        $csp650BatchId = getBatchIdByCategory('CSP650', $conn);
                        ?>
                        <option value="<?php echo $csp600BatchId; ?>" <?php echo ($selected_option == $csp600BatchId) ? 'selected' : ''; ?>>CSP600</option>
                        <option value="<?php echo $csp650BatchId; ?>" <?php echo ($selected_option == $csp650BatchId) ? 'selected' : ''; ?>>CSP650</option>
                    </select>
                </form>
            </div>
            <script>
                function submitForm() {
                    document.getElementById("batchForm").submit();
                }
            </script>
            <div>
                <a href="fypl_document_create.php" class="ml-auto text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 block">
                    Upload document
                </a>
            </div>
        </div>

        <table class="w-full text-sm text-left text-gray-400">
            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                Supplementary Submission Center
                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">A space for submitting smaller materials like forms, consents, or additional written content that complements the project.</p>
            </caption>

            <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                <tr>
                    <th scope="col" class="w-3/7 px-6 py-3">
                        Document
                    </th>
                    <th scope="col" class="w-1/7 px-6 py-3">
                        Date Created
                    </th>
                    <th scope="col" class="w-1/7 px-6 py-3">
                        Due Date
                    </th>
                    <th scope="col" class="w-1/7 px-6 py-3 text-center">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_assoc()) { ?>
                        <tr class=' border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>
                            <td scope='row' class='px-6 py-4 font-medium whitespace-nowrap text-white'>
                                <a href='fypl_document_details.php?doc_id=<?php echo $row["doc_id"]; ?>' class='font-medium text-blue-500 hover:underline'>
                                    <?php echo $row['doc_title'];
                                    ?>
                                </a>
                            </td>
                            <td class='px-6 py-4'><?php echo $row['doc_date_create']; ?></td>
                            <td class='px-6 py-4'><?php echo $row['doc_date_due']; ?></td>
                            <td class='px-6 py-4 text-center' onclick=' return confirm("Are you sure you want to delete this document?")'>
                                <form action='function/doc_del.php' method='POST'>
                                    <input type='hidden' name='doc_id' value='<?php echo $row["doc_id"]; ?>'>
                                    <button type='submit' class='font-medium text-blue-500 hover:text-blue-600'>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
            </tbody>
    <?php
                } else {
                    echo "<tbody>";
                    echo "<tr class='border-b bg-gray-800 border-gray-700 hover:bg-gray-900'>";
                    echo "<td colspan='5' class='px-6 py-4 text-center'>No Document found</td>";
                    echo "</tr>";
                    echo "</tbody>";
                }
            } else {
                echo "Error executing the query: " . $stmt->error;
            } ?>

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