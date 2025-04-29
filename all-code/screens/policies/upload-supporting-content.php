<div class="mb-5" style="margin-right: 5px; width: 100% !important">
    <?php
    if (isset($_POST['add-details'])) {
        $control_id = mysqli_real_escape_string($connection, $_POST['control_id']);
        $control_support = mysqli_real_escape_string($connection, $_POST['control_support']);

        $insert_previous_data = "INSERT INTO `control_history`
            (ctrl_h_pol_id,
            ctrl_h_pol_old_det, 
            ctrl_h_updated_by,
            ctrl_h_assigned_to_old,
            ctrl_h_due_date_old) VALUES (
            '$fetched_control_id', 
            '$fetched_control_support',
            '$user_name',
            '$fetched_control_assigned_to',
            '$fetched_control_due_date') ";
        $insert_previous_data_res = mysqli_query($connection, $insert_previous_data);
        if ($insert_previous_data_res) {
            $control_added_by = mysqli_real_escape_string($connection, $_POST['control_added_by']);
            $update_details = "UPDATE `controls` SET `control_support` = '$control_support', `control_added_by` = '$control_added_by' WHERE `control_id` = '$control_id'";
            $update_details_res = mysqli_query($connection, $update_details);
        }
    }
    ?>
    <form action="" method="POST" class="policy-txt-editor mb-3" style="background-color: #fff;">
        <!-- ========== AUTO INCLUDE ========== -->
        <input type="text" value="<?php echo $control_id ?>" name="control_id" hidden>
        <input type="text" value="<?php echo $user_name ?>" name="control_added_by" hidden>
        <div class="WYSIWYG-editor mb-3">
            <label for="editorNew" class="form-label">Policy Details</label>
            <textarea id="editorNew" name="control_support"><?php echo $fetched_control_support ?></textarea>
        </div>

        <button type="submit" name="add-details" class="btn btn-sm btn-primary">Add</button>
    </form>
</div>