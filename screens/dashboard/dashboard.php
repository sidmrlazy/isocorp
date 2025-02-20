<?php
$user_name = isset($_COOKIE['user_name']) ? $_COOKIE['user_name'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest');
?>
<div class="dashboard-container">
    <div class="dashboard-welcome-tab">
        <h1>Welcome, <?php echo htmlspecialchars($user_name); ?></h1>
    </div>


    <!-- <div>
        <p>Activity Log</p>
    </div> -->
</div>