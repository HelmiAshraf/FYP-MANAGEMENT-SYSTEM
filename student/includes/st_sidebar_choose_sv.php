<?php
// start a session 
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: .././logout.php");
    exit();
}

include '../db.php';

// Retrieve user data based on the user's login ID
$user_id = $_SESSION['user_id']; // Assuming you store the login ID in a session variable

// Prepare and execute a query to retrieve user details including the image path
$sql = "SELECT st_name, st_email, st_image FROM student WHERE st_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    // Bind the results to variables
    $stmt->bind_result($user_name, $user_email, $st_image_path);

    // Fetch the data
    $stmt->fetch();

    // Close the statement
    $stmt->close();

    // Check if the image path exists
    if (file_exists($st_image_path)) {
        // Read the image content and convert it to base64
        $st_image_base64 = base64_encode(file_get_contents($st_image_path));
    } else {
        // Set a default image if the path doesn't exist
        $default_image_path = '../file/image/user.png';
        $st_image_base64 = base64_encode(file_get_contents($default_image_path));
    }
} else {
    echo "Error executing the query: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css" rel="stylesheet" />
    <title>FYPMS - Student</title>
</head>

<body class="bg-gray-100">

    <nav class="fixed top-0 z-50 w-full border-b bg-gray-800 border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm rounded-lg sm:hidden focus:outline-none focus:ring-2 text-gray-400 hover:bg-gray-700 focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    <div class="flex items-center ">
                        <!-- First element -->
                        <a href="./st_available_sv.php" class="flex items-center ">
                            <img src=".././assets/uitm.png" class="h-8 mr-3" alt="FlowBite Logo" />
                            <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-white mr-2">FYPMS</span>
                        </a>
                        <!-- Second element -->
                        <a href="#" download="FYP-Computing-Essential.pdf" class="ml-2 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 ">
                            <button class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 hidden sm:flex">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                FYP Computing Essential
                            </button>
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ml-3">
                        <div class="ml-4">
                            <p class="text-sm text-gray-300 font-semibold" role="none">
                                <?php echo $user_name; // Display the user's name 
                                ?>
                            </p>
                        </div>
                        <div class="ml-4">
                            <button type="button" class="flex text-sm bg-gray-800 rounded-lg -full focus:ring-4 focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user" data-dropdown-placement="bottom-start">
                                <span class="sr-only">Open user menu</span>
                                <img class="w-8 h-8 rounded-lg -full" src="data:image/jpeg;base64,<?php echo $st_image_base64; ?>" alt="User Photo">
                            </button>
                        </div>
                    </div>
                </div>
                <div class="z-50 hidden my-4 text-base list-none divide-y rounded-lg  shadow bg-gray-700 divide-gray-600" id="dropdown-user">
                    <div class="px-4 py-3" role="none">
                        <p class="text-sm text-white" role="none">
                            <?php echo $user_name; // Display the user's name 
                            ?>
                        </p>
                        <p class="text-sm font-medium truncate text-gray-300" role="none">
                            <?php echo $user_id; // Display the user's email 
                            ?>
                        </p>
                    </div>
                    <ul class="py-1" role="none">
                        <li>
                            <a href="./st_profile_choose.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 hover:text-white" role="menuitem">Profile</a>
                        </li>
                        <li>
                            <a href=".././logout.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 hover:text-white" role="menuitem">Sign out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full border-r  sm:translate-x-0 bg-gray-800 border-gray-700" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-gray-800">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="st_available_sv.php" class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 transition duration-75 text-gray-400 group-hover:text-white">
                            <path fill-rule="evenodd" d="M2.625 6.75a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875 0A.75.75 0 018.25 6h12a.75.75 0 010 1.5h-12a.75.75 0 01-.75-.75zM2.625 12a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zM7.5 12a.75.75 0 01.75-.75h12a.75.75 0 010 1.5h-12A.75.75 0 017.5 12zm-4.875 5.25a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875 0a.75.75 0 01.75-.75h12a.75.75 0 010 1.5h-12a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-3">Available Supervisor</span>
                    </a>
                </li>
                <li>
                    <a href="st_sv_status.php" class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 transition duration-75 text-gray-400 group-hover:text-white">
                            <path fill-rule="evenodd" d="M4.125 3C3.089 3 2.25 3.84 2.25 4.875V18a3 3 0 003 3h15a3 3 0 01-3-3V4.875C17.25 3.839 16.41 3 15.375 3H4.125zM12 9.75a.75.75 0 000 1.5h1.5a.75.75 0 000-1.5H12zm-.75-2.25a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5H12a.75.75 0 01-.75-.75zM6 12.75a.75.75 0 000 1.5h7.5a.75.75 0 000-1.5H6zm-.75 3.75a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5H6a.75.75 0 01-.75-.75zM6 6.75a.75.75 0 00-.75.75v3c0 .414.336.75.75.75h3a.75.75 0 00.75-.75v-3A.75.75 0 009 6.75H6z" clip-rule="evenodd" />
                            <path d="M18.75 6.75h1.875c.621 0 1.125.504 1.125 1.125V18a1.5 1.5 0 01-3 0V6.75z" />
                        </svg>

                        <span class="flex-1 ml-3 whitespace-nowrap">Supervisor Result</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.1.1/flowbite.min.js"></script>
    <div class="p-4 sm:ml-64">

        <div class="p-4 mt-8">

            <div class="">
                <!-- content will show here -->