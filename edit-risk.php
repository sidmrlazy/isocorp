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
    $description = $_POST['description'];  // Now properly named
    $likelihood = $_POST['likelihood'];
    $impact = $_POST['impact'];
    $status = $_POST['status'];

    $stmt = $connection->prepare("UPDATE risks SET 
        risks_name = ?, risks_description = ?, risks_likelihood = ?, 
        risks_impact = ?, risks_status = ? WHERE risks_id = ?");

    $stmt->bind_param("sssssi", $risk_name, $description, $likelihood, $impact, $status, $risk_id);

    if ($stmt->execute()) {
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
    <div class="form-container">
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
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <?php
                    $status_options = ['Open', 'In Progress', 'Closed'];
                    foreach ($status_options as $status) {
                        $selected = ($risk['risks_status'] == $status) ? 'selected' : '';
                        echo "<option value='$status' $selected>$status</option>";
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