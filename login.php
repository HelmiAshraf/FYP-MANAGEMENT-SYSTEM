<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $user_id = $_POST["user_id"];
    $password = $_POST["password"];

    include 'db.php';

    // Prepare and execute the query to check user credentials
    $stmt = $conn->prepare("SELECT user_id, role FROM user WHERE user_id = ? AND password = ?");
    $stmt->bind_param("ss", $user_id, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a row is returned (authentication successful)
    if ($result->num_rows == 1) {
        // Fetch the user's role
        $row = $result->fetch_assoc();
        $user_role = $row['role'];

        // Store user_id in the session
        $_SESSION['user_id'] = $user_id;

        // Store user role in the session
        $_SESSION['role'] = $user_role;

        // Redirect based on user role
        if ($user_role == 'student') {
            header("Location: student/st_available_sv.php");
            exit();
        } elseif ($user_role == 'supervisor') {
            header("Location: supervisor/sv_supervisee.php");
            exit();
        } else {
            // Handle other roles or scenarios
            // You can redirect to a default page or display an error message
            $_SESSION['message'] = "Invalid user role.";
            header("Location: login.php");
            exit();
        }
    } else {
        // Authentication failed; display an error message
        $_SESSION['message'] = "Invalid username or password.";
        header("Location: login.php");
        exit();
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>






<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>


<body class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">

        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex flex-col items-center justify-center px-6 pt-6">
                <a href="#" class="flex items-center  text-2xl font-extrabold text-gray-900 dark:text-white">
                    <img src="assets/uitm.png" class="h-6 mr-2" alt="uitm Logo" />
                    FYPMS
                </a>
            </div>
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-medium leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Sign in to your account
                </h1>
                <p class="text-gray-900 md:text-sm dark:text-white"> <?php $_SESSION['message'] ?> </p>
                <form class="space-y-4 md:space-y-6" action="login.php" method="POST">
                    <div>
                        <label for="userid" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your ID</label>
                        <input type="userid" name="user_id" id="user_id" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="2022..." required="">
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required="">
                    </div>
                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-lg -md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>


</html>