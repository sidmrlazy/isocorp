<?php
include 'includes/config.php';
if (isset($_POST['save'])) {
    $policy_id = $_POST['policy_id'] ?? null;
    $linked_policy_id = $_POST['linked_policy_id'] ?? null;
    $inner_policy_id = $_POST['inner_policy_id'] ?? null;
    $policy_table = $_POST['policy_table'] ?? null;
    $editorContent = $_POST['editorContent'] ?? null;
    $editorBlob = !empty($editorContent) ? addslashes($editorContent) : null;

    if (!empty($policy_table)) {
        if (!empty($policy_id) || !empty($linked_policy_id) || !empty($inner_policy_id)) {
            $current_policy_id = !empty($policy_id) ? $policy_id : (!empty($linked_policy_id) ? $linked_policy_id : $inner_policy_id);

            $query = "SELECT 1 FROM policy_details WHERE policy_id = ? AND policy_table = ?";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "is", $current_policy_id, $policy_table);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $rowCount = mysqli_stmt_num_rows($stmt);

            date_default_timezone_set('Asia/Kolkata');
            $update_on = date('Y-m-d H:i:s');
            $updated_by = $user_name;

            if ($rowCount > 0) {
                // ✅ Step: Fetch current record BEFORE updating (for history)
                $fetch_query = "SELECT * FROM policy_details WHERE policy_id = ? AND policy_table = ?";
                $fetch_stmt = mysqli_prepare($connection, $fetch_query);
                mysqli_stmt_bind_param($fetch_stmt, "is", $current_policy_id, $policy_table);
                mysqli_stmt_execute($fetch_stmt);
                $result = mysqli_stmt_get_result($fetch_stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    $existingData = mysqli_fetch_assoc($result);

                    // ✅ Step: Save OLD data to history table
                    $history_query = "INSERT INTO policy_details_history (
                        policy_details_id, policy_id, policy_table, policy_details, 
                        policy_document, policy_assigned_to, policy_status, 
                        policy_update_on, policy_updated_by
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $history_stmt = mysqli_prepare($connection, $history_query);
                    mysqli_stmt_bind_param(
                        $history_stmt,
                        "issssssss",
                        $existingData['policy_details_id'],
                        $existingData['policy_id'],
                        $existingData['policy_table'],
                        $existingData['policy_details'],
                        $existingData['policy_document'],
                        $existingData['policy_assigned_to'],
                        $existingData['policy_status'],
                        $existingData['policy_update_on'],
                        $existingData['policy_updated_by']
                    );
                    mysqli_stmt_execute($history_stmt);
                }

                // ✅ Step: Now update the live data
                $update_query = "UPDATE policy_details 
                    SET policy_details = ?, policy_update_on = ?, policy_updated_by = ?
                    WHERE policy_id = ? AND policy_table = ?";
                $stmt = mysqli_prepare($connection, $update_query);
                mysqli_stmt_bind_param($stmt, "sssis", $editorBlob, $update_on, $updated_by, $current_policy_id, $policy_table);
                mysqli_stmt_execute($stmt);
            } else {
                // ✅ Step: Insert new policy_details row
                $insert_query = "INSERT INTO policy_details (
                    policy_id, policy_table, policy_details, policy_document, policy_update_on, policy_updated_by
                ) VALUES (?, ?, ?, NULL, ?, ?)";
                $stmt = mysqli_prepare($connection, $insert_query);
                mysqli_stmt_bind_param($stmt, "issss", $current_policy_id, $policy_table, $editorBlob, $update_on, $updated_by);
                mysqli_stmt_execute($stmt);
            }

            if ($stmt && mysqli_stmt_affected_rows($stmt) > 0) {
                echo '<div id="alertBox" class="alert alert-success mt-3 mb-3">Policy details saved successfully.</div>';

                // ✅ Step: Insert activity log
                $activity_done_on = $policy_table;
                $activity_done_on_id = $current_policy_id;
                $activity_name = "Added details to policy";
                $activity_by = $user_name;
                $activity_date = date('m-d-Y H:i:s');

                $activity_sql = "INSERT INTO activity (
                    activity_done_on, 
                    activity_done_on_id, 
                    activity_name, 
                    activity_by, 
                    activity_date
                ) VALUES (?, ?, ?, ?, ?)";

                $activity_stmt = mysqli_prepare($connection, $activity_sql);
                mysqli_stmt_bind_param($activity_stmt, "sisss", $activity_done_on, $activity_done_on_id, $activity_name, $activity_by, $activity_date);
                mysqli_stmt_execute($activity_stmt);
            } else {
                echo '<div id="alertBox" class="alert alert-danger mt-3 mb-3">Error saving policy details: ' . mysqli_error($connection) . '</div>';
            }
        }
    }
}