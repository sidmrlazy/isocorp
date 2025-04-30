<div class="mb-5" style="margin-right: 5px; width: 100% !important">
    <?php
    // Common variables
    date_default_timezone_set('Asia/Kolkata');
    $current_date = date('m-d-Y'); // Format: mm-dd-yyyy
    $control_id = isset($_POST['control_id']) ? mysqli_real_escape_string($connection, $_POST['control_id']) : '';
    $user_name = isset($_POST['control_added_by']) ? mysqli_real_escape_string($connection, $_POST['control_added_by']) : '';

    if (isset($_POST['add-details'])) {
        $control_support = mysqli_real_escape_string($connection, $_POST['control_support']);

        // Check if control_support is actually being updated
        if ($control_support != $fetched_control_support) {
            // Insert previous data into history table only if the control_support is being updated
            $insert_previous_data = "INSERT INTO `control_history`
                (ctrl_h_pol_id,
                ctrl_h_pol_old_det, 
                ctrl_h_updated_by,
                ctrl_h_assigned_to_old,
                ctrl_h_due_date_old,
                ctrl_update_date) VALUES (
                '$fetched_control_id', 
                '$fetched_control_support',
                '$user_name',
                '$fetched_control_assigned_to',
                '$fetched_control_due_date',
                '$current_date')";

            $insert_previous_data_res = mysqli_query($connection, $insert_previous_data);

            if (!$insert_previous_data_res) {
                echo "<div class='alert alert-danger'>Error inserting history data.</div>";
            }
        }

        // Update control details
        // $update_details = "UPDATE `control` 
        //     SET `control_support` = '$control_support', 
        //         `control_added_by` = '$user_name'
        //     WHERE `control_id` = '$control_id'";
        // $update_details_res = mysqli_query($connection, $update_details);

        // Insert or update the control_support in control_linked_policies
        $update_control_linked_policies = "UPDATE `control_linked_policies` 
            SET `control_support` = '$control_support'
            WHERE `control_parent_id` = '$control_id' 
            AND `control_linked_level` = 1"; // Adjust level as needed
        $update_control_linked_policies_res = mysqli_query($connection, $update_control_linked_policies);

        if (!$update_control_linked_policies_res) {
            echo "<div class='alert alert-danger'>Error updating control linked policies.</div>";
        }
    }
    ?>

    <!-- ============== SUPPORTING CONTENT SECTION ============== -->
    <form action="" method="POST" class="policy-txt-editor mb-3" style="background-color: #fff;">
        <input type="text" value="<?php echo htmlspecialchars($fetched_control_id); ?>" name="control_id" hidden>
        <input type="text" value="<?php echo htmlspecialchars($user_name); ?>" name="control_added_by" hidden>

        <div class="WYSIWYG-editor mb-3">
            <label for="editorNew" class="form-label">Policy Details</label>
            <textarea id="editorNew" name="control_support"><?php echo $fetched_control_support; ?></textarea>
        </div>
        <button type="submit" name="add-details" class="btn btn-sm btn-primary">Add</button>
    </form>

    <!-- ============== ASSIGNMENT SECTION ============== -->
    <div class="policy-txt-editor" style="width: 50%">

        <?php
        if (isset($_POST['add-assignment'])) {
            $control_assigned_to = mysqli_real_escape_string($connection, $_POST['control_assigned_to']);
            $control_due_date = mysqli_real_escape_string($connection, $_POST['control_due_date']);
            $control_status = mysqli_real_escape_string($connection, $_POST['control_status']);

            $update = "UPDATE `controls` SET 
                `control_assigned_to` = '$control_assigned_to', 
                `control_due_date` = '$control_due_date', 
                `control_status` = '$control_status'
                WHERE `control_id` = '$control_id'";
            $update_r = mysqli_query($connection, $update);
        }
        ?>

        <form action="" method="POST">
            <input type="text" name="control_id" value="<?php echo htmlspecialchars($fetched_control_id); ?>" hidden>
            <div class="mb-3">
                <label style="font-size: 12px !important;" for="assigned_to" class="form-label">Assigned to</label>
                <select style="font-size: 12px !important;" name="control_assigned_to" class="form-select" aria-label="Default select example" id="assigned_to">
                    <option selected style="font-size: 12px !important;" value="<?php echo htmlspecialchars($fetched_control_assigned_to); ?>">
                        <?php echo htmlspecialchars($fetched_control_assigned_to); ?>
                    </option>
                    <?php
                    $fetch_users = "SELECT * FROM user";
                    $fetch_users_r = mysqli_query($connection, $fetch_users);
                    while ($fetch_users_d = mysqli_fetch_assoc($fetch_users_r)) {
                        $user_name_opt = $fetch_users_d['isms_user_name'];
                        if ($user_name_opt !== $fetched_control_assigned_to) {
                            echo "<option style='font-size: 12px !important' value=\"" . htmlspecialchars($user_name_opt) . "\">" . htmlspecialchars($user_name_opt) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label style="font-size: 12px !important;" for="due_date" class="form-label">Due Date</label>
                <input style="font-size: 12px !important;" type="date" name="control_due_date" class="form-control"
                    value="<?php echo htmlspecialchars($fetched_control_due_date); ?>" id="due_date">
            </div>

            <div class="mb-3">
                <label style="font-size: 12px !important;" for="status" class="form-label">Status</label>
                <select style="font-size: 12px !important;" name="control_status" class="form-select" id="status">
                    <option selected value="<?php echo htmlspecialchars($fetched_control_status); ?>">
                        <?php echo htmlspecialchars($fetched_control_status); ?>
                    </option>
                    <?php
                    $statuses = ['Open', 'Closed', 'In-progress'];
                    foreach ($statuses as $status) {
                        if ($status !== $fetched_control_status) {
                            echo "<option value=\"" . htmlspecialchars($status) . "\">" . htmlspecialchars($status) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <button type="submit" name="add-assignment" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>