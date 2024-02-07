<?php

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
        // Delete the record from the database

        $sql = "DELETE FROM $this->tableName WHERE $this->idName = ?";
        $params = [$this->id];

        $this->db->prepare($sql)->execute($params); // Execute the prepared statement

        echo "Deleting record with $this->idName $this->id\n";
    }
}

?>
