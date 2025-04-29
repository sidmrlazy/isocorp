<?php
if (isset($_GET['control_id'])) {
    $control_id = $_GET['control_id'];

    $query = "SELECT * FROM controls WHERE `control_id` = '$control_id'";
    $result = mysqli_query($connection, $query);
    $fetched_control_id = "";
    $fetched_control_name = "";
    $fetched_control_linked_1 = "";
    $fetched_control_linked_2 = "";
    $fetched_control_linked_3 = "";
    $fetched_control_details = "";
    $fetched_control_support = "";
    $fetched_control_assigned_to = "";
    $fetched_control_due_date = "";
    $fetched_control_update_date = "";
    $fetched_control_added_by = "";
    $fetched_control_status = "";
    $fetched_control_added_date = "";
    while ($row = mysqli_fetch_assoc($result)) {
        $fetched_control_id = $row['control_id'];
        $fetched_control_name = $row['control_name'];
        $fetched_control_linked_1 = $row['control_linked_1'];
        $fetched_control_linked_2 = $row['control_linked_2'];
        $fetched_control_linked_3 = $row['control_linked_3'];
        $fetched_control_details = $row['control_details'];
        $fetched_control_support = $row['control_support'];
        $fetched_control_assigned_to = $row['control_assigned_to'];
        $fetched_control_due_date = $row['control_due_date'];
        $fetched_control_update_date = $row['control_update_date'];
        $fetched_control_added_by = $row['control_added_by'];
        $fetched_control_status = $row['control_status'];
        $fetched_control_added_date = $row['control_added_date'];
    }
}
?>
<div class="tab">
    <p style="font-weight: 600 !important; color: #777;">
        <?php echo $fetched_control_name ?>
        <?php if (!empty($fetched_control_linked_1)) {
            echo "> " . $fetched_control_linked_1;
        } else {
            echo "";
        } ?>
        <?php if (!empty($fetched_control_linked_2)) {
            echo "> " . $fetched_control_linked_2;
        } else {
            echo "";
        } ?>
        <?php if (!empty($fetched_control_linked_3)) {
            echo "> " . $fetched_control_linked_3;
        } else {
            echo "";
        } ?>
    </p>
    <p><?php echo $fetched_control_details ?></p>
</div>