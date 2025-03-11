<?php
include 'includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST["id"]);
    $column = $_POST["column"];
    $value = trim($_POST["value"]);
    $table = $_POST["table"];

    // Validate Inputs
    if (!isset($id, $column, $value, $table) || $id <= 0 || empty($column) || empty($value) || empty($table)) {
        echo "Error: Missing required fields.";
        exit;
    }

    // Allowed tables and columns (Whitelist)
    $allowedTables = [
        "mrb" => ["mrb_id", "mrb_topic"],
        "mrb_deliverables" => ["mrb_del_id", "mrb_del_deliverable"],
        "mrb_activities" => ["mrb_act_id", "mrb_act_activity"]
    ];

    // Validate Table & Column
    if (!isset($allowedTables[$table]) || !in_array($column, $allowedTables[$table])) {
        echo "Error: Invalid table or column.";
        exit;
    }

    $primaryKey = $allowedTables[$table][0]; // Get primary key for table

    // Sanitize table & column name (Whitelist ensures safety)
    $table = mysqli_real_escape_string($connection, $table);
    $column = mysqli_real_escape_string($connection, $column);

    // Prepare the SQL statement (Column name and table name cannot be parameterized)
    $stmt = $connection->prepare("UPDATE `$table` SET `$column` = ? WHERE `$primaryKey` = ?");
    if (!$stmt) {
        echo "Error preparing statement: " . $connection->error;
        exit;
    }

    // Dynamically Bind Data Types
    if (is_numeric($value)) {
        $stmt->bind_param("di", $value, $id);
    } else {
        $stmt->bind_param("si", $value, $id);
    }

    // Execute the Query
    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error executing statement: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch & Display MRB Data
$fetch_mrb = "SELECT * FROM mrb";
$fetch_mrb_r = mysqli_query($connection, $fetch_mrb);

if (mysqli_num_rows($fetch_mrb_r) > 0) {
?>
    <div class="form-container">
        <h6 class="form-container-heading mb-5">Management Review Board Structure</h6>
        <ul class="mrb-list">
            <?php while ($mrb = mysqli_fetch_assoc($fetch_mrb_r)) {
                $mrb_id = $mrb['mrb_id'];
                $mrb_topic = htmlspecialchars($mrb['mrb_topic'], ENT_QUOTES, 'UTF-8');
            ?>
                <li>
                    <div class="mrb-section-1">
                        <p><strong><?php echo $mrb_topic; ?></strong></p>
                    </div>

                    <?php
                    $fetch_deliverables = "SELECT * FROM mrb_deliverables WHERE mrb_del_board_id = ?";
                    $stmt = $connection->prepare($fetch_deliverables);
                    $stmt->bind_param("i", $mrb_id);
                    $stmt->execute();
                    $fetch_deliverables_r = $stmt->get_result();

                    if ($fetch_deliverables_r->num_rows > 0) {
                    ?>
                        <ul>
                            <?php while ($deliverable = mysqli_fetch_assoc($fetch_deliverables_r)) {
                                $deliverable_id = $deliverable['mrb_del_id'];
                                $deliverable_name = htmlspecialchars($deliverable['mrb_del_deliverable'], ENT_QUOTES, 'UTF-8');
                            ?>
                                <li>
                                    <div class="mrb-section-2">
                                        <p><?php echo $deliverable_name; ?></p>
                                    </div>

                                    <!-- Fetch Activities -->
                                    <?php
                                    $fetch_activities = "SELECT * FROM mrb_activities WHERE mrb_act_deliverable_id = ?";
                                    $stmt_act = $connection->prepare($fetch_activities);
                                    $stmt_act->bind_param("i", $deliverable_id);
                                    $stmt_act->execute();
                                    $fetch_activities_r = $stmt_act->get_result();

                                    if ($fetch_activities_r->num_rows > 0) {
                                    ?>
                                        <ul class="activities-list">
                                            <?php while ($activity = mysqli_fetch_assoc($fetch_activities_r)) {
                                                $activity_id = $activity['mrb_act_id'];
                                                $activity_name = htmlspecialchars($activity['mrb_act_activity'], ENT_QUOTES, 'UTF-8');
                                            ?>
                                                <li class="mrb-section-3">
                                                    <p><?php echo $activity_name; ?></p>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php }
                    $stmt->close();
                    ?>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>
