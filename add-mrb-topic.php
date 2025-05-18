<?php
include('includes/header.php');
include('includes/navbar.php');
include('includes/auth_check.php');
?>
<div class="dashboard-container">
    <div class="screen-name-container">
        <h1>MANAGEMENT REVIEW BOARD SETUP</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Management Review Board Setup</h2>
    </div>
    <div class="section-divider mb-5 mt-3">
        <?php include 'screens/mrb/section-2.php'; ?>
        <?php include 'screens/mrb/section-3.php'; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>