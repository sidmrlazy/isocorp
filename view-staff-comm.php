<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';

?>

<div class="dashboard-container mb-5">
    <?php
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Handle comment submission first
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-comment'])) {
        $sf_comment_parent_id = mysqli_real_escape_string($connection, $_POST['sf_comment_parent_id']);
        $sf_comment_by = mysqli_real_escape_string($connection, $_POST['sf_comment_by']);
        $sf_comment_details = mysqli_real_escape_string($connection, $_POST['sf_comment_details']);
        $sf_comment_date = date('Y-m-d H:i:s');

        $insert_comment = mysqli_query($connection, "INSERT INTO sf_comments (sf_comment_parent_id, sf_comment_by, sf_comment_details, sf_comment_date) VALUES ('$sf_comment_parent_id', '$sf_comment_by', '$sf_comment_details', '$sf_comment_date')") or die(mysqli_error($connection));
        if ($insert_comment) {
            echo "<p id='alertBox' class='alert alert-primary'>Comment added successfully!</p>";
        }
    }

    if (isset($_POST['del-comm'])) {
        $sf_comment_parent_id = mysqli_real_escape_string($connection, $_POST['sf_comment_parent_id']);
        $delete_comment = mysqli_query($connection, "DELETE FROM sf_comments WHERE sf_comment_parent_id='$sf_comment_parent_id'") or die(mysqli_error($connection));
        if ($delete_comment) {
            echo "<p id='alertBox' class='alert alert-danger'>Comment deleted successfully!</p>";
        }
    }

    // Fetch main communication data
    $query = mysqli_query($connection, "SELECT * FROM staff_comm WHERE comm_id = $id") or die(mysqli_error($connection));
    $data = mysqli_fetch_assoc($query);
    ?>
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <!-- Left Section -->
        <div style="margin: 5px; flex: 1; background-color: #fff; padding: 20px; border-radius: 10px;">
            <p style="margin: 0; font-size: 12px !important;"><strong><?php echo $data['comm_by']; ?> -</strong> <?php echo $data['comm_date']; ?></p>
            <p style="margin: 0; font-size: 16px !important;"><strong>Topic:</strong> <?php echo $data['comm_data']; ?></p>
            <p style="margin: 0; font-size: 12px !important;"><?php echo nl2br($data['comm_details']); ?></p>
        </div>

        <!-- Right Section: Comments -->
        <div style="margin: 5px; flex: 1; background-color: #fff; padding: 20px; border-radius: 10px;">
            <div style="margin-top: 10px; display: flex; justify-content: flex-end;">
                <button type="button" data-bs-toggle="modal" data-bs-target="#addStaffComment" class="btn btn-sm btn-outline-success">Add Comment</button>
            </div>


            <!-- =========== SHOW COMMENTS SECTION =========== -->
            <?php
            $fetch_comments = "SELECT * FROM sf_comments WHERE sf_comment_parent_id = $id ORDER BY sf_comment_date DESC";
            $fetch_comments_r = mysqli_query($connection, $fetch_comments) or die(mysqli_error($connection));
            if (mysqli_num_rows($fetch_comments_r) > 0) {
                while ($row = mysqli_fetch_assoc($fetch_comments_r)) {
                    $sf_comment_by = $row['sf_comment_by'];
                    $sf_comment_details = $row['sf_comment_details'];
                    $sf_comment_date = $row['sf_comment_date'];
            ?>
                    <div class="border p-2 mb-2 rounded" style="background-color: #f9f9f9; margin-top: 10px;">
                        <div style="display: flex; justify-content: space-between;">
                            <p style="font-size: 12px !important; margin: 0;"><strong><?php echo htmlspecialchars($sf_comment_by); ?></strong> - <?php echo $sf_comment_date; ?></p>
                            <form action="" method="POST">
                                <input type="hidden" name="sf_comment_parent_id" value="<?php echo $id; ?>">
                                <button type="submit" style="font-size: 12px;" name="del-comm" class="btn btn-sm btn-outline-danger"><ion-icon name="close-outline"></ion-icon></button>
                            </form>
                        </div>
                        <p style="margin: 0 !important"><?php echo $sf_comment_details; ?></p>
                    </div>
            <?php
                }
            } else {
                echo "<p class='text-muted mt-2'>No comments yet.</p>";
            }
            ?>

            <!-- ============= ADD COMMENT MODAL ============= -->
            <div class="modal fade" id="addStaffComment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <form action="" method="POST" class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" style="font-size: 14px !important;">Add Comment</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="sf_comment_by" value="<?php echo htmlspecialchars($user_name); ?>">
                            <input type="hidden" name="sf_comment_parent_id" value="<?php echo $id; ?>">
                            <div class="mb-3">
                                <label style="font-size: 12px;">Details</label>
                                <textarea class="form-control" name="sf_comment_details" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add-comment" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Modal -->
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>