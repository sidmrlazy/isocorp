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
        <!-- ============== LEFT SECTION ============== -->
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

        <!-- ============== TABS FOR SIMs AND POLICIES ============== -->
        <div style="background-color: #fff; width: 50% !important; margin: 5px; padding: 20px; border-radius: 10px;">
            <?php
            $risks_id = intval($risk['risks_id']);
            $fetch_mappings_query = "SELECT * FROM risk_policies WHERE risks_id = $risks_id";
            $fetch_mappings_result = mysqli_query($connection, $fetch_mappings_query);
            ?>

            <ul class="nav nav-tabs" id="riskTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button style="color: #000;" class="nav-link active" id="policies-tab" data-bs-toggle="tab" data-bs-target="#policies" type="button" role="tab">Linked Policies</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button style="color: #000;" class="nav-link" id="sims-tab" data-bs-toggle="tab" data-bs-target="#sims" type="button" role="tab">Linked Security Incidents</button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="riskTabContent">
                <!-- ============== POLICIES TAB ============== -->
                <div class="tab-pane fade show active" id="policies" role="tabpanel">

                    <!-- =============== ADD POLICY BUTTON =============== -->
                    <div style="margin-bottom: 10px; display: flex; justify-content: flex-end;">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#assignPolicyModal" style="font-size: 12px !important;" class="btn btn-sm btn-outline-success">Add Policy</button>
                    </div>



                    <?php
                    mysqli_data_seek($fetch_mappings_result, 0);
                    $policies = [];

                    while ($mapping = mysqli_fetch_assoc($fetch_mappings_result)) {
                        $type = $mapping['clause_type'];
                        $id = intval($mapping['clause_id']);
                        $display_text = "";

                        if ($type === 'policy') {
                            $q = mysqli_query($connection, "SELECT policy_clause, policy_name FROM policy WHERE policy_id = $id");
                            if ($r = mysqli_fetch_assoc($q)) {
                                $display_text = "{$r['policy_clause']} {$r['policy_name']}";
                            }
                        } elseif ($type === 'sub_control_policy') {
                            $q = mysqli_query($connection, "SELECT s.sub_control_policy_number, s.sub_control_policy_heading, p.policy_clause, p.policy_name FROM sub_control_policy s JOIN policy p ON p.policy_id = s.main_control_policy_id WHERE s.sub_control_policy_id = $id");
                            if ($r = mysqli_fetch_assoc($q)) {
                                $display_text = "{$r['policy_clause']} {$r['policy_name']} > {$r['sub_control_policy_number']} {$r['sub_control_policy_heading']}";
                            }
                        } elseif ($type === 'linked_control_policy') {
                            $q = mysqli_query($connection, "SELECT l.linked_control_policy_number, l.linked_control_policy_heading, s.sub_control_policy_number, s.sub_control_policy_heading, p.policy_clause, p.policy_name FROM linked_control_policy l JOIN sub_control_policy s ON s.sub_control_policy_id = l.sub_control_policy_id JOIN policy p ON p.policy_id = s.main_control_policy_id WHERE l.linked_control_policy_id = $id");
                            if ($r = mysqli_fetch_assoc($q)) {
                                $display_text = "{$r['policy_clause']} {$r['policy_name']} > {$r['sub_control_policy_number']} {$r['sub_control_policy_heading']} > {$r['linked_control_policy_number']} - {$r['linked_control_policy_heading']}";
                            }
                        } elseif ($type === 'inner_linked_control_policy') {
                            $q = mysqli_query($connection, "SELECT i.inner_linked_control_policy_number, i.inner_linked_control_policy_heading, l.linked_control_policy_number, l.linked_control_policy_heading, s.sub_control_policy_number, s.sub_control_policy_heading, p.policy_clause, p.policy_name FROM inner_linked_control_policy i JOIN linked_control_policy l ON l.linked_control_policy_id = i.linked_control_policy_id JOIN sub_control_policy s ON s.sub_control_policy_id = l.sub_control_policy_id JOIN policy p ON p.policy_id = s.main_control_policy_id WHERE i.inner_linked_control_policy_id = $id");
                            if ($r = mysqli_fetch_assoc($q)) {
                                $display_text = "{$r['policy_clause']} {$r['policy_name']} > {$r['sub_control_policy_number']} {$r['sub_control_policy_heading']} > {$r['linked_control_policy_number']} {$r['linked_control_policy_heading']} > {$r['inner_linked_control_policy_number']} {$r['inner_linked_control_policy_heading']}";
                            }
                        }

                        if (!empty($display_text)) {
                            $policies[] = htmlspecialchars($display_text);
                        }
                    }
                    ?>

                    <?php if (!empty($policies)): ?>
                        <ul style="font-size: 12px !important;">
                            <?php foreach ($policies as $text): ?>
                                <li><?= $text ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="font-size: 12px !important;" class="text-muted">No policies linked to this risk.</p>
                    <?php endif; ?>

                    <!-- =============== ADD POLICY MODAL =============== -->
                    <div class="modal fade" id="assignPolicyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl">
                            <?php
                            if (isset($_POST['connect_risk'])) {
                                $risk_id = $_POST['risk_id'];  // You might need to capture the risk ID too if it's part of the form
                                $selected_policies = $_POST['assign_policies'];  // Policies assigned from the modal

                                foreach ($selected_policies as $policy) {
                                    // Extract the policy type and ID from the format: "type:id"
                                    list($type, $id) = explode(':', $policy);

                                    // Insert the policy assignment into the risk_policies table
                                    $query = "INSERT INTO risk_policies (risks_id, clause_id, clause_type) VALUES ($risk_id, $id, '$type')";
                                    mysqli_query($connection, $query);
                                }

                                echo "<div class='alert alert-success mt-2'>Policies successfully linked to the risk.</div>";
                            }
                            ?>

                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Assign Policies</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <?php
                                if (isset($_POST['connect_risk'])) {
                                    $risk_id = intval($_POST['risk_id']);
                                    $selected_policies = $_POST['assign_policies'] ?? [];

                                    foreach ($selected_policies as $policy) {
                                        list($type, $id) = explode(':', $policy);
                                        $id = intval($id);
                                        $type = mysqli_real_escape_string($connection, $type);

                                        // Prevent duplicates (optional, recommended)
                                        $exists = mysqli_query($connection, "SELECT 1 FROM risk_policies WHERE risks_id = $risk_id AND clause_id = $id AND clause_type = '$type'");
                                        if (mysqli_num_rows($exists) === 0) {
                                            $query = "INSERT INTO risk_policies (risks_id, clause_id, clause_type) VALUES ($risk_id, $id, '$type')";
                                            mysqli_query($connection, $query);
                                        }
                                    }

                                    echo "<div class='alert alert-success mt-2'>Policies successfully linked to the risk.</div>";
                                }

                                // Fetch all available policies for assignment
                                $available_policies = [];
                                $all_policies_query = "SELECT 'policy' AS type, policy_id AS id, CONCAT(policy_clause, ' ', policy_name) AS name FROM policy
                                UNION SELECT 'sub_control_policy', sub_control_policy_id, CONCAT(p.policy_clause, ' ', p.policy_name, ' > ', s.sub_control_policy_number, ' ', s.sub_control_policy_heading) FROM sub_control_policy s
                                JOIN policy p ON p.policy_id = s.main_control_policy_id
                                UNION
                                SELECT 'linked_control_policy', l.linked_control_policy_id,
                                CONCAT(p.policy_clause, ' ', p.policy_name, ' > ', s.sub_control_policy_number, ' ', s.sub_control_policy_heading, ' > ', l.linked_control_policy_number, ' ', l.linked_control_policy_heading)
                                FROM linked_control_policy l
                                JOIN sub_control_policy s ON s.sub_control_policy_id = l.sub_control_policy_id
                                JOIN policy p ON p.policy_id = s.main_control_policy_id
                                UNION
                                SELECT 'inner_linked_control_policy', i.inner_linked_control_policy_id,
                                CONCAT(p.policy_clause, ' ', p.policy_name, ' > ', s.sub_control_policy_number, ' ', s.sub_control_policy_heading, ' > ', l.linked_control_policy_number, ' ', l.linked_control_policy_heading, ' > ', i.inner_linked_control_policy_number, ' ', i.inner_linked_control_policy_heading)
                                FROM inner_linked_control_policy i
                                JOIN linked_control_policy l ON l.linked_control_policy_id = i.linked_control_policy_id
                                JOIN sub_control_policy s ON s.sub_control_policy_id = l.sub_control_policy_id
                                JOIN policy p ON p.policy_id = s.main_control_policy_id
                            ";
                                $res = mysqli_query($connection, $all_policies_query);
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $available_policies[] = $row;
                                }
                                ?>
                                <div class="modal-body">
                                    <input type="hidden" name="risk_id" value="<?= $risks_id ?>">

                                    <label style="font-size: 12px;" for="exampleInputEmail1" class="form-label">Email address</label>
                                    <select style="font-size: 12px;" name="assign_policies[]" id="policy_select" class="form-select" multiple size="10">
                                        <?php foreach ($available_policies as $policy): ?>
                                            <option style="border-bottom: 1px solid #e7e7e7; padding-bottom: 10px; margin-top: 5px" value="<?= $policy['type'] . ':' . $policy['id'] ?>">
                                                <?= htmlspecialchars($policy['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="connect_risk" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>


                <!-- ============= SIMs TAB ============= -->
                <div class="tab-pane fade" id="sims" role="tabpanel">
                    <?php
                    mysqli_data_seek($fetch_mappings_result, 0);
                    $has_sims = false; ?>
                    <ul style='font-size: 12px !important;'>
                        <?php while ($mapping = mysqli_fetch_assoc($fetch_mappings_result)) {
                            if ($mapping['clause_type'] === 'sim') {
                                $sim_id = (int)$mapping['clause_id']; // Cast to int for safety
                                $q = mysqli_query($connection, "SELECT sim_id, sim_topic FROM sim WHERE sim_id = $sim_id");

                                if ($r = mysqli_fetch_assoc($q)) {
                                    $has_sims = true;
                                    echo "<li>" . htmlspecialchars($r['sim_id']) . ". " . htmlspecialchars($r['sim_topic']) . "</li>";
                                }
                            }
                        }

                        echo "</ul>";
                        if (!$has_sims) {
                            echo "<p style='font-size: 12px !important;' class='text-muted'>No SIMs linked to this risk.</p>";
                        }
                        ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>