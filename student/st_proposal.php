<?php
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include 'includes/st_sidebar.php';

?>

<h1>Name Proposal Page</h1>
    <p>Welcome to the Name Proposal page! Here, you can suggest names for a project, team, or any other exciting endeavor.</p>
    <!-- Your name proposal form and content can go here -->

<!-- content end -->
</div>
</div>
</div>
</body>

</html>