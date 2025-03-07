<?php
include 'includes/connection.php';
// include 'includes/config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $risk_id = intval($_GET['id']);
    $stmt = $connection->prepare("DELETE FROM risks WHERE risks_id = ?");
    $stmt->bind_param("i", $risk_id);
    $stmt->execute();
    header("Location: risks-treatments.php?delete=success");
    exit();
} else {
    header("Location: risks-treatments.php?delete=error");
    exit();
}
?>
