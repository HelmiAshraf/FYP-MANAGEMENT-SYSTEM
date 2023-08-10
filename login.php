<?php
// Include the database connection
include 'db.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $password = $_POST["student_password"];

    // Prepare and execute a database query
    $sql = "SELECT * FROM student WHERE student_id = '$student_id' AND student_password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Redirect to a dashboard or welcome page upon successful login
        header("Location: student/st_choose_sv.php");
        exit;
    } else {
        $error = "Invalid student ID or password.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-96 p-8 bg-white rounded shadow">
        <h2 class="text-2xl font-semibold mb-4">Login</h2>
        <?php if (isset($error)) {
            echo "<p class='text-red-500 mb-4'>$error</p>";
        } ?>
        <form action="login.php" method="POST">
            <label class="block mb-2" for="username">Student ID:</label>
            <input class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500" type="text" id="student_id" name="student_id" required><br><br>

            <label class="block mb-2" for="password">Password:</label>
            <input class="w-full px-4 py-2 border rounded focus:outline-none focus:border-blue-500" type="password" id="student_password`" name="student_password" required><br><br>

            <button class="w-full px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" type="submit">Login</button>
        </form>
    </div>
</body>

</html>