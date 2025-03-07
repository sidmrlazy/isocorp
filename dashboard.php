<?php
include('includes/header.php');
include('includes/navbar.php'); ?>
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

<div class="dashboard-container">
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
<?php include('includes/footer.php'); ?>