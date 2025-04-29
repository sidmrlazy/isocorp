<div class="form-column">
    <?php
    include 'includes/connection.php';

    if (isset($_POST['main_mrb_submit'])) {
        $mrb_topic = mysqli_real_escape_string($connection, $_POST['mrb_topic']);
        $mrb_status = "1";
        $insert_mrb_query = "INSERT INTO mrb (mrb_topic, mrb_status) VALUES ('$mrb_topic', $mrb_status)";
        $insert_mrb_result = mysqli_query($connection, $insert_mrb_query);
    }
    ?>

    <form action="" method="POST" class="form-container">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Management Review Board Topic</label>
            <input type="text" name="mrb_topic" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        </div>
        <button type="submit" name="main_mrb_submit" class="btn btn-primary">Submit</button>
    </form>

    <?php
    if (isset($_POST['mrb_del_submit'])) {
        $mrb_del_board_id = mysqli_real_escape_string($connection, $_POST['mrb_del_board_id']);
        $mrb_del_deliverable = mysqli_real_escape_string($connection, $_POST['mrb_del_deliverable']);
        $mrb_del_status = "1";
        if (!empty($mrb_del_board_id) && is_numeric($mrb_del_board_id) && !empty($mrb_del_deliverable)) {
            $insert_del = "INSERT INTO mrb_deliverables (mrb_del_board_id, mrb_del_deliverable, mrb_del_status) 
                       VALUES ('$mrb_del_board_id', '$mrb_del_deliverable', '$mrb_del_status')";
            $insert_del_r = mysqli_query($connection, $insert_del);
        }
    }
    ?>

    <form action="" method="POST" class="form-container">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Select Topic</label>
            <select class="form-select" name="mrb_del_board_id" aria-label="Default select example">
                <option value="">Open this select menu</option>
                <?php
                $fetch_main_mrb = "SELECT * FROM mrb";
                $fetch_main_mrb_r = mysqli_query($connection, $fetch_main_mrb);
                while ($row = mysqli_fetch_assoc($fetch_main_mrb_r)) {
                    $mrb_id = $row['mrb_id'];
                    $mrb_topic = $row['mrb_topic']; ?>
                    <option value="<?php echo $mrb_id; ?>"><?php echo htmlspecialchars($mrb_topic, ENT_QUOTES, 'UTF-8'); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Add Deliverables</label>
            <input type="text" name="mrb_del_deliverable" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        </div>
        <button type="submit" name="mrb_del_submit" class="btn btn-primary">Submit</button>
    </form>

    <?php
    if (isset($_POST['submit_activity'])) {
        $mrb_act_deliverable_id = mysqli_real_escape_string($connection, $_POST['mrb_act_deliverable_id']);
        $mrb_act_activity = mysqli_real_escape_string($connection, $_POST['mrb_act_activity']);
        $mrb_act_status = "1";

        // Fix the SQL query by adding single quotes for strings
        $insert_act_query = "INSERT INTO mrb_activities (
                mrb_act_deliverable_id,
                mrb_act_activity,
                mrb_act_status
                ) VALUES (
                '$mrb_act_deliverable_id',
                '$mrb_act_activity',
                '$mrb_act_status'
                )";

        $insert_act_res = mysqli_query($connection, $insert_act_query);
    }
    ?>

    <form action="" method="POST" class="form-container">
        <div class="mb-3">
            <label for="deliverable" class="form-label">Select Deliverable</label>
            <select class="form-select" name="mrb_act_deliverable_id" id="deliverable" aria-label="Select Deliverable">
                <option selected disabled>Open this select menu</option>
                <?php
                $fetch_mrb_activity = "SELECT * FROM mrb_deliverables";
                $fetch_mrb_activity_r = mysqli_query($connection, $fetch_mrb_activity);

                while ($row = mysqli_fetch_assoc($fetch_mrb_activity_r)) {
                    $mrb_del_id = $row['mrb_del_id'];
                    $mrb_del_deliverable = $row['mrb_del_deliverable'];
                    echo "<option value='" . htmlspecialchars($mrb_del_id, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($mrb_del_deliverable, ENT_QUOTES, 'UTF-8') . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="activity" class="form-label">Add Activity</label>
            <input type="text" name="mrb_act_activity" class="form-control" id="activity" required>
        </div>

        <button type="submit" name="submit_activity" class="btn btn-primary">Submit</button>
    </form>
</div>