<!-- ============== RISKS & TREATMENTS ============== -->
<div class="mt-3">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <h6>Risks & Treatments</h6>
        <button data-bs-toggle="modal" data-bs-target="#risksModal" type="button" class="btn btn-sm btn-outline-success">+</button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="font-size: 12px !important;">Risk/Threat</th>
                    <th style="font-size: 12px !important;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($fetch_history_r)) { ?>
                    <tr>
                        <td style="font-size: 12px;">
                            <a href="control_previous_details.php?id=<?php echo $row['ctrl_h_id']; ?>" target="_blank">Details added</a>
                        </td>
                        <td style="font-size: 12px;"><?php echo htmlspecialchars($row['ctrl_h_updated_by']) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>