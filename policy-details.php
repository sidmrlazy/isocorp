<?php
if (!isset($_SESSION['user_session']) && !isset($_COOKIE['user_session'])) {
    // Send a 404 Not Found response
    http_response_code(404);
    exit();
}
include('includes/header.php');
include('includes/navbar.php');
include 'includes/connection.php';

?>
<div class="dashboard-container">
    <?php
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
    ?>

    <!-- ========== CONTROL DETAILS ========== -->
    <div class="card p-3 mb-3">
        <?php
        if (!$connection) {
            die("<div style='font-size: 12px !important;' id='alertBox' class='alert alert-danger mt-3 mb-3'>Database connection failed: " . mysqli_connect_error() . "</div>");
        }

        $policy_id = null;
        $policy_table = "";
        $policy_column = "";

        if (isset($_GET['policy_id'])) {
            $policy_id = intval($_GET['policy_id']);
            $policy_table = "sub_control_policy";
            $policy_column = "sub_control_policy_id";
        } elseif (isset($_GET['linked_policy_id'])) {
            $policy_id = intval($_GET['linked_policy_id']);
            $policy_table = "linked_control_policy";
            $policy_column = "linked_control_policy_id";
        } elseif (isset($_GET['inner_policy_id'])) {
            $policy_id = intval($_GET['inner_policy_id']);
            $policy_table = "inner_linked_control_policy";
            $policy_column = "inner_linked_control_policy_id";
        }

        $allowed_tables = ['sub_control_policy', 'linked_control_policy', 'inner_linked_control_policy'];
        if (!in_array($policy_table, $allowed_tables)) {
            die("<div style='font-size: 12px !important;' id='alertBox' class='alert alert-danger mt-3 mb-3'>Invalid policy table specified.</div>");
        }

        if ($policy_id && $policy_table && $policy_column) {

            $query = "SELECT * FROM $policy_table WHERE $policy_column = ?";
            $stmt = mysqli_prepare($connection, $query);
            if (!$stmt) {
                die("<div style='font-size: 12px !important;' id='alertBox' class='alert alert-danger mt-3 mb-3'>Prepare Error: " . mysqli_error($connection) . "</div>");
            }
            mysqli_stmt_bind_param($stmt, "i", $policy_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $policy = mysqli_fetch_assoc($result);


                if ($policy_table === "sub_control_policy") {
                    $policy_number = $policy["sub_control_policy_number"];
                    $policy_heading = $policy["sub_control_policy_heading"];
                    $policy_content = $policy["sub_control_policy_det"];
                } elseif ($policy_table === "linked_control_policy") {
                    $policy_number = $policy["linked_control_policy_number"];
                    $policy_heading = $policy["linked_control_policy_heading"];
                    $policy_content = $policy["linked_control_policy_det"];
                } elseif ($policy_table === "inner_linked_control_policy") {
                    $policy_number = $policy["inner_linked_control_policy_number"];
                    $policy_heading = $policy["inner_linked_control_policy_heading"];
                    $policy_content = $policy["inner_linked_control_policy_det"];
                } else {
                    $policy_number = '';
                    $policy_heading = '';
                    $policy_content = '';
                }
        ?>

                <!-- <h1 style="font-size: 24px; font-weight: 500;">Policy Details</h1> -->
                <div>
                    <h6 style="font-size: 16px !important;"><?= $policy_number . " " . $policy_heading ?></h6>
                    <p style="font-size: 16px !important; margin: 0;"><?= $policy_content ?></p>
                </div>

        <?php
            }
        }
        ?>
    </div>

    <div class="row" style="margin-bottom: 40px;">
        <div class="col-md-6">
            <!-- ========== POLICY CONTENT SECTION ========== -->
            <div class="card p-3">
                <?php
                $policy_content = "";
                $valid_tables = ['policy', 'sub_control_policy', 'linked_control_policy', 'inner_linked_control_policy'];

                if ($policy_id && in_array($policy_table, $valid_tables)) {
                    // First try fetching from centralized policy_details table
                    $query = "SELECT policy_details FROM policy_details WHERE policy_id = ? AND policy_table = ?";
                    $stmt = mysqli_prepare($connection, $query);
                    mysqli_stmt_bind_param($stmt, "is", $policy_id, $policy_table);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($result && mysqli_num_rows($result) > 0) {
                        $policy = mysqli_fetch_assoc($result);
                        $policy_content = stripslashes($policy["policy_details"]);
                    } else {
                        // Fallback to original table if not found in policy_details
                        $query = "SELECT * FROM $policy_table WHERE {$policy_table}_id = ?";
                        $stmt = mysqli_prepare($connection, $query);
                        mysqli_stmt_bind_param($stmt, "i", $policy_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if ($result && mysqli_num_rows($result) > 0) {
                            $policy = mysqli_fetch_assoc($result);
                            $policy_content = stripslashes($policy[$policy_table . "_det"]);
                        }
                    }
                }
                ?>

                <form action="" method="POST">
                    <input type="hidden" name="policy_id"
                        value="<?php echo isset($_GET['policy_id']) ? $_GET['policy_id'] : ''; ?>">
                    <input type="hidden" name="linked_policy_id"
                        value="<?php echo isset($_GET['linked_policy_id']) ? $_GET['linked_policy_id'] : ''; ?>">
                    <input type="hidden" name="inner_policy_id"
                        value="<?php echo isset($_GET['inner_policy_id']) ? $_GET['inner_policy_id'] : ''; ?>">
                    <input type="hidden" name="policy_table" value="<?php echo isset($policy_table) ? $policy_table : ''; ?>">

                    <div class="WYSIWYG-editor">
                        <textarea id="editorNew"><?php echo htmlspecialchars_decode($policy_content); ?></textarea>
                    </div>
                    <input type="hidden" name="editorContent" id="editorContent">
                    <?php
                    if ($user_role == "2") {
                    ?>
                        <button type="submit" name="save" class="d-none btn btn-sm btn-success mt-3">Update</button>
                    <?php } else { ?>
                        <button type="submit" name="save" class="btn btn-sm btn-success mt-3">Update</button>
                    <?php } ?>

                </form>
            </div>

            <!-- ========== ASSIGNMENT SECTION ========== -->
            <div class="card p-3 mt-3 mb-3">
                <?php
                // Identify the ID and its type
                $vc_data_id = null;
                $vc_data_type = '';

                if (isset($_GET['inner_policy_id'])) {
                    $vc_data_id = $_GET['inner_policy_id'];
                    $vc_data_type = 'Inner Policy';
                } elseif (isset($_GET['linked_policy_id'])) {
                    $vc_data_id = $_GET['linked_policy_id'];
                    $vc_data_type = 'Linked Policy';
                } elseif (isset($_GET['policy_id'])) {
                    $vc_data_id = $_GET['policy_id'];
                    $vc_data_type = 'Policy';
                }

                // Initialize prefilled values
                $vc_assigned_to_value = '';
                $vc_status_value = '';
                $vc_due_date_value = '';

                // Handle form submission
                if (isset($_POST['update-details'])) {
                    $vc_data_id = $_POST['vc_data_id'];
                    $vc_data_type = $_POST['vc_data_type'];
                    date_default_timezone_set('Asia/Kolkata');
                    $vc_updated_on = date('Y-m-d H:i:s');
                    $vc_assigned_to = $_POST['vc_assigned_to'];
                    $vc_status = $_POST['vc_status'];
                    $vc_due_date = $_POST['vc_due_date'];
                    $vc_updated_by = isset($_POST['vc_updated_by']) ? $_POST['vc_updated_by'] : 'System';

                    // Use prepared statements to prevent SQL injection
                    $check_stmt = mysqli_prepare($connection, "SELECT 1 FROM version_control WHERE vc_data_id = ? AND vc_screen_name = ?");
                    mysqli_stmt_bind_param($check_stmt, "ss", $vc_data_id, $vc_data_type);
                    mysqli_stmt_execute($check_stmt);
                    $check_result = mysqli_stmt_get_result($check_stmt);

                    if (mysqli_num_rows($check_result) > 0) {
                        $update_stmt = mysqli_prepare($connection, "UPDATE version_control 
                SET vc_assigned_to = ?, vc_status = ?, vc_updated_on = ?, vc_updated_by = ?, vc_due_date = ?
                WHERE vc_data_id = ? AND vc_screen_name = ?");
                        mysqli_stmt_bind_param($update_stmt, "sssssss", $vc_assigned_to, $vc_status, $vc_updated_on, $vc_updated_by, $vc_due_date, $vc_data_id, $vc_data_type);
                        $query_result = mysqli_stmt_execute($update_stmt);
                    } else {
                        $insert_stmt = mysqli_prepare($connection, "INSERT INTO version_control 
                (vc_data_id, vc_screen_name, vc_assigned_to, vc_status, vc_updated_on, vc_due_date, vc_updated_by)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
                        mysqli_stmt_bind_param($insert_stmt, "sssssss", $vc_data_id, $vc_data_type, $vc_assigned_to, $vc_status, $vc_updated_on, $vc_due_date, $vc_updated_by);
                        $query_result = mysqli_stmt_execute($insert_stmt);
                    }

                    echo $query_result
                        ? "<div id='alertBox' class='alert alert-success'>Details saved successfully.</div>"
                        : "<div id='alertBox' class='alert alert-danger'>Error: " . mysqli_error($connection) . "</div>";
                }

                // Pre-fill values if available
                if ($vc_data_id && $vc_data_type) {
                    $get_stmt = mysqli_prepare($connection, "SELECT vc_assigned_to, vc_status, vc_due_date FROM version_control WHERE vc_data_id = ? AND vc_screen_name = ? LIMIT 1");
                    mysqli_stmt_bind_param($get_stmt, "ss", $vc_data_id, $vc_data_type);
                    mysqli_stmt_execute($get_stmt);
                    $get_result = mysqli_stmt_get_result($get_stmt);

                    if (mysqli_num_rows($get_result) > 0) {
                        $vc_data = mysqli_fetch_assoc($get_result);
                        $vc_assigned_to_value = $vc_data['vc_assigned_to'];
                        $vc_status_value = $vc_data['vc_status'];
                        $vc_due_date_value = $vc_data['vc_due_date'];
                    }
                }
                ?>

                <form action="" method="POST">
                    <input type="hidden" name="vc_data_id" value="<?php echo htmlspecialchars($vc_data_id); ?>">
                    <input type="hidden" name="vc_data_type" value="<?php echo htmlspecialchars($vc_data_type); ?>">
                    <input type="hidden" name="vc_updated_by" value="<?php echo htmlspecialchars($user_name ?? 'System'); ?>">
                    <?php
                    if ($user_role == "2") {
                    ?>
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 12px;">Assigned to</label>
                            <select class="form-select" name="vc_assigned_to" required style="font-size: 12px;" disabled>
                                <option disabled <?php if (empty($vc_assigned_to_value)) echo 'selected'; ?>>Select a user</option>
                                <?php
                                $users = mysqli_query($connection, "SELECT isms_user_name FROM user");
                                while ($row = mysqli_fetch_assoc($users)) {
                                    $name = $row['isms_user_name'];
                                    $selected = ($name == $vc_assigned_to_value) ? 'selected' : '';
                                    echo "<option value=\"$name\" $selected>$name</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-size: 12px;">Status</label>
                            <select class="form-select" name="vc_status" required style="font-size: 12px;" disabled>
                                <option disabled <?php if (empty($vc_status_value)) echo 'selected'; ?>>Select status</option>
                                <?php
                                $statuses = ['Open', 'In Progress', 'Completed'];
                                foreach ($statuses as $status) {
                                    $selected = ($status == $vc_status_value) ? 'selected' : '';
                                    echo "<option value=\"$status\" $selected>$status</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-size: 12px;">Due Date</label>
                            <input class="form-control" style="font-size: 12px;" name="vc_due_date" type="date"
                                value="<?php echo htmlspecialchars($vc_due_date_value); ?>" disabled>
                        </div>
                    <?php } else { ?>
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 12px;">Assigned to</label>
                            <select class="form-select" name="vc_assigned_to" required style="font-size: 12px;">
                                <option disabled <?php if (empty($vc_assigned_to_value)) echo 'selected'; ?>>Select a user</option>
                                <?php
                                $users = mysqli_query($connection, "SELECT isms_user_name FROM user");
                                while ($row = mysqli_fetch_assoc($users)) {
                                    $name = $row['isms_user_name'];
                                    $selected = ($name == $vc_assigned_to_value) ? 'selected' : '';
                                    echo "<option value=\"$name\" $selected>$name</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-size: 12px;">Status</label>
                            <select class="form-select" name="vc_status" required style="font-size: 12px;">
                                <option disabled <?php if (empty($vc_status_value)) echo 'selected'; ?>>Select status</option>
                                <?php
                                $statuses = ['Open', 'In Progress', 'Completed'];
                                foreach ($statuses as $status) {
                                    $selected = ($status == $vc_status_value) ? 'selected' : '';
                                    echo "<option value=\"$status\" $selected>$status</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-size: 12px;">Due Date</label>
                            <input class="form-control" style="font-size: 12px;" name="vc_due_date" type="date"
                                value="<?php echo htmlspecialchars($vc_due_date_value); ?>">
                        </div>

                    <?php } ?>
                    <?php
                    if ($user_role == "2") {
                    ?>
                        <button type="button" class="d-none btn btn-sm btn-success">Submit</button>
                    <?php } else { ?>
                        <button type="submit" name="update-details" class="btn btn-sm btn-success">Submit</button>
                    <?php } ?>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">

                <!-- ========== HISTORY ========== -->
                <div class="card p-3 mb-3">
                    <div style="display: flex !important; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h6>History</h6>
                    </div>
                    <div style="width: 100% !important;">
                        <?php
                        if (isset($_POST['history_remove']) && isset($_POST['history_id'])) {
                            $history_id = $_POST['history_id'];

                            // Use prepared statements to prevent SQL injection
                            $stmt = $connection->prepare("DELETE FROM policy_details_history WHERE history_id = ?");
                            $stmt->bind_param("i", $history_id);

                            if ($stmt->execute()) {
                                echo "<div style='font-size: 12px;' id='alertBox' class='alert alert-success'>History record deleted successfully!</div>";
                            } else {
                                echo "<div style='font-size: 12px;' id='alertBox' class='alert alert-danger'>Error deleting record: " . $stmt->error . "</div>";
                            }

                            $stmt->close();
                        }

                        $history_query = "SELECT * FROM policy_details_history WHERE policy_id = '$policy_id' AND policy_table = '$policy_table' ORDER BY policy_update_on DESC";
                        $history_result = mysqli_query($connection, $history_query);
                        if (mysqli_num_rows($history_result) > 0) {
                        ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px !important;" scope="col">Previous Details</th>
                                            <th style="font-size: 12px !important;" scope="col">Updated on</th>
                                            <th style="font-size: 12px !important;" scope="col">View</th>
                                            <?php
                                            if ($user_role == "2") {
                                            ?>
                                                <th class="d-none" style="font-size: 12px !important;" scope="col">Remove</th>
                                            <?php } else { ?>
                                                <th style="font-size: 12px !important;" scope="col">Remove</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($history = mysqli_fetch_assoc($history_result)) { ?>
                                            <tr>
                                                <td style="font-size: 12px !important;">Details added</td>
                                                <td style="font-size: 12px !important;"><?php echo $history['version_saved_on']; ?></td>
                                                <td>
                                                    <button
                                                        class="btn btn-sm btn-outline-success view-history-btn"
                                                        data-version="<?php echo htmlspecialchars($history['policy_details']); ?>"
                                                        data-updatedon="<?php echo htmlspecialchars($history['version_saved_on']); ?>"
                                                        style="font-size: 12px !important;"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#historyModal">
                                                        View Previous Version
                                                    </button>
                                                </td>
                                                <?php
                                                if ($user_role == "2") {
                                                ?>
                                                    <td class="d-none">
                                                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                            <input type="hidden" name="history_id" value="<?php echo $history['history_id']; ?>">
                                                            <button type="submit" name="history_remove" style="font-size: 12px;" class="btn btn-sm btn-outline-danger">
                                                                Remove
                                                            </button>
                                                        </form>
                                                    </td>
                                                <?php } else { ?>
                                                    <td>
                                                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                            <input type="hidden" name="history_id" value="<?php echo $history['history_id']; ?>">
                                                            <button type="submit" name="history_remove" style="font-size: 12px;" class="btn btn-sm btn-outline-danger">
                                                                Remove
                                                            </button>
                                                        </form>
                                                    </td>
                                                <?php } ?>

                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>


                            <?php } else { ?>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px !important;" scope="col">Previous Details</th>
                                            <th style="font-size: 12px !important;" scope="col">Updated on</th>
                                            <th style="font-size: 12px !important;" scope="col">View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="3" style="font-size: 12px !important;">No previous policy details found.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php } ?>
                            </div>
                    </div>
                    <!-- ========== HISTORY MODAL ========== -->
                    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Previous Version Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Updated On:</strong> <span id="history-updated-on"></span></p>


                                    <div class="WYSIWYG-editor">
                                        <textarea id="history-content" style="width: 100%; height: 500px !important; white-space: pre-wrap; border: 0"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ========== DOCUMENTS ========== -->
                <div class="card p-3 mb-3">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h6>Documents</h6>
                        <?php
                        if ($user_role == "2") {
                        ?>
                            <button class="d-none btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#uploadDoc" style="font-size: 12px !important;">
                                <ion-icon name="add-outline"></ion-icon>
                            </button>
                        <?php } else { ?>
                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#uploadDoc" style="font-size: 12px !important;">
                                <ion-icon name="add-outline"></ion-icon>
                            </button>
                        <?php } ?>

                    </div>

                    <?php
                    if (isset($_POST['delete-doc'])) {
                        $document_id = isset($_POST['document_id']) ? intval($_POST['document_id']) : null;
                        if ($document_id) {
                            $query = "SELECT document_path FROM policy_documents WHERE policy_document_id = ?";
                            $stmt = mysqli_prepare($connection, $query);
                            mysqli_stmt_bind_param($stmt, "i", $document_id);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            if ($result && mysqli_num_rows($result) > 0) {
                                $doc = mysqli_fetch_assoc($result);
                                $document_path = $doc['document_path'];
                                // Delete file from server
                                if (file_exists($document_path)) {
                                    unlink($document_path);
                                }
                                // Delete from database
                                $delete_query = "DELETE FROM policy_documents WHERE policy_document_id = ?";
                                $stmt = mysqli_prepare($connection, $delete_query);
                                mysqli_stmt_bind_param($stmt, "i", $document_id);

                                if (mysqli_stmt_execute($stmt)) {
                                    echo "<div style='font-size: 12px !important;' id='alertBox' class='alert alert-success mt-3 mb-3'>Document deleted successfully.</div>";
                                } else {
                                    echo "<div style='font-size: 12px !important;' id='alertBox' class='alert alert-danger mt-3 mb-3'>Error deleting document: " . mysqli_error($connection) . "</div>";
                                }
                            } else {
                                echo "<div style='font-size: 12px !important;' id='alertBox' class='alert alert-warning mt-3 mb-3'>Document not found.</div>";
                            }
                        } else {
                            echo "<div style='font-size: 12px !important;' id='alertBox' class='alert alert-danger mt-3 mb-3'>Invalid document ID.</div>";
                        }
                    }

                    if (isset($_POST['upload'])) {
                        $policy_id = intval($_POST['policy_id']);
                        $policy_table = isset($_POST['policy_table_for_document']) ? $_POST['policy_table_for_document'] : null;

                        if (is_null($policy_table)) {
                            die("<div style='font-size: 12px !important;' id='alertBox' class='alert alert-danger mt-3 mb-3'>Policy table for document is missing!</div>");
                        }

                        if (!isset($_FILES['document']) || $_FILES['document']['error'] != UPLOAD_ERR_OK) {
                            die("<div style='font-size: 12px !important;' id='alertBox' class='alert alert-danger mt-3 mb-3'>File upload error!</div>");
                        }

                        $file_name = basename($_FILES['document']['name']);
                        $file_tmp = $_FILES['document']['tmp_name'];
                        $upload_dir = "uploads/";
                        $file_path = $upload_dir . time() . "_" . $file_name;

                        $document_version = isset($_POST['document_version']) ? $_POST['document_version'] : null;

                        if (move_uploaded_file($file_tmp, $file_path)) {
                            // Insert document details into policy_documents table
                            $query = "INSERT INTO policy_documents (policy_id, policy_table_for_document, document_name, document_path, document_version) VALUES (?, ?, ?, ?, ?)";
                            $stmt = mysqli_prepare($connection, $query);
                            mysqli_stmt_bind_param($stmt, "issss", $policy_id, $policy_table, $file_name, $file_path, $document_version);

                            if (mysqli_stmt_execute($stmt)) {
                                echo "<div style='font-size: 12px !important;' id='alertBox' class='alert alert-success mt-3 mb-3'>Document uploaded successfully.</div>";

                                // ✅ Insert into activity table
                                date_default_timezone_set('Asia/Kolkata');
                                $activity_name = "Document added";
                                $activity_done_on_id = $policy_id;
                                $activity_done_on = $policy_table;
                                $activity_done_by = $user_name; // Assuming user_name is set in session
                                $activity_date = date('m-d-Y H:i:s');

                                $activity_sql = "INSERT INTO activity (
                                activity_done_on, 
                                activity_done_on_id,
                                activity_name, 
                                activity_by, 
                                activity_date
                            ) VALUES (
                                '$activity_done_on',
                                '$activity_done_on_id',
                                '$activity_name',
                                '$activity_done_by',
                                '$activity_date'
                            )";

                                mysqli_query($connection, $activity_sql);
                            } else {
                                echo "<div style='font-size: 12px !important;' id='alertBox' class='alert alert-danger mt-3 mb-3'>Error uploading document: " . mysqli_error($connection) . "</div>";
                            }
                        } else {
                            echo "<div style='font-size: 12px !important;' id='alertBox' class='alert alert-danger mt-3 mb-3'>Failed to move uploaded file.</div>";
                        }
                    }
                    ?>

                    <!-- ========== UPLOAD DOCUMENT MODAL ========== -->
                    <div class="modal fade" id="uploadDoc" tabindex="-1" aria-labelledby="uploadDocLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="" method="POST" enctype="multipart/form-data" class="modal-content">
                                <input type="hidden" name="policy_id" value="<?= htmlspecialchars($policy_id) ?>">
                                <input type="hidden" name="policy_table" value="<?= htmlspecialchars($policy_table) ?>">
                                <input type="hidden" name="policy_table_for_document" value="<?= htmlspecialchars($policy_table) ?>">

                                <div class="modal-header">
                                    <h5 class="modal-title" id="uploadDocLabel">Upload Document</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label style="font-size: 12px !important;" for="document" class="form-label">Select File</label>
                                        <input style="font-size: 12px !important;" type="file" name="document" class="form-control" id="document" required>
                                    </div>
                                    <div class="mb-3">
                                        <label style="font-size: 12px !important;" for="documentVersion" class="form-label">Version</label>
                                        <input style="font-size: 12px !important;" type="text" name="document_version" class="form-control" id="documentVersion">
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="upload" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php
                    $query = "SELECT policy_document_id, document_name, document_path, document_version FROM policy_documents WHERE policy_id = ? AND policy_table_for_document = ?";
                    $stmt = mysqli_prepare($connection, $query);
                    mysqli_stmt_bind_param($stmt, "is", $policy_id, $policy_table);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($result && mysqli_num_rows($result) > 0): ?>
                        <div class="table-responsive mt-2">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="font-size: 12px !important;">Name</th>
                                        <th style="font-size: 12px !important;">Version</th>
                                        <th style="font-size: 12px !important;">Download</th>
                                        <?php
                                        if ($user_role == "2") {
                                        ?>
                                            <th class="d-none" style="font-size: 12px !important;">Edit</th>
                                            <th class="d-none" style="font-size: 12px !important;">Delete</th>
                                        <?php } else { ?>
                                            <th style="font-size: 12px !important;">Edit</th>
                                            <th style="font-size: 12px !important;">Delete</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($doc = mysqli_fetch_assoc($result)):
                                        $document_id = (int)$doc['policy_document_id'];
                                        $document_path = htmlspecialchars($doc['document_path']);
                                        $document_name = htmlspecialchars($doc['document_name']);
                                        $document_version = htmlspecialchars($doc['document_version']);
                                    ?>
                                        <tr>
                                            <td style="font-size: 12px;"><?php echo $document_name; ?></td>
                                            <td style="font-size: 12px;"><?php echo $document_version; ?></td>
                                            <td>
                                                <a href="<?= $document_path ?>" class="btn btn-sm btn-outline-info" style="font-size: 12px;" download>Download</a>
                                            </td>
                                            <?php
                                            if ($user_role == "2") {
                                            ?>
                                                <td class="d-none">
                                                    <form action="update-document.php" method="POST">
                                                        <input type="text" name="policy_document_id" value="<?php echo $document_id; ?>" hidden>
                                                        <button type="submit" name="doc-edit-btn" class="btn btn-sm btn-outline-warning" style="font-size: 12px;">
                                                            Edit
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="d-none">
                                                    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                        <input type="text" name="document_id" value="<?= $document_id ?>" hidden>
                                                        <button type="submit" name="delete-doc" class="btn btn-sm btn-outline-danger" style="font-size: 12px;">Delete</button>
                                                    </form>
                                                </td>

                                            <?php } else { ?>
                                                <td>
                                                    <form action="update-document.php" method="POST">
                                                        <input type="text" name="policy_document_id" value="<?php echo $document_id; ?>" hidden>
                                                        <button type="submit" name="doc-edit-btn" class="btn btn-sm btn-outline-warning" style="font-size: 12px;">
                                                            Edit
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                        <input type="text" name="document_id" value="<?= $document_id ?>" hidden>
                                                        <button type="submit" name="delete-doc" class="btn btn-sm btn-outline-danger" style="font-size: 12px;">Delete</button>
                                                    </form>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <!-- <p style='font-size: 12px;'>No documents uploaded for this policy.</p> -->
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="font-size: 12px !important;">Name</th>
                                    <th style="font-size: 12px !important;">Version</th>
                                    <th style="font-size: 12px !important;">Download</th>
                                    <th style="font-size: 12px !important;">Edit</th>
                                    <th style="font-size: 12px !important;">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" style="font-size: 12px !important;">No documents uploaded for this policy.</td>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                <!-- ========== RISKS ========== -->
                <div class="mb-3 card p-3">
                    <?php
                    if (isset($_POST['connect_risk'])) {
                        $risk_ids = $_POST['risk_ids'];
                        $clause_id = intval($_POST['clause_id']);
                        $clause_type = mysqli_real_escape_string($connection, $_POST['clause_type']); // dynamic

                        foreach ($risk_ids as $risk_id) {
                            $risk_id = intval($risk_id);
                            $check_exist = "SELECT * FROM risk_policies WHERE risks_id = $risk_id AND clause_id = $clause_id AND clause_type = '$clause_type'";
                            $result = mysqli_query($connection, $check_exist);

                            if (mysqli_num_rows($result) == 0) {
                                $insert = "INSERT INTO risk_policies (risks_id, clause_id, clause_type) VALUES ($risk_id, $clause_id, '$clause_type')";
                                mysqli_query($connection, $insert);
                            }
                        }
                        echo "<div id='alertBox' style='font-size: 12px !important;' class='alert alert-success mt-2'>Risks successfully linked to the clause.</div>";
                    }

                    ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h6>Associated Risks & Treatments</h6>
                        <?php
                        if ($user_role == "2") {
                        ?>
                            <button class="d-none btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#riskModal" style="font-size: 12px !important;">
                                <ion-icon name="add-outline"></ion-icon>
                            </button>
                        <?php } else { ?>
                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#riskModal" style="font-size: 12px !important;">
                                <ion-icon name="add-outline"></ion-icon>
                            </button>
                        <?php } ?>
                    </div>
                    <!-- ======= ADD RISK MODAL ======= -->
                    <div class="modal fade" id="riskModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                        <div class="modal-dialog modal-dialog-centered modal-xl">
                            <form action="" method="POST" class="modal-content">
                                <input type="hidden" name="clause_id" value="<?php echo htmlspecialchars($policy_id); ?>">
                                <input type="hidden" name="clause_type" value="<?php echo htmlspecialchars($policy_table); ?>">


                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Risk to Policy</h1>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label style="font-size: 12px !important;" for="riskSearch" class="form-label">Search Risks</label>
                                        <input type="text" id="riskSearch" class="form-control mb-2" placeholder="Type to search risks..." style="font-size: 12px !important;">

                                        <label style="font-size: 12px !important;" for="riskSelect" class="form-label">Risks</label>
                                        <select name="risk_ids[]" id="riskSelect" style="font-size: 12px !important; height: 300px !important" multiple class="form-select">
                                            <option disabled selected>Choose Risks</option>
                                            <?php
                                            $get_risks = "SELECT * FROM risks ORDER BY risks_id ASC";
                                            $get_risks_r = mysqli_query($connection, $get_risks);
                                            while ($row = mysqli_fetch_assoc($get_risks_r)) {
                                                $risks_id = $row['risks_id'];
                                                $risks_name = $row['risks_name'];
                                                echo "<option style='border-bottom: 1px solid #e7e7e7; padding-bottom: 5px !important;' value=\"$risks_id\">$risks_id.  $risks_name</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="connect_risk" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- ======= ASSOCIATED RISKS TABLE ======= -->
                    <?php
                    if (isset($_POST['del-risk'])) {
                        $risk_id = intval($_POST['risk_id']);
                        $clause_id = intval($_POST['clause_id']);
                        $clause_type = mysqli_real_escape_string($connection, $_POST['clause_type']);

                        $delete_query = "DELETE FROM risk_policies WHERE risks_id = $risk_id AND clause_id = $clause_id AND clause_type = '$clause_type'";
                        mysqli_query($connection, $delete_query);

                        echo "<div style='font-size: 12px;' id='alertBox' class='alert alert-success mt-2'>Risk removed successfully.</div>";
                    }


                    $fetch_risks_query = "SELECT r.risks_id, r.risks_name FROM risk_policies rp
                    JOIN risks r ON rp.risks_id = r.risks_id
                    WHERE rp.clause_id = $policy_id AND rp.clause_type = '$policy_table'";
                    $fetch_risks_r = mysqli_query($connection, $fetch_risks_query);
                    ?>

                    <?php if (mysqli_num_rows($fetch_risks_r) > 0) { ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="font-size: 12px !important;">Risk Name</th>
                                        <th style="font-size: 12px !important;">View</th>
                                        <?php
                                        if ($user_role == "2") {
                                        ?>
                                            <th class="d-none" style="font-size: 12px !important;">Remove</th>
                                        <?php } else { ?>
                                            <th style="font-size: 12px !important;">Remove</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($risk = mysqli_fetch_assoc($fetch_risks_r)) { ?>
                                        <tr>
                                            <td style="font-size: 12px !important;"><?php echo htmlspecialchars($risk['risks_name']); ?></td>
                                            <td>
                                                <a href="risks-details.php?id=<?php echo $risk['risks_id']; ?>" class="btn btn-sm btn-outline-success" style="font-size: 12px !important;">
                                                    View
                                                </a>
                                            </td>
                                            <?php
                                            if ($user_role == "2") {
                                            ?>
                                                <td class="d-none">
                                                    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                        <input type="hidden" name="risk_id" value="<?php echo $risk['risks_id']; ?>">
                                                        <input type="hidden" name="clause_id" value="<?php echo htmlspecialchars($policy_id); ?>">
                                                        <input type="hidden" name="clause_type" value="<?php echo htmlspecialchars($policy_table); ?>">

                                                        <button type="submit" name="del-risk" class="btn btn-sm btn-outline-danger" style="font-size: 12px !important;">
                                                            <ion-icon name="trash-outline"></ion-icon>
                                                        </button>
                                                    </form>

                                                </td>
                                            <?php } else { ?>
                                                <td>
                                                    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                        <input type="hidden" name="risk_id" value="<?php echo $risk['risks_id']; ?>">
                                                        <input type="hidden" name="clause_id" value="<?php echo htmlspecialchars($policy_id); ?>">
                                                        <input type="hidden" name="clause_type" value="<?php echo htmlspecialchars($policy_table); ?>">

                                                        <button type="submit" name="del-risk" class="btn btn-sm btn-outline-danger" style="font-size: 12px !important;">
                                                            <ion-icon name="trash-outline"></ion-icon>
                                                        </button>
                                                    </form>

                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        <!-- <p style='font-size: 12px;'>No risks & treatments assigned to this policy.</p> -->
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="font-size: 12px !important;">Risk Name</th>
                                    <th style="font-size: 12px !important;">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3" style="font-size: 12px !important;">No risks & treatments assigned to this policy.</td>
                                </tr>
                            </tbody>
                        </table>
                    <?php } ?>

                </div>
                <!-- ========== COMMENT SECTION ========== -->
                <div class="card p-3 mb-3">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h6>Comments</h6>
                        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#commentModal" style="font-size: 12px !important;">
                            <ion-icon name="add-outline"></ion-icon>
                        </button>
                    </div>

                    <?php
                    // Determine ID and type
                    $ca_comment_parent_id = null;
                    $ca_comment_type = '';

                    if (isset($_GET['inner_policy_id'])) {
                        $ca_comment_parent_id = $_GET['inner_policy_id'];
                        $ca_comment_type = 'Inner Policy';
                    } elseif (isset($_GET['linked_policy_id'])) {
                        $ca_comment_parent_id = $_GET['linked_policy_id'];
                        $ca_comment_type = 'Linked Policy';
                    } elseif (isset($_GET['policy_id'])) {
                        $ca_comment_parent_id = $_GET['policy_id'];
                        $ca_comment_type = 'Policy';
                    }

                    // Add comment logic
                    if (isset($_POST['add-new-comment'])) {
                        date_default_timezone_set('Asia/Kolkata');
                        $ca_comment_parent_id = mysqli_real_escape_string($connection, $_POST['ca_comment_parent_id']);
                        $ca_comment_type = mysqli_real_escape_string($connection, $_POST['ca_comment_type']);
                        $ca_comment_data = mysqli_real_escape_string($connection, $_POST['ca_comment_data']);
                        $ca_comment_by = mysqli_real_escape_string($connection, $_POST['ca_comment_by']);
                        $ca_comment_date = date('Y-m-d H:i:s');

                        $insert_comment_query = "INSERT INTO tblca_comment (ca_comment_parent_id, ca_comment_type, ca_comment_data, ca_comment_by, ca_comment_date) VALUES ('$ca_comment_parent_id', '$ca_comment_type', '$ca_comment_data', '$ca_comment_by', '$ca_comment_date')";
                        $insert_comment_result = mysqli_query($connection, $insert_comment_query);
                    }

                    // Delete comment logic
                    if (isset($_POST['delete-note'])) {
                        $ca_comment_id = mysqli_real_escape_string($connection, $_POST['ca_comment_id']);
                        $delete_comment_query = "DELETE FROM tblca_comment WHERE ca_comment_id = '$ca_comment_id'";
                        $delete_comment_result = mysqli_query($connection, $delete_comment_query);
                    }

                    // Fetch and display comments
                    $get_comment_query = "SELECT * FROM tblca_comment 
                          WHERE ca_comment_parent_id = '$ca_comment_parent_id' 
                          AND ca_comment_type = '$ca_comment_type' 
                          ORDER BY ca_comment_date DESC";
                    $get_comment_result = mysqli_query($connection, $get_comment_query);

                    if (mysqli_num_rows($get_comment_result) > 0) {
                        while ($row = mysqli_fetch_assoc($get_comment_result)) {
                            $main_comment_id = $row['ca_comment_id'];
                            $main_comment = $row['ca_comment_data'];
                            $main_comment_by = $row['ca_comment_by'];
                            $main_comment_date = $row['ca_comment_date'];
                    ?>
                            <div style="margin-bottom: 20px !important; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                                <form action="" method="POST" style="display: flex; justify-content: space-between; align-items: center;">
                                    <input type="hidden" name="ca_comment_id" value="<?php echo $main_comment_id ?>">
                                    <p style="font-size: 12px !important; font-weight: 600 !important; margin: 0;"><?php echo $main_comment_by ?> - <?php echo $main_comment_date ?></p>
                                    <button style="font-size: 12px !important;" name="delete-note" class="btn btn-sm btn-outline-danger">
                                        <ion-icon name="close-circle-outline"></ion-icon>
                                    </button>
                                </form>
                                <p style="margin: 0; font-size: 16px"><?php echo $main_comment ?></p>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<p style="font-size: 12px;">No comments added</p>';
                    }
                    ?>

                    <!-- ======= ADD COMMENT MODAL ======= -->
                    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="" method="POST" class="modal-content">
                                <input type="hidden" name="ca_comment_parent_id" value="<?php echo htmlspecialchars($ca_comment_parent_id); ?>">
                                <input type="hidden" name="ca_comment_type" value="<?php echo htmlspecialchars($ca_comment_type); ?>">
                                <input type="hidden" name="ca_comment_by" value="<?php echo htmlspecialchars($user_name); ?>">

                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Comment</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-floating">
                                        <textarea class="form-control" name="ca_comment_data" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                                        <label for="floatingTextarea2">Comments</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="add-new-comment" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    $('#history-content').summernote({
                        height: 300,
                        minHeight: 150,
                        maxHeight: 500,
                        focus: true,
                        toolbar: [
                            ["style", ["style"]],
                            ["font", ["bold", "underline", "clear"]],
                            ["color", ["color"]],
                            ["para", ["ul", "ol", "paragraph"]],
                            ["table", ["table"]],
                            ["insert", ["link"]],
                            ["view", ["fullscreen"]],
                        ],
                    });

                    document.querySelectorAll('.view-history-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const versionDetails = this.getAttribute('data-version');
                            const updatedOn = this.getAttribute('data-updatedon');
                            document.getElementById('history-updated-on').innerHTML = updatedOn;
                            $('#history-content').summernote('code', versionDetails);
                        });
                    });
                });

                $("form").on("submit", function() {
                    // If you're submitting #history-content
                    const content = $('#history-content').summernote('code');
                    $('#history-content').val(content); // sets value back to textarea
                });



                function stripHtml(str) {
                    var doc = new DOMParser().parseFromString(str, 'text/html');
                    return doc.body.textContent || "";
                }
                $(document).ready(function() {
                    $('#editorNewSim').summernote({
                        height: 300,
                        minHeight: 150,
                        maxHeight: 500,
                        focus: true
                    });

                    $('form').on('submit', function() {
                        $('#editorNewSim').val($('#editorNewSim').summernote('code'));
                    });
                });

                document.getElementById('riskSearch').addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    const options = document.querySelectorAll('#riskSelect option');

                    options.forEach(option => {
                        const text = option.textContent.toLowerCase();
                        option.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            </script>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>