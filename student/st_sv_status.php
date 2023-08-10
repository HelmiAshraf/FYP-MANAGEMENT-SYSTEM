<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choosen Supervisor Status</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="bg-white text-left p-2 outline outline-1 outline-gray-300">
        <h1 class="text-2xl font-semibold font-sans">Chosen Supervisor Status</h1>
    </div>
    <div class="container mx-auto p-4">


        <div class="grid gap-4">
            <h2 class="text-xl font-semibold mt-2">Supervisor Details</h2>
            <!-- Chosen Supervisor and Status -->
            <div class="grid grid-cols-3 gap-4">
                <!-- Chosen Supervisor Details -->
                <div class="bg-white p-6 rounded-md shadow-md col-span-2">
                    <div class="flex items-center mb-6">
                        <img src="supervisor.jpg" alt="Supervisor" class="w-32 h-32 rounded-full mr-4">
                        <div>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-800 text-lg">Dr. Ahmad Fadli Bin Saad</p>
                                <p class="text-gray-600">Email: ahmad@gmail.com</p>
                                <p class="text-gray-600">Phone Number: +0134753796</p>
                            </div>
                            <div class="ml-4 mt-2">
                                <p class="font-semibold text-gray-800 text-lg">Expertise</p>
                                <p class="text-gray-600">Mobile computing, IoT, Data analytics, Deep Learning, Enterprise Computing</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status of Application -->
                <div class="bg-white p-6 rounded-md shadow-md">
                    <p class="font-semibold text-gray-800 text-lg">Application Status</p>
                    <p class="text-green-500 font-semibold">Accepted</p>
                </div>
            </div>

            <!-- Additional Notes -->
            <h2 class="text-xl font-semibold mt-2">Notes</h2>
            <div class="bg-white p-4 rounded-md shadow-md">
                <p class="text-gray-600 text-justify">Lepas ni jumpa saya di office saya. kamu terangkan lebih detail berkenaan project kamu ni.
                </p>
            </div>

            <!-- Change Supervisor and Confirm Supervisor Buttons -->
            <div class="flex justify-end space-x-4 mt-4 mb-14">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none">
                    Change Supervisor
                </button>
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none" onclick="showConfirmation('Are you sure you want to confirm this supervisor?')">Confirm Supervisor</button>
            </div>
        </div>
    </div>

    <!-- Confirmation Popup -->
    <div id="confirmationModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-2">Confirmation</h2>
            <p id="confirmationText" class="text-gray-700"></p>
            <div class="flex justify-end mt-4">
                <button id="confirmButton" class="bg-blue-500 text-white px-2 py-1 rounded-md hover:bg-blue-700 focus:outline-none mr-2" onclick="confirmSupervisor()">Confirm</button>
                <button class="bg-gray-500 text-white px-2 py-1 rounded-md hover:bg-gray-700 focus:outline-none" onclick="closeConfirmation()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        function showConfirmation(description) {
            document.getElementById('confirmationText').innerText = description;
            document.getElementById('confirmationModal').classList.remove('hidden');
        }

        function closeConfirmation() {
            document.getElementById('confirmationModal').classList.add('hidden');
        }

        function confirmSupervisor() {
            // TODO: Add code to confirm the supervisor
            window.location.href = "st_home.php";
        }
    </script>
</body>

</html>