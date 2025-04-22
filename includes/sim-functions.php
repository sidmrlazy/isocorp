<div class="dashboard-container">
    <?php

    if (isset($_POST['delete'])) {
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $sim_id = mysqli_real_escape_string($connection, $_POST['sim_id']);

            // Ensure the ID is valid before deletion
            $delete_query = "DELETE FROM `sim` WHERE `SIM_ID` = $sim_id";
            $delete_query_r = mysqli_query($connection, $delete_query);

            if ($delete_query_r) {
                echo '<div id="alertBox" class="alert alert-success mt-5" role="alert">Incident deleted successfully!</div>';
            } else {
                throw new Exception("Failed to delete the incident.");
            }
        } catch (Exception $e) {
            echo '<div id="alertBox" class="alert alert-danger mt-5" role="alert">Error: ' . $e->getMessage() . '</div>';
        }
    }
    if (isset($_POST['record'])) {
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            if (
                empty($_POST['sim_id']) || empty($_POST['sim_status']) || empty($_POST['sim_severity']) ||
                empty($_POST['sim_source']) || empty($_POST['sim_type']) || empty($_POST['sim_reported_by'])
            ) {
                throw new Exception("All required fields must be filled.");
            }

            $updated_sim_id = mysqli_real_escape_string($connection, $_POST['sim_id']);
            $updated_sim_status = mysqli_real_escape_string($connection, $_POST['sim_status']);
            $updated_sim_severity = mysqli_real_escape_string($connection, $_POST['sim_severity']);
            $updated_sim_source = mysqli_real_escape_string($connection, $_POST['sim_source']);
            $updated_sim_type = mysqli_real_escape_string($connection, $_POST['sim_type']);
            $updated_sim_reported_date = mysqli_real_escape_string($connection, $_POST['sim_reported_date']);
            $updated_sim_reported_by = mysqli_real_escape_string($connection, $_POST['sim_reported_by']);
            $updated_sim_assigned_to = mysqli_real_escape_string($connection, $_POST['sim_assigned_to'] ?? '');
            $updated_sim_due_date = mysqli_real_escape_string($connection, $_POST['sim_due_date'] ?? '');
            $updated_sim_details = mysqli_real_escape_string($connection, $_POST['sim_details'] ?? '');

            // Fetch sim_final directly from the database
            $check_final_query = "SELECT sim_final FROM `sim` WHERE sim_id = '$updated_sim_id'";
            $check_final_result = mysqli_query($connection, $check_final_query);

            if (!$check_final_result || mysqli_num_rows($check_final_result) == 0) {
                throw new Exception("Incident not found.");
            }

            $row = mysqli_fetch_assoc($check_final_result);
            $existing_sim_final = $row['sim_final'];

            // Prevent changing status to resolved unless sim_final is 2
            if ($updated_sim_status == '2' && $existing_sim_final != '2') {
                throw new Exception("Incident cannot be marked as resolved unless details are finalized.");
            }

            $update_query = "UPDATE `sim` SET 
                `sim_status` = '$updated_sim_status',
                `sim_severity` = '$updated_sim_severity',
                `sim_source` = '$updated_sim_source',
                `sim_type` = '$updated_sim_type',
                `sim_reported_date` = '$updated_sim_reported_date',
                `sim_reported_by` = '$updated_sim_reported_by',
                `sim_assigned_to` = '$updated_sim_assigned_to',
                `sim_due_date` = '$updated_sim_due_date',
                `sim_details` = '$updated_sim_details'
                WHERE `sim_id` = $updated_sim_id";

            $update_query_r = mysqli_query($connection, $update_query);

            if ($update_query_r) {
                echo '<div id="alertBox" class="alert alert-success mt-5" role="alert">Incident updated!</div>';
            }
        } catch (Exception $e) {
            echo '<div id="alertBox" class="alert alert-danger mt-5" role="alert">Error: ' . $e->getMessage() . '</div>';
        }
    }

    ?>
</div>

