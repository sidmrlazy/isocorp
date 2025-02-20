<?php
$user_name = isset($_COOKIE['user_name']) ? $_COOKIE['user_name'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest');
?>


<div class="dashboard-container">
    <!-- <div class="dashboard-welcome-tab">
        <h1>Welcome, <?php echo htmlspecialchars($user_name); ?></h1>
    </div> -->

    <!-- ============ SCREEN NAME ============ -->
    <div class="screen-name-container">
        <h1>ISO 27001:2022 POLICIES & CONTROLS</h1>
        <h2><a href="dashboard.php">Dashboard</a> > ISO 27001:2022 POLICIES & CONTROLS</h2>
    </div>

    <!-- ============ CONTROLS AND POLICIES ============ -->
    <div class="center-aligned-container">
        <div class="policies-control-container">
            <h3>Overview</h3>
            <p>A framework for implementing and overseeing all requirements, controls, and policies related to ISO
                27001:2022.</p>
            <?php include('screens/policies/policies.php') ?>
        </div>
    </div>
</div>


