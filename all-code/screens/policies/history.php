<div class="policy-txt-editor" style="background-color: margin-left: 5px; width: 70% !important;">
    <h6>Updates</h6> <!-- Fixed closing tag and changed <p> to <h6> -->

    <?php
    // Correct query: use single quotes for the value, no backticks
    $fetch_history = "SELECT * FROM control_history WHERE ctrl_h_pol_id = '$control_id'";
    $fetch_history_r = mysqli_query($connection, $fetch_history);

    if ($fetch_history_r) {
        $history_count = mysqli_num_rows($fetch_history_r);

        if ($history_count > 0) {
    ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="font-size: 12px !important;">Previous Details</th>
                            <th style="font-size: 12px !important;">Previously Updated By</th>
                            <th style="font-size: 12px !important;">Previously Assigned To</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($fetch_history_r)) { ?>
                            <tr>
                                <td style="font-size: 12px;">
                                    <a href="control_previous_details.php?id=<?php echo $row['ctrl_h_id']; ?>" target="_blank">Details added</a>
                                </td>
                                <td style="font-size: 12px;"><?php echo htmlspecialchars($row['ctrl_h_updated_by']) ?></td>
                                <td style="font-size: 12px;"><?php echo htmlspecialchars($row['ctrl_h_assigned_to_old']) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>
    <?php
        } else {
            echo "<p style='font-size:12px;'>No history available.</p>";
        }
    } else {
        // Optional debug info
        echo "<p class='text-danger'>Error fetching history: " . mysqli_error($connection) . "</p>";
    }
    ?>

</div>