<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
include 'includes/config.php';

// Check if an ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='container mt-5'><p class='text-danger'>Invalid Risk ID.</p></div>";
    exit();
}

$risk_id = intval($_GET['id']);
$query = $connection->prepare("SELECT * FROM risks WHERE risks_id = ?");
$query->bind_param("i", $risk_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows == 0) {
    echo "<div class='container mt-5'><p class='text-danger'>Risk not found.</p></div>";
    exit();
}

$risk = $result->fetch_assoc();

// Fetch policy details
$policy = $sub_control_policy = $linked_control_policy = $inner_linked_control_policy = null;

if (!empty($risk['risks_policy_id'])) {
    $stmt = $connection->prepare("SELECT policy_name, policy_clause FROM POLICY WHERE policy_id = ?");
    $stmt->bind_param("s", $risk['risks_policy_id']);
    $stmt->execute();
    $policy = $stmt->get_result()->fetch_assoc();
}

if (!empty($risk['risks_sub_control_policy_id'])) {
    $stmt = $connection->prepare("SELECT sub_control_policy_heading, sub_control_policy_number FROM SUB_CONTROL_POLICY WHERE sub_control_policy_id = ?");
    $stmt->bind_param("s", $risk['risks_sub_control_policy_id']);
    $stmt->execute();
    $sub_control_policy = $stmt->get_result()->fetch_assoc();
}

if (!empty($risk['risks_linked_control_policy_id'])) {
    $stmt = $connection->prepare("SELECT linked_control_policy_heading, linked_control_policy_number FROM LINKED_CONTROL_POLICY WHERE linked_control_policy_id = ?");
    $stmt->bind_param("s", $risk['risks_linked_control_policy_id']);
    $stmt->execute();
    $linked_control_policy = $stmt->get_result()->fetch_assoc();
}

if (!empty($risk['risks_inner_linked_control_policy'])) {
    $stmt = $connection->prepare("SELECT inner_linked_control_policy_heading, inner_linked_control_policy_number FROM INNER_LINKED_CONTROL_POLICY WHERE inner_linked_control_policy_id = ?");
    $stmt->bind_param("s", $risk['risks_inner_linked_control_policy']);
    $stmt->execute();
    $inner_linked_control_policy = $stmt->get_result()->fetch_assoc();
}
?>

<div class="dashboard-container">
    <div class="screen-name-container mb-3">
    <h1>Risk & Treatments Details</h1>
    <h2><a href="risks-treatments.php">Risks & Treatments</a> > Risk & Treatments Details</h2>
    </div>
    <div class="table-responsive table-container">
        <table class="table table-bordered">
            <tr>
                <th>Risk Name</th>
                <td><?= htmlspecialchars($risk['risks_name']) ?></td>
            </tr>
            
            <tr>
                <th>Treatment</th>
                <td><?= $risk['risks_description'] ?></td>
            </tr>
            <tr>
                <th>Likelihood</th>
                <td><?= $risk['risks_likelihood'] ?></td>
            </tr>
            <tr>
                <th>Impact</th>
                <td><?= $risk['risks_impact'] ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?= $risk['risks_status'] ?></td>
            </tr>
            <tr>
                <th>Created At</th>
                <td><?= $risk['risks_created_at'] ?></td>
            </tr>
            <tr>
                <th>Updated On</th>
                <td><?= $risk['risks_updated_at'] ?></td>
            </tr>
            <?php if ($policy): ?>
            <tr>
                <th>Policy</th>
                <td><?= htmlspecialchars($policy['policy_clause'] . ' - ' . $policy['policy_name']) ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($sub_control_policy): ?>
            <tr>
                <th>Sub-Control Policy</th>
                <td><?= htmlspecialchars($sub_control_policy['sub_control_policy_number'] . ' - ' . $sub_control_policy['sub_control_policy_heading']) ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($linked_control_policy): ?>
            <tr>
                <th>Linked Control Policy</th>
                <td><?= htmlspecialchars($linked_control_policy['linked_control_policy_number'] . ' - ' . $linked_control_policy['linked_control_policy_heading']) ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($inner_linked_control_policy): ?>
            <tr>
                <th>Inner Linked Control Policy</th>
                <td><?= htmlspecialchars($inner_linked_control_policy['inner_linked_control_policy_number'] . ' - ' . $inner_linked_control_policy['inner_linked_control_policy_heading']) ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
    <a href="risks-treatments.php" class="btn btn-secondary mt-3 mb-5">Back to Risks</a>
</div>

<?php include 'includes/footer.php'; ?>
