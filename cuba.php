<h1 class="text-2xl font-bold mb-4 mt-4">Submit Task</h1>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg -lg">
    <div class="p-4 bg-gray-800">
        <form action="function/submit_task.php" method="POST" enctype="multipart/form-data">
            <!-- Add an input field for task_id -->
            <input type="hidden" name="task_id" value="<?php echo $_GET['task_id']; ?>">
            <div class="mb-6">
                <label for="files" class="block mb-2 text-sm font-medium text-white">Upload Files</label>
                <input type="file" name="files[]" id="fileInput" multiple onchange="displaySelectedFiles(this.files)" class="block w-full text-sm border rounded-lg cursor-pointer text-gray-400 focus:outline-none bg-gray-700 border-gray-600 placeholder-gray-400" aria-describedby="user_avatar_help">
                <!-- Display selected files -->
                <div id="selected-files"></div>
            </div>
            <input type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark-bg-blue-600 dark:hover-bg-blue-700 dark:focus-ring-blue-800" value="Submit Task">
        </form>


    </div>
</div>
<script>
    function displaySelectedFiles(files) {
        var displayDiv = document.getElementById("selected-files");

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var fileName = file.name;
            var fileSize = (file.size / 1024).toFixed(2) + " KB"; // Display file size in KB

            var fileInfo = document.createElement("p");
            fileInfo.textContent = "Selected File " + (i + 1) + ": " + fileName + " (" + fileSize + ")";
            displayDiv.appendChild(fileInfo);
        }
    }
</script>