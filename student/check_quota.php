<?php
include '../db.php';
// Create a database connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['sv_id'])) {
    $svId = $_GET['sv_id'];

    // Query to check supervisor's quota
    $sql = "SELECT s.sv_quota, COUNT(*) AS current_students
            FROM supervisor s
            LEFT JOIN supervise v ON s.sv_id = v.supervisor_id
            WHERE s.sv_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $svId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        $quotaFull = ($row['current_students'] >= $row['sv_quota']);

        echo json_encode(['quotaFull' => $quotaFull]);
    } else {
        // Handle database error
        echo json_encode(['quotaFull' => true]);
    }

    // Close database connection
    $conn->close();
} else {
    echo json_encode(['quotaFull' => true]);
}
