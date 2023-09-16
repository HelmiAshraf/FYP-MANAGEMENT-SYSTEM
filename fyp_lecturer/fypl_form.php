<?php
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'includes/sidebar.php';

?>

<p>fyp form</p>

<!-- content end -->
</div>
</div>
</div>
</body>

</html>