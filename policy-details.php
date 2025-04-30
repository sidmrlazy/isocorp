<?php
include('includes/header.php');
include('includes/navbar.php');
include 'includes/connection.php';
include 'includes/config.php';
include 'functions/policy-details/save-function.php';
?>
<div class="dashboard-container">
    <div class="mb-3 policy-det-heading-section">
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

                <h1 style="font-size: 24px; font-weight: 500;">Policy Details</h1>
                <div class="details-container">
                    <h6 style="font-size: 16px !important;"><?= $policy_number . " " . $policy_heading ?></h6>
                    <p style="font-size: 16px !important; margin: 0;"><?= $policy_content ?></p>
                </div>

        <?php
            }
        }

        $policy_content = "";
        if ($policy_id && $policy_table) {
            $query = "SELECT policy_details FROM policy_details WHERE policy_id = ? AND policy_table = ?";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "is", $policy_id, $policy_table);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $policy = mysqli_fetch_assoc($result);
                $policy_content = stripslashes($policy["policy_details"]);
            }
        }
        ?>
        <div style="margin-bottom: 0; display: flex; justify-content: space-between; align-items: flex-start;">
            <!-- ========== UPLOAD CONTENT ========== -->
            <div style="flex: 1 !important; margin-bottom: 50px;">
                <form action="" method="POST" style="background-color: #fff; margin-top: 10px; padding: 20px; border-radius: 10px;">
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

                    <button type="submit" name="save" class="btn btn-sm btn-success mt-3">Update</button>
                </form>

                <!-- ========== ASSIGNMENT SECTION ========== -->
                <div style="background-color: #fff; margin-top: 10px; padding: 20px; border-radius: 10px;">
                    <?php
                    if (isset($_POST['update-details'])) {
                        $vc_data_id = $_POST['vc_data_id'];
                        $vc_screen_name = "Policy Details";
                        date_default_timezone_set('Asia/Kolkata');
                        $vc_updated_on = date('Y-m-d H:i:s');
                        $vc_assigned_to = $_POST['vc_assigned_to'];
                        $vc_status = $_POST['vc_status'];
                        $vc_updated_by = $_POST['vc_updated_by'];

                        // Check if a record already exists for this policy
                        $check_query = "SELECT * FROM version_control WHERE vc_data_id = '$vc_data_id'";
                        $check_result = mysqli_query($connection, $check_query);

                        if (mysqli_num_rows($check_result) > 0) {
                            // Record exists — perform UPDATE
                            $update_query = "UPDATE version_control 
                SET vc_assigned_to = '$vc_assigned_to',
                    vc_status = '$vc_status',
                    vc_updated_on = '$vc_updated_on',
                    vc_updated_by = '$vc_updated_by'
                WHERE vc_data_id = '$vc_data_id'";
                            $query_result = mysqli_query($connection, $update_query);
                        } else {

                            $insert_query = "INSERT INTO version_control (
                            vc_data_id, 
                            vc_screen_name, 
                            vc_assigned_to, 
                            vc_status, 
                            vc_updated_on, 
                            vc_updated_by
                        ) VALUES (
                            '$vc_data_id',
                            '$vc_screen_name',
                            '$vc_assigned_to',
                            '$vc_status',
                            '$vc_updated_on',
                            '$vc_updated_by'
                        )";
                            $query_result = mysqli_query($connection, $insert_query);
                        }

                        if ($query_result) {
                            echo "<div id='alertBox' class='alert alert-success'>Details saved successfully.</div>";
                        } else {
                            echo "<div id='alertBox' class='alert alert-danger'>Error: " . mysqli_error($connection) . "</div>";
                        }
                    }

                    // Fetch values to prepopulate form
                    $vc_assigned_to_value = '';
                    $vc_status_value = '';

                    $get_vc_data_query = "SELECT vc_assigned_to, vc_status FROM version_control WHERE vc_data_id = '$policy_id' LIMIT 1";
                    $get_vc_data_result = mysqli_query($connection, $get_vc_data_query);

                    if (mysqli_num_rows($get_vc_data_result) > 0) {
                        $vc_data = mysqli_fetch_assoc($get_vc_data_result);
                        $vc_assigned_to_value = $vc_data['vc_assigned_to'];
                        $vc_status_value = $vc_data['vc_status'];
                    }
                    ?>

                    <form action="" method="POST">
                        <input type="text" name="vc_data_id" value="<?php echo $policy_id ?>" hidden>
                        <input type="text" name="vc_updated_by" value="<?php echo $user_name ?>" hidden>

                        <div class="mb-3">
                            <label style="font-size: 12px !important;" class="form-label">Assigned to</label>
                            <select style="font-size: 12px !important;" class="form-select" name="vc_assigned_to" aria-label="Assigned user">
                                <option disabled selected>Select a user</option>
                                <?php
                                $get_assigned_user = "SELECT * FROM user";
                                $get_assigned_user_r = mysqli_query($connection, $get_assigned_user);
                                while ($row = mysqli_fetch_assoc($get_assigned_user_r)) {
                                    $assigned_user_name = $row['isms_user_name'];
                                    $selected = ($assigned_user_name == $vc_assigned_to_value) ? 'selected' : '';
                                    echo "<option value=\"$assigned_user_name\" $selected>$assigned_user_name</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label style="font-size: 12px !important;" class="form-label">Status</label>
                            <select style="font-size: 12px !important;" class="form-select" name="vc_status" aria-label="Status select">
                                <option disabled selected>Select status</option>
                                <?php
                                $statuses = ['Open', 'Closed', 'In Progress'];
                                foreach ($statuses as $status) {
                                    $selected = ($status == $vc_status_value) ? 'selected' : '';
                                    echo "<option value=\"$status\" $selected>$status</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <button type="submit" name="update-details" class="btn btn-sm btn-success">Submit</button>
                    </form>
                </div>

            </div>




            <div style="flex: 1 !important; margin-bottom: 20px !important;">
                <!-- ========== VERSION CONTROL ========== -->
                <div style="margin: 10px; padding: 20px; border-radius: 10px; background-color: #fff;">
                    <div style="display: flex !important; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h6>History</h6>
                    </div>
                    <div style="width: 100% !important;">
                        <?php
                        $history_query = "SELECT * FROM policy_details_history WHERE policy_id = '$policy_id' ORDER BY policy_update_on DESC";
                        $history_result = mysqli_query($connection, $history_query);
                        if (mysqli_num_rows($history_result) > 0) :
                        ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px !important;" scope="col">Previous Details</th>
                                            <th style="font-size: 12px !important;" scope="col">Updated on</th>
                                            <th style="font-size: 12px !important;" scope="col">View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($history = mysqli_fetch_assoc($history_result)) : ?>
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

                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                                <!-- ========== HISTORY MODAL ========== -->
                                <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Previous Version Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Updated On:</strong> <span id="history-updated-on"></span></p>
                                                <div class="border rounded p-3 bg-light" id="history-content" style="white-space: pre-wrap;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    document.querySelectorAll('.view-history-btn').forEach(btn => {
                                        btn.addEventListener('click', function() {
                                            const versionDetails = this.getAttribute('data-version');
                                            const updatedOn = this.getAttribute('data-updatedon');

                                            document.getElementById('history-updated-on').innerHTML = updatedOn;
                                            document.getElementById('history-content').innerHTML = versionDetails;
                                        });
                                    });
                                </script>

                            <?php else : ?>
                                <p style="font-size: 12px;">No previous policy details found.</p>
                            <?php endif; ?>
                            </div>
                    </div>
                </div>

                <!-- ========== SUPPORTING DOCUMENTS ========== -->
                <div style="margin: 10px; padding: 20px; border-radius: 10px; background-color: #fff;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h6>Documents</h6>
                        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#uploadDoc" style="font-size: 12px !important;">
                            <ion-icon name="add-outline"></ion-icon>
                        </button>
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
                            <table class="table table-striped table-bordered">
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
                                            <td>
                                                <form action="update-document.php" method="POST">
                                                    <input type="text" name="policy_document_id" value="<?php echo $document_id; ?>" hidden>
                                                    <button type="submit" name="doc-edit-btn" class="btn btn-sm btn-outline-warning" style="font-size: 12px;">
                                                        Edit
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="" method="POST">
                                                    <input type="text" name="document_id" value="<?= $document_id ?>" hidden>
                                                    <button type="submit" name="delete-doc" class="btn btn-sm btn-outline-danger" style="font-size: 12px;">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p style='font-size: 12px;'>No documents uploaded for this policy.</p>
                    <?php endif; ?>
                </div>

                <!-- ========== RISKS ========== -->
                <div style="margin: 10px; padding: 20px; border-radius: 10px; background-color: #fff;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h6>Associated Risks & Treatments</h6>
                        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#riskModal" style="font-size: 12px !important;">
                            <ion-icon name="add-outline"></ion-icon>
                        </button>
                    </div>
                    <!-- ======= ADD RISK MODAL ======= -->
                    <div class="modal fade" id="riskModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <?php
                        if (isset($_POST['connect_risk'])) {

                            $risk_ids = $_POST['risk_ids'];
                            $clause_id = intval($_POST['clause_id']);
                            $clause_type = mysqli_real_escape_string($connection, $_POST['clause_type']);

                            foreach ($risk_ids as $risk_id) {
                                $risk_id = intval($risk_id);

                                // Check if the relation already exists
                                $check_exist = "SELECT * FROM risk_policies WHERE risks_id = $risk_id AND clause_id = $clause_id AND clause_type = '$clause_type'";
                                $result = mysqli_query($connection, $check_exist);

                                if (mysqli_num_rows($result) == 0) {
                                    // Insert new relationship
                                    $insert = "INSERT INTO risk_policies (risks_id, clause_id, clause_type) VALUES ($risk_id, $clause_id, '$clause_type')";
                                    mysqli_query($connection, $insert);
                                }
                            }

                            echo "<div class='alert alert-success mt-2'>Risks successfully linked to the policy/control.</div>";
                        }
                        ?>
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="" method="POST" class="modal-content">
                                <input type="hidden" name="clause_id" value="<?php echo $policy_id ?>">
                                <input type="hidden" name="clause_type" value="policy"> <!-- You can change this dynamically -->

                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Risk to Policy</h1>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Risks</label>
                                        <select name="risk_ids[]" style="font-size: 12px !important;" multiple class="form-select">
                                            <option disabled selected>Choose Risks</option>
                                            <?php
                                            $get_risks = "SELECT * FROM risks";
                                            $get_risks_r = mysqli_query($connection, $get_risks);
                                            while ($row = mysqli_fetch_assoc($get_risks_r)) {
                                                $risks_id = $row['risks_id'];
                                                $risks_name = $row['risks_name'];
                                                echo "<option value=\"$risks_id\">$risks_name</option>";
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
                    $fetch_risks_query = "
                        SELECT r.risks_id, r.risks_name
                        FROM risk_policies rp
                        JOIN risks r ON rp.risks_id = r.risks_id
                        WHERE rp.clause_id = $policy_id AND rp.clause_type = 'policy'
                    ";
                    $fetch_risks_r = mysqli_query($connection, $fetch_risks_query);
                    ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="font-size: 12px !important;">Risk Name</th>
                                    <th style="font-size: 12px !important;">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($risk = mysqli_fetch_assoc($fetch_risks_r)) { ?>
                                    <tr>
                                        <td style="font-size: 12px !important;"><?php echo htmlspecialchars($risk['risks_name']); ?></td>
                                        <td>
                                            <a href="risks-details.php?id=<?php echo $risk['risks_id']; ?>" class="btn btn-sm btn-outline-success" style="font-size: 12px !important;">
                                                View Risk Details
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- ========== COMMENT ========== -->
                <div style="margin: 10px; padding: 20px; border-radius: 10px; background-color: #fff;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h6>Comments</h6>
                        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#commentModal" style="font-size: 12px !important;">
                            <ion-icon name="add-outline"></ion-icon>
                        </button>
                    </div>
                    <?php
                    if (isset($_POST['add-new-comment'])) {
                        date_default_timezone_set('Asia/Kolkata');
                        $ca_comment_parent_id = mysqli_real_escape_string($connection, $_POST['ca_comment_parent_id']);
                        $ca_comment_data = mysqli_real_escape_string($connection, $_POST['ca_comment_data']);
                        $ca_comment_by = mysqli_real_escape_string($connection, $_POST['ca_comment_by']);
                        $ca_comment_date = date('Y-m-d H:i:s');
                        $insert_comment_query = "INSERT INTO `tblca_comment` (
                        `ca_comment_parent_id`,
                        `ca_comment_data`,
                        `ca_comment_by`,
                        `ca_comment_date`
                    ) VALUES (
                        '$ca_comment_parent_id',
                        '$ca_comment_data',
                        '$ca_comment_by',
                        '$ca_comment_date'
                    )";
                        $insert_comment_result = mysqli_query($connection, $insert_comment_query);
                    }
                    ?>

                    <!-- ======= ADD COMMENT MODAL ======= -->
                    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div action="" method="POST" class="modal-dialog modal-dialog-centered">
                            <form action="" method="POST" class="modal-content">
                                <input type="text" name="ca_comment_parent_id" value="<?php echo $policy_id ?>" hidden>
                                <input type="text" name="ca_comment_by" value="<?php echo $user_name ?>" hidden>
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
                    <?php
                    if (isset($_POST['delete-note'])) {
                        $ca_comment_id = mysqli_real_escape_string($connection, $_POST['ca_comment_id']);
                        $delete_comment_query = "DELETE FROM `tblca_comment` WHERE ca_comment_id = '$ca_comment_id'";
                        $delete_comment_result = mysqli_query($connection, $delete_comment_query);
                    }

                    $get_comment = "SELECT * FROM `tblca_comment` WHERE `ca_comment_parent_id` = '$policy_id'";
                    $get_comment_r = mysqli_query($connection, $get_comment);
                    $get_comment_count = mysqli_num_rows($get_comment_r);
                    if ($get_comment_count > 0) {
                        while ($row = mysqli_fetch_assoc($get_comment_r)) {
                            $main_comment_id = $row['ca_comment_id'];
                            $main_comment = $row['ca_comment_data'];
                            $main_comment_by = $row['ca_comment_by'];
                            $main_comment_date = $row['ca_comment_date'];

                    ?>
                            <div style="margin-bottom: 20px !important; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                                <form action="" method="POST" style="display: flex; justify-content: space-between; align-items: center;">
                                    <input type="hidden" name="ca_comment_id" value="<?php echo $main_comment_id ?>">
                                    <p style="font-size: 12px !important; font-weight: 600 !important; margin: 0;"><?php echo $main_comment_by ?> - <?php echo $main_comment_date ?></p>
                                    <button style="font-size: 12px !important;" name="delete-note" class="btn btn-sm btn-outline-danger"><ion-icon name="close-circle-outline"></ion-icon></button>
                                </form>
                                <p style="margin: 0; font-size: 16px"><?php echo $main_comment ?></p>
                            </div>
                        <?php }
                    } else { ?>
                        <p style="font-size: 12px;">No comments added</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>