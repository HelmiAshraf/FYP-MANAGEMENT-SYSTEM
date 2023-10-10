<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Supervisor List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200">
    <div class="bg-white text-left p-2 outline outline-1 outline-gray-300">
        <h1 class="text-2xl font-semibold font-sans subpixel-antialiased tracking-wide text-gray-600">Available Supervisor List</h1>
    </div>

    <div class="container mx-auto p-3">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
            <table class="w-full text-sm text-left text-gray-500 bg-white border border-gray-300">
                <thead class="text-xs text-black uppercase bg-gray-300">
                    <tr class="">
                        <th class="py-2 px-4 border border-gray-400">Name</th>
                        <th class="py-2 px-4 border border-gray-400">HP Number</th>
                        <th class="py-2 px-4 border border-gray-400">Email</th>
                        <th class="py-2 px-4 border border-gray-400">Faculty</th>
                        <th class="py-2 px-4 border border-gray-400">Research Interest / Expertise</th>
                        <th class="py-2 px-4 border border-gray-400 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include '../db.php';

                    $sql = "SELECT
                    s.sv_id,
                    s.sv_name,
                    s.sv_email,
                    s.sv_phnum,
                    s.sv_expertise,
                    s.sv_image,
                    s.sv_status,
                    s.sv_quota,
                    COUNT(v.supervisor_id) AS current_students
                FROM
                    supervisor s
                LEFT JOIN
                    supervise v ON s.sv_id = v.supervisor_id
                GROUP BY
                    s.sv_id, s.sv_name, s.sv_email, s.sv_phnum, s.sv_expertise, s.sv_image, s.sv_status, s.sv_quota
                HAVING
                    current_students <= s.sv_quota;";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='py-2 px-4 border border-gray-300'>" . $row["supervisor_image"] . "</td>";
                            echo "<td class='py-2 px-4 border border-gray-300'>" . $row["supervisor_name"] . "</td>";
                            echo "<td class='py-2 px-4 border border-gray-300'>" . $row["supervisor_phnum"] . "</td>";
                            echo "<td class='py-2 px-4 border border-gray-300'>" . $row["supervisor_email"] . "</td>";
                            echo "<td class='py-2 px-4 border border-gray-300'>" . $row["supervisor_faculty"] . "</td>";
                            echo "<td class='py-2 px-4 border border-gray-300'>" . $row["supervisor_expertise"] . "</td>";
                            echo "<td class='py-2 px-4 border border-gray-300'>
                        <button class='bg-[#114c61] text-white px-2 py-1 rounded-lg -md hover:bg-[#147497] focus:outline-none'
                            onclick='showDescription(\"" . $row["supervisor_description"] . "\")'>
                            Choose
                        </button>
                    </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='py-2 px-4 text-center'>No supervisors found.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>

        </div>
    </div>

    <div id="descriptionModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-2">Supervisor Description</h2>
            <p id="descriptionText" class="text-gray-700"></p>
            <button class="mt-4 bg-blue-500 text-white px-2 py-1 rounded-lg -md hover:bg-blue-700 focus:outline-none" onclick="closeDescription()">Close</button>
        </div>
    </div>

    <script>
        function showDescription(description) {
            document.getElementById('descriptionText').innerText = description;
            document.getElementById('descriptionModal').classList.remove('hidden');
        }

        function closeDescription() {
            document.getElementById('descriptionModal').classList.add('hidden');
        }
    </script>
</body>

</html>