<?php

class Insight
{
    private $id;
    private $db;
    private $selectStatement;

    public function __construct($id, $db, $selectStatement)
    {
        $this->id = $id;
        $this->db = $db;
        $this->selectStatement = $selectStatement;
    }

    public function select()
    {
        $result = [];

        // Execute the dynamic select statement
        $stmt = $this->db->prepare($this->selectStatement);
        $stmt->execute([$this->id]);

        // Fetch the result as an associative array
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
}


// class Assignment extends Insight
// {
//     public function __construct($id, $db)
//     {
//         parent::__construct($id, $db, 'assignment', ['ass_title', 'submit_count', 'late_count', 'missing_count'], 'doc_id');
//     }
// }

// class Task extends Insight
// {
//     public function __construct($id, $db)
//     {
//         parent::__construct($id, $db, 'task', ['task_title', 'task_description', 'task_date_due'], 'task_id');
//     }
// }
