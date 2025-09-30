<?php
// ---- includes (match your project structure) ----
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';

// normalize DB handle if needed
if (!isset($mysqli) && isset($connection)) {
    $mysqli = $connection;
}

// ---- validate id ----
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Invalid Opportunity ID.</div></div>";
    include 'includes/footer.php';
    exit;
}
$opp_id = (int)$_GET['id'];

// ---- fetch opportunity ----
$opportunity = null;
$sql = "SELECT opp_id, opp_title, opp_desc, opp_category, opp_ben, opp_owner, opp_status, opp_created_on, opp_review_date
        FROM opportunities WHERE opp_id = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $opp_id);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        $opportunity = $res->fetch_assoc();
    }
    $stmt->close();
}

?>
<div class="dashboard-container my-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- <div>
            <h2 class="mb-0" style="font-size:20px;">Opportunity Details</h2>
            <small><a href="index.php">Home</a> > <a href="opportunities.php">Opportunities & Treatments</a> > Details</small>
        </div> -->

        <div class="screen-name-container mb-3">
            <h1>Opportunity Details </h1>
            <h2><a href="index.php">Home</a> > Opportunity Details</h2>
        </div>

        <div>
            <a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary" style="font-size:12px !important;">Back</a>
        </div>
    </div>

    <?php if (!$opportunity) { ?>
        <div class="alert alert-warning">No details found for this Opportunity.</div>
    <?php } else { ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th style="width:220px; font-size:12px !important;">Opportunity ID</th>
                            <td style="font-size:12px !important;"><?php echo htmlspecialchars($opportunity['opp_id']); ?></td>
                        </tr>
                        <tr>
                            <th style="font-size:12px !important;">Title</th>
                            <td style="font-size:12px !important;"><?php echo htmlspecialchars($opportunity['opp_title']); ?></td>
                        </tr>
                        <tr>
                            <th style="font-size:12px !important;">Category</th>
                            <td style="font-size:12px !important;"><?php echo htmlspecialchars($opportunity['opp_category']); ?></td>
                        </tr>
                        <tr>
                            <th style="font-size:12px !important;">Expected Benefit</th>
                            <td style="font-size:12px !important;"><?php echo htmlspecialchars($opportunity['opp_ben']); ?></td>
                        </tr>
                        <tr>
                            <th style="font-size:12px !important;">Owner</th>
                            <td style="font-size:12px !important;"><?php echo htmlspecialchars($opportunity['opp_owner']); ?></td>
                        </tr>
                        <tr>
                            <th style="font-size:12px !important;">Status</th>
                            <td style="font-size:12px !important;"><?php echo htmlspecialchars($opportunity['opp_status']); ?></td>
                        </tr>
                        <tr>
                            <th style="font-size:12px !important;">Date Identified</th>
                            <td style="font-size:12px !important;"><?php echo htmlspecialchars($opportunity['opp_created_on']); ?></td>
                        </tr>
                        <tr>
                            <th style="font-size:12px !important;">Review Date</th>
                            <td style="font-size:12px !important;"><?php echo htmlspecialchars($opportunity['opp_review_date']); ?></td>
                        </tr>
                        <tr>
                            <th style="font-size:12px !important;">Description</th>
                            <td style="font-size:12px !important;">
                                <?php
                                // If your editor stores HTML, render as-is; else escape:
                                // echo nl2br(htmlspecialchars($opportunity['opp_desc']));
                                echo $opportunity['opp_desc']; // assumes sanitized editor HTML
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
</div>

<?php include 'includes/footer.php'; ?>