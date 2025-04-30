<?php
if (isset($_GET['control_linked_policies_id'])) {
    // Securely retrieve control_id
    $control_id = mysqli_real_escape_string($connection, $_GET['control_linked_policies_id']);

    // Debug: Display control ID
    echo "<!-- Debug: control_id = $control_id -->";

    $query = "SELECT 
        c.control_id, 
        c.control_name, 
        c.control_assigned_to, 
        c.control_due_date, 
        c.control_status, 
        c.control_added_date,
        clp1.control_linked_name AS linked_1, 
        clp2.control_linked_name AS linked_2, 
        clp3.control_linked_name AS linked_3,
        clp1.control_linked_id AS control_linked_policies_id,
        clp1.control_details,
        clp1.control_support
    FROM controls c
    LEFT JOIN control_linked_policies clp1 
        ON c.control_id = clp1.control_parent_id AND clp1.control_linked_level = 1
    LEFT JOIN control_linked_policies clp2 
        ON c.control_id = clp2.control_parent_id AND clp2.control_linked_level = 2
    LEFT JOIN control_linked_policies clp3 
        ON c.control_id = clp3.control_parent_id AND clp3.control_linked_level = 3
    WHERE c.control_id = '$control_id'";

    $result = mysqli_query($connection, $query);

    // Debug: Check row count
    echo "<!-- Rows found: " . mysqli_num_rows($result) . " -->";

    // Initialize variables
    $fetched_control_id = "";
    $fetched_control_name = "";
    $fetched_control_assigned_to = "";
    $fetched_control_due_date = "";
    $fetched_control_status = "";
    $fetched_control_added_date = "";
    $fetched_control_details = "";
    $fetched_control_linked_1 = "";
    $fetched_control_linked_2 = "";
    $fetched_control_linked_3 = "";
    $fetched_control_support = "";

    if ($row = mysqli_fetch_assoc($result)) {
        $fetched_control_id = $row['control_id'];
        $fetched_control_name = $row['control_name'];
        $fetched_control_assigned_to = $row['control_assigned_to'];
        $fetched_control_due_date = $row['control_due_date'];
        $fetched_control_status = $row['control_status'];
        $fetched_control_added_date = $row['control_added_date'];
        $fetched_control_details = $row['control_details'];
        $fetched_control_linked_1 = $row['linked_1'];
        $fetched_control_linked_2 = $row['linked_2'];
        $fetched_control_linked_3 = $row['linked_3'];
        $fetched_control_support = $row['control_support'];
    } else {
        echo "<p style='color: red;'>No policy details found for control ID: $control_id</p>";
    }
}
?>

<div class="tab">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <p style="font-weight: 600 !important; color: #777;">
            <?php echo htmlspecialchars($fetched_control_name); ?>
            <?php
            if (!empty($fetched_control_linked_1)) echo " > " . htmlspecialchars($fetched_control_linked_1);
            if (!empty($fetched_control_linked_2)) echo " > " . htmlspecialchars($fetched_control_linked_2);
            if (!empty($fetched_control_linked_3)) echo " > " . htmlspecialchars($fetched_control_linked_3);
            ?>
        </p>
    </div>

    <?php if (!empty($fetched_control_details)) : ?>
        <p style="font-size: 18px !important; margin-top: 10px !important;">
            <?php echo nl2br(htmlspecialchars($fetched_control_details)); ?>
        </p>
    <?php endif; ?>

    <!-- Update control support -->
    <?php
    if (isset($_POST['add-control-support'])) {
        $control_id = mysqli_real_escape_string($connection, $_POST['control_id']);
        $control_support = mysqli_real_escape_string($connection, $_POST['control_support']);

        $update_control_support = "UPDATE `controls` 
            SET `control_support` = '$control_support' 
            WHERE `control_id` = '$control_id'";
        $update_result = mysqli_query($connection, $update_control_support);

        if ($update_result) {
            echo "<p style='color: green;'>Control support updated successfully.</p>";
        } else {
            echo "<p style='color: red;'>Error updating control support: " . mysqli_error($connection) . "</p>";
        }
    }
    ?>
</div>