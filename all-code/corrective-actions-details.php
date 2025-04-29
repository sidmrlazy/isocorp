<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
include 'includes/config.php';
?>
<div class="dashboard-container">
    <?php
    if (isset($_GET['id'])) {
        $ca_id = $_GET['id'];
        $fetch_data = "SELECT * FROM tblca WHERE ca_id = '$ca_id'";
        $fetch_data_r = mysqli_query($connection, $fetch_data);
        $tbl_ca_id = "";
        $tbl_ca_description = "";
        while ($row = mysqli_fetch_assoc($fetch_data_r)) {
            $tbl_ca_id = $row['ca_id'];
            $tbl_ca_description = $row['ca_description'];
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
        $ca_updated_by = mysqli_real_escape_string($connection, $user_name);
        $ca_form_status = "1";
        if (!empty($ca_id)) {
            $update_form_query = "UPDATE tblca SET
            ca_status = '$ca_status',
            ca_financial_value = '$ca_financial_value',
            ca_source = '$ca_source',
            ca_severity = '$ca_severity',
            ca_assigned_to = '$ca_assigned_to',
            ca_updated_date = '$ca_updated_date',
            ca_updated_by = '$ca_updated_by',
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
        $ca_updated_by = mysqli_real_escape_string($connection, $user_name);
        $ca_form_status = "2";
        if (!empty($ca_id)) {
            $update_form_query = "UPDATE tblca SET
            ca_status = '$ca_status',
            ca_financial_value = '$ca_financial_value',
            ca_source = '$ca_source',
            ca_severity = '$ca_severity',
            ca_assigned_to = '$ca_assigned_to',
            ca_updated_date = '$ca_updated_date',
            ca_updated_by = '$ca_updated_by',
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
    <div class="section-divider mb-5">
        <!-- ============ FORM SECTION ============ -->
        <form class="form-container" action="" method="POST">
            <input type="text" name="new_ca_id" value="<?php echo $tbl_ca_id ?>" hidden>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Status</label>
                <select name="ca_status" class="form-select" aria-label="Default select example">
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
                <label for="exampleInputEmail1" class="form-label">Financial Value</label>
                <input type="text" class="form-control" name="ca_financial_value" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>

            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Source</label>
                <select name="ca_source[]" class="form-select" aria-label="Default select example" multiple>
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
                <label for="exampleInputEmail1" class="form-label">Severity</label>
                <select name="ca_severity[]" class="form-select" aria-label="Default select example" multiple>
                    <option selected>Open this select menu</option>
                    <option value="Major Non-Conformity">Major Non-Conformity</option>
                    <option value="Minor Non-Conformity">Minor Non-Conformity</option>
                    <option value="Observation">Observation</option>
                    <option value="Opportunity for Improvement">Opportunity for Improvement</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Assigned to</label>
                <select class="form-select" name="ca_assigned_to" aria-label="Default select example">
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
                <button type="submit" name="save-form-draft" class="btn btn-dark btn-sm">Save Draft</button>
                <button type="submit" name="submit-form-draft" class="btn btn-success btn-sm">Submit Notes</button>
            </div>
        </form>

        <!-- ============ DESCRIPTION SECTION ============ -->
        <div style="flex: 2">
            <?php
            if (isset($_POST['delete-note'])) {
                $delete_comment_id = $_POST['delete_comment_id'];
                $delete_query = "DELETE FROM tblca_comment WHERE ca_comment_id = '$delete_comment_id'";
                $delete_res = mysqli_query($connection, $delete_query);
            }

            if (isset($_POST['add-comment'])) {
                $ca_comment_parent_id = $_POST['ca_comment_parent_id'];
                $ca_comment_data = $_POST['ca_comment_data'];
                $ca_comment_by = $_POST['ca_comment_by'];
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
            <form action="" method="POST" class="form-container">
                <input type="text" name="ca_id" value="<?php echo $tbl_ca_id ?>" hidden>
                <div class="WYSIWYG-editor">
                    <label for="editorNew" class="form-label">Description</label>
                    <textarea id="editorNew" name="ca_description"><?php echo $tbl_ca_description ?></textarea>
                </div>
                <div class="btn-row">
                    <button type="submit" name="save-draft-details" class="btn btn-dark btn-sm">Save Draft</button>
                    <button type="submit" name="submit-notes-details" class="btn btn-success btn-sm">Submit Notes</button>
                </div>
            </form>

            <!-- ============ COMMENT SECTION ============ -->
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

                <!-- ========== SHOW COMMENTS ========== -->
                <?php
                $fetch_comment_q = "SELECT * FROM `tblca_comment` WHERE `ca_comment_parent_id` = '$tbl_ca_id'";
                $fetch_comment_r = mysqli_query($connection, $fetch_comment_q);
                $fetch_comment_count = mysqli_num_rows($fetch_comment_r);
                if ($fetch_comment_count > 0) {
                    while ($row = mysqli_fetch_assoc($fetch_comment_r)) {
                        $ca_comment_id = $row['ca_comment_id'];
                        $ca_comment_data = $row['ca_comment_data'];
                        $ca_comment_by = $row['ca_comment_by'];
                        $ca_comment_date = $row['ca_comment_date'];
                ?>
                        <div class="note-container" style="margin-bottom: 20px;">
                            <div class="d-flex justify-content-center align-items-center">
                                <p class="note-owner" style="flex: 1"><strong><?php echo $ca_comment_by ?></strong> - <?php echo $ca_comment_date ?></p>
                                <form action="" method="POST" style="margin-top: 0 !important;">
                                    <input type="hidden" name="delete_comment_id" value="<?php echo $ca_comment_id ?>">
                                    <button type="submit" name="delete-note" class="btn btn-sm btn-outline-dark" style="border: 0; font-size: 18px;">
                                        <ion-icon name="close-circle-outline"></ion-icon>
                                    </button>
                                </form>
                            </div>
                            <div>
                                <p class="main-note"><?php echo $ca_comment_data ?></p>
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
                    <?php } ?>
                <?php } else { ?>
                    No Comments added.
                <?php } ?>
            </div>



            <!-- ======= ADD COMMENT MODAL ======= -->
            <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div action="" method="POST" class="modal-dialog modal-dialog-centered">
                    <form action="" method="POST" class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Comment</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" name="ca_comment_parent_id" value="<?php echo $tbl_ca_id ?>" hidden>
                            <input type="text" name="ca_comment_by" value="<?php echo $user_name ?>" hidden>

                            <div class="WYSIWYG-editor form-floating">
                                <textarea id="editorNew" class="form-control" name="ca_comment_data" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                                <label for="floatingTextarea2">Comments</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="add-comment" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const note = document.querySelector(".main-note");
        const readMoreBtn = document.querySelector(".read-more-btn");
        const readLessBtn = document.querySelector(".read-less-btn");

        if (note) {
            let words = note.innerText.trim().split(/\s+/);
            if (words.length > 50) {
                let shortenedText = words.slice(0, 50).join(" ") + "...";
                let fullText = note.innerHTML; // Store original content

                note.innerHTML = shortenedText;
                note.parentElement.classList.add("show-read-more"); // Show Read More button

                readMoreBtn.addEventListener("click", function() {
                    note.innerHTML = fullText; // Expand text
                    note.parentElement.classList.remove("show-read-more");
                    note.parentElement.classList.add("show-read-less"); // Show Read Less button
                });

                readLessBtn.addEventListener("click", function() {
                    note.innerHTML = shortenedText; // Collapse text
                    note.parentElement.classList.remove("show-read-less");
                    note.parentElement.classList.add("show-read-more"); // Show Read More button
                });
            }
        }
    });
</script>
<?php 
include 'includes/footer.php'; ?>