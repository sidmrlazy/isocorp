<?php
include 'includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_risk'])) {
    $risk_name = $_POST['risk_name'];
    $likelihood = $_POST['likelihood'];
    $impact = $_POST['impact'];
    $action = $_POST['action'];
    $review_date = $_POST['review_date'];
    $assigned_to = $_POST['assigned_to'];
    $added_by = $_POST['added_by'];
    $status = 'Open';

    $sql = "INSERT INTO risks (
        risks_name, risks_likelihood, risks_impact, risks_status, 
        risks_created_by, risks_action, risks_review_date, risks_assigned_to
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssssssss", 
        $risk_name, $likelihood, $impact, $status, 
        $added_by, $action, $review_date, $assigned_to
    );

    if ($stmt->execute()) {
        header("Location: risks-treatments.php?add=success");
    } else {
        header("Location: risks-treatments.php?add=error");
    }
    $stmt->close();
}
?>
