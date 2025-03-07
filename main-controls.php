<?php
include 'includes/header.php';
include 'includes/navbar.php'; ?>
<div class="add-control-form-container">
    <?php
    include 'includes/connection.php';
    if (isset($_POST['add'])) {
        $policy_clause = mysqli_real_escape_string($connection, $_POST['policy_clause']);
        $policy_name = mysqli_real_escape_string($connection, $_POST['policy_name']);
        $policy_det = mysqli_real_escape_string($connection, $_POST['policy_det']);
        $policy_status = "1";

        $stmt = $connection->prepare("INSERT INTO `policy` 
                (`policy_clause`, `policy_name`, `policy_det`, `policy_status`) 
                VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $policy_clause, $policy_name, $policy_det, $policy_status);

        if ($stmt->execute()) { ?>
            <div class="alert alert-success mb-3" role="alert">
                Policy | Control added successfully!
            </div>
        <?php } else { ?>
            <div class="alert alert-danger mb-3" role="alert">
                Policy | Control addition failed: <?= htmlspecialchars($stmt->error) ?>
            </div>
    <?php }
        $stmt->close();
    }
    ?>

    <form class="add-control-main-form" action="" method="POST">
        <div class="mb-3">
            <label for="policy_clause" class="form-label">Control | Policy Number</label>
            <input type="text" class="form-control" name="policy_clause" id="policy_clause" required>
        </div>
        <div class="mb-3">
            <label for="policy_name" class="form-label">Control | Policy Name</label>
            <input type="text" class="form-control" name="policy_name" id="policy_name" required>
        </div>
        <div class="WYSIWYG-editor">
            <label for="editorNew" class="form-label">Policy Details</label>
            <textarea id="editorNew" name="policy_det"></textarea>
        </div>
        <button type="submit" name="add" class="btn btn-primary mt-3">Add</button>
    </form>
</div>
<?php include 'includes/footer.php'; ?>