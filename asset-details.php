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
    <div class="asset-details">
        <p class="asset-details-heading">Asset Inventory details</p>
        <?php if (!empty($fetched_asset_name)) { ?>
            <p class="asset-details-notes"><?php echo htmlspecialchars($fetched_asset_name); ?></p>
        <?php } else { ?>
            <div id="alertBox" class="alert alert-danger mt-2" role="alert">
                Asset note not found!
            </div>
        <?php } ?>
    </div>

    <div class="form-container mb-5" style="width: 50%; max-height: 502px; overflow: hidden; display: flex; flex-direction: column;">
        <form action="" method="POST" style="overflow-y: auto; flex-grow: 1; padding-right: 10px;">
            <input type="hidden" name="asset_id" value="<?php echo htmlspecialchars($asset_id); ?>">

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="asset_status">
                    <option disabled selected>Select status</option>
                    <?php foreach (["To-Do", "Live", "Resolved"] as $status): ?>
                        <option value="<?= $status ?>" <?= ($asset_status == $status) ? "selected" : "" ?>><?= $status ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Financial Value</label>
                <input name="asset_value" type="text" class="form-control" value="<?php echo htmlspecialchars($asset_value); ?>">
            </div>

            <?php
            // Reusable multi-select rendering
            function renderMultiSelect($name, $options, $selectedCSV) {
                $selectedValues = explode(",", $selectedCSV);
                echo '<div class="mb-3"><label class="form-label">' . ucfirst(str_replace("_", " ", $name)) . '</label>';
                echo '<select class="form-select" name="' . $name . '[]" multiple>';
                foreach ($options as $opt) {
                    $sel = in_array($opt, $selectedValues) ? "selected" : "";
                    echo "<option value=\"$opt\" $sel>$opt</option>";
                }
                echo '</select></div>';
            }

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
                "Supply Chain", "Other info assets", "Physical Security", "Staff/HR"], $asset_type);

            renderMultiSelect("asset_classification", ["Confidential", "Sensitive", "Public"], $asset_classification);

            renderMultiSelect("asset_location", ["Company Office", "Teleworker (Home)", "Everywhere", "Third Party Datacentre", "On person", "On premise Datacentre", "Other Location"], $asset_location);

            renderMultiSelect("asset_owner_legal", ["Company", "Employee (inc. BYOD)", "Supplier", "Other"], $asset_owner_legal);

            renderMultiSelect("asset_owner", ["CEO", "DPO", "FD", "HR", "CMO (Marketing)", "CSO (Sales)", "CPO (Purchasing)", "CTO"], $asset_owner);
            ?>

            <div class="mb-3">
                <label class="form-label">Review Date</label>
                <input type="date" name="asset_review_date" class="form-control" value="<?php echo htmlspecialchars($asset_review_date); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Assigned To</label>
                <input type="text" name="asset_assigned_to" class="form-control" value="<?php echo htmlspecialchars($asset_assigned_to); ?>">
            </div>

            <button type="submit" name="save-asset-draft-form" class="btn btn-secondary me-2">Save Draft</button>
            <button type="submit" name="submit-asset-draft-form" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
