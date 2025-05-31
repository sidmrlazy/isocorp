<?php
ob_start();
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
include 'includes/config.php';

// Check if risk_id is set
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: risks-treatments.php?edit=error");
    exit();
}

$risk_id = intval($_GET['id']);

// Fetch existing risk details
$stmt = $connection->prepare("SELECT * FROM risks WHERE risks_id = ?");
$stmt->bind_param("i", $risk_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: risks-treatments.php?edit=notfound");
    exit();
}

$risk = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_risk'])) {
    $risk_name = $_POST['risk_name'];
    $description = $_POST['description'];
    $likelihood = $_POST['likelihood'];
    $impact = $_POST['impact'];
    $action = $_POST['action'];
    $review_date = $_POST['review_date'];
    $assigned_to = $_POST['assigned_to'];
    $status = $_POST['status'];

    // Update the risks table
    $stmt = $connection->prepare("UPDATE risks SET 
        risks_name = ?, 
        risks_description = ?, 
        risks_likelihood = ?, 
        risks_impact = ?, 
        risks_action = ?, 
        risks_review_date = ?, 
        risks_assigned_to = ?, 
        risks_status = ? 
        WHERE risks_id = ?");

    $stmt->bind_param(
        "ssssssssi",
        $risk_name,
        $description,
        $likelihood,
        $impact,
        $action,
        $review_date,
        $assigned_to,
        $status,
        $risk_id
    );

    if ($stmt->execute()) {
        // Insert into risk_versions to save history
        $insert_version = $connection->prepare("INSERT INTO risk_versions 
            (risk_id, risks_name, risks_likelihood, risks_impact, risks_status, risks_action, risks_review_date, risks_assigned_to)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $insert_version->bind_param(
            "isssssss",
            $risk_id,
            $risk_name,
            $likelihood,
            $impact,
            $status,
            $action,
            $review_date,
            $assigned_to
        );

        $insert_version->execute();
        $insert_version->close();

        header("Location: risks-treatments.php?edit=success");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error updating risk: " . $stmt->error . "</div>";
    }
}


?>

<div class="dashboard-container">
    <div class="screen-name-container mb-3">
        <h1>Edit Risks & Treatments</h1>
        <h2><a href="risks-treatments.php">Risks & Treatments</a> > Edit Risks & Treatments</h2>
    </div>
    <div class="form-container mb-5">
        <form method="post">
            <div class="mb-3">
                <label>Risk Name</label>
                <input type="text" name="risk_name" class="form-control" value="<?= htmlspecialchars($risk['risks_name']) ?>" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea id="editorNew" name="description"><?= htmlspecialchars($risk['risks_description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label>Likelihood</label>
                <select name="likelihood" class="form-control" required>
                    <?php
                    $likelihood_levels = ['Very Low', 'Low', 'Medium', 'High', 'Very High'];
                    foreach ($likelihood_levels as $level) {
                        $selected = ($risk['risks_likelihood'] == $level) ? 'selected' : '';
                        echo "<option value='$level' $selected>$level</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Impact</label>
                <select name="impact" class="form-control" required>
                    <?php
                    $impact_levels = ['Insignificant', 'Minor', 'Moderate', 'Major', 'Severe'];
                    foreach ($impact_levels as $level) {
                        $selected = ($risk['risks_impact'] == $level) ? 'selected' : '';
                        echo "<option value='$level' $selected>$level</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Action</label>
                <select name="action" class="form-control" required>
                    <?php
                    $action_options = [
                        'Terminate',
                        'Combination of actions',
                        'Tolerate: Residual risk',
                        'Transfer',
                        'Treat (Other)'
                    ];
                    foreach ($action_options as $action_option) {
                        $selected = ($risk['risks_action'] == $action_option) ? 'selected' : '';
                        echo "<option value=\"$action_option\" $selected>$action_option</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="reviewDate" class="form-label">Review Date</label>
                <input type="date" name="review_date" class="form-control" id="reviewDate" value="<?= htmlspecialchars($risk['risks_review_date']) ?>">
            </div>

            <div class="mb-3">
                <label>Assigned to</label>
                <select name="assigned_to" class="form-control" required>
                    <?php
                    $get_user = "SELECT * FROM user";
                    $get_user_r = mysqli_query($connection, $get_user);
                    while ($row = mysqli_fetch_assoc($get_user_r)) {
                        $user_name = $row['isms_user_name'];
                        $selected = ($risk['risks_assigned_to'] == $user_name) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($user_name) . "' $selected>$user_name</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <?php
                    $status_options = ['Open', 'In Progress', 'Closed'];
                    foreach ($status_options as $status_option) {
                        $selected = ($risk['risks_status'] == $status_option) ? 'selected' : '';
                        echo "<option value='$status_option' $selected>$status_option</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" name="update_risk" class="btn btn-primary">Update Risk</button>
            <a href="risks-treatments.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php
ob_end_flush();
include 'includes/footer.php';
?>