<?php
include 'includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $policy_ids = $_POST['policy_id'];
    $policy_types = $_POST['policy_type'];
    $policy_names = $_POST['policy_name'];
    $applicabilities = $_POST['applicable_status'] ?? [];
    $justifications = $_POST['justification'] ?? [];

    foreach ($policy_ids as $index => $policy_id) {
        $type = $policy_types[$index];
        $name = $policy_names[$index];
        $applicable = isset($applicabilities[$index]) ? $applicabilities[$index] : null;
        $justification = trim($justifications[$index] ?? '');

        // Check if entry exists
        $check_sql = "SELECT soa_applicable, soa_justification FROM soa WHERE soa_policy_type = ? AND soa_policy_id = ?";
        $stmt = $connection->prepare($check_sql);
        $stmt->bind_param("si", $type, $policy_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($existing_applicable, $existing_justification);
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            // Update if something changed
            if ((string)$applicable !== (string)$existing_applicable || $justification !== $existing_justification) {
                $update_sql = "UPDATE soa SET soa_applicable = ?, soa_justification = ?, soa_policy_name = ?, soa_created_at = NOW() WHERE soa_policy_type = ? AND soa_policy_id = ?";
                $update_stmt = $connection->prepare($update_sql);
                $update_stmt->bind_param("isssi", $applicable, $justification, $name, $type, $policy_id);
                $update_stmt->execute();
                $update_stmt->close();
            }
        } else {
            // Insert if new
            $insert_sql = "INSERT INTO soa (soa_policy_type, soa_policy_id, soa_policy_name, soa_applicable, soa_justification, soa_created_at) VALUES (?, ?, ?, ?, ?, NOW())";
            $insert_stmt = $connection->prepare($insert_sql);
            $insert_stmt->bind_param("sisis", $type, $policy_id, $name, $applicable, $justification);
            $insert_stmt->execute();
            $insert_stmt->close();
        }

        $stmt->close();
    }

    header("Location: soa-setup.php?success=1");
    exit;
}
