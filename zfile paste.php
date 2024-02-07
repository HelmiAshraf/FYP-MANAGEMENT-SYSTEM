<?php

try {
    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define the directory where files will be stored
    $uploadDirectory = __DIR__ . '/../file/task/';
    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if any files were uploaded
        if (isset($_FILES["files"]["name"]) && !empty($_FILES["files"]["name"])) {
            // Upload the file to the server directory and store the file path in the database
            $uploadSuccessful = true; // Flag to track successful file upload

            $file_name = $_FILES["files"]["name"];
            $file_type = $_FILES["files"]["type"];
            $file_size = $_FILES["files"]["size"];
            $file_path = $uploadDirectory . $file_name;

            // Check if the file type is allowed
            $allowedFileTypes = ['application/pdf', 'image/jpeg', 'image/png']; // Add more types if needed
            if (in_array($file_type, $allowedFileTypes)) {
                // If the file type is valid, proceed with inserting task details into the database
                // Extract task details from the form
                $task_title = $_POST["task_title"];
                $task_description = $_POST["task_description"];
                $date_create = date("Y-m-d H:i:s"); // Use the current date and time for task creation
                $task_date_due = $_POST["task_date_due"];
                $task_sv_id = $_SESSION["user_id"];
                $uploader_id = $_SESSION["user_id"];

                // Validate the due date to ensure it's not in the past
                $currentDateTime = new DateTime();
                $dueDateTime = new DateTime($task_date_due);

                if ($dueDateTime < $currentDateTime) {
                    $uploadSuccessful = false;
                    $Message = 'Error: Due date cannot be in the past.';
                } else {
                    // Insert task details into the task table
                    $sqlTask = "INSERT INTO task (task_title, task_description, task_date_create, task_date_due, task_sv_id)
                            VALUES (?, ?, ?, ?, ?)";
                    $stmtTask = $pdo->prepare($sqlTask);
                    $stmtTask->bindParam(1, $task_title);
                    $stmtTask->bindParam(2, $task_description);
                    $stmtTask->bindParam(3, $date_create);
                    $stmtTask->bindParam(4, $task_date_due);
                    $stmtTask->bindParam(5, $task_sv_id);

                    if ($stmtTask->execute()) {
                        // Get the task_id that was just inserted
                        $task_id = $pdo->lastInsertId();

                        $file_type = 'tsv'; // task = t
                        $file_name = $file_type . '_' . $task_id . '_' . $_FILES["files"]["name"];

                        // Insert file data into the file_path table using the retrieved task_id and updated file name
                        $sqlFile = "INSERT INTO file_path (file_name, type_id, file_type, file_uploader_id, file_path)
                                VALUES (?, ?, ?, ?, ?)";
                        $stmtFile = $pdo->prepare($sqlFile);
                        $stmtFile->bindParam(1, $file_name);
                        $stmtFile->bindParam(2, $task_id);
                        $stmtFile->bindParam(3, $file_type);
                        $stmtFile->bindParam(4, $uploader_id);
                        $stmtFile->bindParam(5, $file_path);

                        // Move the uploaded file to the server directory with the updated file name
                        if (move_uploaded_file($_FILES["files"]["tmp_name"], $uploadDirectory . $file_name)) {
                            if (!$stmtFile->execute()) {
                                $uploadSuccessful = false;
                                $Message = 'Error: Failed to insert file details into the database.';
                            } else {
                                // Insert task-student relationships into the task_student table
                                if (isset($_POST["student_ids"])) {
                                    $student_ids = $_POST["student_ids"];
                                    foreach ($student_ids as $student_id) {
                                        $sql = "INSERT INTO task_student (task_id, student_id)
                                                VALUES (?, ?)";
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->bindParam(1, $task_id);
                                        $stmt->bindParam(2, $student_id);
                                        $stmt->execute();
                                    }
                                }
                            }
                        } else {
                            $uploadSuccessful = false;
                            $Message = 'Error: Failed to move the uploaded file to the server directory.';
                        }
                    } else {
                        $uploadSuccessful = false;
                        $Message = 'Error: Failed to insert task details into the database.';
                    }
                }
            } else {
                $uploadSuccessful = false;
                $Message = 'Error: Invalid file type. Allowed types: PDF, JPEG, PNG.';
            }
        } else {
            // No files were uploaded, proceed with task creation without files
            $uploadSuccessful = true;

            // Extract task details from the form
            $task_title = $_POST["task_title"];
            $task_description = $_POST["task_description"];
            $date_create = date("Y-m-d H:i:s"); // Use the current date and time for task creation
            $task_date_due = $_POST["task_date_due"];
            $task_sv_id = $_SESSION["user_id"];
            $uploader_id = $_SESSION["user_id"];

            // Validate the due date to ensure it's not in the past
            $currentDateTime = new DateTime();
            $dueDateTime = new DateTime($task_date_due);

            if ($dueDateTime < $currentDateTime) {
                $uploadSuccessful = false;
                $Message = 'Error: Due date cannot be in the past.';
            } else {
                // Insert task details into the task table
                $sqlTask = "INSERT INTO task (task_title, task_description, task_date_create, task_date_due, task_sv_id)
                        VALUES (?, ?, ?, ?, ?)";
                $stmtTask = $pdo->prepare($sqlTask);
                $stmtTask->bindParam(1, $task_title);
                $stmtTask->bindParam(2, $task_description);
                $stmtTask->bindParam(3, $date_create);
                $stmtTask->bindParam(4, $task_date_due);
                $stmtTask->bindParam(5, $task_sv_id);

                if ($stmtTask->execute()) {
                    // Get the task_id that was just inserted
                    $task_id = $pdo->lastInsertId();

                    // Insert task-student relationships into the task_student table
                    if (isset($_POST["student_ids"])) {
                        $student_ids = $_POST["student_ids"];
                        foreach ($student_ids as $student_id) {
                            $sql = "INSERT INTO task_student (task_id, student_id)
                                VALUES (?, ?)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(1, $task_id);
                            $stmt->bindParam(2, $student_id);
                            $stmt->execute();
                        }
                    }
                } else {
                    $uploadSuccessful = false;
                    $Message = 'Error: Failed to insert task details into the database.';
                }
            }
        }

        // Display success message if file upload was successful or no files were uploaded
        if ($uploadSuccessful) {
            $Message = 'Successfully created a task.';
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

?>


<!--
assignment = a
assignment submission = as
document = d
document submission = ds
task = t
task submission = ts 
-->

SELECT
a.ass_id,
a.ass_title,
b.batch_category,
COUNT(DISTINCT CASE WHEN asb.ass_status = 1 AND asb.ass_submissiondate <= a.ass_date_due THEN asb.ass_student_id END) AS submit_count, COUNT(DISTINCT CASE WHEN asb.ass_status=1 AND asb.ass_submissiondate> a.ass_date_due THEN asb.ass_student_id END) AS late_count,
    (SELECT COUNT(s.st_id) FROM student s AS missing_count
    FROM
    assignment a
    JOIN batches b ON a.batch_id = b.batch_id
    LEFT JOIN assignment_submission asb ON a.ass_id = asb.ass_id
    LEFT JOIN student s ON b.batch_id = s.st_batch
    GROUP BY
    a.ass_id, a.ass_title, b.batch_category;


    !! //sql insight assi
    SELECT
    a.ass_id,
    a.ass_title,
    b.batch_category,
    COUNT(DISTINCT s.ass_submission_id) AS num_submitted,
    SUM(CASE WHEN s.ass_status = 1 AND COALESCE(s.ass_submissiondate, a.ass_date_due) > a.ass_date_due THEN 1 ELSE 0 END) AS num_late_submissions,
    (SELECT COUNT(DISTINCT st.st_id) FROM student st WHERE st.st_batch = a.batch_id) - COUNT(DISTINCT s.ass_student_id) AS num_missing_submissions
    FROM
    assignment a
    JOIN
    batches b ON a.batch_id = b.batch_id
    LEFT JOIN
    assignment_submission s ON a.ass_id = s.ass_id
    WHERE
    s.ass_status = 1 OR s.ass_status IS NULL
    GROUP BY
    a.ass_id, a.ass_title, b.batch_category
    ORDER BY
    a.ass_id;



    !! sql doc
    SELECT
    d.doc_id,
    d.doc_title,
    b.batch_category,
    COUNT(ds.doc_submission_id) AS num_submitted,
    SUM(CASE WHEN ds.doc_submissiondate > d.doc_date_due THEN 1 ELSE 0 END) AS num_late_submission,
    (SELECT COUNT(*) FROM student s WHERE s.st_batch = d.batch_id) - COUNT(ds.doc_submission_id) AS num_missing_submission
    FROM
    document d
    JOIN
    batches b ON d.batch_id = b.batch_id
    LEFT JOIN
    document_submission ds ON d.doc_id = ds.doc_id
    GROUP BY
    d.doc_id, d.doc_title, d.doc_date_due, b.batch_category;

    task

    SELECT
    t.task_id,
    t.task_title,
    t.task_date_due,
    b.batch_category,
    t.task_sv_id,
    COUNT(ts.task_submission_id) AS num_submitted,
    SUM(CASE WHEN ts.submissiondate > t.task_date_due THEN 1 ELSE 0 END) AS num_late_submission,
    (SELECT COUNT(*) FROM student s WHERE s.st_batch = t.batch_id) - COUNT(ts.task_submission_id) AS num_missing_submission
    FROM
    task t
    JOIN
    batches b ON t.batch_id = b.batch_id
    LEFT JOIN
    task_submission ts ON t.task_id = ts.task_id
    WHERE
    t.task_sv_id =2013500098
    GROUP BY
    t.task_id, t.task_title, t.task_date_due, b.batch_category, t.task_sv_id;


    /// total complete task student

    SELECT
    s.st_id,
    COUNT(DISTINCT ts.task_id) AS total_complete_task,
    COUNT(DISTINCT t.task_id) + COUNT(DISTINCT tstu.task_id)AS total_assigned_task
    FROM
    student s
    LEFT JOIN
    task_submission ts ON s.st_id = ts.student_id
    LEFT JOIN
    (
    SELECT t1.task_id, t1.batch_id
    FROM task t1
    JOIN student s
    WHERE t1.batch_id = s.st_batch
    UNION
    SELECT t2.task_id, t2.batch_id
    FROM task_student tstu
    JOIN task t2 ON tstu.task_id = t2.task_id
    JOIN student s
    ) t ON t.batch_id = s.st_batch
    LEFT JOIN
    task_student tstu ON s.st_id = tstu.student_id
    WHERE
    s.st_id = 2022937731
    GROUP BY
    s.st_id;


assignment student dashboard

SELECT
    s.st_id,
    s.st_name,
    b.batch_id,
    b.batch_name,
    COUNT(DISTINCT a.ass_id) AS total_assignments,
    COUNT(DISTINCT CASE WHEN asub.ass_status = 1 THEN a.ass_id END) AS total_submitted_assignments
FROM
    student s
JOIN
    batches b ON s.st_batch = b.batch_id
LEFT JOIN
    assignment_submission asub ON s.st_id = asub.ass_student_id
LEFT JOIN
    assignment a ON asub.ass_id = a.ass_id
WHERE s.st_id = 2022937731
GROUP BY
    s.st_id, b.batch_id;
    
    <?php
    class Update
    {
        private $id;
        private $db;
        private $tableName;
        private $attributes;
        private $idName;

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

            $this->db->prepare($sql)->execute($params); // Execute the prepared statement

            echo "Updating record with $this->idName $this->id\n";
            foreach ($this->attributes as $index => $attribute) {
                echo "New $attribute: {$newValues[$index]}\n";
            }
        }
    }

    class Assignment extends Update
    {
        public function __construct($id, $db)
        {
            parent::__construct($id, $db, 'assignment', ['ass_title', 'ass_description', 'ass_date_due'], 'ass_id');
        }
    }

    class DocumentUpdate extends Update
    {
        public function __construct($id, $db)
        {
            parent::__construct($id, $db, 'document', ['doc_title', 'doc_description', 'doc_date_due'], 'doc_id');
        }
    }

    class Task extends Update
    {
        public function __construct($id, $db)
        {
            parent::__construct($id, $db, 'task', ['task_title', 'task_description', 'task_date_due'], 'task_id');
        }
    }

    ?>