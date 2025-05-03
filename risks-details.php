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
?>

<div class="dashboard-container">
    <div class="screen-name-container mb-3">
        <h1>Risk & Treatments Details</h1>
        <h2><a href="risks-treatments.php">Risks & Treatments</a> > Risk & Treatments Details</h2>
    </div>
    <div style="display: flex; justify-content: center; align-items: flex-start">
        <div style="width: 50% !important; margin: 5px !important;">
            <div class="table-responsive table-container">
                <table class="table table-bordered">
                    <tr>
                        <th style="font-size: 12px !important; width: 20% !important;">Risk Name</th>
                        <td style="font-size: 12px !important;"><?= htmlspecialchars($risk['risks_name']) ?></td>
                    </tr>

                    <tr>
                        <th style="font-size: 12px !important; width: 20% !important;">Treatment</th>
                        <td style="font-size: 12px !important;"><?= $risk['risks_description'] ?></td>
                    </tr>
                    <tr>
                        <th style="font-size: 12px !important; width: 20% !important;">Likelihood</th>
                        <td style="font-size: 12px !important;"><?= $risk['risks_likelihood'] ?></td>
                    </tr>
                    <tr>
                        <th style="font-size: 12px !important; width: 20% !important;">Impact</th>
                        <td style="font-size: 12px !important;"><?= $risk['risks_impact'] ?></td>
                    </tr>
                    <tr>
                        <th style="font-size: 12px !important; width: 20% !important;">Status</th>
                        <td style="font-size: 12px !important;"><?= $risk['risks_status'] ?></td>
                    </tr>
                    <tr>
                        <th style="font-size: 12px !important; width: 20% !important;">Created At</th>
                        <td style="font-size: 12px !important;"><?= $risk['risks_created_at'] ?></td>
                    </tr>
                    <tr>
                        <th style="font-size: 12px !important; width: 20% !important;">Updated On</th>
                        <td style="font-size: 12px !important;"><?= $risk['risks_updated_at'] ?></td>
                    </tr>

                </table>
            </div>
            <a href="risks-treatments.php" class="btn btn-sm btn-secondary mt-3 mb-5">Back to Risks</a>
        </div>

        <!-- ============== APPLICABLE POLICIES ============== -->
        <div style="background-color: #fff !important; width: 50% !important; margin: 5px !important; padding: 20px !important; border-radius: 10px !important;">
            <?php
            $risks_id = intval($risk['risks_id']);
            $fetch_mappings_query = "SELECT * FROM risk_policies WHERE risks_id = $risks_id";
            $fetch_mappings_result = mysqli_query($connection, $fetch_mappings_query);

            if ($fetch_mappings_result && mysqli_num_rows($fetch_mappings_result) > 0) {
                echo "<h6 class='mt-3'>Associated Controls / Policies:</h6>";
                echo "<ul style='font-size: 12px !important;'>";

                while ($mapping = mysqli_fetch_assoc($fetch_mappings_result)) {
                    $type = $mapping['clause_type'];
                    $id = $mapping['clause_id'];
                    $display_text = "";

                    if ($type === 'policy') {
                        $q = mysqli_query($connection, "SELECT * FROM policy WHERE policy_id = $id");
                        if ($r = mysqli_fetch_assoc($q)) {
                            $display_text = $r['policy_clause'] . " " . $r['policy_name'];
                        }
                    } elseif ($type === 'sub') {
                        $q = mysqli_query($connection, "
                SELECT s.*, p.policy_clause, p.policy_name 
                FROM sub_control_policy s 
                JOIN policy p ON p.policy_id = s.main_control_policy_id 
                WHERE s.sub_control_policy_id = $id
            ");
                        if ($r = mysqli_fetch_assoc($q)) {
                            $display_text = $r['policy_clause'] . " " . $r['policy_name'] . " > " .
                                $r['sub_control_policy_number'] . " " . $r['sub_control_policy_heading'];
                        }
                    } elseif ($type === 'linked') {
                        $q = mysqli_query($connection, "
                SELECT l.*, s.sub_control_policy_number, s.sub_control_policy_heading, p.policy_clause, p.policy_name 
                FROM linked_control_policy l
                JOIN sub_control_policy s ON s.sub_control_policy_id = l.sub_control_policy_id
                JOIN policy p ON p.policy_id = s.main_control_policy_id
                WHERE l.linked_control_policy_id = $id
            ");
                        if ($r = mysqli_fetch_assoc($q)) {
                            $display_text = $r['policy_clause'] . " " . $r['policy_name'] . " > " .
                                $r['sub_control_policy_number'] . " " . $r['sub_control_policy_heading'] . " > " .
                                $r['linked_control_policy_number'] . " - " . $r['linked_control_policy_heading'];
                        }
                    } elseif ($type === 'inner') {
                        $q = mysqli_query($connection, "
                SELECT i.*, l.linked_control_policy_number, l.linked_control_policy_heading,
                       s.sub_control_policy_number, s.sub_control_policy_heading,
                       p.policy_clause, p.policy_name
                FROM inner_linked_control_policy i
                JOIN linked_control_policy l ON l.linked_control_policy_id = i.linked_control_policy_id
                JOIN sub_control_policy s ON s.sub_control_policy_id = l.sub_control_policy_id
                JOIN policy p ON p.policy_id = s.main_control_policy_id
                WHERE i.inner_linked_control_policy_id = $id
            ");
                        if ($r = mysqli_fetch_assoc($q)) {
                            $display_text = $r['policy_clause'] . " " . $r['policy_name'] . " > " .
                                $r['sub_control_policy_number'] . " " . $r['sub_control_policy_heading'] . " > " .
                                $r['linked_control_policy_number'] . " " . $r['linked_control_policy_heading'] . " > " .
                                $r['inner_linked_control_policy_number'] . " " . $r['inner_linked_control_policy_heading'];
                        }
                    }

                    if (!empty($display_text)) {
                        echo "<li>" . htmlspecialchars($display_text) . "</li>";
                    }
                }

                echo "</ul>";
            } else {
                echo "<p style='font-size: 12px !important;' class='text-muted'>No controls/policies linked to this risk yet.</p>";
            }
            ?>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>