<?php
include('includes/header.php');
include('includes/navbar.php');
include('includes/connection.php');

$id = $_GET['id'] ?? 0;
$showSuccess = false;
$showAssignSuccess = false;

// === UPDATE DESCRIPTION ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_description'])) {
    $updatedDescription = mysqli_real_escape_string($connection, $_POST['training_description']);
    $updateQuery = "UPDATE iso_training SET training_description = ? WHERE training_id = ?";
    $updateStmt = $connection->prepare($updateQuery);
    $updateStmt->bind_param("si", $updatedDescription, $id);
    if ($updateStmt->execute()) {
        $showSuccess = true;
    }
}

// === UPDATE ASSIGNMENT ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update-assign'])) {
    $newDate = $_POST['training_date'];
    $assignedTo = $_POST['training_assigned_to'];
    $status = $_POST['training_status'];

    $updateAssignQuery = "UPDATE iso_training SET training_date = ?, training_assigned_to = ?, training_status = ? WHERE training_id = ?";
    $updateAssignStmt = $connection->prepare($updateAssignQuery);
    $updateAssignStmt->bind_param("sssi", $newDate, $assignedTo, $status, $id);
    if ($updateAssignStmt->execute()) {
        $showAssignSuccess = true;
    }
}

// === DELETE DOCUMENT ===
if (isset($_GET['delete_doc'])) {
    $docPath = $_GET['delete_doc'];
    if (file_exists($docPath) && strpos(realpath($docPath), realpath('uploads/trainings/')) === 0) {
        unlink($docPath);
        $updateQuery = "UPDATE iso_training SET training_document_path = NULL WHERE training_id = ?";
        $updateStmt = $connection->prepare($updateQuery);
        $updateStmt->bind_param("i", $id);
        $updateStmt->execute();
    }
}

// === FETCH TRAINING DETAILS ===
$query = "SELECT * FROM iso_training WHERE training_id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
?>


<div class="dashboard-container mb-5">
    <div class="row">
        <div class="col-md-6">
            <!-- Training Info -->
            <div class="card p-3 mb-3">
                <label style="font-size: 12px !important;"><?= htmlspecialchars($result['training_date']) ?></label>
                <p style="font-size: 16px !important;"><?= htmlspecialchars($result['training_topic']) ?></p>
            </div>

            <!-- Update Form -->
            <?php if ($showSuccess): ?>
                <div style="font-size: 12px !important;" id="alertBox" class="alert alert-success alert-dismissible fade show" role="alert">
                    Training description updated successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <form method="POST">
                <div class="card p-3 mb-3">
                    <label class="mb-3">Training Details</label>
                    <div class="WYSIWYG-editor mb-3">
                        <textarea name="training_description" id="editorNew" rows="10" class="form-control">
                            <?= htmlspecialchars($result['training_description']) ?>
                        </textarea>

                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" name="update_description" class="btn btn-sm btn-outline-success" style="font-size: 12px !important;">Update</button>
                    </div>
                </div>
            </form>

            <!-- Assignment Section -->
            <form method="POST" class="card p-3">
                <div class="mb-3">
                    <label style="font-size: 12px !important">Training Date</label>
                    <input style="font-size: 12px !important" name="training_date" type="date" class="form-control"
                        value="<?= htmlspecialchars($result['training_date']) ?>" required>
                </div>

                <div class="mb-3">
                    <label style="font-size: 12px !important">Assigned to</label>
                    <select name="training_assigned_to" class="form-select" style="font-size: 12px !important;" required>
                        <option disabled>Select a user</option>
                        <?php
                        $get_users = "SELECT * FROM user";
                        $get_user_result = mysqli_query($connection, $get_users);
                        if ($get_user_result) {
                            while ($row = mysqli_fetch_assoc($get_user_result)) {
                                $user_name = htmlspecialchars($row['isms_user_name']);
                                $selected = ($result['training_assigned_to'] === $user_name) ? 'selected' : '';
                                echo "<option value=\"$user_name\" $selected>$user_name</option>";
                            }
                        } else {
                            echo "<option disabled>Error loading users</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label style="font-size: 12px !important">Status</label>
                    <select style="font-size: 12px !important" name="training_status" class="form-select" required>
                        <option disabled>Select status</option>
                        <?php
                        $statuses = ['Scheduled', 'Completed', 'Cancelled'];
                        foreach ($statuses as $status) {
                            $selected = ($result['training_status'] === $status) ? 'selected' : '';
                            echo "<option value=\"$status\" $selected>$status</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" name="update-assign" class="btn btn-sm btn-outline-success" style="font-size: 12px !important;">Update</button>
                </div>
            </form>
        </div>

        <div class="col-md-6">
            <!-- Document Table -->
            <div class="table-responsive card p-3">
                <label class="mb-3">Documents</label>
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th style="font-size: 12px !important;">Document Name</th>
                            <th style="font-size: 12px !important;">Download</th>
                            <th style="font-size: 12px !important;">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($result['training_document_path'])): ?>
                            <?php $filename = basename($result['training_document_path']); ?>
                            <tr>
                                <td style="font-size: 12px !important;"><?= $filename ?></td>
                                <td>
                                    <a style="font-size: 12px !important" href="<?= $result['training_document_path'] ?>" target="_blank" class="btn btn-sm btn-outline-success">Download</a>
                                </td>
                                <td>
                                    <a style="font-size: 12px !important" href="?id=<?= $id ?>&delete_doc=<?= urlencode($result['training_document_path']) ?>" onclick="return confirm('Are you sure you want to delete this document?')" class="btn btn-sm btn-outline-danger">Delete</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center" style="font-size: 12px !important;">No documents available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>