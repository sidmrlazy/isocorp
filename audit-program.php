<?php
include 'includes/connection.php';
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="dashboard-container">
    <!-- ============ SCREEN NAME ============ -->
    <div class="screen-name-container">
        <h1>AUDIT PROGRAMME</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Audit Programme</h2>
    </div>

    <!-- ============ AUDIT PROGRAM TABLE ============ -->
    <?php
    // FETCH ALL PHASES JOINED WITH PROGRAM AND ACTIVITY NAMES
    $fetch_query = "SELECT 
    ap_phase.ap_phase_id,
    ap_phase.ap_ph_main_id,
    ap_main.ap_name,
    ap_act.ap_act_name,
    ap_phase.ap_ph_name,
    ap_phase.ap_ph_assigned_to,
    ap_phase.ap_ph_due_date,
    ap_phase.ap_ph_status
    FROM ap_phase 
    INNER JOIN ap_main ON ap_phase.ap_ph_main_id = ap_main.ap_id
    INNER JOIN ap_act ON ap_phase.ap_ph_act_id = ap_act.ap_act_id 
    ORDER BY ap_phase.ap_ph_main_id DESC";
    $result = mysqli_query($connection, $fetch_query);
    $serial = 1;
    $count = mysqli_num_rows($result);
    if ($count == 0) {
        echo '<div style="font-size: 12px" class="alert alert-danger mt-3 mb-5" role="alert"> No Audit Programme Found! </div>';
    } else {
    ?>
        <div class="mt-3 mb-5">
            <div class="table-responsive card p-4">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th style="font-size: 12px !important;" scope="col">#</th>
                            <th style="font-size: 12px !important;" scope="col">Program</th>
                            <th style="font-size: 12px !important;" scope="col">Phase</th>
                            <th style="font-size: 12px !important;" scope="col">Activity</th>
                            <th style="font-size: 12px !important;" scope="col">Assigned to</th>
                            <th style="font-size: 12px !important;" scope="col">Due Date</th>
                            <th style="font-size: 12px !important;" scope="col">Status</th>
                            <th style="font-size: 12px !important;" scope="col">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            $ph_id = $row['ap_phase_id']; // fixed
                            $program = $row['ap_name'];
                            $activity = $row['ap_act_name'];
                            $phase = $row['ap_ph_name'];
                            $assigned_to = $row['ap_ph_assigned_to'];
                            $due_date = $row['ap_ph_due_date'];
                            $status = $row['ap_ph_status'];
                        ?>
                            <tr>
                                <td style="font-size: 12px;"><?php echo $serial++; ?></td>
                                <td style="font-size: 12px;"><?php echo htmlspecialchars($program); ?></td>

                                <td style="font-size: 12px;"><?php echo htmlspecialchars($activity); ?></td>
                                <td style="font-size: 12px;"><?php echo htmlspecialchars($phase); ?></td>

                                <td style="font-size: 12px;"><?php echo htmlspecialchars($assigned_to); ?></td>
                                <td style="font-size: 12px;">
                                    <?php
                                    echo (!empty($due_date) && $due_date !== '0000-00-00')
                                        ? date('m-d-Y', strtotime($due_date))
                                        : '';
                                    ?>
                                </td>

                                <td style="font-size: 12px;"><?php echo htmlspecialchars($status); ?></td>
                                <td>
                                    <a href="audit-program-details.php?id=<?php echo $ph_id ?>" class="btn btn-sm btn-outline-success" style="font-size: 12px !important;">View Details</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
</div>
<?php include 'includes/footer.php' ?>