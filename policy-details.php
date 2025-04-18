<?php
include('includes/header.php');
include('includes/navbar.php');
include 'includes/connection.php';
include 'includes/config.php'; ?>
<div class="container mt-3 mb-3 policy-det-heading-section">
    <?php
    if (!$connection) {
        die("<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Database connection failed: " . mysqli_connect_error() . "</div>");
    }

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
                    echo "<div id='alertBox' class='alert alert-success mt-3 mb-3'>Document deleted successfully.</div>";
                } else {
                    echo "<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Error deleting document: " . mysqli_error($connection) . "</div>";
                }
            } else {
                echo "<div id='alertBox' class='alert alert-warning mt-3 mb-3'>Document not found.</div>";
            }
        } else {
            echo "<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Invalid document ID.</div>";
        }
    }

    if (isset($_POST['save'])) {
        $policy_id = isset($_POST['policy_id']) ? $_POST['policy_id'] : null;
        $linked_policy_id = isset($_POST['linked_policy_id']) ? $_POST['linked_policy_id'] : null;
        $inner_policy_id = isset($_POST['inner_policy_id']) ? $_POST['inner_policy_id'] : null;
        $policy_table = isset($_POST['policy_table']) ? $_POST['policy_table'] : null;
        $editorContent = isset($_POST['editorContent']) ? $_POST['editorContent'] : null;
        $editorBlob = !empty($editorContent) ? addslashes($editorContent) : NULL;

        if (!empty($policy_table)) {
            if (!empty($policy_id) || !empty($linked_policy_id) || !empty($inner_policy_id)) {
                $current_policy_id = !empty($policy_id) ? $policy_id : (!empty($linked_policy_id) ? $linked_policy_id : $inner_policy_id);

                $query = "SELECT 1 FROM policy_details WHERE policy_id = ? AND policy_table = ?";
                $stmt = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($stmt, "is", $current_policy_id, $policy_table);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $rowCount = mysqli_stmt_num_rows($stmt);

                if ($rowCount > 0) {
                    $update_query = "UPDATE policy_details SET policy_details = ? WHERE policy_id = ? AND policy_table = ?";
                    $stmt = mysqli_prepare($connection, $update_query);
                    mysqli_stmt_bind_param($stmt, "sis", $editorBlob, $current_policy_id, $policy_table);
                } else {
                    $insert_query = "INSERT INTO policy_details (policy_id, policy_table, policy_details, policy_document) VALUES (?, ?, ?, NULL)";
                    $stmt = mysqli_prepare($connection, $insert_query);
                    mysqli_stmt_bind_param($stmt, "iss", $current_policy_id, $policy_table, $editorBlob);
                }

                if (mysqli_stmt_execute($stmt)) {
                    echo '<div id="alertBox" class="alert alert-success mt-3 mb-3">Policy details saved successfully.</div>';

                    // ✅ Insert into activity table with activity_done_on_id
                    date_default_timezone_set('Asia/Kolkata');
                    $activity_done_on = $policy_table;
                    $activity_done_on_id = $current_policy_id;
                    $activity_name = "Added details to policy";
                    $activity_by = $user_name; // Assume $user_name is set from session or elsewhere
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
        die("<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Invalid policy table specified.</div>");
    }

    if ($policy_id && $policy_table && $policy_column) {

        $query = "SELECT * FROM $policy_table WHERE $policy_column = ?";
        $stmt = mysqli_prepare($connection, $query);
        if (!$stmt) {
            die("<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Prepare Error: " . mysqli_error($connection) . "</div>");
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
                <h2 style="font-size: 20px !important;"><?= $policy_number . " " . $policy_heading ?></h2>
                <p style="font-size: 20px; margin: 0;"><?= $policy_content ?></p>
            </div>

        <?php
        } else {
            echo "<p>Policy details not found.</p>";
        }
    } else {
        echo "<p>No policy ID provided.</p>";
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
        ?>
            <div class="clause-container" style="font-size: 16px !important; width: 100%">
                <style>

                </style>
                <div class="clause-content" style="font-size: 16px !important; font-weight: 400 !important;">
                    <?= htmlspecialchars_decode($policy_content) ?>
                </div>
                <div style="display:flex; justify-content: center; align-items: center;">
                    <button class="read-more-btn btn btn-outline-dark" style="display: none; margin-top: 10px; cursor: pointer; padding: 5px 10px; font-size: 24px; border: 0; background: none;">
                        <ion-icon name="caret-down-circle-outline"></ion-icon>
                    </button>
                </div>
            </div>
    <?php
        } else {
        }
    } else {
        echo "<div id='alertBox' class='alert alert-danger mt-3'>Invalid Policy ID or Table.</div>";
    }


    if (isset($_POST['upload'])) {
        $policy_id = intval($_POST['policy_id']);
        $policy_table = isset($_POST['policy_table_for_document']) ? $_POST['policy_table_for_document'] : null;

        if (is_null($policy_table)) {
            die("<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Policy table for document is missing!</div>");
        }

        if (!isset($_FILES['document']) || $_FILES['document']['error'] != UPLOAD_ERR_OK) {
            die("<div id='alertBox' class='alert alert-danger mt-3 mb-3'>File upload error!</div>");
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
                echo "<div id='alertBox' class='alert alert-success mt-3 mb-3'>Document uploaded successfully.</div>";

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
                echo "<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Error uploading document: " . mysqli_error($connection) . "</div>";
            }
        } else {
            echo "<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Failed to move uploaded file.</div>";
        }
    }
    ?>


    <div class="section-divider mb-5" style="margin-bottom: 0 !important;">
        <?php if ($user_role == '1') { ?>
            <!-- ========== UPLOAD CONTENT ========== -->
            <div style="flex: 2">
                <form action="" method="POST" class="WYSIWYG-editor-container">
                    <input type="hidden" name="policy_id"
                        value="<?php echo isset($_GET['policy_id']) ? $_GET['policy_id'] : ''; ?>">
                    <input type="hidden" name="linked_policy_id"
                        value="<?php echo isset($_GET['linked_policy_id']) ? $_GET['linked_policy_id'] : ''; ?>">
                    <input type="hidden" name="inner_policy_id"
                        value="<?php echo isset($_GET['inner_policy_id']) ? $_GET['inner_policy_id'] : ''; ?>">
                    <input type="hidden" name="policy_table" value="<?php echo isset($policy_table) ? $policy_table : ''; ?>">

                    <div class="WYSIWYG-editor">
                        <textarea id="editorNew"></textarea>
                    </div>
                    <input type="hidden" name="editorContent" id="editorContent">

                    <button type="submit" name="save" class="btn btn-sm btn-success mt-3">Update</button>
                </form>


                <!-- ========== VERSION CONTROL ========== -->
                <?php
                $fetch_vc = "SELECT * FROM `version_control` WHERE `vc_data_id` = '$policy_id'";
                $fetch_vc_r = mysqli_query($connection, $fetch_vc);
                $fetched_vc_assigned_to = "";
                $fetched_vc_status = "";
                $fetched_vc_updated_on = "";
                $fetched_vc_updated_by = "";
                while ($row = mysqli_fetch_assoc($fetch_vc_r)) {
                    $fetched_vc_assigned_to = $row['vc_assigned_to'];
                    $fetched_vc_status = $row['vc_status'];
                    $fetched_vc_updated_on = $row['vc_updated_on'];
                    $fetched_vc_updated_by = $row['vc_updated_by'];
                }
                ?>
                <div style="border-bottom: 1px solid #000; margin-top: 10px; padding: 10px; margin-bottom: 20px; width: 50%;">
                    <h5 style="font-weight: 600 !important; font-size: 18px !important;">Details</h5>
                    <p style="margin: 0; font-size: 12px;">Status: <strong><?php echo $fetched_vc_status ?></strong></p>
                    <p style="margin: 0; font-size: 12px;">Assigned to: <?php echo $fetched_vc_assigned_to ?></p>
                    <p style="margin: 0; font-size: 12px;">Update on: <?php echo $fetched_vc_updated_on ?></p>
                    <p style="margin: 0; font-size: 12px;">Updated by: <?php echo $fetched_vc_updated_by ?></p>
                </div>

                <?php
                // $get_activity = "SELECT * FROM activity WHERE activity_done_on_id = '$policy_id'";
                // $get_activity_r = mysqli_query($connection, $get_activity);
                // $activity_name_fetched = "";
                // $activity_by_fetched = "";
                // $activity_date_fetched = "";
                // while ($row = mysqli_fetch_assoc($get_activity_r)) {
                //     $activity_name_fetched = $row['activity_name'];
                //     $activity_by_fetched = $row['activity_by'];
                //     $activity_date_fetched = $row['activity_date'];
                // }

                // // Ensure you have the policy_id to fetch the activity logs
                // $policy_id = isset($_GET['policy_id']) ? $_GET['policy_id'] : null;

                if ($policy_id) {
                    // Fetch the most recent activity for display in the form
                    $get_activity = "SELECT * FROM activity WHERE activity_done_on_id = '$policy_id' ORDER BY activity_date DESC LIMIT 1";
                    $get_activity_r = mysqli_query($connection, $get_activity);
                    $activity_name_fetched = "";
                    $activity_by_fetched = "";
                    $activity_date_fetched = "";

                    if (mysqli_num_rows($get_activity_r) > 0) {
                        $row = mysqli_fetch_assoc($get_activity_r);
                        $activity_name_fetched = $row['activity_name'];
                        $activity_by_fetched = $row['activity_by'];
                        $activity_date_fetched = $row['activity_date'];
                    }
                }
                ?>
                <form action="" method="POST" style="border-bottom: 1px solid #000; margin-top: 10px; padding: 10px; margin-bottom: 50px; width: 50%;">
                    <h5 style="font-weight: 600 !important; font-size: 18px !important;">Activity Log</h5>
                    <p style="margin: 0; font-size: 12px;">Recent Activity: <strong><?php echo $activity_name_fetched; ?></strong></p>
                    <p style="margin: 0; font-size: 12px;">By: <?php echo $activity_by_fetched; ?></p>
                    <p style="margin: 0; font-size: 12px;">Date: <?php echo $activity_date_fetched; ?></p>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-3" style="margin: 0; font-size: 12px;" data-bs-toggle="modal" data-bs-target="#activityLogModal">
                        View full activity log
                    </button>
                </form>

                <!-- ============ ACTIVITY LOG MODAL ============ -->
                <div class="modal fade" id="activityLogModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Activity Log</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <?php
                                if ($policy_id) {
                                    // Fetch all activity logs for the given policy_id
                                    $get_all_activities = "SELECT * FROM activity WHERE activity_done_on_id = '$policy_id' ORDER BY activity_date DESC";
                                    $get_all_activities_r = mysqli_query($connection, $get_all_activities);

                                    if (mysqli_num_rows($get_all_activities_r) > 0) :
                                ?>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="font-weight: 600 !important; font-size: 12px !important;">Activity Name</th>
                                                    <th style="font-weight: 600 !important; font-size: 12px !important;">Activity By</th>
                                                    <th style="font-weight: 600 !important; font-size: 12px !important;">Activity Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = mysqli_fetch_assoc($get_all_activities_r)) : ?>
                                                    <tr>
                                                        <td style="font-size: 12px !important;"><?php echo htmlspecialchars($row['activity_name']); ?></td>
                                                        <td style="font-size: 12px !important;"><?php echo htmlspecialchars($row['activity_by']); ?></td>
                                                        <td style="font-size: 12px !important;"><?php echo $row['activity_date']; ?></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    <?php else : ?>
                                        <p>No activity found for this policy.</p>
                                    <?php endif; ?>
                                <?php } ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php } elseif ($user_role == '2') { ?>
            <form action="" method="POST" class="WYSIWYG-editor-container d-none">
                <input type="hidden" name="policy_id"
                    value="<?php echo isset($_GET['policy_id']) ? $_GET['policy_id'] : ''; ?>">
                <input type="hidden" name="linked_policy_id"
                    value="<?php echo isset($_GET['linked_policy_id']) ? $_GET['linked_policy_id'] : ''; ?>">
                <input type="hidden" name="inner_policy_id"
                    value="<?php echo isset($_GET['inner_policy_id']) ? $_GET['inner_policy_id'] : ''; ?>">
                <input type="hidden" name="policy_table" value="<?php echo isset($policy_table) ? $policy_table : ''; ?>">

                <div class="WYSIWYG-editor">
                    <textarea id="editorNew"></textarea>
                </div>
                <input type="hidden" name="editorContent" id="editorContent">

                <button type="submit" name="save" class="btn btn-sm btn-success mt-3">Update</button>
            </form>
        <?php } ?>

        <!-- ========== SUPPORTING DOCUMENTS AND VERSION CONTROL ========== -->
        <div style="flex: 1">
            <div class="document-container" style="margin-left: 10px;">
                <?php
                if (isset($_POST['update-details'])) {
                    $vc_data_id = $_POST['vc_data_id'];
                    $vc_screen_name = "Policy Details";
                    date_default_timezone_set('Asia/Kolkata');
                    $vc_updated_on = date('m-d-Y H:i:s');
                    $vc_assigned_to = $_POST['vc_assigned_to'];
                    $vc_status = $_POST['vc_status'];
                    $vc_updated_by = $_POST['vc_updated_by']; // Or use $_SESSION['user_name'] if consistent

                    $insert_vc_query = "INSERT INTO `version_control`(
                        `vc_data_id`, 
                        `vc_screen_name`, 
                        `vc_assigned_to`, 
                        `vc_status`, 
                        `vc_updated_on`, 
                        `vc_updated_by`
                    ) VALUES (
                        '$vc_data_id',
                        '$vc_screen_name',
                        '$vc_assigned_to',
                        '$vc_status',
                        '$vc_updated_on',
                        '$vc_updated_by'
                    )";

                    $insert_vc_res = mysqli_query($connection, $insert_vc_query);

                    if ($insert_vc_res) {
                        // ✅ Insert into activity table with activity_done_on_id
                        $activity_done_on = $vc_screen_name;
                        $activity_done_on_id = $vc_data_id;
                        $activity_name = "Assigned policy to and changed status";
                        $activity_by = $user_name; // Assuming session is active
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
                            '$activity_by',
                            '$activity_date'
                        )";
                        mysqli_query($connection, $activity_sql);
                    }
                }
                ?>
                <form action="" method="POST" style="margin-bottom: 50px;">
                    <input type="text" name="vc_data_id" value="<?php echo $policy_id ?>" hidden>
                    <input type="text" name="vc_updated_by" value="<?php echo $user_name ?>" hidden>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Assigned to</label>
                        <select class="form-select" name="vc_assigned_to" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <?php
                            $get_assigned_user = "SELECT * FROM user";
                            $get_assigned_user_r = mysqli_query($connection, $get_assigned_user);
                            while ($row = mysqli_fetch_assoc($get_assigned_user_r)) {
                                $assigned_user_name = $row['isms_user_name'];
                            ?>
                                <option value="<?php echo $assigned_user_name ?>"><?php echo $assigned_user_name ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Status</label>
                        <select class="form-select" name="vc_status" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="Open">Open</option>
                            <option value="Closed">Closed</option>
                            <option value="In Progress">In Progress</option>
                        </select>
                    </div>

                    <button type="submit" name="update-details" class="btn btn-sm w-100 btn-outline-success">Submit</button>
                </form>
                <?php
                $query = "SELECT policy_document_id, document_name, document_path, document_version FROM policy_documents WHERE policy_id = ? AND policy_table_for_document = ?";
                $stmt = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($stmt, "is", $policy_id, $policy_table);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <?php while ($doc = mysqli_fetch_assoc($result)) {
                                    $document_id = htmlspecialchars($doc['policy_document_id']);
                                    $document_path = htmlspecialchars($doc['document_path']);
                                    $document_name = htmlspecialchars($doc['document_name']);
                                    $document_version = htmlspecialchars($doc['document_version']);
                                ?>
                                    <tr>
                                        <td style="font-size: 12px;">
                                            <a href="<?php echo $document_path; ?>" target="_blank">
                                                <?php echo $document_name; ?>
                                            </a>
                                        </td>
                                        <td style="font-size: 12px;"><?php echo $document_version; ?></td>
                                        <?php
                                        if ($user_role === '1') { ?>
                                            <td class="text-center">
                                                <form action="update-document.php" method="POST">
                                                    <input type="text" name="policy_document_id" value="<?php echo $document_id; ?>" hidden>
                                                    <button type="submit" name="doc-edit-btn" class="doc-edit-btn">
                                                        <ion-icon name="create-outline"></ion-icon>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <form action="" method="POST">
                                                    <input type="hidden" name="policy_document_id" value="<?php echo $document_id; ?>">
                                                    <button type="submit" name="delete-doc" class="doc-edit-btn">
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
                <?php
                } else {
                    echo "<p>No documents uploaded for this policy.</p>";
                }
                ?>

                <div class="document-upload-container">
                    <?php if ($user_role === '1') { ?>
                        <!-- <p class="mt-5">Upload Supporting Document</p> -->
                        <form action="" method="POST" enctype="multipart/form-data" class="mt-3 w-100 ml-3">
                            <input type="hidden" name="policy_id" value="<?php echo $policy_id; ?>">
                            <input type="hidden" name="policy_table" value="<?php echo $policy_table; ?>">
                            <input type="hidden" name="policy_table_for_document" value="<?php echo $policy_table; ?>">

                            <div class="mb-4 w-100">
                                <label for="document" class="form-label">Upload</label>
                                <input type="file" name="document" class="form-control w-100" id="document" placeholder="name@example.com">
                            </div>


                            <div class="mb-3 w-100">
                                <label for="documentVersion" class="form-label">Version</label>
                                <input type="text" name="document_version" class="form-control w-100" id="documentVersion" aria-describedby="emailHelp">
                            </div>


                            <button type="submit" name="upload" class="btn btn-sm btn-primary mt-3">Upload</button>
                        </form>
                    <?php }  ?>
                </div>
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
                if ($insert_comment_result) {
                    echo "Comment added successfully!";
                } else {
                    echo "Error: " . mysqli_error($connection);
                }
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
                            <div class="WYSIWYG-editor form-floating">
                                <textarea id="editorNew" class="form-control" name="ca_comment_data" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
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
            ?>
            <!-- =========== COMMENTS SECTION START =========== -->
            <div class="notes-section mt-1">
                <div class="heading-row">
                    <p style="font-size: 18px;">Comments</p>
                    <button
                        style="font-size: 12px;"
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#commentModal"
                        class="btn btn-sm btn-outline-dark">Add Note</button>
                </div>
                <?php if ($get_comment_count > 0) {
                    while ($row = mysqli_fetch_assoc($get_comment_r)) {
                        $main_comment_id = $row['ca_comment_id'];
                        $main_comment = $row['ca_comment_data'];
                        $main_comment_by = $row['ca_comment_by'];
                        $main_comment_date = $row['ca_comment_date'];
                ?>
                        <div class="note-container" style="margin-bottom: 20px;">
                            <div class="d-flex justify-content-center align-items-center">
                                <p class="note-owner" style="flex: 1"><strong> <?php echo $main_comment_by ?> </strong> - <?php echo $main_comment_date ?></p>
                                <form action="" method="POST" style="margin-top: 0 !important;">
                                    <input type="hidden" name="ca_comment_id" value="<?php echo $main_comment_id ?>">
                                    <button type="submit" name="delete-note" class="btn btn-sm btn-outline-dark" style="border: 0; font-size: 18px;">
                                        <ion-icon name="close-circle-outline"></ion-icon>
                                    </button>
                                </form>
                            </div>
                            <div>
                                <p class="main-note"><?php echo $main_comment ?></p>
                                <!-- Read More -->
                                <div class="d-flex justify-content-center align-items-center mt-3">
                                    <button class="read-more-btn">
                                        <ion-icon name="chevron-down-outline"></ion-icon>
                                    </button>
                                </div>

                                <!-- Read Less -->
                                <div class="d-flex justify-content-center align-items-center mt-3">
                                    <button class="read-less-btn">
                                        <ion-icon name="chevron-up-outline"></ion-icon>
                                    </button>
                                </div>
                            </div>
                        </div>
                <?php }
                } ?>
            </div>
            <!-- =========== COMMENTS SECTION END =========== -->
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const container = document.querySelector(".clause-content");
                const button = document.querySelector(".read-more-btn");
                const readMoreIcon = "caret-down-circle-outline";
                const readLessIcon = "caret-up-circle-outline";

                if (container && button) {
                    let words = container.innerText.trim().split(/\s+/);
                    if (words.length > 200) {
                        let shortenedText = words.slice(0, 200).join(" ") + "...";
                        let fullText = container.innerHTML; // Store original content to preserve formatting

                        container.innerHTML = shortenedText;
                        button.style.display = "block";

                        button.addEventListener("click", function() {
                            let icon = button.querySelector("ion-icon");
                            if (!icon) return; // Prevent errors if the icon is missing

                            if (container.innerText.trim().endsWith("...")) {
                                container.innerHTML = fullText; // Restore full text
                                icon.setAttribute("name", readLessIcon);
                            } else {
                                container.innerHTML = shortenedText; // Collapse text
                                icon.setAttribute("name", readMoreIcon);
                            }
                        });
                    }
                }
            });
        </script>
    </div>

</div>

<?php include('includes/footer.php'); ?>