<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Side Navigation Example</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f1f1f1] h-screen overflow-hidden">

    <header class="bg-[#64c5b1] text-white  text-left py-2 px-6">
        <h1 class="text-2xl font-medium font-serif">FYPMS</h1>
    </header>
    

    <div class="flex">
        <div class="w-1/6 bg-[#374151]  h-screen shadow-lg font-sans font-medium text-gray-300 ">
            <p class=" text-sm font-serif px-6 pt-2">NAVIGATION</p>
            <!-- Side navigation links -->
            <a href="#" onclick="loadContent('st_sv_list.php');
            return false;" class="block p-2 px-8  hover:bg-[#132533] hover:text-white">
                Available Supervisor
            </a>
            <a href="#" onclick="loadContent('st_sv_status.php');
            return false;" class="block p-2 px-8 hover:bg-[#132533] hover:text-white">
                Supervisor Status
            </a>
            <!-- Add more navigation links as needed -->
        </div>

        <div class="w-3/4 flex-1 h-screen overflow-y-auto" id="content">
            <?php include 'st_sv_list.php'; ?>
            <div id="stSvStatus" style="display: none;">
                <?php include 'st_sv_status.php'; ?>
            </div>
        </div>

    </div>


    <script>
        function loadContent(url) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        document.getElementById('content').innerHTML = xhr.responseText;
                    }
                }
            };
            xhr.open('GET', url, true);
            xhr.send();
        }
    </script>
</body>

</html>