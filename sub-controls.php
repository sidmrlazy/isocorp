<?php
include 'includes/header.php';
include 'includes/navbar.php'; ?>
<div class="add-control-form-container">
    <?php
    include 'includes/connection.php';
    if (isset($_POST['add'])) {
        $main_control_policy_id = $_POST['policy_id'];
        $sub_control_policy_number = $_POST['sub_control_policy_number'];
        $sub_control_policy_heading = $_POST['sub_control_policy_heading'];
        $sub_control_policy_det = $_POST['sub_control_policy_det'];
        $sub_control_policy_status = "1";

        $stmt = $connection->prepare("INSERT INTO `sub_control_policy` 
            (`main_control_policy_id`, `sub_control_policy_number`, `sub_control_policy_heading`, `sub_control_policy_det`, `sub_control_policy_status`) 
            VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param(
                "sssss",
                $main_control_policy_id,
                $sub_control_policy_number,
                $sub_control_policy_heading,
                $sub_control_policy_det,
                $sub_control_policy_status
            );

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
        } else { ?>
            <div class="alert alert-danger mb-3" role="alert">
                Failed to prepare the statement: <?= htmlspecialchars($connection->error) ?>
            </div>
    <?php }
    }
    ?>
    <form class="add-control-main-form" action="" method="POST">
        <div class="mb-3">
            <label for="policy_id" class="form-label">Control | Policy Number</label>
            <select class="form-select" name="policy_id" id="policy_id" required>
                <option selected disabled>Open this select menu</option>
                <?php
                $fetch_query = "SELECT policy_id, policy_clause, policy_name FROM `policy`";
                $fetch_res = mysqli_query($connection, $fetch_query);

                if ($fetch_res && mysqli_num_rows($fetch_res) > 0) {
                    while ($row = mysqli_fetch_assoc($fetch_res)) {
                        $policy_id = $row['policy_id'];
                        $policy_clause = $row['policy_clause'];
                        $policy_name = $row['policy_name'];
                ?>
                        <option value="<?= htmlspecialchars($policy_id); ?>">
                            <?= htmlspecialchars($policy_clause . " " . $policy_name); ?>
                        </option>
                    <?php }
                } else { ?>
                    <option disabled>No policies available</option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="sub_control_policy_number" class="form-label">Sub Control | Policy Number</label>
            <input type="text" class="form-control" name="sub_control_policy_number" id="sub_control_policy_number" required>
        </div>

        <div class="mb-3">
            <label for="sub_control_policy_heading" class="form-label">Sub Control | Policy Heading</label>
            <input type="text" class="form-control" name="sub_control_policy_heading" id="sub_control_policy_heading" required>
        </div>

        <div class="WYSIWYG-editor">
            <label for="editorNew" class="form-label">Policy Details</label>
            <textarea id="editorNew" name="sub_control_policy_det"></textarea>
        </div>

        <button type="submit" name="add" class="btn btn-primary mt-3">Add</button>
    </form>
</div>

<!-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof CKEDITOR !== "undefined") {
            CKEDITOR.replace("editorNew");
        }
    });
</script> -->
<?php include 'includes/footer.php'; ?>