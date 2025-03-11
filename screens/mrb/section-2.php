<div class="form-column">
    <?php
    include 'includes/connection.php';

    // Ensure the connection is successful
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Insert MRB Topic
    if (isset($_POST['main_mrb_submit'])) {
        $mrb_topic = trim($_POST['mrb_topic']);
        $mrb_status = 1;

        if (!empty($mrb_topic)) {
            $stmt = mysqli_prepare($connection, "INSERT INTO mrb (mrb_topic, mrb_status) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "si", $mrb_topic, $mrb_status);
            $result = mysqli_stmt_execute($stmt);

            if (!$result) {
                echo "Error: " . mysqli_error($connection);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<p style='color: red;'>MRB Topic cannot be empty.</p>";
        }
    }
    ?>

    <form action="" method="POST" class="form-container">
        <div class="mb-3">
            <label for="mrb_topic" class="form-label">Management Review Board Topic</label>
            <input type="text" name="mrb_topic" class="form-control" id="mrb_topic" required>
        </div>
        <button type="submit" name="main_mrb_submit" class="btn btn-primary">Submit</button>
    </form>

    <?php
    // Insert Deliverables
    if (isset($_POST['mrb_del_submit'])) {
        $mrb_del_board_id = trim($_POST['mrb_del_board_id']);
        $mrb_del_deliverable = trim($_POST['mrb_del_deliverable']);
        $mrb_del_status = 1;

        if (!empty($mrb_del_board_id) && is_numeric($mrb_del_board_id) && !empty($mrb_del_deliverable)) {
            $stmt = mysqli_prepare($connection, "INSERT INTO mrb_deliverables (mrb_del_board_id, mrb_del_deliverable, mrb_del_status) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "isi", $mrb_del_board_id, $mrb_del_deliverable, $mrb_del_status);
            $result = mysqli_stmt_execute($stmt);

            if (!$result) {
                echo "Error: " . mysqli_error($connection);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<p style='color: red;'>All fields are required.</p>";
        }
    }
    ?>

    <form action="" method="POST" class="form-container">
        <div class="mb-3">
            <label for="mrb_del_board_id" class="form-label">Select Topic</label>
            <select class="form-select" name="mrb_del_board_id" required>
                <option value="">Open this select menu</option>
                <?php
                $fetch_main_mrb = "SELECT mrb_id, mrb_topic FROM mrb";
                $fetch_main_mrb_r = mysqli_query($connection, $fetch_main_mrb);
                while ($row = mysqli_fetch_assoc($fetch_main_mrb_r)) {
                    echo "<option value='" . htmlspecialchars($row['mrb_id'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['mrb_topic'], ENT_QUOTES, 'UTF-8') . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="mrb_del_deliverable" class="form-label">Add Deliverables</label>
            <input type="text" name="mrb_del_deliverable" class="form-control" id="mrb_del_deliverable" required>
        </div>
        <button type="submit" name="mrb_del_submit" class="btn btn-primary">Submit</button>
    </form>

    <?php
    // Insert Activities
    if (isset($_POST['submit_activity'])) {
        $mrb_act_deliverable_id = trim($_POST['mrb_act_deliverable_id']);
        $mrb_act_activity = trim($_POST['mrb_act_activity']);
        $mrb_act_status = 1;

        if (!empty($mrb_act_deliverable_id) && is_numeric($mrb_act_deliverable_id) && !empty($mrb_act_activity)) {
            $stmt = mysqli_prepare($connection, "INSERT INTO mrb_activities (mrb_act_deliverable_id, mrb_act_activity, mrb_act_status) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "isi", $mrb_act_deliverable_id, $mrb_act_activity, $mrb_act_status);
            $result = mysqli_stmt_execute($stmt);

            if (!$result) {
                echo "Error: " . mysqli_error($connection);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<p style='color: red;'>All fields are required.</p>";
        }
    }
    ?>

    <form action="" method="POST" class="form-container">
        <div class="mb-3">
            <label for="mrb_act_deliverable_id" class="form-label">Select Deliverable</label>
            <select class="form-select" name="mrb_act_deliverable_id" id="mrb_act_deliverable_id" required>
                <option selected disabled>Open this select menu</option>
                <?php
                $fetch_mrb_activity = "SELECT mrb_del_id, mrb_del_deliverable FROM mrb_deliverables";
                $fetch_mrb_activity_r = mysqli_query($connection, $fetch_mrb_activity);
                while ($row = mysqli_fetch_assoc($fetch_mrb_activity_r)) {
                    echo "<option value='" . htmlspecialchars($row['mrb_del_id'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['mrb_del_deliverable'], ENT_QUOTES, 'UTF-8') . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="mrb_act_activity" class="form-label">Add Activity</label>
            <input type="text" name="mrb_act_activity" class="form-control" id="mrb_act_activity" required>
        </div>

        <button type="submit" name="submit_activity" class="btn btn-primary">Submit</button>
    </form>
</div>
