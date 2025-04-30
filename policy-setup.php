<?php
include('includes/header.php');
include('includes/navbar.php'); ?>

<div class="dashboard-container mb-5">
    <!-- ============= ADD MAIN POLICY ============= -->
    <div class="form-container w-50">
        <?php
        if (isset($_POST['add-control'])) {
            date_default_timezone_set('Asia/Kolkata');
            $control_name = mysqli_real_escape_string($connection, $_POST['control_name']);
            $control_added_date = date('m-d-Y H:i:s');

            $insert_q = "INSERT INTO `controls`(`control_name`, `control_added_date`) 
                         VALUES('$control_name', '$control_added_date')";
            $insert_r = mysqli_query($connection, $insert_q);
            if ($insert_r) { ?>
                <div class="alert alert-success mb-3" id="alertBox" role="alert">
                    Policy added!
                </div>
        <?php
            }
        }
        ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="control_name" class="form-label">Policy Name</label>
                <input type="text" name="control_name" class="form-control" id="control_name" required>
            </div>
            <button type="submit" name="add-control" class="btn btn-primary">Add</button>
        </form>
    </div>

    <!-- ============= LINKED POLICIES ============= -->
    <div class="form-container w-50">
        <?php
        if (isset($_POST['update-linked'])) {
            $control_id = $_POST['control_id'];
            $linked_name = $_POST['linked_name'];
            $linked_level = $_POST['linked_level'];
            $control_details = mysqli_real_escape_string($connection, $_POST['control_details']);
            $control_support = ''; // Placeholder for BLOB (e.g., you can handle file upload here)

            // Insert linked policy
            $insert_linked_q = "INSERT INTO `control_linked_policies` (`control_parent_id`, `control_linked_name`, `control_linked_level`, `control_details`, `control_support`) 
                                VALUES ('$control_id', '$linked_name', '$linked_level', '$control_details', '$control_support')";
            $insert_linked_r = mysqli_query($connection, $insert_linked_q);
            if ($insert_linked_r) { ?>
                <div class="alert alert-success mb-3" id="alertBox" role="alert">
                    Linked Policy added!
                </div>
        <?php
            }
        }
        ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="control_id" class="form-label">Select Main Policy</label>
                <select name="control_id" class="form-select" required>
                    <option selected>Open this select menu</option>
                    <?php
                    $get_control = "SELECT * FROM controls";
                    $get_control_r = mysqli_query($connection, $get_control);
                    while ($row = mysqli_fetch_assoc($get_control_r)) {
                        $fetched_control_id = $row['control_id'];
                        $fetched_control_name = $row['control_name'];
                        echo "<option value=\"$fetched_control_id\">$fetched_control_name</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="linked_name" class="form-label">Linked Policy Name</label>
                <input type="text" name="linked_name" class="form-control" id="linked_name" required>
            </div>
            <div class="mb-3">
                <label for="linked_level" class="form-label">Linked Policy Level</label>
                <select name="linked_level" class="form-select" required>
                    <option selected>Open this select menu</option>
                    <option value="1">Level 1</option>
                    <option value="2">Level 2</option>
                    <option value="3">Level 3</option>
                    <!-- Add more levels if needed -->
                </select>
            </div>
            <div class="mb-3">
                <label for="control_details" class="form-label">Details</label>
                <input type="text" name="control_details" class="form-control" id="control_details">
            </div>
            <button type="submit" name="update-linked" class="btn btn-primary">Add Linked Policy</button>
        </form>
    </div>

    <!-- ============= VIEW LINKED POLICIES ============= -->
    <div class="form-container w-50">
        <h5>Linked Policies</h5>
        <?php
        $get_linked_policies = "SELECT * FROM control_linked_policies INNER JOIN controls ON control_linked_policies.control_parent_id = controls.control_id";
        $get_linked_policies_r = mysqli_query($connection, $get_linked_policies);
        while ($row = mysqli_fetch_assoc($get_linked_policies_r)) {
            $linked_id = $row['control_linked_id'];
            $control_name = $row['control_name'];
            $linked_name = $row['control_linked_name'];
            $linked_level = $row['control_linked_level']; ?>
            <div class="mb-2">
                <strong><?php echo $control_name; ?></strong> > Level <?php echo $linked_level; ?>: <?php echo $linked_name; ?>
            </div>
        <?php
        }
        ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>