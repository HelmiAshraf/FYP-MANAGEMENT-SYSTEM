<?php
// start a session 
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

include '../db.php';

// Retrieve user data based on the user's login ID
$sidenav_user_id = $_SESSION['user_id']; // Assuming you store the login ID in a session variable

// Prepare and execute a query to retrieve user details
$sql = "SELECT sv_name, sv_email, TO_BASE64(sv_image) AS sv_image_base64 FROM supervisor WHERE sv_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $sidenav_user_id);

if ($stmt->execute()) {
    // Bind the results to variables
    $stmt->bind_result($user_name, $user_email, $sv_image_base64);

    // Fetch the data
    $stmt->fetch();

    // Close the statement
    $stmt->close();
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
    <title>FYPMS - Supervisor</title>
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
                        <a href="https://flowbite.com" class="flex items-center ">
                            <img src=".././assets/uitm.png" class="h-8 mr-3" alt="FlowBite Logo" />
                            <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-white mr-2">FYPMS</span>
                            <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-gray-200 italic mr-5">Supervisor</span>
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
                        <div>
                            <p class="text-sm text-gray-300 font-semibold" role="none">
                                <?php echo $user_name; // Display the user's name 
                                ?>
                            </p>
                        </div>
                        <div class="ml-4"> <!-- Added margin-left for spacing -->
                            <button type="button" class="flex text-sm bg-gray-800 rounded-lg -full focus:ring-4 focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                <span class="sr-only">Open user menu</span>
                                <img class="w-8 h-8 rounded-lg -full" src="data:image/jpeg;base64,<?php echo $sv_image_base64; ?>" alt="User Photo">
                            </button>
                        </div>
                        <!-- ... Rest of your code ... -->
                    </div>
                </div>
                <div class="z-50 hidden my-4 text-base list-none divide-y rounded-lg  shadow bg-gray-700 divide-gray-600" id="dropdown-user">
                    <div class="px-4 py-3" role="none">
                        <p class="text-sm text-white" role="none">
                            <?php echo $user_name; // Display the user's name 
                            ?>
                        </p>
                        <p class="text-sm font-medium truncate text-gray-300" role="none">
                            <?php echo $user_email; // Display the user's email 
                            ?>
                        </p>
                    </div>
                    <ul class="py-1" role="none">
                        <li>
                            <a href="./sv_profile.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 hover:text-white" role="menuitem">Profile</a>
                        </li>
                        <li>
                            <a href=".././logout.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600 hover:text-white" role="menuitem">Sign out</a>
                        </li>
                    </ul>
                    <script type="text/javascript" src=".././script/dropdown.js"></script>
                </div>
            </div>
        </div>
        </div>
        </div>
    </nav>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full border-r  sm:translate-x-0 bg-gray-800 border-gray-700" aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-gray-800">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="sv_progress.php" class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 transition duration-75 text-gray-400 group-hover:text-white">
                            <path fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-3">Progress</span>
                    </a>
                </li>
                <li>
                    <a href="sv_proposal.php" class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 transition duration-75 text-gray-400 group-hover:text-white">
                            <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-.05A3 3 0 0015 1.5h-1.5a3 3 0 00-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zM6 12a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V12zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM6 15a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V15zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM6 18a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V18zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                        </svg>
                        <span class="flex-1 ml-3 whitespace-nowrap">Proposal</span>
                    </a>
                </li>
                <li>
                    <a href="sv_task.php" class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 transition duration-75 text-gray-400 group-hover:text-white">
                            <path d="M5.625 3.75a2.625 2.625 0 100 5.25h12.75a2.625 2.625 0 000-5.25H5.625zM3.75 11.25a.75.75 0 000 1.5h16.5a.75.75 0 000-1.5H3.75zM3 15.75a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75zM3.75 18.75a.75.75 0 000 1.5h16.5a.75.75 0 000-1.5H3.75z" />
                        </svg>
                        <span class="ml-3">Task</span>
                    </a>
                </li>
                <li>
                    <a href="sv_student.php" class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 transition duration-75 text-gray-400 group-hover:text-white">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                        </svg>
                        <span class="flex-1 ml-3 whitespace-nowrap">Student</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <div class="p-4 sm:ml-64">

        <div class="p-4 mt-10">

            <div class="">
                <!-- content will show here -->