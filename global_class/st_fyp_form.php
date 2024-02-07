<?php

// Include your database connection file (replace 'your_db_connection.php' with your actual file)
include '../db_credentials.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Create a PDO database connection
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        // Set PDO to throw exceptions on error
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get form data
        $st_id = $_POST['st_id'];
        $project_id = $_POST['project_id'];
        $project_title = $_POST['project_title'];
        $research_area = $_POST['research_area'];
        $domain = $_POST['domain'];
        $end_product = $_POST['end_product'];
        $objective = $_POST['objective'];
        $scope = $_POST['scope'];
        $significant = $_POST['significant'];

        // Prepare and execute the update query
        $sql = "UPDATE project SET 
                project_title = ?, 
                research_area = ?, 
                domain = ?, 
                end_product = ?, 
                objective = ?, 
                scope = ?, 
                significant = ? 
                WHERE student_id = ? AND project_id =?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$project_title, $research_area, $domain, $end_product, $objective, $scope, $significant, $st_id, $project_id ]);

        // Check if the update was successful
        if ($stmt->rowCount() > 0) {
            // Redirect to a success page or perform any other actions
            header("Location: ../student/st_profile.php");
            exit();
        } else {
            // Handle the case where the update fails
            echo "No rows updated. Check your WHERE clause.";
        }

    } catch (PDOException $e) {
        echo "Error updating data: " . $e->getMessage();
    } finally {
        // Close the database connection
        $pdo = null;
    }
}
?>
