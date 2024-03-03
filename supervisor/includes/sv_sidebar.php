<?php
// start a session 
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: .././logout.php");
    exit();
}

include '../db.php';

$user_id = $_SESSION['user_id'];

// Prepare and execute a query to retrieve user details including the image path
$sql = "SELECT sv_name, sv_email, sv_image FROM supervisor WHERE sv_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    // Bind the results to variables
    $stmt->bind_result($user_name, $user_email, $sv_image_path);

    // Fetch the data
    $stmt->fetch();

    // Close the statement
    $stmt->close();

    // Check if the image path exists
    if (file_exists($sv_image_path)) {
        // Read the image content and convert it to base64
        $sv_image_base64 = base64_encode(file_get_contents($sv_image_path));
    } else {
        // Set a default image if the path doesn't exist
        $default_image_path = '../file/image/user.png';
        $sv_image_base64 = base64_encode(file_get_contents($default_image_path));
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
    <title>FYPMS - Supervisor</title>
</head>

<body class="bg-gray-100">

    <!-- <nav class="fixed top-0 z-50 w-full border-b bg-indigo-800 border-gray-700"> -->
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
                        <a href="" class="flex items-center ">
                            <img src=".././assets/uitm.png" class="h-8 mr-3" alt="UITM Logo" />
                            <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-white mr-2">FYPMS</span>
                        </a>
                        <!-- Second element -->
                        <a href=".././FYP-Computing-Essential.pdf" download="FYP-Computing-Essential.pdf" class="ml-2 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2">
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
                            <button type="button" class="flex text-sm bg-gray-800 rounded-lg -full focus:ring-4 focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user" data-dropdown-placement="bottom-start">
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
                    <a href="sv_supervisee.php" class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 transition duration-75 text-gray-400 group-hover:text-white">
                            <path fill-rule="evenodd" d="M2.25 5.25a3 3 0 0 1 3-3h13.5a3 3 0 0 1 3 3V15a3 3 0 0 1-3 3h-3v.257c0 .597.237 1.17.659 1.591l.621.622a.75.75 0 0 1-.53 1.28h-9a.75.75 0 0 1-.53-1.28l.621-.622a2.25 2.25 0 0 0 .659-1.59V18h-3a3 3 0 0 1-3-3V5.25Zm1.5 0v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5Z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-3">Supervisee</span>
                    </a>
                </li>
                <li>
                    <a href="sv_assignment.php" class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 transition duration-75 text-gray-400 group-hover:text-white">
                            <path d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z" />
                            <path d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z" />
                            <path d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z" />
                        </svg>
                        <span class="flex-1 ml-3 whitespace-nowrap">Assignment</span>
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
                    <a href="sv_st_propose.php" class="flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 transition duration-75 text-gray-400 group-hover:text-white">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                        </svg>
                        <span class="flex-1 ml-3 whitespace-nowrap">Propose Project</span>
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