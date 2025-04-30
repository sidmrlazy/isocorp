<?php
include 'includes/connection.php';

if (isset($_GET['id'])) {
    $main_id = $_GET['id'];
    $result = mysqli_query($connection, "SELECT * FROM controls WHERE control_id = '$main_id'");
    $row = mysqli_fetch_assoc($result);

    $linked = [];
    for ($i = 1; $i <= 4; $i++) {
        $key = 'control_linked_' . $i;
        if (!empty($row[$key])) {
            $linked_result = mysqli_query($connection, "SELECT control_id FROM controls WHERE control_name = '" . mysqli_real_escape_string($connection, $row[$key]) . "' LIMIT 1");
            if ($linked_row = mysqli_fetch_assoc($linked_result)) {
                $linked[] = [
                    'id' => $linked_row['control_id'],
                    'name' => $row[$key]
                ];
            }
        }
    }

    echo json_encode($linked);
}
