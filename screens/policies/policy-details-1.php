<div class="container mt-3 mb-3 policy-det-heading-section">
    <?php
    include 'includes/connection.php';

    // Ensure database connection is valid
    if (!$connection) {
        die("<div class='alert alert-danger mt-3 mb-3'>Database connection failed: " . mysqli_connect_error() . "</div>");
    }

    if (isset($_POST['save'])) {
        $sub_policy_control_id = intval($_POST['sub_policy_control_id']);
        $sub_policy_details = $_POST['sub_policy_details'];

        // Check if the policy details already exist
        $check_existing = "SELECT * FROM policy_details WHERE policy_details_linked = ?";
        $stmt = mysqli_prepare($connection, $check_existing);
        if (!$stmt) {
            die("<div class='alert alert-danger mt-3 mb-3'>Prepare Error: " . mysqli_error($connection) . "</div>");
        }
        mysqli_stmt_bind_param($stmt, "i", $sub_policy_control_id);
        mysqli_stmt_execute($stmt);
        $result_existing = mysqli_stmt_get_result($stmt);

        if ($result_existing && mysqli_num_rows($result_existing) > 0) {
            // Update existing record
            $update_dets = "UPDATE policy_details SET policy_details_content = ? WHERE policy_details_linked = ?";
            $stmt = mysqli_prepare($connection, $update_dets);
            if (!$stmt) {
                die("<div class='alert alert-danger mt-3 mb-3'>Prepare Error: " . mysqli_error($connection) . "</div>");
            }
            mysqli_stmt_bind_param($stmt, "si", $sub_policy_details, $sub_policy_control_id);
            $update_dets_res = mysqli_stmt_execute($stmt);
        } else {
            // Insert new record
            $insert_dets = "INSERT INTO policy_details (policy_details_linked, policy_details_content) VALUES (?, ?)";
            $stmt = mysqli_prepare($connection, $insert_dets);
            if (!$stmt) {
                die("<div class='alert alert-danger mt-3 mb-3'>Prepare Error: " . mysqli_error($connection) . "</div>");
            }
            mysqli_stmt_bind_param($stmt, "is", $sub_policy_control_id, $sub_policy_details);
            $insert_dets_res = mysqli_stmt_execute($stmt);
        }

        if ((isset($update_dets_res) && $update_dets_res) || (isset($insert_dets_res) && $insert_dets_res)) {
            echo '<div class="alert alert-success mt-3 mb-3" role="alert">Policy details successfully saved.</div>';
        } else {
            echo "<div class='alert alert-danger mt-3 mb-3'>Error: " . mysqli_error($connection) . "</div>";
        }
    }

    // Fetch policy details based on the type of policy
    $policy_id = null;
    $policy_table = "";

    if (isset($_GET['policy_id'])) {
        $policy_id = intval($_GET['policy_id']);
        $policy_table = "sub_control_policy";
    } elseif (isset($_GET['linked_policy_id'])) {
        $policy_id = intval($_GET['linked_policy_id']);
        $policy_table = "linked_control_policy";
    } elseif (isset($_GET['inner_policy_id'])) {
        $policy_id = intval($_GET['inner_policy_id']);
        $policy_table = "inner_linked_control_policy";
    }

    // Validate table name
    $allowed_tables = ['sub_control_policy', 'linked_control_policy', 'inner_linked_control_policy'];
    if (!in_array($policy_table, $allowed_tables)) {
        die("<div class='alert alert-danger mt-3 mb-3'>Invalid policy table specified.</div>");
    }

    if ($policy_id && $policy_table) {
        // Fetch policy details
        $query = "SELECT * FROM $policy_table WHERE sub_control_policy_id = ? OR linked_control_policy_id = ? OR inner_linked_control_policy_id = ?";
        $stmt = mysqli_prepare($connection, $query);
        if (!$stmt) {
            die("<div class='alert alert-danger mt-3 mb-3'>Prepare Error: " . mysqli_error($connection) . "</div>");
        }
        mysqli_stmt_bind_param($stmt, "iii", $policy_id, $policy_id, $policy_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $policy = mysqli_fetch_assoc($result);
            $policy_number = isset($policy['sub_control_policy_number']) ? htmlspecialchars($policy['sub_control_policy_number']) :
                (isset($policy['linked_control_policy_number']) ? htmlspecialchars($policy['linked_control_policy_number']) :
                    (isset($policy['inner_linked_control_policy_number']) ? htmlspecialchars($policy['inner_linked_control_policy_number']) : ''));

            $policy_heading = isset($policy['sub_control_policy_heading']) ? htmlspecialchars($policy['sub_control_policy_heading']) :
                (isset($policy['linked_control_policy_heading']) ? htmlspecialchars($policy['linked_control_policy_heading']) :
                    (isset($policy['inner_linked_control_policy_heading']) ? htmlspecialchars($policy['inner_linked_control_policy_heading']) : ''));

            $policy_content = isset($policy['sub_control_policy_det']) ? htmlspecialchars($policy['sub_control_policy_det']) :
                (isset($policy['linked_control_policy_det']) ? htmlspecialchars($policy['linked_control_policy_det']) :
                    (isset($policy['inner_linked_control_policy_det']) ? htmlspecialchars($policy['inner_linked_control_policy_det']) : ''));

            ?>

            <h1>Policy Details</h1>
            <div class="details-container">
                <h2><?= $policy_number . " " . $policy_heading ?></h2>
                <p><?= $policy_content ?></p>
            </div>

            <?php
            // Fetch saved policy details (editable in CKEditor)
            // $fetch_policy_details = "SELECT policy_details_content FROM policy_details WHERE policy_details_linked = ?";
            // $stmt = mysqli_prepare($connection, $fetch_policy_details);
            // if (!$stmt) {
            //     die("<div class='alert alert-danger mt-3 mb-3'>Prepare Error: " . mysqli_error($connection) . "</div>");
            // }
            // mysqli_stmt_bind_param($stmt, "i", $policy_id);
            // mysqli_stmt_execute($stmt);
            // $fetch_policy_details_r = mysqli_stmt_get_result($stmt);
    
            // $policy_details_content = "";
            // if ($fetch_policy_details_r && mysqli_num_rows($fetch_policy_details_r) > 0) {
            //     $row = mysqli_fetch_assoc($fetch_policy_details_r);
            //     $policy_details_content = $row['policy_details_content'];
            // }
            ?>

            <!-- <div class="details-activity-container">
                <form action="" method="POST">
                    <input type="hidden" name="sub_policy_control_id" value="<?= $policy_id ?>" />
                    <div class="mb-3">
                        <textarea name="sub_policy_details" id="editor1"
                            class="ck-editor"><?= htmlspecialchars($policy_details_content) ?></textarea>
                        <button type="submit" name="save" class="btn btn-sm btn-success mt-3">Save</button>
                    </div>
                </form>
            </div>

            <script>
                CKEDITOR.replace('editor1');
            </script> -->

            <?php
        } else {
            echo "<p>Policy details not found.</p>";
        }
    } else {
        echo "<p>No policy ID provided.</p>";
    }
    ?>
</div>