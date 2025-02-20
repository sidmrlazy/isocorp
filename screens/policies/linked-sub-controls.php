<div class="add-control-form-container">
    <?php
    include 'includes/connection.php';

    // Process form data
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
        // Retrieve and sanitize form data
        $main_control_policy_id = $_POST['main_control_policy_id']; // Ensure it's an integer
        $sub_control_policy_id = $_POST['sub_control_policy_id']; // Ensure it's an integer
        $linked_control_policy_number = $_POST['linked_control_policy_number'];
        $linked_control_policy_heading = $_POST['linked_control_policy_heading'];
        $linked_control_policy_det = $_POST['linked_control_policy_det'];
        $linked_control_policy_status = 1; // Explicitly setting this as an integer

        // Use prepared statements to prevent SQL Injection
        $stmt = $connection->prepare("INSERT INTO `linked_control_policy` 
            (`main_control_policy_id`, `sub_control_policy_id`, `linked_control_policy_number`, `linked_control_policy_heading`, `linked_control_policy_det`, `linked_control_policy_status`) 
            VALUES (?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param(
                "iisssi",
                $main_control_policy_id,
                $sub_control_policy_id,
                $linked_control_policy_number,
                $linked_control_policy_heading,
                $linked_control_policy_det,
                $linked_control_policy_status
            );

            // Execute the query
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
            <label for="main_control_policy_id" class="form-label">Control | Policy Number</label>
            <select class="form-select" name="main_control_policy_id" id="main_control_policy_id" required>
                <option selected disabled>Open this select menu</option>
                <?php
                // Fetch policy data from the database
                $fetch_query = "SELECT * FROM `policy`";
                $fetch_res = mysqli_query($connection, $fetch_query);

                if ($fetch_res && mysqli_num_rows($fetch_res) > 0) {
                    while ($row = mysqli_fetch_assoc($fetch_res)) { ?>
                        <option value="<?= htmlspecialchars($row['policy_id']) ?>">
                            <?= htmlspecialchars($row['policy_clause'] . " " . $row['policy_name']) ?>
                        </option>
                    <?php }
                } else { ?>
                    <option disabled>No policies available</option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="sub_control_policy_id" class="form-label">Sub Control | Policy Number</label>
            <select class="form-select" name="sub_control_policy_id" id="sub_control_policy_id" required>
                <option selected disabled>Open this select menu</option>
                <?php
                // Fetch sub-control policy data
                $fetch_query = "SELECT * FROM `sub_control_policy`";
                $fetch_res = mysqli_query($connection, $fetch_query);

                if ($fetch_res && mysqli_num_rows($fetch_res) > 0) {
                    while ($row = mysqli_fetch_assoc($fetch_res)) { ?>
                        <option value="<?= htmlspecialchars($row['sub_control_policy_id']) ?>">
                            <?= htmlspecialchars($row['sub_control_policy_number'] . " " . $row['sub_control_policy_heading']) ?>
                        </option>
                    <?php }
                } else { ?>
                    <option disabled>No sub-control policies available</option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="linked_control_policy_number" class="form-label">Linked Control | Policy Number</label>
            <input type="text" class="form-control" name="linked_control_policy_number" id="linked_control_policy_number">
        </div>

        <div class="mb-3">
            <label for="linked_control_policy_heading" class="form-label">Linked Control | Policy Heading</label>
            <input type="text" class="form-control" name="linked_control_policy_heading" id="linked_control_policy_heading">
        </div>

        <div class="mb-3">
            <label for="html-editor" class="form-label">Policy Details</label>
            <textarea id="html-editor" class="form-control" name="linked_control_policy_det"></textarea>
        </div>

        <button type="submit" name="add" class="btn btn-primary">Add</button>
    </form>
</div>
