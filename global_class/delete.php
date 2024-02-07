<?php
session_start(); // Start the session

class Delete
{
    private $id;
    private $db;
    private $tableName;
    private $idName;

    public function __construct($id, $db, $tableName, $idName)
    {
        $this->id = $id;
        $this->db = $db;
        $this->tableName = $tableName;
        $this->idName = $idName;
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->tableName WHERE $this->idName = ?";
        $params = [$this->id];

        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute($params);

        if ($success) {
            // Set a session variable to store the success message
            $_SESSION['delete_success_message'] = 'Delete successful!';
        } else {
            // Error handling if the delete fails
            echo "Delete failed.";
        }
    }
}

class DeleteTask extends Delete
{
    public function __construct($id, $db)
    {
        parent::__construct($id, $db, 'task', 'task_id');
    }
}

// Example usage:
// $deleteTask = new DeleteTask($task_id, $your_db_object);
// $deleteTask->delete();
// Redirect or display success message as needed.
