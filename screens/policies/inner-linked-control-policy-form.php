<div class="add-control-form-container">
    <?php
    include 'includes/connection.php';

    // Process form data
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
        // Retrieve and sanitize form data
        $main_control_policy_id = intval($_POST['main_control_policy_id']);
        $sub_control_policy_id = intval($_POST['sub_control_policy_id']);
        $linked_control_policy_id = intval($_POST['linked_control_policy_id']);
        $inner_linked_control_policy_number = $_POST['inner_linked_control_policy_number'];
        $inner_linked_control_policy_heading = $_POST['inner_linked_control_policy_heading'];
        $inner_linked_control_policy_det = $_POST['inner_linked_control_policy_det'];
        $inner_linked_control_policy_status = 1; // Set default status to active

        // Use prepared statements to prevent SQL Injection
        $stmt = $connection->prepare("INSERT INTO `inner_linked_control_policy` 
            (
            `main_control_policy_id`, 
            `sub_control_policy_id`, 
            `linked_control_policy_id`, 
            `inner_linked_control_policy_number`, 
            `inner_linked_control_policy_heading`, 
            `inner_linked_control_policy_det`, 
            `inner_linked_control_policy_status`) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param(
                "iiisssi",
                $main_control_policy_id,
                $sub_control_policy_id,
                $linked_control_policy_id,
                $inner_linked_control_policy_number,
                $inner_linked_control_policy_heading,
                $inner_linked_control_policy_det,
                $inner_linked_control_policy_status
            );

            // Execute the query
            if ($stmt->execute()) { ?>
                <div class="alert alert-success mb-3" role="alert">
                    Inner Linked Control Policy added successfully!
                </div>
            <?php } else { ?>
                <div class="alert alert-danger mb-3" role="alert">
                    Policy addition failed: <?= $stmt->error ?>
                </div>
            <?php }
            $stmt->close();
        } else { ?>
            <div class="alert alert-danger mb-3" role="alert">
                Failed to prepare the statement: <?= $connection->error ?>
            </div>
        <?php }
    }
    ?>

    <form class="add-control-main-form" action="" method="POST">
        <div class="mb-3">
            <label for="main_control_policy_id" class="form-label">Main Control | Policy</label>
            <select class="form-select" name="main_control_policy_id" id="main_control_policy_id" >
                <option selected disabled>Select Main Control Policy</option>
                <?php
                // Fetch policy data
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
            <label for="sub_control_policy_id" class="form-label">Sub Control | Policy</label>
            <select class="form-select" name="sub_control_policy_id" id="sub_control_policy_id" >
                <option selected disabled>Select Sub Control Policy</option>
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
            <label for="linked_control_policy_id" class="form-label">Linked Control | Policy</label>
            <select class="form-select" name="linked_control_policy_id" id="linked_control_policy_id" >
                <option selected disabled>Select Linked Control Policy</option>
                <?php
                // Fetch linked control policy data
                $fetch_query = "SELECT * FROM `linked_control_policy`";
                $fetch_res = mysqli_query($connection, $fetch_query);

                if ($fetch_res && mysqli_num_rows($fetch_res) > 0) {
                    while ($row = mysqli_fetch_assoc($fetch_res)) { ?>
                        <option value="<?= htmlspecialchars($row['linked_control_policy_id']) ?>">
                            <?= htmlspecialchars($row['linked_control_policy_number'] . " " . $row['linked_control_policy_heading']) ?>
                        </option>
                    <?php }
                } else { ?>
                    <option disabled>No linked policies available</option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="inner_linked_control_policy_number" class="form-label">Inner Linked Control | Policy Number</label>
            <input type="text" class="form-control" name="inner_linked_control_policy_number" id="inner_linked_control_policy_number" >
        </div>

        <div class="mb-3">
            <label for="inner_linked_control_policy_heading" class="form-label">Inner Linked Control | Policy Heading</label>
            <input type="text" class="form-control" name="inner_linked_control_policy_heading" id="inner_linked_control_policy_heading" >
        </div>

        <!-- <div class="mb-3">
            <label for="inner_linked_control_policy_det" class="form-label">Policy Details</label>
            <textarea id="inner_linked_control_policy_det" class="form-control" name="inner_linked_control_policy_det" ></textarea>
        </div> -->

        <div class="WYSIWYG-editor">
            <textarea id="editorNew" name="editor_content"><?php echo $new_details; ?></textarea>
        </div>

        <button type="submit" name="add" class="btn btn-primary mt-3">Add Inner Linked Policy</button>
    </form>
</div>
