<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
include 'includes/config.php';
?>
<div class="dashboard-container">
    <?php
    if (isset($_GET['id'])) {
        $asset_id = $_GET['id'];
        $get_asset_details_query = "SELECT * FROM asset WHERE asset_id = $asset_id";
        $get_asset_details_result = mysqli_query($connection, $get_asset_details_query);
        $fetched_asset_name = "";
        while ($row = mysqli_fetch_assoc($get_asset_details_result)) {
            $fetched_asset_id = $row['asset_id'];
            $fetched_asset_note = $row['asset_note'];
            $fetched_asset_name = $row['asset_name'];
        }
    }

    if (isset($_POST['save-draft-details'])) {
        $fetched_asset_id = mysqli_real_escape_string($connection, $_POST['fetched_asset_id']);
        $fetched_asset_note = addslashes($_POST['asset_note']);
        $fetched_asset_details_status = "1";
        $insert_draft = "UPDATE `asset` SET `asset_note`='$fetched_asset_note', `asset_details_status`=$fetched_asset_details_status WHERE asset_id = $fetched_asset_id";
        $insert_draft_r = mysqli_query($connection, $insert_draft);
    }

    if (isset($_POST['submit-notes-details'])) {
        $fetched_asset_id = mysqli_real_escape_string($connection, $_POST['fetched_asset_id']);
        $fetched_asset_note = addslashes($_POST['asset_note']);
        $fetched_asset_details_status = "2";
        $insert_draft = "UPDATE `asset` SET `asset_note`='$fetched_asset_note', `asset_details_status`=$fetched_asset_details_status WHERE asset_id = $fetched_asset_id";
        $insert_draft_r = mysqli_query($connection, $insert_draft);
    }
    if (isset($_POST['save-asset-draft-form'])) {
        $asset_status = isset($_POST['asset_status']) ? mysqli_real_escape_string($connection, $_POST['asset_status']) : '';
        $asset_value = isset($_POST['asset_value']) ? mysqli_real_escape_string($connection, $_POST['asset_value']) : '';
        $asset_type = isset($_POST['asset_type']) ? mysqli_real_escape_string($connection, implode(',', (array)$_POST['asset_type'])) : '';
        $asset_classification = isset($_POST['asset_classification']) ? mysqli_real_escape_string($connection, implode(',', (array)$_POST['asset_classification'])) : '';
        $asset_location = isset($_POST['asset_location']) ? mysqli_real_escape_string($connection, implode(',', (array)$_POST['asset_location'])) : '';

        // FIX: Convert array to string
        $asset_owner_legal = isset($_POST['asset_owner_legal']) ? mysqli_real_escape_string($connection, implode(',', (array)$_POST['asset_owner_legal'])) : '';
        $asset_owner = isset($_POST['asset_owner']) ? mysqli_real_escape_string($connection, implode(',', (array)$_POST['asset_owner'])) : '';

        $asset_assigned_to = isset($_POST['asset_assigned_to']) ? mysqli_real_escape_string($connection, $_POST['asset_assigned_to']) : '';
        $asset_form_status = "1";

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
            asset_created_by='$user_name' 
        WHERE asset_id='$fetched_asset_id'";

        if (mysqli_query($connection, $update_asset_query)) {
            echo '<div class="alert alert-success mb-3 mt-3" role="alert">Draft Saved!</div>';
        }
    }

    if (isset($_POST['submit-asset-draft-form'])) {
        $asset_status = isset($_POST['asset_status']) ? mysqli_real_escape_string($connection, $_POST['asset_status']) : '';
        $asset_value = isset($_POST['asset_value']) ? mysqli_real_escape_string($connection, $_POST['asset_value']) : '';
        $asset_type = isset($_POST['asset_type']) ? mysqli_real_escape_string($connection, implode(',', (array)$_POST['asset_type'])) : '';
        $asset_classification = isset($_POST['asset_classification']) ? mysqli_real_escape_string($connection, implode(',', (array)$_POST['asset_classification'])) : '';
        $asset_location = isset($_POST['asset_location']) ? mysqli_real_escape_string($connection, implode(',', (array)$_POST['asset_location'])) : '';

        // FIX: Convert array to string
        $asset_owner_legal = isset($_POST['asset_owner_legal']) ? mysqli_real_escape_string($connection, implode(',', (array)$_POST['asset_owner_legal'])) : '';
        $asset_owner = isset($_POST['asset_owner']) ? mysqli_real_escape_string($connection, implode(',', (array)$_POST['asset_owner'])) : '';

        $asset_assigned_to = isset($_POST['asset_assigned_to']) ? mysqli_real_escape_string($connection, $_POST['asset_assigned_to']) : '';
        $asset_form_status = "2";

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
            asset_created_by='$user_name' 
        WHERE asset_id='$fetched_asset_id'";

        if (mysqli_query($connection, $update_asset_query)) {
            echo '<div class="alert alert-success mb-3 mt-3" role="alert">Form Submitted!</div>';
        }
    }


    ?>
    <div class="asset-details">
        <p class="asset-details-heading">Asset Inventory details</p>
        <!-- show asset_note here -->
        <?php if ($fetched_asset_name != NULL) { ?>
            <p class="asset-details-notes"><?php echo $fetched_asset_name ?></p>
        <?php } else { ?>
            <div id="alertBox" class="alert alert-danger mt-2" role="alert">
                Asset note not found!
            </div>
        <?php } ?>
    </div>
    <div class="section-divider">
        <form action="" method="POSt" class="form-container">
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
            <?php if ($asset_details_status == "1") { ?>
                <div class="btn-row">
                    <button type="submit" name="save-draft-details" class="btn btn-dark btn-sm">Save Draft</button>
                    <button type="submit" name="submit-notes-details" class="btn btn-success btn-sm">Submit Notes</button>
                </div>
            <?php } elseif ($asset_details_status == "2") { ?>
                <div class="btn-row d-none">
                    <button type="submit" name="save-draft-details" class="btn btn-dark btn-sm">Save Draft</button>
                    <button type="submit" name="submit-notes-details" class="btn btn-success btn-sm">Submit Notes</button>
                </div>
            <?php } else { ?>
                <div class="btn-row">
                    <button type="submit" name="save-draft-details" class="btn btn-dark btn-sm">Save Draft</button>
                    <button type="submit" name="submit-notes-details" class="btn btn-success btn-sm">Submit Notes</button>
                </div>
            <?php } ?>
        </form>
        <!-- ========== FORM START ========== -->
        <div class="form-container mb-5" style="width: 50%; max-height: 502px !important; overflow: hidden; display: flex; flex-direction: column;">
            <form action="" method="POST" style="overflow-y: auto; flex-grow: 1; padding-right: 10px;">
                <input type="text" value="<?php echo $asset_id ?>" name="asset_id" hidden>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Status</label>
                    <select class="form-select" name="asset_status" aria-label="Default select example">
                        <option selected>Open this select menu</option>
                        <option value="To-Do">To-Do</option>
                        <option value="Live">Live</option>
                        <option value="Resolved">Resolved</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Financial Value</label>
                    <input name="asset_value" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>

                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Type</label>
                    <select class="form-select" name="asset_type[]" aria-label="Default select example" multiple>
                        <option selected>Open this select menu</option>
                        <option value="Financial">Financial</option>
                        <option value="IPR">IPR</option>
                        <option value="Infrastructure">Infrastructure</option>
                        <option value="Personal Data">Personal Data</option>
                        <option value="Processing Devices">Processing Devices</option>
                        <option value="Products & Services">Products & Services</option>
                        <option value="Removable storage/Hard drives">Removable storage/Hard drives</option>
                        <option value="Sales & Marketing">Sales & Marketing</option>
                        <option value="Software applications/services">Software applications/services</option>
                        <option value="Supply Chain">Supply Chain</option>
                        <option value="Other info assets">Other info assets</option>
                        <option value="Physical Security">Physical Security</option>
                        <option value="Staff/HR">Staff/HR</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Classification</label>
                    <select class="form-select" name="asset_classification[]" aria-label="Default select example" multiple>
                        <option selected>Open this select menu</option>
                        <option value="Confidential">Confidential</option>
                        <option value="Sensitive">Sensitive</option>
                        <option value="Public">Public</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Location</label>
                    <select class="form-select" name="asset_location[]" aria-label="Default select example" multiple>
                        <option selected>Open this select menu</option>
                        <option value="Company Office">Company Office</option>
                        <option value="Teleworker (Home)">Teleworker (Home)</option>
                        <option value="Everywhere">Everywhere</option>
                        <option value="Third Party Datacentre">Third Party Datacentre</option>
                        <option value="Other Location">Other Location</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Legal Owner</label>
                    <select class="form-select" name="asset_owner_legal[]" aria-label="Default select example" multiple>
                        <option selected>Open this select menu</option>
                        <option value="Company">Company</option>
                        <option value="Employee (inc. BYOD)">Employee (inc. BYOD)</option>
                        <option value="Supplier">Supplier</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Owner Lead</label>
                    <select class="form-select" name="asset_owner[]" aria-label="Default select example" multiple>
                        <option selected>Open this select menu</option>
                        <option value="CEO">CEO</option>
                        <option value="DPO">DPO</option>
                        <option value="FD">FD</option>
                        <option value="HR">HR</option>
                        <option value="CMO (Marketing)">CMO (Marketing)</option>
                        <option value="CSO (Sales)">CSO (Sales)</option>
                        <option value="CPO (Purchasing)">CPO (Purchasing)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Assigned to</label>
                    <select class="form-select" name="asset_assigned_to" aria-label="Default select example">
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
                <!-- ========== BUTTON START ========== -->
                <?php
                $get_status_q = "SELECT * FROM ASSET where asset_id = '$asset_id'";
                $get_status_r = mysqli_query($connection, $get_status_q);
                $asset_form_status = "";
                while ($row = mysqli_fetch_assoc($get_status_r)) {
                    $asset_form_status = $row['asset_form_status'];
                }
                ?>
                <?php if ($asset_form_status == '1') { ?>
                    <div class="btn-row">
                        <button type="submit" name="save-asset-draft-form" class="btn btn-sm btn-dark">Save Draft</button>
                        <button type="submit" name="submit-asset-draft-form" class="btn btn-sm btn-success">Submit Details</button>
                    </div>
                <?php } elseif ($asset_form_status == '2') { ?>
                    <div class="btn-row d-none">
                        <button type="submit" name="save-asset-draft-form" class="btn btn-sm btn-dark">Save Draft</button>
                        <button type="submit" name="submit-asset-draft-form" class="btn btn-sm btn-success">Submit Details</button>
                    </div>
                <?php } else { ?>
                    <div class="btn-row">
                        <button type="submit" name="save-asset-draft-form" class="btn btn-sm btn-dark">Save Draft</button>
                        <button type="submit" name="submit-asset-draft-form" class="btn btn-sm btn-success">Submit Details</button>
                    </div>
                <?php } ?>
                <!-- ========== BUTTON END ========== -->
            </form>
        </div>
        <!-- ========== FORM END ========== -->
    </div>
</div>
<?php include 'includes/footer.php' ?>