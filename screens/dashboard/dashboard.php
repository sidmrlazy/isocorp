<?php
$user_name = isset($_COOKIE['user_name']) ? $_COOKIE['user_name'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest');
$user_role = isset($_COOKIE['user_role']) ? $_COOKIE['user_role'] : (isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Guest');
?>
<div class="dashboard-container">
    <?php if ($user_role == '1') { ?>
        <div class="dashboard-welcome-tab">
            <h1>Welcome, <?php echo htmlspecialchars($user_name); ?></h1>
            <!-- <p>Admin</p> -->
        </div>
    <?php } elseif ($user_role == '2') { ?>
        <div class="dashboard-welcome-tab">
            <h1>Welcome, <?php echo htmlspecialchars($user_name); ?></h1>
            <!-- <p>Read Only</p> -->
        </div>
    <?php } else { ?>
        <div class="dashboard-welcome-tab">
            <h1>Welcome, <?php echo htmlspecialchars($user_name); ?></h1>
            <p>Unauthorized Access</p>
        </div>
    <?php } ?>
</div>
