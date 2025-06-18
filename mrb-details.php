<?php
ob_start();
include 'includes/header.php';
include('includes/auth_check.php');
include 'includes/navbar.php';
include 'includes/connection.php';

// ========= INIT ==========
$training_id = '';
$training_topic = '';
$training_data_fetched = '';
$training_details_status = '';

// ========= GET TRAINING ID FIRST ==========
if (isset($_GET['id'])) {
    $training_id = mysqli_real_escape_string($connection, $_GET['id']);
}

// ========= DELETE COMMENT ==========
if (isset($_POST['delete-note'])) {
    $delete_comment_id = mysqli_real_escape_string($connection, $_POST['delete_comment_id']);
    $delete_query = "DELETE FROM training_comment WHERE training_comment_id = '$delete_comment_id'";
    mysqli_query($connection, $delete_query);
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $training_id);
    exit();
}

// ========= ADD COMMENT ==========
if (isset($_POST['add-note'])) {
    $training_comment_parent_id = mysqli_real_escape_string($connection, $_POST['training_comment_parent_id']);
    $training_comment_by = mysqli_real_escape_string($connection, $_POST['training_comment_by']);
    $training_comment_data = mysqli_real_escape_string($connection, $_POST['training_comment_data']);

    $insert_comment = "INSERT INTO `training_comment` (
                            `training_comment_parent_id`, 
                            `training_comment_data`, 
                            `training_comment_by`) 
                        VALUES (
                            '$training_comment_parent_id',
                            '$training_comment_data',
                            '$training_comment_by')";
    mysqli_query($connection, $insert_comment);

    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $training_comment_parent_id);
    exit();
}

// ========= HANDLE SAVE DRAFT / SUBMIT ==========
if (isset($_POST['save-training-draft']) || isset($_POST['submit-training-draft'])) {
    $training_parent_id = mysqli_real_escape_string($connection, $_POST['training_parent_id']);
    $training_details = mysqli_real_escape_string($connection, $_POST['training_details']);
    $training_details_status = (isset($_POST['save-training-draft'])) ? "1" : "2";

    $update_details_q = "UPDATE `training` 
                         SET `training_details` = '$training_details', 
                             `training_details_status` = '$training_details_status' 
                         WHERE `training_id` = '$training_parent_id'";
    mysqli_query($connection, $update_details_q);
}

// ========= FETCH TRAINING DETAILS ==========
if (!empty($training_id)) {
    $fetch_q = "SELECT * FROM `training` WHERE `training_id` = '$training_id'";
    $fetch_r = mysqli_query($connection, $fetch_q);

    if ($fetch_r && mysqli_num_rows($fetch_r) > 0) {
        $row = mysqli_fetch_assoc($fetch_r);
        $training_topic = $row['training_topic'];
        $training_data_fetched = $row['training_details'];
        $training_details_status = $row['training_details_status'];
    }
}
?>

<div class="dashboard-container">
    <div class="row">
        <!-- LEFT SECTION -->
        <div class="col-md-6">
            <div class="card p-3 mb-3">
                <p style="margin: 0">Details:</p>
                <h5><?php echo htmlspecialchars($training_topic); ?></h5>
            </div>

            <div class="card p-3">
                <form action="" method="POST">
                    <input type="hidden" name="training_parent_id" value="<?php echo htmlspecialchars($training_id); ?>">
                    <div class="WYSIWYG-editor" style="max-height: fit-content; overflow-y: auto;">
                        <textarea id="editorNew" name="training_details"><?php echo htmlspecialchars($training_data_fetched); ?></textarea>
                    </div>
                    <?php if ($training_details_status == "1") { ?>
                        <button type="submit" name="save-training-draft" class="btn btn-sm btn-outline-primary mt-3">Save Draft</button>
                        <button type="submit" name="submit-training-draft" class="btn btn-sm btn-outline-success mt-3">Submit Details</button>
                    <?php } elseif ($training_details_status == "2") { ?>
                        <!-- Hide buttons once submitted -->
                        <button type="button" disabled class="btn btn-sm btn-secondary mt-3">Details Submitted</button>
                    <?php } else { ?>
                        <button type="submit" name="save-training-draft" class="btn btn-sm btn-outline-primary mt-3">Save Draft</button>
                        <button type="submit" name="submit-training-draft" class="btn btn-sm btn-outline-success mt-3">Submit Details</button>
                    <?php } ?>
                </form>
            </div>
        </div>

        <!-- RIGHT SECTION -->
        <div class="col-md-6">
            <div class="card p-3">
                <div class="heading-row d-flex justify-content-between">
                    <p style="font-size: 18px;">Comments</p>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#commentModal" class="btn btn-sm btn-outline-dark">Add Note</button>
                </div>

                <?php
                $comment_query = "SELECT * FROM training_comment 
                                  WHERE training_comment_parent_id = '$training_id' 
                                  ORDER BY training_comment_datetime DESC";
                $comment_query_r = mysqli_query($connection, $comment_query);

                if ($comment_query_r && mysqli_num_rows($comment_query_r) > 0) {
                    while ($row = mysqli_fetch_assoc($comment_query_r)) {
                        $training_comment_id = $row['training_comment_id'];
                        $training_comment_by = htmlspecialchars($row['training_comment_by']);
                        $training_comment_data = htmlspecialchars($row['training_comment_data']);
                        $training_comment_datetime = $row['training_comment_datetime'];
                ?>
                        <div class="note-container mb-3">
                            <div class="d-flex justify-content-between">
                                <p class="mb-1" style="font-size: 12px;"><strong><?php echo $training_comment_by; ?></strong> - <?php echo $training_comment_datetime; ?></p>
                                <form action="" method="POST">
                                    <input type="hidden" name="delete_comment_id" value="<?php echo $training_comment_id; ?>">
                                    <button type="submit" name="delete-note" class="btn btn-sm btn-outline-dark" style="font-size: 16px;">
                                        <ion-icon name="close-circle-outline"></ion-icon>
                                    </button>
                                </form>
                            </div>
                            <p style="font-size: 14px;"><?php echo $training_comment_data; ?></p>
                        </div>
                <?php
                    }
                } else {
                    echo "<p style='font-size: 14px;'>No comments available.</p>";
                }
                ?>

                <!-- Add Note Modal -->
                <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <form action="" method="POST" class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Comment</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="training_comment_parent_id" value="<?php echo $training_id; ?>">
                                <input type="hidden" name="training_comment_by" value="<?php echo htmlspecialchars($user_name); ?>">
                                <div class="form-floating">
                                    <textarea class="form-control" name="training_comment_data" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                                    <label for="floatingTextarea2">Comments</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="add-note" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ob_end_flush(); ?>
