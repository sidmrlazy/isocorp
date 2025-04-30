<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php'; ?>
<div class="dashboard-container">
    <?php
    if (isset($_GET['id'])) {
        $id = mysqli_real_escape_string($connection, $_GET['id']);
        $query = "SELECT * FROM control_history WHERE ctrl_h_id = '$id'";
        $result = mysqli_query($connection, $query);
        if ($row = mysqli_fetch_assoc($result)) { ?>
            <h4>Previous Details</h4>
            <p> <?php echo $row['ctrl_h_pol_old_det']; ?> </p>

    <?php } else {
            echo "<p>No details found.</p>";
        }
    } else {
        echo "<p>Invalid request.</p>";
    }
    ?>
</div>
<?php include('includes/footer.php'); ?>