<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />

    <title>Registration</title>
</head>

<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 px-4 pb-4 rounded-lg shadow-md w-2/3">

        <div class="flex">
            <div class="mb-4 border-b border-gray-700 w-full">
                <ul class="w-full flex flex-wrap text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
                    <li class="w-1/2" role="presentation">
                        <button class="w-full inline-block p-4 border-b rounded-t-lg hover:text-gray-600 hover:border-gray-300 hover:text-gray-300" id="profile-tab" data-tabs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Student</button>
                    </li>
                    <li class="w-1/2" role="presentation">
                        <button class="w-full inline-block p-4 border-b rounded-t-lg hover:text-gray-600 hover:border-gray-300 hover:text-gray-300" id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">Supervisor</button>
                    </li>
                </ul>
            </div>
        </div>


        <div id="default-tab-content">
            <div class="hidden px-4 pb-4  rounded-lg bg-gray-800" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="flex ">
                    <div class="flex items-center justify-center mr-5">
                        <a href="index.php" class="mb-6 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7 hover:text-blue-300">
                                <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    <div>
                        <h1 class="flex  justify-center text-3xl font-extrabold text-white mb-6">
                            Student Register
                        </h1>
                    </div>
                </div>

                <!-- Student Registration Form -->
                <form id="studentForm" action="student/st_register.php" method="post" enctype="multipart/form-data">
                    <div class="flex mb-3 ">
                        <!-- Left Div: Profile Picture Upload and Student ID -->
                        <div class=" flex items-center justify-center">
                            <div class="mr-4 items-center flex flex-col ">
                                <label for="profile_picture" class="block mb-4 text-sm font-medium text-white text-center">
                                    Profile Picture
                                </label>
                                <div class="mb-2 flex items-center justify-center">
                                    <img id="image_previewst" src="assets/default_pfp.png" alt="Profile Preview" class="rounded-lg max-w-40 h-40">
                                </div>
                                <div class="items-center mt-3">
                                    <input type="file" name="files[]" accept="image/*" id="fileInputst" onchange="previewImagest()" class="block text-sm rounded-lg cursor-pointer text-gray-400 focus:outline-none bg-gray-700 border-gray-600 placeholder-gray-400" aria-describedby="file_input_help" id="file_input" type="file" required>
                                    <p class="mt-1 text-sm text-gray-300" id="file_input_help">PNG, JPEG, JPG (MAX. 800x400px).</p>
                                </div>
                            </div>
                        </div>
                        <!-- Right Div: Name, Phone Number, and Password -->
                        <div class="flex-1 pl-8 ">
                            <div class="flex flex-col mb-2">
                                <label for="student_id" class="mb-2 text-sm font-medium text-white">Student ID</label>
                                <input type="text" name="student_id" id="student_id" class="shadow-sm border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm-light" placeholder="Make sure it is the same as the student's ID card" required>
                            </div>
                            <div class="flex flex-col mb-2">
                                <label for="name" class="mb-2 text-sm font-medium text-white">Name</label>
                                <input type="text" name="st_name" class="shadow-sm border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm-light" placeholder="HELMI ASHRAF" required>
                            </div>
                            <div class="flex flex-col mb-2">
                                <label for="st_email" class="mb-2 text-sm font-medium text-white">Email (Enter a valid email for verification)</label>
                                <input type="text" name="st_email" class="shadow-sm border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm-light" placeholder="FYPMS@gmail.com" required>
                            </div>
                            <div class="flex flex-row space-x-4 mb-2 ">
                                <div class=" w-full">
                                    <label for="password" class="mb-2 text-sm font-medium text-white">Password (at least 6 characters)</label>
                                    <input type="password" name="password" id="passwordst" class="shadow-sm border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm-light" placeholder="••••••" required>
                                </div>
                                <div class=" w-full">
                                    <label for="passwordConfirm" class="mb-2 text-sm font-medium text-white">Confirm Password</label>
                                    <input type="password" id="confirm_passwordst" class="shadow-sm border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm-light" placeholder="••••••" required>
                                </div>
                            </div>
                            <div id="validationst" style="display:none;" class="mx-auto w-fit flex items-center  text-sm rounded-lg bg-gray-800 text-red-400" role="alert">
                                <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                </svg>
                                <div>
                                    Passwords do not match
                                </div>
                            </div>
                            <div id="validationst2" style="display:none;" class="mx-auto w-fit flex items-center mb-1 text-sm rounded-lg bg-gray-800 text-red-400" role="alert">
                                <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                </svg>
                                <div>
                                    Passwords must be more than 6 character
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-center justify-center mt-6">
                        <button type="submit" name="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-lg px-10 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">Sign Up</button>
                        <label class="mt-4 text-sm font-medium text-gray-300">Already have acoount? <a href="login.php" class="hover:underline text-blue-500">Log In</a></label>
                    </div>
                </form>
            </div>

            <div class="hidden px-4 pb-4 rounded-lg bg-gray-800" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                <div class="flex ">
                    <div class="flex items-center justify-center mr-5">
                        <a href="index.php" class="mb-6 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7 hover:text-blue-300">
                                <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    <div>
                        <h1 class="flex  justify-center text-3xl font-extrabold text-white mb-6">
                            Supervisor Register
                        </h1>
                    </div>
                </div>


                <!-- Supervisor Registration Form -->
                <form id="supervisorForm" action="supervisor/sv_register.php" method="post" enctype="multipart/form-data">
                    <div class="flex mb-3 ">
                        <!-- Left Div: Profile Picture Upload and Student ID -->
                        <div class=" flex items-center justify-center">
                            <div class="mr-4 items-center flex flex-col">
                                <label for="profile_picture" class="block mb-4 text-sm font-medium text-white text-center">
                                    Profile Picture
                                </label>
                                <div class="mb-2 flex items-center justify-center">
                                    <img id="image_previewsv" src="assets/default_pfp.png" alt="Profile Preview" class="rounded-lg max-w-40 h-40">
                                </div>
                                <div class="items-center mt-3">
                                    <input type="file" name="files[]" accept="image/*" id="fileInputsv" onchange="previewImagesv()" class="block text-sm rounded-lg cursor-pointer text-gray-400 focus:outline-none bg-gray-700 border-gray-600 placeholder-gray-400" aria-describedby="file_input_help" id="file_input" required>
                                    <p class="mt-1 text-sm text-gray-300" id="file_input_help">PNG, JPEG, JPG (MAX. 800x400px).</p>
                                </div>
                            </div>
                        </div>
                        <!-- Right Div: Name, Phone Number, and Password -->
                        <div class="flex-1 pl-8 ">
                            <div class="flex flex-col mb-2">
                                <label for="sv_id" class="mb-2 text-sm font-medium text-white">Supervisor ID</label>
                                <input type="text" name="sv_id" id="sv_id" class="shadow-sm border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm-light" placeholder="Make sure it is the same as the lecturer's ID card" required>
                            </div>
                            <div class="flex flex-col mb-2">
                                <label for="name" class="mb-2 text-sm font-medium text-white">Name</label>
                                <input type="text" name="sv_name" class="shadow-sm border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm-light" placeholder="HELMI ASHRAF" required>
                            </div>
                            <div class="flex flex-col mb-2">
                                <label for="sv_email" class="block mb-2 text-sm font-medium text-white">Email (Enter a valid email for verification)</label>
                                <input type="text" name="sv_email" class="shadow-sm border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm-light" placeholder="FYPMS@gmail.com" required>
                            </div>
                            <div class="flex flex-col mb-2">
                                <label for="sv_expertise" class="block mb-2 text-sm font-medium text-white">Expertise</label>
                                <textarea name="sv_expertise" rows="4" class="shadow-sm border text-sm rounded-lg block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm-light" placeholder="Web Dev..." required></textarea>
                            </div>
                            <div class="flex flex-row space-x-4 mb-2">
                                <div class=" w-full">
                                    <label for="password" class="mb-2 text-sm font-medium text-white">Password (at least 6 characters)</label>
                                    <input type="password" name="password" id="passwordsv" class="shadow-sm border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm-light" placeholder="••••••" required>
                                </div>
                                <div class=" w-full">
                                    <label for="passwordConfirm" class="mb-2 text-sm font-medium text-white">Confirm Password</label>
                                    <input type="password" id="confirm_passwordsv" class="shadow-sm border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm-light" placeholder="••••••" required>
                                </div>
                            </div>
                            <div id="validationsv" style="display:none;" class="mx-auto w-fit flex items-center  text-sm rounded-lg bg-gray-800 text-red-400" role="alert">
                                <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                </svg>
                                <div>
                                    Passwords do not match
                                </div>
                            </div>
                            <div id="validationsv2" style="display:none;" class="mx-auto w-fit flex items-center mb-1 text-sm rounded-lg bg-gray-800 text-red-400" role="alert">
                                <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                </svg>
                                <div>
                                    Passwords must be more than 6 character
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-center justify-center mt-6">
                        <button type="submit" name="submit" class="text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-lg px-10 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">Sign Up</button>
                        <label class="mt-4 text-sm font-medium text-gray-300">Already have acoount? <a href="login.php" class="hover:underline text-blue-500">Log In</a></label>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Initially disable the submit button
            $("button[type='submit']").prop("disabled", true);

            $("#confirm_passwordst, #passwordst").on("keyup", function() {
                var password = $("#passwordst").val();
                var confirmPassword = $("#confirm_passwordst").val();

                // Check if passwords match
                if (confirmPassword !== "" && password !== confirmPassword) {
                    $("#validationst").show();
                    $("button[type='submit']").prop("disabled", true);
                } else {
                    $("#validationst").hide();
                }

                // Check if password length is less than 6
                var passwordLength = password.length;
                if (passwordLength < 6) {
                    $("#validationst2").show();
                    $("button[type='submit']").prop("disabled", true);
                } else {
                    $("#validationst2").hide();
                }

                // Enable or disable submit button based on validations
                if (passwordLength >= 6 && password === confirmPassword) {
                    $("button[type='submit']").prop("disabled", false);
                } else {
                    $("button[type='submit']").prop("disabled", true);
                }
            });

            $("#showPw").click(function() {
                var passInput = $("#passwordst, #confirm_passwordst");
                if (passInput.attr("type") === "passwordst") {
                    passInput.attr("type", "text");
                    $("#showHide").html("Hide");
                } else {
                    passInput.attr("type", "passwordst");
                    $("#showHide").html("Show");
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Initially disable the submit button
            $("button[type='submit']").prop("disabled", true);

            $("#confirm_passwordsv, #passwordsv").on("keyup", function() {
                var password = $("#passwordsv").val();
                var confirmPassword = $("#confirm_passwordsv").val();

                // Check if passwords match
                if (confirmPassword !== "" && password !== confirmPassword) {
                    $("#validationsv").show();
                    $("button[type='submit']").prop("disabled", true);
                } else {
                    $("#validationsv").hide();
                }

                // Check if password length is less than 6
                var passwordLength = password.length;
                if (passwordLength < 6) {
                    $("#validationsv2").show();
                    $("button[type='submit']").prop("disabled", true);
                } else {
                    $("#validationsv2").hide();
                }

                // Enable or disable submit button based on validations
                if (passwordLength >= 6 && password === confirmPassword) {
                    $("button[type='submit']").prop("disabled", false);
                } else {
                    $("button[type='submit']").prop("disabled", true);
                }
            });

            $("#showPw").click(function() {
                var passInput = $("#passwordsv, #confirm_passwordsv");
                if (passInput.attr("type") === "passwordsv") {
                    passInput.attr("type", "text");
                    $("#showHide").html("Hide");
                } else {
                    passInput.attr("type", "passwordsv");
                    $("#showHide").html("Show");
                }
            });
        });
    </script>

    <script>
        function showForm(type) {
            if (type === 'student') {
                document.getElementById('studentForm').style.display = 'block';
                document.getElementById('supervisorForm').style.display = 'none';
            } else if (type === 'supervisor') {
                document.getElementById('studentForm').style.display = 'none';
                document.getElementById('supervisorForm').style.display = 'block';
            }
        }
    </script>

    <script>
        function previewImagest() {
            var preview = document.getElementById('image_previewst');
            var fileInput = document.getElementById('fileInputst');
            var file = fileInput.files[0];
            var reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "default_profile_picture.jpg";
            }
        }
    </script>

    <script>
        function previewImagesv() {
            var preview = document.getElementById('image_previewsv');
            var fileInput = document.getElementById('fileInputsv');
            var file = fileInput.files[0];
            var reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "default_profile_picture.jpg";
            }
        }
    </script>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

</html>