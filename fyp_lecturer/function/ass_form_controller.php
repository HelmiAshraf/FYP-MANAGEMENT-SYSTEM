<?php
include '../../db_credentials.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form data
    $assId = $_POST['ass_id'];
    $newTitle = $_POST['ass_title'];
    $newDueDate = $_POST['ass_date_due'];
    $ass_description = $_POST['ass_description'];

    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update assignment details
        $query = "UPDATE assignment SET 
                    ass_title = :newTitle,
                    ass_date_due = :newDueDate,
                    ass_description = :ass_description
                  WHERE ass_id = :assId";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':newTitle', $newTitle, PDO::PARAM_STR);
        $stmt->bindParam(':newDueDate', $newDueDate, PDO::PARAM_STR);
        $stmt->bindParam(':ass_description', $ass_description, PDO::PARAM_STR);
        $stmt->bindParam(':assId', $assId, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect to your desired page
        header("Location: ../fypl_assignment_details.php?ass_id=" . $assId);
        exit(); // Ensure that no further code is executed after the refresh
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}


// Include your HTML form here
