<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
?>
<div class="dashboard-container">
    <?php include 'screens/policies/edit-policy-details.php'; ?>
    <div class="section-row">
        <?php include 'screens/policies/upload-supporting-content.php'; ?>
        <?php include 'screens/policies/history.php'; ?>
    </div>
</div>
<?php include('includes/footer.php'); ?>