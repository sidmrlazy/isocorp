<?php
include 'includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST["id"]);
    $column = $_POST["column"];
    $value = trim($_POST["value"]);
    $table = $_POST["table"];

    echo $id . "<br>";
    echo $column . "<br>";
    echo $value . "<br>";
    echo $table . "<br>";

    if (empty($id) || empty($column) || empty($value) || empty($table)) {
        echo "Error: Missing required fields.";
        exit;
    }

    $allowedTables = [
        "mrb" => ["mrb_id", "mrb_topic"],
        "mrb_deliverables" => ["mrb_del_id", "mrb_del_deliverable"],
        "mrb_activities" => ["mrb_act_id", "mrb_act_activity"]
    ];

    if (!isset($allowedTables[$table]) || !in_array($column, $allowedTables[$table])) {
        echo "Error: Invalid table or column.";
        exit;
    }

    $primaryKey = $allowedTables[$table][0];

    $stmt = $connection->prepare("UPDATE `$table` SET `$column` = ? WHERE `$primaryKey` = ?");
    if (!$stmt) {
        echo "Error preparing statement: " . $connection->error;
        exit;
    }

    $stmt->bind_param("si", $value, $id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error executing statement: " . $stmt->error;
    }

    $stmt->close();
}

$fetch_mrb = "SELECT * FROM mrb";
$fetch_mrb_r = mysqli_query($connection, $fetch_mrb);
$fetch_mrb_count = mysqli_num_rows($fetch_mrb_r);
if ($fetch_mrb_count > 0) {
?>
    <div class="form-container">
        <h6 class="form-container-heading mb-5">Management Review Board Structure</h6>
        <ul class="mrb-list">
            <?php
            while ($mrb = mysqli_fetch_assoc($fetch_mrb_r)) {
                $mrb_id = $mrb['mrb_id'];
                $mrb_topic = htmlspecialchars($mrb['mrb_topic'], ENT_QUOTES, 'UTF-8');
            ?>
                <li>
                    <div class="mrb-section-1">
                        <p><strong><?php echo $mrb_topic; ?></strong></p>
                    </div>

                    <?php
                    $fetch_deliverables = "SELECT * FROM mrb_deliverables WHERE mrb_del_board_id = $mrb_id";
                    $fetch_deliverables_r = mysqli_query($connection, $fetch_deliverables);
                    if (mysqli_num_rows($fetch_deliverables_r) > 0) {
                    ?>
                        <ul>
                            <?php
                            while ($deliverable = mysqli_fetch_assoc($fetch_deliverables_r)) {
                                $deliverable_id = $deliverable['mrb_del_id'];
                                $deliverable_name = htmlspecialchars($deliverable['mrb_del_deliverable'], ENT_QUOTES, 'UTF-8');
                            ?>
                                <li>
                                    <div class="mrb-section-2">
                                        <p><?php echo $deliverable_name; ?></p>
                                    </div>

                                    <!-- Fetch Activities -->
                                    <?php
                                    $fetch_activities = "SELECT * FROM mrb_activities WHERE mrb_act_deliverable_id = $deliverable_id";
                                    $fetch_activities_r = mysqli_query($connection, $fetch_activities);
                                    if (mysqli_num_rows($fetch_activities_r) > 0) {
                                    ?>
                                        <ul class="activities-list">
                                            <?php
                                            while ($activity = mysqli_fetch_assoc($fetch_activities_r)) {
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
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>