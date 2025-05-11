<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
include 'includes/config.php';

$risks = $connection->query("SELECT risks_likelihood, risks_impact FROM risks");

$heatmap = [];
$likelihood_levels = ['Very Low', 'Low', 'Medium', 'High', 'Very High'];
$impact_levels = ['Insignificant', 'Minor', 'Moderate', 'Major', 'Severe'];

foreach ($impact_levels as $impact) {
    foreach ($likelihood_levels as $likelihood) {
        $heatmap[$impact][$likelihood] = 0;
    }
}

while ($row = $risks->fetch_assoc()) {
    $heatmap[$row['risks_impact']][$row['risks_likelihood']]++;
}

$color_map = [
    'Very Low' => [
        'Insignificant' => '#42AC94',
        'Minor' => '#42AC94',
        'Moderate' => '#FEBD02',
        'Major' => '#FEBD02',
        'Severe' => '#EA5265'
    ],
    'Low' => [
        'Insignificant' => '#42AC94',
        'Minor' => '#42AC94',
        'Moderate' => '#FEBD02',
        'Major' => '#EA5265',
        'Severe' => '#EA5265'
    ],
    'Medium' => [
        'Insignificant' => '#42AC94',
        'Minor' => '#FEBD02',
        'Moderate' => '#FEBD02',
        'Major' => '#EA5265',
        'Severe' => '#494854'
    ],
    'High' => [
        'Insignificant' => '#FEBD02',
        'Minor' => '#FEBD02',
        'Moderate' => '#EA5265',
        'Major' => '#494854',
        'Severe' => '#494854'
    ],
    'Very High' => [
        'Insignificant' => '#FEBD02',
        'Minor' => '#EA5265',
        'Moderate' => '#EA5265',
        'Major' => '#494854',
        'Severe' => '#494854'
    ],
];

?>

<div class="dashboard-container">
    <div class="screen-name-container mb-3">
        <h1>Risks & Treatments</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Risks & Treatments</h2>
    </div>
    <?php if (isset($_GET['add'])): ?>
        <div id="alertBox" class="alert alert-<?= $_GET['add'] == 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
            <?= $_GET['add'] == 'success' ? 'Risk added successfully.' : 'Error adding risk.' ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="risk-treatment-divider mb-5">
        <!-- =========== HEATMAP =========== -->
        <div class="table-container" style="flex: 1; margin: 5px;">
            <table class="table table-bordered text-center heatmap-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="font-size: 12px;">Impact ↓ <br> Likelihood →</th>
                        <?php foreach ($likelihood_levels as $level) : ?>
                            <th style="font-size: 12px;"><?= $level ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_reverse($impact_levels) as $impact) : ?>
                        <tr>
                            <th style="font-size: 12px;"><?= $impact ?></th>
                            <?php foreach ($likelihood_levels as $likelihood) :
                                $count = $heatmap[$impact][$likelihood];
                                $color = $color_map[$likelihood][$impact];
                            ?>
                                <td style="background-color: <?= $color ?>; color: black; text-align: center; vertical-align: middle;">
                                    <?php if ($count > 0) : ?>
                                        <div style="display: inline-flex; align-items: center; justify-content: center; 
                    width: 30px; height: 30px; border-radius: 50%; background-color: #fff;">
                                            <?= $count ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- =========== RISKS DETAILS =========== -->
        <div class="table-responsive table-container" style="margin: 5px; flex: 2; height: 500px; overflow-y: auto; border: 1px solid #ccc;">
            <?php if ($user_role === '1') { ?>
                <button class="btn btn-sm btn-outline-success mb-3" data-bs-toggle="modal" data-bs-target="#addRiskModal">Add Risk/Threat</button>
            <?php } ?>

            <!-- =========== SEARCH =========== -->
            <div class="form-floating mb-3">
                <input type="text" class="form-control" style="font-size: 12px;" id="searchRiskInput" placeholder="name@example.com">
                <label style="font-size: 12px;" for="floatingInput">Search Risks & Treatments</label>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="risk-details-headers">
                        <th style="font-size: 12px !important; font-weight: 600 !important;">#</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;">Risk/Threat</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;">Likelihood</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;">Impact</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;">Action</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;">Review Date</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;">Assigned to</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;">Status</th>
                        <!-- <th style="font-size: 12px !important; font-weight: 600 !important;">Created Date</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;">Updated On</th> -->
                        <?php if ($user_role === '1') { ?>
                            <th style="font-size: 12px !important; font-weight: 600 !important;">Edit</th>
                            <th style="font-size: 12px !important; font-weight: 600 !important;">Delete</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $risks = $connection->query("SELECT * FROM risks ORDER BY risks_created_at DESC");
                    while ($row = $risks->fetch_assoc()):
                    ?>
                        <tr class="risk-details-content">
                            <td><?= $row['risks_id'] ?></td>
                            <td>
                                <a href="risks-details.php?id=<?= $row['risks_id'] ?>" class="text-primary">
                                    <?= htmlspecialchars($row['risks_name']) ?>
                                </a>
                            </td>

                            <!-- <td><?= htmlspecialchars($row['risks_description']) ?></td> -->
                            <td><?= $row['risks_likelihood'] ?></td>
                            <td><?= $row['risks_impact'] ?></td>
                            <td><?= $row['risks_action'] ?></td>
                            <td><?= $row['risks_review_date'] ?></td>
                            <td><?= $row['risks_assigned_to'] ?></td>
                            <td><?= $row['risks_status'] ?></td>
                            <!-- <td><?= $row['risks_created_at'] ?></td>
                            <td ><?= $row['risks_updated_at'] ?></td> -->
                            <?php
                            if ($user_role === '1') {
                            ?>
                                <td>
                                    <a href="edit-risk.php?id=<?= $row['risks_id'] ?>" class="btn btn-sm btn-warning">
                                        <ion-icon name="create-outline"></ion-icon>
                                    </a>
                                </td>

                                <td>
                                    <a href="delete-risk.php?id=<?= $row['risks_id'] ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this risk? This action cannot be undone.');">
                                        <ion-icon name="trash"></ion-icon>
                                    </a>
                                </td>
                            <?php } ?>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ====== Add Risk Modal ====== -->
<div class="modal fade" id="addRiskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Risk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="add-risk.php">
                    <div class="mb-3">
                        <label>Risk Name</label>
                        <input type="text" name="risk_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Likelihood</label>
                        <select name="likelihood" class="form-control" required>
                            <option>Very Low</option>
                            <option>Low</option>
                            <option>Medium</option>
                            <option>High</option>
                            <option>Very High</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Impact</label>
                        <select name="impact" class="form-control" required>
                            <option>Insignificant</option>
                            <option>Minor</option>
                            <option>Moderate</option>
                            <option>Major</option>
                            <option>Severe</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Action</label>
                        <select name="action" class="form-control" required>
                            <option>Terminate</option>
                            <option>Combination of actions</option>
                            <option>Tolerate: Residual risk</option>
                            <option>Transfer</option>
                            <option>Treat (Other)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Review Date</label>
                        <input type="date" name="review_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Assigned to</label>
                        <select name="assigned_to" class="form-control" required>
                            <?php
                            $get_user = "SELECT * FROM `user`";
                            $get_user_r = mysqli_query($connection, $get_user);
                            if ($get_user_r && mysqli_num_rows($get_user_r) > 0) {
                                while ($row = mysqli_fetch_assoc($get_user_r)) {
                                    $fetched_user_name = $row['isms_user_name'];
                                    echo "<option value='" . htmlspecialchars($fetched_user_name) . "'>$fetched_user_name</option>";
                                }
                            } else {
                                echo "<option disabled>No users found</option>";
                            }
                            ?>
                        </select>

                    </div>
                    <div class="mb-3">
                        <label>Added By</label>
                        <input type="text" name="added_by" class="form-control" value="<?= $user_name; ?>" readonly>
                    </div>
                    <button type="submit" name="add_risk" class="btn btn-primary">Add Risk</button>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchRiskInput");
        const rows = document.querySelectorAll(".risk-details-content");

        searchInput.addEventListener("keyup", function() {
            const searchTerm = this.value.toLowerCase();

            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    });
</script>

<?php include 'includes/footer.php' ?>