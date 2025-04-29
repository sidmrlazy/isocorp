<div class="dashboard-container">
    <?php
    if (isset($_POST['add-det'])) {
        $ap_id = mysqli_real_escape_string($connection, $_POST['ap_act_id']);
        $ap_details = mysqli_real_escape_string($connection, $_POST['ap_details']);
        $update_q = "UPDATE `audit_program` SET `ap_details`= '$ap_details' WHERE `ap_id` = '$ap_id'";
        $update_r = mysqli_query($connection, $update_q);
        if ($update_r) { ?>
            <div class="alert alert-success mb-3" role="alert" id="alertBox">
                Details added successfully!
            </div>
    <?php
        }
    }
    ?>
</div>