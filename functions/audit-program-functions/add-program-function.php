<div class="dashboard-container">
    <?php
    if (isset($_POST['add-pr'])) {
        $ap_name = mysqli_real_escape_string($connection, $_POST['ap_name']);
        $add_ap_name = "INSERT INTO `audit_program`(`ap_name`) VALUES ('$ap_name')";
        $add_ap_name_r = mysqli_query($connection, $add_ap_name);
        if ($add_ap_name_r) { ?>
            <div class="alert alert-success mb-3" id="alertBox" role="alert">Programme name added successfully!</div>
    <?php }
    }
    ?>
</div>