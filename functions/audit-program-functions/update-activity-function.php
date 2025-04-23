<div class="dashboard-container">
    <?php
    if (isset($_POST['add-act'])) {
        $ap_id = mysqli_real_escape_string($connection, $_POST['ap_name']);
        $ap_act_name = mysqli_real_escape_string($connection, $_POST['ap_act_name']);
        $update_q = "UPDATE `audit_program` SET `ap_act_name`= '$ap_act_name' WHERE `ap_id` = '$ap_id'";
        $update_r = mysqli_query($connection, $update_q);
        if ($update_r) { ?>
            <div class="alert alert-success mb-3" role="alert" id="alertBox">
                Activity name added successfully!
            </div>
    <?php
        }
    }
    ?>
</div>