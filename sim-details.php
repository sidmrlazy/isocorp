<?php
include('includes/header.php');
include('includes/navbar.php');
include 'includes/config.php';
include 'includes/connection.php';
?>
<div class="dashboard-container">
    <?php
    if (isset($_GET['id'])) {
        $sim_id = intval($_GET['id']);
        $query = "SELECT * FROM sim WHERE sim_id = ?";
        $stmt = mysqli_prepare($connection, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $sim_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                $sim_id = htmlspecialchars($row['sim_id']);
                $sim_topic = htmlspecialchars($row['sim_topic']);
                $sim_details = htmlspecialchars($row['sim_details']);
                $sim_status = htmlspecialchars($row['sim_status']);
                $sim_severity = htmlspecialchars($row['sim_severity']);
                $sim_source = htmlspecialchars($row['sim_source']);
                $sim_type = htmlspecialchars($row['sim_type']);
                $sim_final = htmlspecialchars($row['sim_final']);
                $sim_reported_date = htmlspecialchars($row['sim_reported_date']);
            } else {
                echo "<p id='alertBox' class='alert alert-warning'>No record found.</p>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<p id='alertBox' class='alert alert-danger'>Database error: " . mysqli_error($connection) . "</p>";
        }
    }

    if (isset($_POST['update-sim-detail']) && isset($sim_id)) {
        $sim_details = trim($_POST['sim_details']);
        $update_query = "UPDATE sim SET sim_details = ? WHERE sim_id = ?";
        $stmt = mysqli_prepare($connection, $update_query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $sim_details, $sim_id);
            if (mysqli_stmt_execute($stmt)) {
                echo "<p id='alertBox' class='alert alert-success'>Details updated successfully!</p>";
            } else {
                echo "<p id='alertBox' class='alert alert-danger'>Error updating details: " . mysqli_error($connection) . "</p>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<p id='alertBox' class='alert alert-danger'>Failed to prepare statement.</p>";
        }
    }

    if (isset($_POST['update-sim-final']) && isset($sim_id)) {
        $sim_details = trim($_POST['sim_details']);
        $close_sim_query = "UPDATE sim SET sim_details = ?, sim_final = '2' WHERE sim_id = ?";
        $stmt = mysqli_prepare($connection, $close_sim_query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $sim_details, $sim_id);
            if (mysqli_stmt_execute($stmt)) {
                echo "<p id='alertBox' class='alert alert-success'>SIM finalized and closed successfully!</p>";
            } else {
                echo "<p id='alertBox' class='alert alert-danger'>Error finalizing SIM: " . mysqli_error($connection) . "</p>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<p id='alertBox' class='alert alert-danger'>Failed to prepare statement.</p>";
        }
    }

    // ========= COMMENT ENTRY =========
    if (isset($_POST['add-comment'])) {
        $sim_id = $_POST['sim_id'];
        $sim_comment = trim($_POST['sim_comment']);
        $sim_comment_by = $user_name;

        if (!empty($sim_comment)) {
            $insert_comment = "INSERT INTO sim_comment (sim_comment_fetched_id, sim_comment_details, sim_comment_by) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($connection, $insert_comment);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sss", $sim_id, $sim_comment, $sim_comment_by);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<p id='alertBox' class='alert alert-success'>Comment added successfully!</p>";
                } else {
                    echo "<p id='alertBox' class='alert alert-danger'>Error adding comment: " . mysqli_error($connection) . "</p>";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "<p id='alertBox' class='alert alert-danger'>Failed to prepare statement.</p>";
            }
        } else {
            echo "<p id='alertBox' class='alert alert-warning'>Comment cannot be empty!</p>";
        }
    }

    if (isset($_GET['id'])) {
        $sim_id = intval($_GET['id']);
        $query = "SELECT sim_comment_details, sim_comment_by, sim_comment_date FROM sim_comment WHERE sim_comment_fetched_id = ? ORDER BY sim_comment_date DESC";
        $stmt = mysqli_prepare($connection, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $sim_id);
            mysqli_stmt_execute($stmt);
            $comments_result = mysqli_stmt_get_result($stmt);
        } else {
            echo "<p id='alertBox' class='alert alert-danger'>Error fetching comments: " . mysqli_error($connection) . "</p>";
        }
    }
    ?>

    <div class="section-divider">
        <!-- <div class="comment-section-container m-1">
            <div class="heading-row">
                <p>Comments | Notes</p>
                <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">Create</button>
            </div>
            
            <div class="comment-tab-container">

                <div class="comment-tab">
                    <?php if (isset($comments_result) && mysqli_num_rows($comments_result) > 0) { ?>
                        <?php while ($comment_row = mysqli_fetch_assoc($comments_result)) { ?>
                            <p style="font-size: 12px; margin: 0;"><strong><?php echo htmlspecialchars($comment_row['sim_comment_by']); ?></strong> -
                                <small><?php echo date("d M Y, h:i A", strtotime($comment_row['sim_comment_date'])); ?></small>
                            </p>
                            <p style="margin: 0;"><?php echo nl2br(htmlspecialchars($comment_row['sim_comment_details'])); ?></p>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="text-muted">No comments available.</p>
                    <?php } ?>
                </div>
            </div>
            
            <div class="response-tab-container">

                <div class="response-tab">
                    <?php if (isset($comments_result) && mysqli_num_rows($comments_result) > 0) { ?>
                        <?php while ($comment_row = mysqli_fetch_assoc($comments_result)) { ?>
                            <p style="font-size: 12px; margin: 0;"><strong><?php echo htmlspecialchars($comment_row['sim_comment_by']); ?></strong> -
                                <small><?php echo date("d M Y, h:i A", strtotime($comment_row['sim_comment_date'])); ?></small>
                            </p>
                            <p style="margin: 0;"><?php echo nl2br(htmlspecialchars($comment_row['sim_comment_details'])); ?></p>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="text-muted">No comments available.</p>
                    <?php } ?>
                </div>
            </div>

        </div> -->
        <div class="WYSIWYG-editor-container m-1">
            <form action="" method="POST">
                <div class="WYSIWYG-editor">
                    <?php if ($sim_final == '2') { ?>
                        <p><?php echo !empty($sim_details) ? htmlspecialchars_decode($sim_details)  : 'No details available.'; ?></p>
                    <?php } else { ?>
                        <textarea id="editorNew" name="sim_details"><?php echo $sim_details; ?></textarea>
                    <?php } ?>
                </div>

                <?php if ($sim_final == "2") { ?>
                    <button type="submit" style="display: none" name="update-sim-detail" class="btn btn-primary mt-3">Save Details</button>
                    <button type="submit" style="display: none" name="update-sim-final" class="btn btn-success mt-3">Submit Details</button>
                <?php } else { ?>
                    <button type="submit" name="update-sim-detail" class="btn btn-primary mt-3">Save Details</button>
                    <button type="submit" name="update-sim-final" class="btn btn-success mt-3">Submit Details</button>
                <?php } ?>
            </form>
        </div>
    </div>

    <!-- ============= MODAL ============= -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="" method="POST" class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input type="hidden" name="sim_id" value="<?php echo $sim_id ?>">
                <div class="modal-body">
                    <div class="form-floating">
                        <textarea class="form-control" name="sim_comment" style="height: 150px;" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                        <label for="floatingTextarea">Comments</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add-comment" class="btn btn-outline-dark">Add Comment</button>
                </div>
            </form>
        </div>
    </div>

</div>

<?php include('includes/footer.php');
