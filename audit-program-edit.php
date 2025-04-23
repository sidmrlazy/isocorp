<?php
include('includes/header.php');
include('includes/navbar.php');
include('includes/connection.php'); ?>
<div class="dashboard-container">
    <?php
    if (isset($_POST['edit-program'])) {
        $ap_id = $_POST['ap_id'];

        $get_dets = "SELECT * FROM `audit_program` WHERE `ap_id` = '$ap_id'";
        $get_dets_r = mysqli_query($connection, $get_dets);
        $fetched_ap_id = "";
        $fetched_ap_name = "";
        $fetched_ap_act_name = "";
        $fetched_ap_details = "";
        $fetched_ap_blob = "";
        $fetched_ap_assigned_to = "";
        $fetched_ap_status = "";
        $fetched_ap_due_date = "";
        while ($row = mysqli_fetch_assoc($get_dets_r)) {
            $fetched_ap_id = $row['ap_id'];
            $fetched_ap_name = $row['ap_name'];
            $fetched_ap_act_name = $row['ap_act_name'];
            $fetched_ap_details = $row['ap_details'];
            $fetched_ap_blob = $row['ap_blob'];
            $fetched_ap_assigned_to = $row['ap_assigned_to'];
            $fetched_ap_status = $row['ap_status'];
            $fetched_ap_due_date = $row['ap_due_date']; ?>
            <div class="form-container mb-5" style="width: 50%;">
                <form action="audit-program.php" method="POST">
                    <input type="hidden" name="ap_id" value="<?php echo $fetched_ap_id ?>">

                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Audit Programme Name</label>
                        <input disabled style="font-size: 12px !important;" type="text" name="ap_name" class="form-control" value="<?php echo $fetched_ap_name ?>">
                    </div>

                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Activity Name</label>
                        <input disabled style="font-size: 12px !important;" type="text" name="ap_act_name" class="form-control" value="<?php echo $fetched_ap_act_name ?>">
                    </div>

                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Details</label>
                        <input disabled style="font-size: 12px !important;" type="text" name="ap_details" class="form-control" value="<?php echo $fetched_ap_details ?>">
                    </div>

                    <div class="WYSIWYG-editor form-floating mb-3">
                        <textarea id="editorNew" class="form-control" name="ap_blob" style="height: 100px"><?php echo $fetched_ap_blob ?></textarea>
                        <label style="font-size: 12px !important;" for="editorNew">Blob Details</label>
                    </div>

                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Assigned to</label>
                        <select name="ap_assigned_to" class="form-select">
                            <option style="font-size: 12px !important;" selected disabled>Select user</option>
                            <?php
                            $fetch_user = "SELECT * FROM `user`";
                            $fetch_user_r = mysqli_query($connection, $fetch_user);
                            while ($row = mysqli_fetch_assoc($fetch_user_r)) {
                                $fetched_user_name = $row['isms_user_name'];
                                $selected = ($fetched_ap_assigned_to == $fetched_user_name) ? "selected" : "";
                                echo "<option style='font-size: 12px !important;' value='$fetched_user_name' $selected>$fetched_user_name</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="ap_status" class="form-select">
                            <option style="font-size: 12px !important;" disabled>Select status</option>
                            <option style="font-size: 12px !important;" value="Completed" <?php echo ($fetched_ap_status == 'Completed') ? 'selected' : '' ?>>Completed</option>
                            <option style="font-size: 12px !important;" value="In-Progress" <?php echo ($fetched_ap_status == 'In-Progress') ? 'selected' : '' ?>>In-Progress</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Due Date</label>
                        <input style="font-size: 12px !important;" type="date" name="ap_due_date" class="form-control" value="<?php echo $fetched_ap_due_date ?>">
                    </div>

                    <button type="submit" name="add" class="btn btn-sm btn-primary w-100">Submit</button>
                </form>
            </div>

    <?php
        }
    }
    ?>

</div>

<?php include('includes/footer.php'); ?>