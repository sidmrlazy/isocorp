<div class="policy-txt-editor" style="margin-left: 5px; width: 70% !important;">
    <h6>Updates</h6>
    <?php
    include 'includes/config.php';
    // Correct query: use single quotes for the value, no backticks
    $fetch_history = "SELECT * FROM control_history WHERE ctrl_h_pol_id = '$fetched_control_id'";
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
                            <th style="font-size: 12px !important;">Updated On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($fetch_history_r)) { ?>
                            <tr>
                                <td style="font-size: 12px;">
                                    <a href="control_previous_details.php?id=<?php echo $row['ctrl_h_id']; ?>" target="_blank">Details added</a>
                                </td>
                                <td style="font-size: 12px;"><?php echo htmlspecialchars($row['ctrl_update_date']) ?></td>
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
        echo "<p class='text-danger'>Error fetching history: " . mysqli_error($connection) . "</p>";
    }
    ?>

    <?php include 'screens/policies/risks-treatments.php'; ?>
    <?php include 'screens/policies/document-section.php'; ?>



    <!-- ============== COMMENTS ============== -->
    <div class="mt-3">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h6>Comments</h6>
            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#commentModal">+</button>
        </div>

        <?php
        $fetch_comment = "SELECT * FROM control_comment WHERE control_com_parent_id = '$fetched_control_id'";
        $fetch_comment_r = mysqli_query($connection, $fetch_comment);
        $fetch_comment_count = mysqli_num_rows($fetch_comment_r);
        if ($fetch_comment_count > 0) {
            while ($row = mysqli_fetch_assoc($fetch_comment_r)) {
                $control_com_by = $row['control_com_by'];
                $control_com_date = $row['control_com_date'];
                $control_com = $row['control_com'];
        ?>
                <div style="margin-top: 10px; border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <p style="margin: 0; font-size: 12px !important; font-weight: 600 !important"><?php echo $control_com_by ?></p>
                        <p style="margin: 0; font-size: 12px !important;"><?php echo $control_com_date ?></p>
                    </div>
                    <p style="font-size: 14px !important; margin: 0 !important;"><?php echo $control_com ?></p>
                </div>
        <?php }
        } else {
            echo "<p style='font-size:12px;'>No comments found.</p>";
        } ?>

        <!-- ============== MODAL ============== -->
        <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <?php
                if (isset($_POST['add-comment'])) {
                    $control_com_parent_id = mysqli_real_escape_string($connection, $_POST['control_com_parent_id']);
                    $control_com_by = mysqli_real_escape_string($connection, $_POST['control_com_by']);
                    date_default_timezone_set('Asia/Kolkata');
                    $control_com_date = date('m-d-Y H:i:s');
                    $control_com = mysqli_real_escape_string($connection, $_POST['control_com']);

                    $insert_comment = "INSERT INTO control_comment (
                        control_com_parent_id, 
                        control_com_by, 
                        control_com_date, 
                        control_com
                    ) VALUES (
                        '$control_com_parent_id', 
                        '$control_com_by', 
                        '$control_com_date', 
                        '$control_com'
                    )";
                    $insert_comment_r = mysqli_query($connection, $insert_comment);
                }
                ?>
                <form action="" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Comment</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="control_com_parent_id" value="<?php echo $fetched_control_id ?>" hidden>
                        <input type="text" name="control_com_by" value="<?php echo $user_name ?>" hidden>
                        <div class="form-floating">
                            <textarea class="form-control" name="control_com" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                            <label for="floatingTextarea2">Comments</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add-comment" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>