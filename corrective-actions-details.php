<?php
include 'includes/header.php';
include 'includes/navbar.php';

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Guest');
$comment_user_name = isset($_COOKIE['user_name']) ? $_COOKIE['user_name'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest');
$user_role = isset($_COOKIE['user_role']) ? $_COOKIE['user_role'] : (isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Guest');
?>
<div class="dashboard-container">
    <?php
    include 'includes/connection.php';
    if (isset($_GET['id'])) {
        $ca_id = $_GET['id'];
        $fetch_data = "SELECT * FROM tblca WHERE ca_id = '$ca_id'";
        $fetch_data_r = mysqli_query($connection, $fetch_data);
        $tbl_ca_id = "";
        $tbl_ca_description = "";
        $tbl_ca_description_status_fetched = "";
        while ($row = mysqli_fetch_assoc($fetch_data_r)) {
            $tbl_ca_id = $row['ca_id'];
            $tbl_ca_description = $row['ca_description'];
            $tbl_ca_description_status_fetched = $row['ca_description_status'];
        }
    }

    if (isset($_POST['save-draft-details'])) {
        $tbl_ca_id = $_POST['ca_id'];
        $tbl_ca_description = $_POST['ca_description'];
        $tbl_ca_description_status = "1";
        $tbl_ca_updated_date = date('Y-m-d');
        $save_draft_query = "UPDATE tblca SET 
        ca_description = '$tbl_ca_description', 
        ca_description_status = '$tbl_ca_description_status', 
        ca_updated_by = '$user_name',
        ca_updated_date = '$tbl_ca_updated_date' 
        WHERE ca_id = $tbl_ca_id";
        $save_draft_result = mysqli_query($connection, $save_draft_query);
    }

    if (isset($_POST['save-draft-details'])) {
        $tbl_ca_id = $_POST['ca_id'];
        $tbl_ca_description = $_POST['ca_description'];
        $tbl_ca_description_status = "2";
        $tbl_ca_updated_date = date('Y-m-d');
        $save_draft_query = "UPDATE tblca SET 
        ca_description = '$tbl_ca_description', 
        ca_description_status = '$tbl_ca_description_status', 
        ca_updated_by = '$user_name',
        ca_updated_date = '$tbl_ca_updated_date'
        WHERE ca_id = $tbl_ca_id";
        $save_draft_result = mysqli_query($connection, $save_draft_query);
    }

    if (isset($_POST['save-form-draft'])) {
        $ca_id = mysqli_real_escape_string($connection, $_POST['new_ca_id']);
        $ca_status = mysqli_real_escape_string($connection, $_POST['ca_status']);
        $ca_financial_value = mysqli_real_escape_string($connection, $_POST['ca_financial_value']);
        $ca_assigned_to = mysqli_real_escape_string($connection, $_POST['ca_assigned_to']);

        $ca_source = isset($_POST['ca_source']) ? implode(',', $_POST['ca_source']) : '';
        $ca_severity = isset($_POST['ca_severity']) ? implode(',', $_POST['ca_severity']) : '';

        $ca_updated_date = date('Y-m-d');
        $ca_form_status = "1";
        if (!empty($ca_id)) {
            $update_form_query = "UPDATE tblca SET
            ca_status = '$ca_status',
            ca_financial_value = '$ca_financial_value',
            ca_source = '$ca_source',
            ca_severity = '$ca_severity',
            ca_assigned_to = '$ca_assigned_to',
            ca_updated_date = '$ca_updated_date',
            ca_updated_by = '$user_name',
            ca_form_status = '$ca_form_status'
            WHERE ca_id = '$ca_id'";

            $update_form_result = mysqli_query($connection, $update_form_query);

            if ($update_form_result) { ?>
                <div class="alert alert-success mt-3 mb-3" id="alertBox" role="alert">
                    Form updated!
                </div>

            <?php } else { ?>
                <div class="alert alert-danger mt-3 mb-3" id="alertBox" role="alert">
                    Error in updateing Form!
                </div>
            <?php }
        }
    }

    if (isset($_POST['submit-form-draft'])) {
        $ca_id = mysqli_real_escape_string($connection, $_POST['new_ca_id']);
        $ca_status = mysqli_real_escape_string($connection, $_POST['ca_status']);
        $ca_financial_value = mysqli_real_escape_string($connection, $_POST['ca_financial_value']);
        $ca_assigned_to = mysqli_real_escape_string($connection, $_POST['ca_assigned_to']);

        $ca_source = isset($_POST['ca_source']) ? implode(',', $_POST['ca_source']) : '';
        $ca_severity = isset($_POST['ca_severity']) ? implode(',', $_POST['ca_severity']) : '';

        $ca_updated_date = date('Y-m-d');
        $ca_form_status = "2";
        if (!empty($ca_id)) {
            $update_form_query = "UPDATE tblca SET
            ca_status = '$ca_status',
            ca_financial_value = '$ca_financial_value',
            ca_source = '$ca_source',
            ca_severity = '$ca_severity',
            ca_assigned_to = '$ca_assigned_to',
            ca_updated_date = '$ca_updated_date',
            ca_updated_by = '$user_name',
            ca_form_status = '$ca_form_status'
            WHERE ca_id = '$ca_id'";

            $update_form_result = mysqli_query($connection, $update_form_query);

            if ($update_form_result) { ?>
                <div class="alert alert-success mt-3 mb-3" id="alertBox" role="alert">
                    Form updated!
                </div>

            <?php } else { ?>
                <div class="alert alert-danger mt-3 mb-3" id="alertBox" role="alert">
                    Error in updateing Form!
                </div>
    <?php }
        }
    }
    ?>
    <div class="row mb-5">

        <!-- ============ FORM SECTION ============ -->
        <div class="col-md-6">
            <form class="card p-3" action="" method="POST">
                <input type="text" name="new_ca_id" value="<?php echo $tbl_ca_id ?>" hidden>
                <div class="mb-3">
                    <label style="font-size: 12px !important" for="exampleInputEmail1" class="form-label">Status</label>
                    <select style="font-size: 12px !important" name="ca_status" class="form-select" aria-label="Default select example">
                        <option selected>Open this select menu</option>
                        <option value="To-do">To-do</option>
                        <option value="Assessment">Assessment</option>
                        <option value="Awaiting Board Approval">Awaiting Board Approval</option>
                        <option value="Implementation">Implementation</option>
                        <option value="Monitoring">Monitoring</option>
                        <option value="Resolved">Resolved</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label style="font-size: 12px !important" for="exampleInputEmail1" class="form-label">Financial Value</label>
                    <input type="text" class="form-control" name="ca_financial_value" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>

                <div class="mb-3">
                    <label style="font-size: 12px !important" for="exampleInputEmail1" class="form-label">Source</label>
                    <select style="font-size: 12px !important" name="ca_source[]" class="form-select" aria-label="Default select example" multiple>
                        <option selected>Open this select menu</option>
                        <option value="To-do">Pre-stage 1</option>
                        <option value="External Audit Findings">External Audit Findings</option>
                        <option value="Internal Audit Findings">Internal Audit Findings</option>
                        <option value="Concern/Complaint">Concern/Complaint</option>
                        <option value="Security Incident/Event/Weakness">Security Incident/Event/Weakness</option>
                        <option value="Measurement Trend">Measurement Trend</option>
                        <option value="Risk Assessment">Risk Assessment</option>
                        <option value="Suggestions">Suggestions</option>
                        <option value="Process Review">Process Review</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label style="font-size: 12px !important" for="exampleInputEmail1" class="form-label">Severity</label>
                    <select style="font-size: 12px !important" name="ca_severity[]" class="form-select" aria-label="Default select example" multiple>
                        <option selected>Open this select menu</option>
                        <option value="Major Non-Conformity">Major Non-Conformity</option>
                        <option value="Minor Non-Conformity">Minor Non-Conformity</option>
                        <option value="Observation">Observation</option>
                        <option value="Opportunity for Improvement">Opportunity for Improvement</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label style="font-size: 12px !important" for="exampleInputEmail1" class="form-label">Assigned to</label>
                    <select style="font-size: 12px !important" class="form-select" name="ca_assigned_to" aria-label="Default select example">
                        <option selected>Open this select menu</option>
                        <?php
                        $fetch_user = "SELECT * FROM user";
                        $fetch_user_r = mysqli_query($connection, $fetch_user);
                        while ($row = mysqli_fetch_assoc($fetch_user_r)) {
                            $user_name = $row['isms_user_name'];

                        ?>
                            <option value="<?php echo $user_name ?>"><?php echo $user_name ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="btn-row">
                    <button style="font-size: 12px !important" type="submit" name="save-form-draft" class="btn btn-dark btn-sm">Save Draft</button>
                    <button style="font-size: 12px !important" type="submit" name="submit-form-draft" class="btn btn-success btn-sm">Submit Notes</button>
                </div>
            </form>
        </div>

        <!-- =========== DESCRIPTION SECTION =========== -->
        <div class="col-md-6">
            <?php
            if (isset($_POST['save-draft-description'])) {
                $tbl_ca_id = $_POST['ca_id'];
                $tbl_ca_description = $_POST['ca_description'];
                $tbl_ca_description_status = "1";
                $tbl_ca_updated_date = date('Y-m-d');
                $save_draft_query = "UPDATE tblca SET 
                ca_description = '$tbl_ca_description', 
                ca_description_status ='$tbl_ca_description_status',
                ca_updated_by = '$comment_user_name',
                ca_updated_date = '$tbl_ca_updated_date' 
                WHERE ca_id = $tbl_ca_id";
                $save_draft_result = mysqli_query($connection, $save_draft_query);
            }
            if (isset($_POST['submit-notes-description'])) {
                $tbl_ca_id = $_POST['ca_id'];
                $tbl_ca_description = $_POST['ca_description'];
                $tbl_ca_description_status = "2";
                $tbl_ca_updated_date = date('Y-m-d');
                $save_draft_query = "UPDATE tblca SET 
                ca_description = '$tbl_ca_description', 
                ca_description_status ='$tbl_ca_description_status',
                ca_updated_by = '$comment_user_name',
                ca_updated_date = '$tbl_ca_updated_date' 
                WHERE ca_id = $tbl_ca_id";
                $save_draft_result = mysqli_query($connection, $save_draft_query);
            }
            ?>
            <form action="" method="POST" class="card p-3">
                <input type="text" name="ca_id" value="<?php echo $tbl_ca_id ?>" hidden>
                <div class="WYSIWYG-editor">
                    <label style="font-size: 12px !important" for="editorNew" class="form-label">Description</label>
                    <textarea id="editorNew" name="ca_description"><?php echo $tbl_ca_description ?></textarea>
                </div>
                <?php if ($tbl_ca_description_status_fetched == '1') { ?>

                    <div class="btn-row">
                        <button style="font-size: 12px !important" type="submit" name="save-draft-description" class="btn btn-dark btn-sm">Save Draft</button>
                        <button style="font-size: 12px !important" type="submit" name="submit-notes-description" class="btn btn-success btn-sm">Submit Notes</button>
                    </div>
                <?php } else if ($tbl_ca_description_status_fetched == '2') { ?>
                    <div class="btn-row d-none">
                        <button style="font-size: 12px !important" type="submit" name="save-draft-description" class="btn btn-dark btn-sm">Save Draft</button>
                        <button style="font-size: 12px !important" type="submit" name="submit-notes-description" class="btn btn-success btn-sm">Submit Notes</button>
                    </div>
                <?php } else { ?>
                    <div class="btn-row">
                        <button style="font-size: 12px !important" type="submit" name="save-draft-description" class="btn btn-dark btn-sm">Save Draft</button>
                        <button style="font-size: 12px !important" type="submit" name="submit-notes-description" class="btn btn-success btn-sm">Submit Notes</button>
                    </div>
                <?php } ?>
            </form>

            <!-- ============ COMMENT SECTION ============ -->
            <div class="card p-3 mt-3">
                <div class="heading-row">
                    <p style="font-size: 12px;">Comments</p>
                    <button style="font-size: 12px !important"
                        style="font-size: 12px;"
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#commentModal"
                        class="btn btn-sm btn-outline-dark">Add Note</button>
                </div>
                <!-- ======= ADD COMMENT MODAL ======= -->
                <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div action="" method="POST" class="modal-dialog modal-dialog-centered modal-xl">
                        <?php
                        if (isset($_POST['add-comment'])) {
                            $ca_comment_parent_id = $_POST['ca_comment_parent_id'];
                            $ca_comment_data = $_POST['ca_comment_data'];
                            $ca_comment_by = $_POST['new_comment'];
                            $ca_comment_date = date('Y-m-d');

                            $add_comment_q = "INSERT INTO `tblca_comment`(
                    `ca_comment_parent_id`, 
                    `ca_comment_data`, 
                    `ca_comment_by`, 
                    `ca_comment_date`) VALUES (
                    '$ca_comment_parent_id',
                    '$ca_comment_data',
                    '$ca_comment_by',
                    '$ca_comment_date')";
                            $add_comment_r = mysqli_query($connection, $add_comment_q);
                        }
                        ?>
                        <form action="" method="POST" class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Comment</h1>
                                <button style="font-size: 12px !important" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="ca_comment_parent_id" value="<?php echo $tbl_ca_id ?>" hidden>
                                <input type="text" name="new_comment" value="<?php echo $comment_user_name ?>" hidden>

                                <div class="WYSIWYG-editor form-floating">
                                    <textarea class="form-control" name="ca_comment_data" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                                    <label style="font-size: 12px !important" for="floatingTextarea2">Comments</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button style="font-size: 12px !important" type="submit" name="add-comment" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ========== SHOW COMMENTS ========== -->

                <?php
                if (isset($_POST['delete-note'])) {
                    $delete_comment_id = $_POST['delete_comment_id'];
                    $delete_query = "DELETE FROM tblca_comment WHERE ca_comment_id = '$delete_comment_id'";
                    $delete_res = mysqli_query($connection, $delete_query);
                }

                $fetch_comment_q = "SELECT * FROM `tblca_comment` WHERE `ca_comment_parent_id` = '$tbl_ca_id'";
                $fetch_comment_r = mysqli_query($connection, $fetch_comment_q);
                $fetch_comment_count = mysqli_num_rows($fetch_comment_r);
                if ($fetch_comment_count > 0) {
                    while ($row = mysqli_fetch_assoc($fetch_comment_r)) {
                        $ca_comment_id = $row['ca_comment_id'];
                        $ca_comment_data = $row['ca_comment_data'];
                        $comment_by = $row['ca_comment_by'];
                        $ca_comment_date = $row['ca_comment_date'];
                ?>
                        <div style="margin-bottom: 20px;">
                            <div class="d-flex justify-content-center align-items-center">
                                <p class="note-owner" style="flex: 1"><strong>
                                        <?php echo $comment_by ?></strong> - <?php echo $ca_comment_date ?></p>
                                <form action="" method="POST" style="margin-top: 0 !important;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                    <input type="hidden" name="delete_comment_id" value="<?php echo $ca_comment_id ?>">
                                    <button style="font-size: 12px !important" type="submit" name="delete-note" class="btn btn-sm btn-outline-dark" style="border: 0; font-size: 18px;">
                                        <ion-icon name="close-circle-outline"></ion-icon>
                                    </button>
                                </form>
                            </div>
                            <div>
                                <p class="main-note"><?php echo $ca_comment_data ?></p>
                            </div>
                        </div>

                    <?php } ?>
                <?php } else { ?>
                    <p style="margin: 0; font-size: 12px !important;">No Comments added.</p>
                <?php } ?>
            </div>

        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>