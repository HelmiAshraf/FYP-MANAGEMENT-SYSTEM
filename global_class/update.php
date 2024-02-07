<?php
session_start();

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

    public function update($newValues, $redirectPath, $imageFile)
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
        $success = $stmt->execute($params);

        if ($success) {
            $_SESSION['update_success_message'] = 'Update successful!';
            header("Location: $redirectPath");
            exit();
        } else {
            echo "Update failed.";
        }
    }
}

class Supervisor_profile extends Update
{
    private $imageUploadPath;

    public function __construct($id, $db, $imageUploadPath)
    {
        parent::__construct($id, $db, 'supervisor', ['sv_expertise', 'sv_phnum', 'sv_email'], 'sv_id');
        $this->imageUploadPath = $imageUploadPath;
    }

    public function update($newValues, $redirectPath, $imageFile)
    {
        if ($imageFile['error'] === UPLOAD_ERR_OK) {
            $this->deleteExistingImage();
            $this->uploadImage($imageFile);
            $newValues[] = $this->id . '_' . $imageFile['name'];
        } else {
            echo "File upload failed with error code: " . $imageFile['error'];
        }

        parent::update($newValues, $redirectPath, $imageFile);
    }

    private function deleteExistingImage()
    {
        // Fetch the existing image filename from the database
        $sql = "SELECT sv_image FROM supervisor WHERE sv_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->id]);
        $existingImage = $stmt->fetchColumn();

        // Check if there is an existing image and delete it
        if ($existingImage) {
            $this->deleteImageFile($existingImage);
        }
    }

    private function deleteImageFile($filename)
    {
        // Specify the directory path relative to your script
        $directoryPath = $this->imageUploadPath;

        // Create the full path to the image file
        $imagePath = $directoryPath . $filename;

        // Check if the file exists and delete it
        if (file_exists($imagePath)) {
            $deleted = unlink($imagePath);

            if (!$deleted) {
                echo "Error deleting the existing image.";
            }
        } else {
            echo "Existing image not found.";
        }
    }




    private function uploadImage($imageFile)
    {
        if (!is_dir($this->imageUploadPath)) {
            mkdir($this->imageUploadPath, 0777, true);
        }

        $targetFilename = $this->id . '_' . $imageFile['name'];
        $targetPath = $this->imageUploadPath . $targetFilename;
        move_uploaded_file($imageFile['tmp_name'], $targetPath);
    }
}






class Student_profile extends Update
{
    private $imageUploadPath; // Add this property to store the path where images are uploaded

    public function __construct($id, $db, $imageUploadPath)
    {
        parent::__construct($id, $db, 'student', ['st_phnum', 'st_email', 'st_image'], 'st_id');
        $this->imageUploadPath = $imageUploadPath;
    }

    public function update($newValues, $redirectPath, $imageFile)
    {
        // Check if a new image is provided
        if ($imageFile['error'] === 0) {
            // Delete the existing image
            $this->deleteExistingImage();

            // Upload the new image
            $this->uploadImage($imageFile);

            // Set the 'st_image' attribute to the new image filename
            $newValues[] = $imageFile['name'];
        }

        // Call the parent update method with the correct order of parameters
        parent::update($redirectPath, $newValues);
    }

    private function deleteExistingImage()
    {
        // Fetch the existing image filename from the database
        $sql = "SELECT st_image FROM student WHERE st_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->id]);
        $existingImage = $stmt->fetchColumn();

        // Check if there is an existing image and delete it
        if ($existingImage) {
            $imagePath = $this->imageUploadPath . $existingImage;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }

    private function uploadImage($imageFile)
    {
        // Ensure the 'uploads' directory exists
        if (!is_dir($this->imageUploadPath)) {
            mkdir($this->imageUploadPath, 0777, true);
        }

        // Move the uploaded image to the 'uploads' directory
        $targetPath = $this->imageUploadPath . $imageFile['name'];
        move_uploaded_file($imageFile['tmp_name'], $targetPath);
    }
}


class Student_fyp extends Update
{
    public function __construct($id, $db)
    {
        parent::__construct($id, $db, 'project', ['project_title', 'research_area', 'domain', 'end_product', 'objective', 'scope', 'significant'], 'student_id');
    }
}
