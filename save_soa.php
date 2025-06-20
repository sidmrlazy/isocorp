<?php
include('includes/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $policy_ids = $_POST['policy_id'] ?? [];
    $policy_types = $_POST['policy_type'] ?? [];
    $policy_names = $_POST['policy_name'] ?? [];
    $justifications = $_POST['justification'] ?? [];
    $applicable_reasons = $_POST['applicable_reason'] ?? [];
    $applicable_status = $_POST['applicable_status'] ?? [];

    $soa_ra = $_POST['soa_ra'] ?? [];
    $soa_br_bp = $_POST['soa_br_bp'] ?? [];
    $soa_lr_co = $_POST['soa_lr_co'] ?? [];

    if (empty($policy_ids)) {
        echo "⚠️ No policy data posted.";
        exit;
    }

    $all_good = true;

    for ($i = 0; $i < count($policy_ids); $i++) {
        $id = $policy_ids[$i] ?? '';
        $type = $policy_types[$i] ?? '';
        $name = $policy_names[$i] ?? '';
        $justification = $justifications[$i] ?? '';
        $reason = $applicable_reasons[$i] ?? '';

        // Applicable status stays Y/N
        $applicable = isset($applicable_status[$i])
            ? ($applicable_status[$i] === '1' ? 'Y' : 'N')
            : null;


        // If checkbox was ticked, it's 'Y'; if not ticked, let it be NULL
        $ra = array_key_exists($i, $soa_ra) ? 'Y' : null;
        $brbp = array_key_exists($i, $soa_br_bp) ? 'Y' : null;
        $lrco = array_key_exists($i, $soa_lr_co) ? 'Y' : null;

        $stmt = $connection->prepare("
            INSERT INTO soa (
                soa_policy_id, soa_policy_type, soa_policy_name,
                soa_justification, soa_applicable_reason, soa_applicable,
                soa_ra, soa_br_bp, soa_lr_co, soa_created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        if (!$stmt) {
            echo "❌ Prepare failed: " . $connection->error;
            $all_good = false;
            continue;
        }

        $stmt->bind_param(
            "sssssssss",
            $id,
            $type,
            $name,
            $justification,
            $reason,
            $applicable,
            $ra,
            $brbp,
            $lrco
        );

        if (!$stmt->execute()) {
            echo "❌ Execute failed: " . $stmt->error . "<br>";
            $all_good = false;
        }

        $stmt->close();
    }

    if ($all_good) {
        header("Location: soa-setup.php?status=success");
        exit;
    } else {
        echo "⚠️ One or more rows failed to insert. Check above errors.";
    }
} else {
    echo "⚠️ Invalid request.";
}
