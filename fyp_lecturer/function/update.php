<?php

class Update
{
    protected $id;
    protected $db;
    protected $tableName;
    protected $attributes;
    protected $idName;

    public function __construct($id, $db, $tableName, $attributes, $idName)
    {
        $this->id = $id;
        $this->db = $db;
        $this->tableName = $tableName;
        $this->attributes = $attributes;
        $this->idName = $idName;
    }

    public function update($newValues)
    {
        if (!is_array($newValues)) {
            throw new InvalidArgumentException('$newValues must be an array.');
        }

        $updateFields = array_map(function ($attribute) {
            return "$attribute = ?";
        }, $this->attributes);

        $sql = "UPDATE $this->tableName SET " . implode(', ', $updateFields) . " WHERE $this->idName = ?";
        $params = array_merge($newValues, [$this->id]);

        $stmt = $this->db->prepare($sql);

        if ($stmt->execute($params)) {
            echo "Updating record with $this->idName $this->id\n";
            foreach ($this->attributes as $index => $attribute) {
                echo "New $attribute: {$newValues[$index]}\n";
            }
        } else {
            echo "Update failed.";
        }
    }

    protected function deleteFile($filename)
    {
        // Define the directory path relative to your script
        $directoryPath = '../file/document/';

        // Create the full path to the file
        $filePath = $directoryPath . $filename;

        // Check if the file exists and delete it
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}

class DocumentUpdate extends Update
{
    public function __construct($id, $db)
    {
        parent::__construct($id, $db, 'document', ['doc_title', 'doc_description', 'doc_date_due'], 'doc_id');
    }

    public function update($newValues)
    {
        // Check if a file is uploaded
        $file = func_get_arg(0);

        if ($file['error'] === UPLOAD_ERR_OK) {
            // Delete the existing file, if any
            $this->deleteExistingFile();

            // Upload the new file
            $this->uploadFile($file);

            // Set the 'doc_file' attribute to the new file filename
            $newValues[] = $this->id . '_' . $file['name'];
        }

        // Call the parent update method with the newValues array
        parent::update($newValues);
    }

    private function deleteExistingFile()
    {
        // Fetch the existing file filename from the database
        $sql = "SELECT doc_file FROM document WHERE doc_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->id]);
        $existingFile = $stmt->fetchColumn();

        // Check if there is an existing file and delete it
        if ($existingFile) {
            $this->deleteFile($existingFile);
        }
    }

    private function uploadFile($file)
    {
        // Specify the directory path relative to your script
        $directoryPath = '../file/document/';

        // Move the uploaded file to the directory with doc_id prefix
        $targetFilename = $this->id . '_' . $file['name'];
        $targetPath = $directoryPath . $targetFilename;
        move_uploaded_file($file['tmp_name'], $targetPath);
    }
}




class Assignment extends Update
{
    public function __construct($id, $db)
    {
        parent::__construct($id, $db, 'assignment', ['ass_title', 'ass_description', 'ass_date_due'], 'ass_id', '../file/assignment/');
    }
}


class Task extends Update
{
    public function __construct($id, $db)
    {
        parent::__construct($id, $db, 'task', ['task_title', 'task_description', 'task_date_due'], 'task_id', '../file/task/');
    }
}

// Usage examples
