<?php
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include 'includes/st_sidebar.php';

?>
<h1>Task Page</h1>
<p>This is the Task page where you can manage and keep track of various tasks and to-dos.</p>
<!-- Your task management interface, task lists, and related content can go here -->

<!-- content end -->
</div>
</div>
</div>
</body>

</html>