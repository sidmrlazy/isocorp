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

    <a href="risks-treatments.php" class="btn btn-sm btn-secondary mb-2">Back to Risks</a>

    <div class="row mb-5">
        <!-- ============== LEFT SECTION ============== -->
        <div class="col-md-6">

            <!-- ============== GRAPH SECTION ============== -->
            <div class="card p-3 mb-3">
                <?php
                if ($risk_id > 0) {
                    // Fetch current risk data
                    $risk_query = $connection->query("SELECT * FROM risks WHERE risks_id = $risk_id");
                    $risk_data = $risk_query->fetch_assoc();

                    // Fetch historical versions ordered by date
                    $history_query = $connection->query("SELECT risks_likelihood, risks_impact, updated_at FROM risk_versions WHERE risk_id = $risk_id ORDER BY updated_at ASC");
                    $history_data = [];
                    while ($row = $history_query->fetch_assoc()) {
                        $history_data[] = $row;
                    }
                }
                ?>

                <?php if (!empty($risk_data)) : ?>
                    <!-- <h5 class="mb-3">Risk Analysis Chart</h5> -->
                    <canvas id="riskChart" style="width: 100%; height: 250px !important;"></canvas>
                <?php else: ?>
                    <p>No risk data available.</p>
                <?php endif; ?>

                <?php if (!empty($risk_data)) : ?>
                    <script>
                        const likelihood_map = {
                            'Very Low': 1,
                            'Low': 2,
                            'Medium': 3,
                            'High': 4,
                            'Very High': 5
                        };
                        const impact_map = {
                            'Insignificant': 1,
                            'Minor': 2,
                            'Moderate': 3,
                            'Major': 4,
                            'Severe': 5
                        };

                        // History data from PHP (encoded safely)
                        const history = <?= json_encode($history_data) ?>;

                        const ctx = document.getElementById('riskChart').getContext('2d');

                        if (history.length > 0) {
                            const labels = history.map(item => new Date(item.updated_at).toLocaleDateString());
                            const likelihoodData = history.map(item => likelihood_map[item.risks_likelihood] || 0);
                            const impactData = history.map(item => impact_map[item.risks_impact] || 0);

                            const data = {
                                labels: labels,
                                datasets: [{
                                        label: 'Likelihood',
                                        data: likelihoodData,
                                        borderColor: '#6a946d',
                                        backgroundColor: '#6a946d',
                                        fill: false,
                                        tension: 0.1,
                                        pointRadius: 5,
                                        pointHoverRadius: 7,
                                    },
                                    {
                                        label: 'Impact',
                                        data: impactData,
                                        borderColor: '#b86a79',
                                        backgroundColor: '#b86a79',
                                        fill: false,
                                        tension: 0.1,
                                        pointRadius: 5,
                                        pointHoverRadius: 7,
                                    }
                                ]
                            };

                            const config = {
                                type: 'line',
                                data: data,
                                options: {
                                    plugins: {
                                        legend: {
                                            position: 'top',
                                            labels: {
                                                usePointStyle: true,
                                            }
                                        },
                                        title: {
                                            display: true,
                                            text: 'Risk Analysis Chart'
                                        },
                                        tooltip: {
                                            mode: 'index',
                                            intersect: false,
                                        }
                                    },
                                    scales: {
                                        y: {
                                            min: 1,
                                            max: 5,
                                            ticks: {
                                                stepSize: 1,
                                            },
                                            title: {
                                                display: true,
                                                text: 'Risk Level'
                                            }
                                        },
                                        x: {
                                            title: {
                                                display: true,
                                                text: 'Date'
                                            }
                                        }
                                    },
                                    interaction: {
                                        mode: 'nearest',
                                        axis: 'x',
                                        intersect: true,
                                    }
                                }
                            };

                            new Chart(ctx, config);

                        } else {
                            const likelihood = likelihood_map['<?= addslashes($risk_data['risks_likelihood']) ?>'] || 0;
                            const impact = impact_map['<?= addslashes($risk_data['risks_impact']) ?>'] || 0;

                            const data = {
                                labels: ['Likelihood', 'Impact'],
                                datasets: [{
                                    label: 'Risk Level',
                                    data: [likelihood, impact],
                                    borderColor: ['#6a946d', '#b86a79'],
                                    backgroundColor: ['#6a946d', '#b86a79'],
                                    fill: false,
                                    borderWidth: 2,
                                    tension: 0.4,
                                    pointRadius: 6,
                                    pointHoverRadius: 8,
                                }]
                            };

                            const config = {
                                type: 'line',
                                data: data,
                                options: {
                                    plugins: {
                                        legend: {
                                            display: false,
                                            labels: {
                                                usePointStyle: true,
                                            }
                                        },
                                        title: {
                                            display: true,
                                            text: 'Current Risk Level'
                                        },
                                        tooltip: {
                                            mode: 'nearest',
                                            intersect: true,
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            max: 5,
                                            ticks: {
                                                stepSize: 1,
                                            },
                                            title: {
                                                display: true,
                                                text: 'Risk Level'
                                            }
                                        },
                                        x: {
                                            title: {
                                                display: true,
                                                text: 'Category'
                                            }
                                        }
                                    }
                                }
                            };

                            new Chart(ctx, config);
                        }
                    </script>
                <?php endif; ?>
            </div>





            <!-- ============== RISK DETAILS SECTION ============== -->
            <div class="table-responsive card p-3">
                <table class="table table-bordered">
                    <tr>
                        <th style="font-size: 12px !important; width: 20% !important;">Risk Name</th>
                        <td style="font-size: 14px !important; font-weight: 600"><?= htmlspecialchars($risk['risks_name']) ?></td>
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

        </div>

        <!-- ============== TABS FOR SIMs AND POLICIES ============== -->
        <div class="col-md-6">
            <?php
            $risks_id = intval($risk['risks_id']);
            $fetch_mappings_query = "SELECT * FROM risk_policies WHERE risks_id = $risks_id";
            $fetch_mappings_result = mysqli_query($connection, $fetch_mappings_query);
            ?>
            <div class="card p-3">
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
                            <button type="button" data-bs-toggle="modal" data-bs-target="#assignPolicyModal" style="font-size: 12px !important;" class="btn btn-sm btn-outline-success">Assign Policy</button>
                        </div>

                        <?php
                        if (isset($_POST['remove_policy'])) {
                            $risk_id = intval($_POST['risk_id']);
                            $clause_id = intval($_POST['clause_id']);
                            $clause_type = mysqli_real_escape_string($connection, $_POST['clause_type']);

                            $delete_query = "DELETE FROM risk_policies WHERE risks_id = $risk_id AND clause_id = $clause_id AND clause_type = '$clause_type'";
                            mysqli_query($connection, $delete_query);

                            echo "<div class='alert alert-warning mt-2'>Policy unlinked from the risk.</div>";
                        }


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
                                $policies[] = [
                                    'text' => htmlspecialchars($display_text),
                                    'id' => $id,
                                    'type' => $type
                                ];
                            }
                        }
                        ?>

                        <?php if (!empty($policies)): ?>

                            <ul style="font-size: 12px !important; margin-top: 15px; padding-left: 20px;">
                                <?php foreach ($policies as $policy): ?>
                                    <?php
                                    // Determine correct query parameter based on type
                                    switch ($policy['type']) {
                                        case 'policy':
                                            $param = 'policy_id';
                                            break;
                                        // case 'sub_control_policy':
                                        //     $param = 'sub_policy_id';
                                        //     break;
                                        case 'linked_control_policy':
                                            $param = 'linked_policy_id';
                                            break;
                                        case 'inner_linked_control_policy':
                                            $param = 'inner_policy_id';
                                            break;
                                        default:
                                            $param = 'policy_id'; // fallback
                                    }
                                    $url = "policy-details.php?$param=" . $policy['id'];
                                    ?>
                                    <div style="margin-bottom: 5px; padding: 5px; border-bottom: 1px solid #e7e7e7;">
                                        <li style="margin-bottom: 5px;">
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <span><?= $policy['text'] ?></span>
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <a href="<?= $url ?>" class="btn btn-sm btn-outline-success" style="font-size: 10px;">View</a>
                                                    <!-- <a href="<?= $url ?>" class="btn btn-sm btn-outline-danger" style="font-size: 10px;">Remove</a> -->

                                                    <form method="POST" style="display:inline-block; margin-left: 5px;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                        <input type="hidden" name="remove_policy" value="1">
                                                        <input type="hidden" name="clause_id" value="<?= $policy['id'] ?>">
                                                        <input type="hidden" name="clause_type" value="<?= $policy['type'] ?>">
                                                        <input type="hidden" name="risk_id" value="<?= $risks_id ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size: 10px;">Remove</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </li>
                                    </div>
                                <?php endforeach; ?>
                            </ul>

                        <?php else: ?>
                            <p style="font-size: 12px !important;" class="text-muted">No policies linked to this risk.</p>
                        <?php endif; ?>

                        <!-- =============== ADD POLICY MODAL =============== -->
                        <div class="modal fade" id="assignPolicyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl">

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

                                        // echo "<div class='alert alert-success mt-2'>Policies successfully linked to the risk.</div>";
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

                                        <!-- =========== SEARCH POLICY =========== -->
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="searchPolicyOptions" placeholder="Search Policies" onkeyup="filterPolicyOptions()" style="font-size: 12px;">
                                            <label for="searchPolicyOptions" style="font-size: 12px;">Search Policies</label>
                                        </div>


                                        <label style="font-size: 12px;" for="exampleInputEmail1" class="form-label">Select Policy/s</label>
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
                        <!-- =============== ADD SIM BUTTON =============== -->
                        <div style="margin-bottom: 10px; display: flex; justify-content: flex-end;">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#assignSimModal" style="font-size: 12px !important;" class="btn btn-sm btn-outline-success">Assign Security Incident</button>
                        </div>

                        <?php
                        mysqli_data_seek($fetch_mappings_result, 0);
                        $has_sims = false;
                        ?>
                        <ul style='font-size: 12px !important;'>
                            <?php while ($mapping = mysqli_fetch_assoc($fetch_mappings_result)) {
                                if ($mapping['clause_type'] === 'sim') {
                                    $sim_id = (int)$mapping['clause_id'];
                                    $q = mysqli_query($connection, "SELECT sim_id, sim_topic FROM sim WHERE sim_id = $sim_id");

                                    if ($r = mysqli_fetch_assoc($q)) {
                                        $has_sims = true;
                            ?>


                                        <div style="margin-bottom: 5px; padding: 5px; border-bottom: 1px solid #e7e7e7;">
                                            <li style="margin-bottom: 5px;">
                                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                                    <span><?php echo htmlspecialchars($r['sim_id']) . ". " . htmlspecialchars($r['sim_topic']) ?></span>
                                                    <div class="d-flex justify-content-end align-items-center">
                                                        <a style="font-size: 10px" class="btn btn-sm btn-outline-success" href="sim-details.php?id=<?php echo $r['sim_id'] ?>">View</a>
                                                        <form method="POST" action="" style="display:inline-block; margin-left: 5px;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                            <input type="hidden" name="remove_sim_id" value="<?= $r['sim_id'] ?>">
                                                            <input type="hidden" name="risk_id" value="<?= $risks_id ?>">
                                                            <button type="submit" name="remove_sim" class="btn btn-sm btn-outline-danger" style="font-size: 10px;">Remove</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </li>
                                        </div>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </ul>
                        <?php
                        if (!$has_sims) {
                            echo "<p style='font-size: 12px !important;' class='text-muted'>No SIMs linked to this risk.</p>";
                        }

                        if (isset($_POST['assign-sim'])) {
                            $risk_id = intval($_POST['risk_id']);
                            $selected_sims = $_POST['assign_sims'] ?? [];

                            foreach ($selected_sims as $sim_id) {
                                $sim_id = intval($sim_id);
                                $exists = mysqli_query($connection, "SELECT 1 FROM risk_policies WHERE risks_id = $risk_id AND clause_id = $sim_id AND clause_type = 'sim'");
                                if (mysqli_num_rows($exists) === 0) {
                                    mysqli_query($connection, "INSERT INTO risk_policies (risks_id, clause_id, clause_type) VALUES ($risk_id, $sim_id, 'sim')");
                                }
                            }

                            echo "<div style='font-size: 12px;' id='alertBox' class='alert alert-success mt-2'>SIMs successfully linked to the risk.</div>";
                        }

                        if (isset($_POST['remove_sim'])) {
                            $risk_id = intval($_POST['risk_id']);
                            $sim_id = intval($_POST['remove_sim_id']);
                            mysqli_query($connection, "DELETE FROM risk_policies WHERE risks_id = $risk_id AND clause_id = $sim_id AND clause_type = 'sim'");
                            echo "<div style='font-size: 12px;' id='alertBox' class='alert alert-warning mt-2'>SIM unlinked from this risk.</div>";
                        }
                        ?>

                        <!-- =============== ADD SIM MODAL =============== -->
                        <div class="modal fade" id="assignSimModal" tabindex="-1" aria-labelledby="assignSimModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                <form action="" method="POST" class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="assignSimModalLabel">Assign Security Incident</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <?php
                                    $available_sims = [];
                                    $sim_query = "SELECT * FROM sim";
                                    $sim_query_r = mysqli_query($connection, $sim_query);

                                    if (!$sim_query_r) {
                                        die("Query Failed: " . mysqli_error($connection));
                                    }

                                    while ($row = mysqli_fetch_assoc($sim_query_r)) {
                                        $available_sims[] = $row;
                                    }
                                    ?>


                                    <div class="modal-body">
                                        <input type="hidden" name="risk_id" value="<?= $risks_id ?>">

                                        <!-- =========== SEARCH POLICY =========== -->
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="searchSimOptions" placeholder="Search Policies" onkeyup="filterSimOptions()" style="font-size: 12px;">
                                            <label for="searchSimOptions" style="font-size: 12px;">Search Policies</label>
                                        </div>

                                        <label style="font-size: 12px;" for="sim_select" class="form-label">Select Incident</label>
                                        <select style="font-size: 12px;" name="assign_sims[]" id="sim_select" class="form-select" multiple size="10">
                                            <?php foreach ($available_sims as $sim): ?>
                                                <option style="border-bottom: 1px solid #e7e7e7; padding-bottom: 10px; margin-top: 5px" value="<?= $sim['sim_id'] ?>">
                                                    <?= htmlspecialchars($sim['sim_id'] . ' - ' . $sim['sim_topic']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="assign-sim" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function filterPolicyOptions() {
        const input = document.getElementById('searchPolicyOptions');
        const filter = input.value.toLowerCase();
        const select = document.getElementById('policy_select');
        const options = select.options;

        for (let i = 0; i < options.length; i++) {
            const txt = options[i].text.toLowerCase();
            options[i].style.display = txt.includes(filter) ? '' : 'none';
        }
    }

    function filterSimOptions() {
        const input = document.getElementById('searchSimOptions');
        const filter = input.value.toLowerCase();
        const select = document.getElementById('sim_select');
        const options = select.options;

        for (let i = 0; i < options.length; i++) {
            const txt = options[i].text.toLowerCase();
            options[i].style.display = txt.includes(filter) ? '' : 'none';
        }
    }
</script>



<?php include 'includes/footer.php'; ?>