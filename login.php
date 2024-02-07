<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $user_id = $_POST["user_id"];
    $password = $_POST["password"];

    include 'db.php';

    // Prepare and execute the query to retrieve hashed password
    $stmt = $conn->prepare("SELECT user_id, role, verify_email, password FROM user WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a row is returned
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $user_role = $row['role'];
        $user_password_hash = $row['password'];
        $verify_email = $row['verify_email'];

        // Check if the email is verified
        if ($verify_email != 1) {
            // Email not verified; display an alert and redirect
            echo '<script>alert("Verify your email first. Check email for verification"); window.location = "login.php";</script>';
            exit();
        }

        // Verify the entered password with the hashed password from the database
        if (password_verify($password, $user_password_hash)) {
            // Password is correct

            // Store user_id in the session
            $_SESSION['user_id'] = $user_id;

            // Store user role in the session
            $_SESSION['role'] = $user_role;

            // Fetch the student's batch_id
            $sqlBatchId = "SELECT st_batch FROM student WHERE st_id = ?";
            $stmtBatchId = $conn->prepare($sqlBatchId);
            $stmtBatchId->bind_param("i", $user_id); // Assuming user_id is an integer

            // Execute the query
            $stmtBatchId->execute();

            // Get the result set
            $resultBatchId = $stmtBatchId->get_result();

            // Check if a row is returned
            if ($resultBatchId->num_rows == 1) {
                $rowBatchId = $resultBatchId->fetch_assoc();

                // Store the batch_id in the session
                $_SESSION['batch_id'] = $rowBatchId['st_batch'];

                // Check if the user has an entry in the supervise table
                $sqlCheckSupervise = "SELECT * FROM supervise WHERE student_id = ?";
                $stmtCheckSupervise = $conn->prepare($sqlCheckSupervise);
                $stmtCheckSupervise->bind_param("i", $user_id);

                // Execute the query
                $stmtCheckSupervise->execute();

                // Get the result set
                $resultCheckSupervise = $stmtCheckSupervise->get_result();

                // Check if a row is returned
                if ($resultCheckSupervise->num_rows == 0 && $user_role == 'student') {
                    // Student doesn't have an entry in the supervise table; redirect to st_available_sv.php
                    header("Location: student/st_available_sv.php");
                    exit();
                }
            }

            // Redirect based on user role
            if ($user_role == 'student') {
                header("Location: student/st_dashboard.php");
                exit();
            } else if ($user_role == 'supervisor') {
                header("Location: supervisor/sv_supervisee.php");
                exit();
            } else {
                // Handle other roles or scenarios
                // You can redirect to a default page or display an error message
                $message = "Invalid user role.";
            }
        } else {
            // Password is incorrect
            $message = "Invalid username or password.";
        }
    } else {
        // No matching user found
        $message = "Invalid username or password.";
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

<body class="bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <div class="w-full rounded-lg shadow border md:mt-0 sm:max-w-md xl:p-0 bg-gray-800 border-gray-700">
            <div class="flex items-center justify-between px-8 pt-4">
                <div class="flex items-center">
                    <div>
                        <a href="index.php">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-gray-300 hover:text-blue-500">
                                <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                                <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
                            </svg>
                        </a>
                    </div>
                    <div class="ml-4">
                        <a href="index.php" class="flex items-center text-2xl font-extrabold text-white">
                            <img src="assets/uitm.png" class="h-6 mr-2" alt="uitm Logo" />
                            FYPMS
                        </a>
                    </div>
                </div>
            </div>


            <div class="p-6 space-y-4 md:space-y-6 sm:p-8 ">
                <h1 class="flex items-center text-xl font-bold tracking-tight md:text-2xl text-white">
                    Student & Supervisor Sign in
                </h1>
                <?php if (!empty($message)) : ?>
                    <p class="text-red-500 text-sm"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>

                <form class="space-y-4 md:space-y-6" action="login.php" method="POST">
                    <div>
                        <label for="userid" class="block mb-2 text-sm font-medium text-white">Your ID</label>
                        <input type="userid" name="user_id" id="user_id" class=" border sm:text-sm rounded-lg  block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500" placeholder="2022..." required="">
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-white">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" placeholder="#FYPMS_2024" class="border sm:text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 mb-2" required>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 cursor-pointer" onclick="togglePasswordVisibility()">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-gray-500">
                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                    <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <a href="forgot_password.php" class="hover:underline text-blue-500 text-sm font-medium">Forgot Password?</a>
                    </div>
                    <div class="flex flex-col items-center justify-center">
                        <button type="submit" class="flex w-full bg-blue-700 hover:bg-blue-800 justify-center rounded-lg -md px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign in</button>
                        <label class="mt-4 text-sm font-medium text-gray-300">Don't have an account? <a href="register.php" class="hover:underline text-blue-500">Sign Up</a></label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("password");
        var icon = document.querySelector('.absolute svg');

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.setAttribute('class', 'h-6 w-6 text-blue-500');
        } else {
            passwordInput.type = "password";
            icon.setAttribute('class', 'h-6 w-6 text-gray-400');
        }
    }
</script>

</html>