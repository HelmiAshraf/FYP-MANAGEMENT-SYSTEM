<?php
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include 'includes/sv_sidebar.php';

?>

<p>proposal</p>

<!-- content end -->
</div>
</div>
</div>
</body>

</html>