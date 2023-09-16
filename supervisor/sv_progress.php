<?php
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include 'includes/sv_sidebar.php';

?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Create a canvas element for the chart -->
<canvas id="progressChart" width="400" height="200"></canvas>
<script src="../script/progress.js"></script>

<!-- content end -->
</div>
</div>
</div>
</body>

</html>