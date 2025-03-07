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
        'Insignificant' => '#55cf4a',
        'Minor' => '#55cf4a',
        'Moderate' => '#f5cb22',
        'Major' => '#f5cb22',
        'Severe' => '#f55858'
    ],
    'Low' => [
        'Insignificant' => '#55cf4a',
        'Minor' => '#55cf4a',
        'Moderate' => '#f5cb22',
        'Major' => '#f55858',
        'Severe' => '#f55858'
    ],
    'Medium' => [
        'Insignificant' => '#55cf4a',
        'Minor' => '#f5cb22',
        'Moderate' => '#f5cb22',
        'Major' => '#f55858',
        'Severe' => '#000000'
    ],
    'High' => [
        'Insignificant' => '#f5cb22',
        'Minor' => '#f5cb22',
        'Moderate' => '#f55858',
        'Major' => '#000000',
        'Severe' => '#000000'
    ],
    'Very High' => [
        'Insignificant' => '#f5cb22',
        'Minor' => '#f55858',
        'Moderate' => '#f55858',
        'Major' => '#000000',
        'Severe' => '#000000'
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

    <div class="risk-treatment-divider">
        <!-- =========== HEATMAP =========== -->
        <div class="table-container" style="flex: 1; margin: 5px;">
            <table class="table table-bordered text-center heatmap-table">
                <thead>
                    <tr>
                        <th rowspan="2">Impact ↓ <br> Likelihood →</th>
                        <?php foreach ($likelihood_levels as $level) : ?>
                            <th><?= $level ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_reverse($impact_levels) as $impact) : ?>
                        <tr>
                            <th><?= $impact ?></th>
                            <?php foreach ($likelihood_levels as $likelihood) :
                                $count = $heatmap[$impact][$likelihood];
                                $color = $color_map[$likelihood][$impact];
                            ?>
                                <td style="background-color: <?= $color ?>; color: white;">
                                    <?= $count > 0 ? $count : '' ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>            
        </div>

        <!-- =========== RISKS DETAILS =========== -->
        <div class="table-responsive table-container" style="margin: 5px; flex: 1;">
            <?php if($user_role === '1') { ?>
            <button class="btn btn-sm btn-outline-success mb-3" data-bs-toggle="modal" data-bs-target="#addRiskModal">Add Risk/Threat</button>
            <?php } ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="risk-details-headers">
                        <th>Risk/Threat</th>
                        <th>Likelihood</th>
                        <th>Impact</th>
                        <th>Status</th>
                        <?php if ($user_role === '1') { ?>
                            <th>Edit</th>
                            <th>Delete</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $risks = $connection->query("SELECT * FROM risks ORDER BY risks_created_at DESC");
                    while ($row = $risks->fetch_assoc()):
                    ?>
                        <tr class="risk-details-content">
                            <td>
                                <a href="risks-details.php?id=<?= $row['risks_id'] ?>" class="text-primary">
                                    <?= htmlspecialchars($row['risks_name']) ?>
                                </a>
                            </td>

                            <!-- <td><?= htmlspecialchars($row['risks_description']) ?></td> -->
                            <td><?= $row['risks_likelihood'] ?></td>
                            <td><?= $row['risks_impact'] ?></td>
                            <td><?= $row['risks_status'] ?></td>
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
    <div class="modal-dialog">
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
                        <label>Added By</label>
                        <input type="text" name="added_by" class="form-control" value="<?= $user_name; ?>" readonly>
                    </div>
                    <button type="submit" name="add_risk" class="btn btn-primary">Add Risk</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php' ?>