<?php

// Check if the form is submitted
include '../../db_credentials.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the new quota value from the form
    $newQuota = $_POST['sv_quota'];
    $sv_id = $_POST['sv_id'];

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update sv_quota for the specific supervisor
        $query = "UPDATE supervisor SET sv_quota = :newQuota WHERE sv_id = :sv_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':newQuota', $newQuota, PDO::PARAM_INT);
        $stmt->bindParam(':sv_id', $sv_id, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: ../fypl_lecturer_details.php?sv_id=" . $sv_id);

        // Redirect to your desired page
        exit(); // Ensure that no further code is executed after the refresh
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
