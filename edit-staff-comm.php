<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
?>

<div class="dashboard-container mb-5">
    <?php
    if (!isset($_GET['id'])) {
        echo "Invalid Request.";
        exit;
    }

    $comm_id = intval($_GET['id']);
    $query = mysqli_query($connection, "SELECT * FROM staff_comm WHERE comm_id='$comm_id'") or die(mysqli_error($connection));
    $row = mysqli_fetch_assoc($query);

    if (isset($_POST['update-comm'])) {
        $comm_data = mysqli_real_escape_string($connection, $_POST['comm_data']);
        $comm_details = mysqli_real_escape_string($connection, $_POST['comm_details']);
        $comm_by = mysqli_real_escape_string($connection, $_POST['comm_by']);
        $comm_date = mysqli_real_escape_string($connection, $_POST['comm_date']);

        $update = mysqli_query($connection, "UPDATE staff_comm SET comm_data='$comm_data', comm_details='$comm_details', comm_by='$comm_by', comm_date='$comm_date' WHERE comm_id='$comm_id'") or die(mysqli_error($connection));

        if ($update) {
            echo "<p id='alertBox' class='alert alert-success'>Staff communication updated successfully.</p>";
        }
    }
    ?>
    <form method="POST" class="card p-3">
        <input type="hidden" name="comm_by" value="<?php echo $row['comm_by']; ?>">
        <div class="mb-3">
            <label for="comm_date" class="form-label">Upload Date</label>
            <input type="date" name="comm_date" class="form-control" value="<?php echo $row['comm_date']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="comm_data" class="form-label">Topic</label>
            <input type="text" name="comm_data" class="form-control" value="<?php echo htmlspecialchars($row['comm_data']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="comm_details" class="form-label">Details</label>
            <div class="WYSIWYG-editor">
                <textarea id="editorNew" name="comm_details"><?php echo htmlspecialchars($row['comm_details']); ?></textarea>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" name="update-comm" class="btn btn-sm btn-outline-success">Update</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>