<?php
include '../../db_credentials.php';


// Create a database connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Retrieve the selected batch category from the Ajax request
$selectedBatch = isset($_GET['batch_category']) ? $_GET['batch_category'] : null;

// Your existing code to retrieve student data based on the selected batch category
$sql = "SELECT s.st_id, s.st_name, s.st_batch, b.batch_category
        FROM student s
        INNER JOIN supervise ON s.st_id = supervise.student_id
        INNER JOIN batches b ON s.st_batch = b.batch_id
        WHERE supervise.supervisor_id = ? AND s.st_batch = ?";

$stmt = $conn->prepare($sql);

$supervisor_id = $_SESSION["user_id"];

if ($stmt) {
    $stmt->bind_param("ii", $supervisor_id, $selectedBatch);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Error preparing the statement.");
}

// Only output the table body content
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr class=\"border-b bg-gray-800 border-gray-700 hover:bg-gray-900\">";
        echo "<th scope=\"row\" class=\"px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white\">{$row['st_name']}</th>";
        echo "<td class=\"w-4 p-4\"><div class=\"flex items-center justify-center\"><span class=\"text-gray-300 dark:text-white\">{$row['batch_category']}</span></div></td>";
        echo "<td class=\"w-4 p-4\"><div class=\"flex justify-end\"><input type=\"checkbox\" name=\"student_ids[]\" value=\"{$row['st_id']}\" class=\"w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600\"></div></td>";
        echo "</tr>";
    }
} else {
    // Handle the case where there are no rows
    echo "<tr><td colspan=\"3\">No students found</td></tr>";
}

// Close the database connection
$stmt->close();
$conn->close();
