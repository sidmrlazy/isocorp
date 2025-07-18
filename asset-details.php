<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';

$asset_id = "";
$fetched_asset_name = "";
$fetched_asset_note = "";
$asset_status = "";
$asset_value = "";
$asset_type = "";
$asset_classification = "";
$asset_location = "";
$asset_owner_legal = "";
$asset_owner = "";
$asset_assigned_to = "";
$asset_review_date = "";

// GET asset info if ID is passed
if (isset($_GET['id'])) {
    $asset_id = mysqli_real_escape_string($connection, $_GET['id']);
    $get_asset_details_query = "SELECT * FROM asset WHERE asset_id = '$asset_id'";
    $get_asset_details_result = mysqli_query($connection, $get_asset_details_query);

    if ($row = mysqli_fetch_assoc($get_asset_details_result)) {
        $fetched_asset_name = $row['asset_name'];
        $fetched_asset_note = $row['asset_note'];
        $asset_status = $row['asset_status'];
        $asset_value = $row['asset_value'];
        $asset_type = $row['asset_type'];
        $asset_classification = $row['asset_classification'];
        $asset_location = $row['asset_location'];
        $asset_owner_legal = $row['asset_owner_legal'];
        $asset_owner = $row['asset_owner'];
        $asset_assigned_to = $row['asset_assigned_to'];
        $asset_review_date = $row['asset_review_date'];
    }
}

// Save Draft or Submit Notes
if (isset($_POST['save-draft-details']) || isset($_POST['submit-notes-details'])) {
    $fetched_asset_id = mysqli_real_escape_string($connection, $_POST['fetched_asset_id']);
    $fetched_asset_note = addslashes($_POST['asset_note']);
    $fetched_asset_details_status = isset($_POST['save-draft-details']) ? "1" : "2";

    $update_note_query = "UPDATE `asset` SET `asset_note`='$fetched_asset_note', `asset_details_status`=$fetched_asset_details_status WHERE asset_id = $fetched_asset_id";
    mysqli_query($connection, $update_note_query);
}

// Save/Submit full form
if (isset($_POST['save-asset-draft-form']) || isset($_POST['submit-asset-draft-form'])) {
    $fetched_asset_id = mysqli_real_escape_string($connection, $_POST['asset_id']);

    $asset_status = isset($_POST['asset_status']) ? mysqli_real_escape_string($connection, $_POST['asset_status']) : "";
    $asset_value = isset($_POST['asset_value']) ? mysqli_real_escape_string($connection, $_POST['asset_value']) : "";
    $asset_type = isset($_POST['asset_type']) ? mysqli_real_escape_string($connection, implode(',', (array)$_POST['asset_type'])) : "";
    $asset_classification = isset($_POST['asset_classification']) ? mysqli_real_escape_string($connection, implode(',', (array)$_POST['asset_classification'])) : "";
    $asset_location = isset($_POST['asset_location']) ? mysqli_real_escape_string($connection, implode(',', (array)$_POST['asset_location'])) : "";
    $asset_owner_legal = isset($_POST['asset_owner_legal']) ? mysqli_real_escape_string($connection, implode(',', (array)$_POST['asset_owner_legal'])) : "";
    $asset_owner = isset($_POST['asset_owner']) ? mysqli_real_escape_string($connection, implode(',', (array)$_POST['asset_owner'])) : "";
    $asset_assigned_to = isset($_POST['asset_assigned_to']) ? mysqli_real_escape_string($connection, $_POST['asset_assigned_to']) : "";
    $asset_review_date = isset($_POST['asset_review_date']) ? mysqli_real_escape_string($connection, $_POST['asset_review_date']) : "";
    $asset_form_status = isset($_POST['save-asset-draft-form']) ? "1" : "2";

    $update_asset_query = "UPDATE asset SET 
        asset_status='$asset_status',
        asset_value='$asset_value',
        asset_type='$asset_type',
        asset_classification='$asset_classification',
        asset_location='$asset_location',
        asset_owner_legal='$asset_owner_legal',
        asset_owner='$asset_owner',
        asset_form_status='$asset_form_status',
        asset_assigned_to='$asset_assigned_to',
        asset_review_date='$asset_review_date',
        asset_created_by='$user_name' 
    WHERE asset_id='$fetched_asset_id'";

    if (mysqli_query($connection, $update_asset_query)) {
        echo '<div class="alert alert-success mb-3 mt-3" role="alert">' . ($asset_form_status == "1" ? "Draft Saved!" : "Form Submitted!") . '</div>';
    }
}
?>


<div class="dashboard-container">
    <!-- =========== ASSET DETAILS SECTION =========== -->
    <div class="card p-3">
        <p class="asset-details-heading">Asset Inventory details</p>
        <?php if (!empty($fetched_asset_name)) { ?>
            <p class="asset-details-notes"><?php echo htmlspecialchars($fetched_asset_name); ?></p>
        <?php } else { ?>
            <div id="alertBox" class="alert alert-danger mt-2" role="alert">
                Asset note not found!
            </div>
        <?php } ?>
    </div>

    <div class="row mt-3 mb-5">
        <!-- =========== LEFT SECTION =========== -->
        <div class="col-md-6">
            <form action="" method="POST" class="card p-3">
                <input type="hidden" name="asset_id" value="<?php echo htmlspecialchars($asset_id); ?>">


                <?php if ($user_role == "2") { ?>
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Status</label>
                        <select disabled style="font-size: 12px !important;" class="form-select" name="asset_status">
                            <option disabled selected>Select status</option>
                            <?php foreach (["To-Do", "Live", "Resolved"] as $status): ?>
                                <option value="<?= $status ?>" <?= ($asset_status == $status) ? "selected" : "" ?>><?= $status ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php } else { ?>
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Status</label>
                        <select style="font-size: 12px !important;" class="form-select" name="asset_status">
                            <option disabled selected>Select status</option>
                            <?php foreach (["To-Do", "Live", "Resolved"] as $status): ?>
                                <option value="<?= $status ?>" <?= ($asset_status == $status) ? "selected" : "" ?>><?= $status ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php } ?>

                <?php if ($user_role == "2") { ?>
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Financial Value</label>
                        <input disabled name="asset_value" style="font-size: 12px !important;" type="text" class="form-control" value="<?php echo htmlspecialchars($asset_value); ?>">
                    </div>
                <?php } else { ?>
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Financial Value</label>
                        <input name="asset_value" style="font-size: 12px !important;" type="text" class="form-control" value="<?php echo htmlspecialchars($asset_value); ?>">
                    </div>
                <?php } ?>

                <?php
                // Reusable multi-select rendering with user role check
                function renderMultiSelect($name, $options, $selectedCSV, $user_role)
                {
                    $selectedValues = explode(",", $selectedCSV);
                    $disabledAttr = ($user_role == "2") ? "disabled" : "";

                    echo '<div class="mb-3"><label style="font-size: 12px !important;" class="form-label">' . ucfirst(str_replace("_", " ", $name)) . '</label>';
                    echo '<select style="font-size: 12px !important;" class="form-select" name="' . $name . '[]" multiple ' . $disabledAttr . '>';
                    foreach ($options as $opt) {
                        $sel = in_array($opt, $selectedValues) ? "selected" : "";
                        echo "<option value=\"$opt\" $sel>$opt</option>";
                    }
                    echo '</select></div>';
                }

                // Usage
                renderMultiSelect("asset_type", [
                    "Financial",
                    "IPR",
                    "Infrastructure",
                    "Personal Data",
                    "Processing Devices",
                    "Products & Services",
                    "Removable storage/Hard drives",
                    "Sales & Marketing",
                    "Software applications/services",
                    "Supply Chain",
                    "Other info assets",
                    "Physical Security",
                    "Staff/HR"
                ], $asset_type, $user_role);

                renderMultiSelect("asset_classification", ["Confidential", "Sensitive", "Public"], $asset_classification, $user_role);

                renderMultiSelect("asset_location", ["Company Office", "Teleworker (Home)", "Everywhere", "Third Party Datacentre", "On person", "On premise Datacentre", "Other Location"], $asset_location, $user_role);

                renderMultiSelect("asset_owner_legal", ["Company", "Employee (inc. BYOD)", "Supplier", "Other"], $asset_owner_legal, $user_role);

                renderMultiSelect("asset_owner", ["CEO", "DPO", "FD", "HR", "CMO (Marketing)", "CSO (Sales)", "CPO (Purchasing)", "CTO"], $asset_owner, $user_role);
                ?>


                <?php if ($user_role == "2") { ?>
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Review Date</label>
                        <input disabled type="date" name="asset_review_date" style="font-size: 12px !important;" class="form-control" value="<?php echo htmlspecialchars($asset_review_date); ?>">
                    </div>
                <?php } else { ?>
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Review Date</label>
                        <input type="date" name="asset_review_date" style="font-size: 12px !important;" class="form-control" value="<?php echo htmlspecialchars($asset_review_date); ?>">
                    </div>
                <?php } ?>

                <?php if ($user_role == "2") { ?>
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Assigned To</label>
                        <!-- <input type="text" name="asset_assigned_to" class="form-control" value="<?php echo htmlspecialchars($asset_assigned_to); ?>"> -->
                        <select disabled class="form-select" name="asset_assigned_to" style="font-size: 12px !important;" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <?php
                            $get_user = "SELECT * FROM user";
                            $get_user_r = mysqli_query($connection, $get_user);
                            while ($row = mysqli_fetch_assoc($get_user_r)) {
                                $user_name = $row['isms_user_name'];
                                $user_id = $row['isms_user_id'];
                                echo "<option value='$user_name' " . (($asset_assigned_to == $user_name) ? "selected" : "") . ">$user_name</option>";
                            }
                            ?>
                        </select>
                    </div>
                <?php } else { ?>
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Assigned To</label>
                        <!-- <input type="text" name="asset_assigned_to" class="form-control" value="<?php echo htmlspecialchars($asset_assigned_to); ?>"> -->
                        <select class="form-select" name="asset_assigned_to" style="font-size: 12px !important;" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <?php
                            $get_user = "SELECT * FROM user";
                            $get_user_r = mysqli_query($connection, $get_user);
                            while ($row = mysqli_fetch_assoc($get_user_r)) {
                                $user_name = $row['isms_user_name'];
                                $user_id = $row['isms_user_id'];
                                echo "<option value='$user_name' " . (($asset_assigned_to == $user_name) ? "selected" : "") . ">$user_name</option>";
                            }
                            ?>
                        </select>
                    </div>
                <?php } ?>

                <?php if ($user_role == "2") { ?>
                    <div class="d-flex justify-content-end">
                        <button style="font-size: 12px !important;" type="submit" name="save-asset-draft-form" class="d-none btn btn-sm btn-outline-secondary me-2">Save Draft</button>
                        <button style="font-size: 12px !important;" type="submit" name="submit-asset-draft-form" class="d-none btn btn-sm btn-outline-success">Submit</button>
                    </div>
                <?php } else { ?>
                    <div class="d-flex justify-content-end">
                        <button style="font-size: 12px !important;" type="submit" name="save-asset-draft-form" class="btn btn-sm btn-outline-secondary me-2">Save Draft</button>
                        <button style="font-size: 12px !important;" type="submit" name="submit-asset-draft-form" class="btn btn-sm btn-outline-success">Submit</button>
                    </div>
                <?php } ?>
            </form>
        </div>

        <!-- ========== RIGHT SECTION ========== -->
        <div class="col-md-6">
            <!-- ========== NOTES SECTION ========== -->
            <div class="card p-3 mb-3">
                <form action="" method="POST">
                    <input type="text" value="<?php echo $asset_id ?>" name="fetched_asset_id" hidden>
                    <div class="WYSIWYG-editor">
                        <label for="editorNew" class="form-label">Notes</label>
                        <textarea id="editorNew" name="asset_note"><?php echo $fetched_asset_note ?></textarea>
                    </div>
                    <?php
                    $get_details_status_q = "SELECT * FROM `asset` WHERE `asset_id` = '$asset_id'";
                    $get_details_status_r = mysqli_query($connection, $get_details_status_q);
                    $asset_details_status = "";
                    while ($row = mysqli_fetch_assoc($get_details_status_r)) {
                        $asset_details_status = $row['asset_details_status'];
                    }
                    ?>
                    <?php if ($user_role == "2") { ?>
                        <div class="d-none btn-row">
                            <button style="font-size: 12px !important;" type="submit" name="save-draft-details" class="btn btn-dark btn-sm">Save Draft</button>
                        </div>
                    <?php } else { ?>
                        <div class="btn-row">
                            <button style="font-size: 12px !important;" type="submit" name="save-draft-details" class="btn btn-dark btn-sm">Save Draft</button>
                        </div>
                    <?php } ?>

                </form>
            </div>
            <!-- ============ COMMENT SECTION ============ -->
            <div class="card p-3">
                <div class="heading-row">
                    <p style="font-size: 18px;">Comments</p>
                    <button
                        style="font-size: 12px;"
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#commentModal"
                        class="btn btn-sm btn-outline-dark">Add Note</button>
                </div>

                <?php
                if (isset($_POST['delete-note'])) {
                    $delete_comment_id = $_POST['delete_comment_id'];
                    $delete_query = "DELETE FROM asset_comment WHERE asset_comment_id = '$delete_comment_id'";
                    $delete_res = mysqli_query($connection, $delete_query);
                }

                if (isset($_POST['add-comment'])) {
                    $asset_comment_parent_id = $_POST['asset_comment_parent_id'];
                    $asset_comment_data = mysqli_real_escape_string($connection, $_POST['asset_comment_data']);
                    $asset_comment_date = date('Y-m-d');

                    $insert_note = "INSERT
                            INTO
                            `asset_comment`(
                                `asset_comment_parent_id`,
                                `asset_comment_data`,
                                `asset_comment_by`,
                                `asset_comment_date`
                            )
                            VALUES(
                            '$asset_comment_parent_id',
                            '$asset_comment_data',
                            '$user_name',
                            '$asset_comment_date'
                            )";
                    $insert_note_r = mysqli_query($connection, $insert_note);
                }

                $fetch_note = "SELECT * FROM asset_comment WHERE asset_comment_parent_id = '$asset_id'";
                $fetch_note_r = mysqli_query($connection, $fetch_note);
                $fetch_count = mysqli_num_rows($fetch_note_r);
                if ($fetch_count > 0) {
                    while ($row = mysqli_fetch_assoc($fetch_note_r)) {
                        $comment_id = $row['asset_comment_id'];
                        $comment_by = $row['asset_comment_by'];
                        $comment_date = $row['asset_comment_date'];
                        $comment_data = $row['asset_comment_data'];
                ?>
                        <!-- ========== SHOW COMMENTS ========== -->
                        <div class="note-container" style="margin-bottom: 20px;">
                            <div class="d-flex justify-content-center align-items-center">
                                <p class="note-owner" style="flex: 1"><strong><?php echo $comment_by ?></strong> - <?php echo $comment_date ?></p>
                                <form action="" method="POST" style="margin-top: 0 !important;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                    <input type="hidden" name="delete_comment_id" value="<?php echo $comment_id ?>">
                                    <button type="submit" name="delete-note" class="btn btn-sm btn-outline-dark" style="border: 0; font-size: 18px;">
                                        <ion-icon name="close-circle-outline"></ion-icon>
                                    </button>
                                </form>
                            </div>
                            <div>
                                <p class="main-note"><?php echo $comment_data ?></p>
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
                    <p style="font-size: 14px !important; margin-bottom: 20px;">No commenents added.</p>
                <?php } ?>
            </div>
            <!-- ======= ADD COMMENT MODAL ======= -->
            <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div action="" method="POST" class="modal-dialog modal-dialog-centered modal-lg">
                    <form action="" method="POST" class="modal-content">
                        <input type="text" value="<?php echo $asset_id ?>" name="asset_comment_parent_id" hidden>
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Comment</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="WYSIWYG-editor form-floating">
                                <textarea id="editorNew" class="form-control" name="asset_comment_data" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
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
<?php include 'includes/footer.php' ?>