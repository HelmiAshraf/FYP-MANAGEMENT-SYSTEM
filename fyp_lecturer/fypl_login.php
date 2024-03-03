<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $user_id = $_POST["user_id"];
    $password = $_POST["password"];

    include '../db.php'; // Include your database connection file

    // Prepare and execute the query to check user credentials
    $stmt = $conn->prepare("SELECT fl_id, fypl_course FROM fyp_lecturer WHERE fl_id = ? AND password = ?");
    $stmt->bind_param("ss", $user_id, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        // User is authenticated
        $stmt->bind_result($fl_id, $fypl_course);
        $stmt->fetch();

        // Store user information in the session
        $_SESSION["user_id"] = $fl_id;
        $_SESSION["fypl_course"] = $fypl_course;

        header("Location: insight.php"); // Redirect to the dashboard or any desired page
        exit();
    } else {
        $message = "Invalid FYP Lecturer ID or password. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>FYP Course Lecturer Login Page</title>
    <script src=" https://cdn.tailwindcss.com">
    </script>
</head>


<body class="bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">

        <div class="w-full rounded-lg shadow border md:mt-0 sm:max-w-md xl:p-0 bg-gray-800 border-gray-700">
            <div class="flex items-center justify-between px-8 pt-4">
                <div class="flex items-center">
                    <div>
                        <a href="../index.php">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-gray-300 hover:text-blue-500">
                                <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                                <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
                            </svg>
                        </a>
                    </div>
                    <div class="ml-4">
                        <a href="../index.php" class="flex items-center text-2xl font-extrabold text-white">
                            <img src="../assets/uitm.png" class="h-6 mr-2" alt="uitm Logo" />
                            FYPMS
                        </a>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="flex items-center text-xl tracking-tight md:text-2xl text-white font-bold">
                    FYP Course Lecturer Sign in
                </h1>
                <?php if (!empty($message)) : ?>
                    <p class="text-red-500 text-sm"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>

                <form class="space-y-4 md:space-y-6" action="fypl_login.php" method="POST">
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
                    </div>
                    <div>
                        <button type="submit" class="flex w-full bg-blue-700 hover:bg-blue-800 justify-center rounded-lg -md px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign in</button>
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