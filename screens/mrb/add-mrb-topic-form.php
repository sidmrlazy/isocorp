<div class="dashboard-container">
    <div class="screen-name-container">
        <h1>MANAGEMENT REVIEW BOARD SETUP</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Management Review Board Setup</h2>
    </div>
    <div class="section-divider mb-5 mt-3">
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

        <?php
        include 'includes/connection.php';

        $fetch_mrb = "SELECT * FROM mrb";
        $fetch_mrb_r = mysqli_query($connection, $fetch_mrb);
        $fetch_mrb_count = mysqli_num_rows($fetch_mrb_r);

        if ($fetch_mrb_count > 0) {
        ?>
            <div class="form-container">
                <h6 class="form-container-heading">Management Review Board Structure</h6>
                <ul class="mrb-list">
                    <?php
                    while ($mrb = mysqli_fetch_assoc($fetch_mrb_r)) {
                        $mrb_id = $mrb['mrb_id'];
                        $mrb_topic = $mrb['mrb_topic'];
                    ?>
                        <li>
                            <strong><?php echo htmlspecialchars($mrb_topic, ENT_QUOTES, 'UTF-8'); ?></strong>

                            <!-- Fetch Deliverables for this MRB -->
                            <?php
                            $fetch_deliverables = "SELECT * FROM mrb_deliverables WHERE mrb_del_board_id = $mrb_id";
                            $fetch_deliverables_r = mysqli_query($connection, $fetch_deliverables);
                            if (mysqli_num_rows($fetch_deliverables_r) > 0) {
                            ?>
                                <ul class="deliverables-list">
                                    <?php
                                    while ($deliverable = mysqli_fetch_assoc($fetch_deliverables_r)) {
                                        $deliverable_id = $deliverable['mrb_del_id'];
                                        $deliverable_name = $deliverable['mrb_del_deliverable'];
                                    ?>
                                        <li>
                                            <?php echo htmlspecialchars($deliverable_name, ENT_QUOTES, 'UTF-8'); ?>

                                            <!-- Fetch Activities for this Deliverable -->
                                            <?php
                                            $fetch_activities = "SELECT * FROM mrb_activities WHERE mrb_act_deliverable_id = $deliverable_id";
                                            $fetch_activities_r = mysqli_query($connection, $fetch_activities);
                                            if (mysqli_num_rows($fetch_activities_r) > 0) {
                                            ?>
                                                <ul class="activities-list">
                                                    <?php
                                                    while ($activity = mysqli_fetch_assoc($fetch_activities_r)) {
                                                        echo "<li>" . htmlspecialchars($activity['mrb_act_activity'], ENT_QUOTES, 'UTF-8') . "</li>";
                                                    }
                                                    ?>
                                                </ul>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
    </div>
</div>