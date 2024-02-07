
<?php

// Include the Delete and DeleteTask class definitions
require_once 'delete.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteTask'])) {
    // Validate and sanitize the input
    $task_id = isset($_POST['task_id']) ? filter_var($_POST['task_id'], FILTER_SANITIZE_NUMBER_INT) : null;

    if ($task_id !== null) {
        // Create a PDO database connection
        include '../db_credentials.php';

        // Create a PDO database connection
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        // Set PDO to throw exceptions on error
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Instantiate the Delete class and delete the task
        $deleteTask = new Delete($task_id, $pdo, 'task', 'task_id');
        $deleteTask->delete();

        // Set a session variable to store the success message
        $_SESSION['delete_success_message'] = 'Delete successful!';

        // Redirect back to the original page
        header("Location: ../supervisor/sv_task.php");
        exit();
    } else {
        // Handle the case where task_id is not valid
        echo "Invalid task ID.";
    }
} else {
    // Handle the case where the request method is not POST
    echo "Invalid request method.";
}
?>
