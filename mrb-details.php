<?php
ob_start();
include 'includes/header.php';
include('includes/auth_check.php');
include 'includes/navbar.php';
include 'includes/connection.php';
?>

<div class="dashboard-container">
    <div style="display: flex; flex-direction: row-reverse; justify-content: space-between; margin-bottom: 50px;">
        <!-- ======================== COMMENT SECTION ======================== -->
        <div class="notes-section mt-1" style="width: 50% !important">
            <div class="heading-row">
                <p style="font-size: 18px;">Comments</p>
                <!-- ========== ADD ========== -->
                <button style="font-size: 12px;" type="button" data-bs-toggle="modal" data-bs-target="#commentModal" class="btn btn-sm btn-outline-dark">Add Note</button>
            </div>

            <?php
            if (isset($_GET['id'])) {
                $id = mysqli_real_escape_string($connection, $_GET['id']);
                $query = "SELECT * FROM training WHERE training_id = '$id'";
                $query_r = mysqli_query($connection, $query);

                if (!$query_r) {
                    die("Query Failed: " . mysqli_error($connection));
                }

                $count = mysqli_num_rows($query_r);
                $training_id = "";
                $training_topic = "";
                if ($count > 0) {
                    while ($row = mysqli_fetch_assoc($query_r)) {
                        $training_id = $row['training_id'];
                        $training_topic = $row['training_topic'];
                    }
                }
            }

            if (isset($_POST['add-note'])) {
                $training_comment_parent_id = $_POST['training_comment_parent_id'];
                $training_comment_by = $_POST['training_comment_by'];
                $training_comment_data = mysqli_real_escape_string($connection, $_POST['training_comment_data']);

                $insert_comment = "INSERT INTO `training_comment`(
                    `training_comment_parent_id`, 
                    `training_comment_data`, 
                    `training_comment_by`) 
                    VALUES (
                    '$training_comment_parent_id',
                    '$training_comment_data',
                    '$training_comment_by')";
                mysqli_query($connection, $insert_comment);

                // Refresh page to show new comment
                header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $training_comment_parent_id);
                exit();
            }

            $comment_query = "SELECT * FROM training_comment WHERE training_comment_parent_id = '$training_id' ORDER BY training_comment_datetime DESC";
            $comment_query_r = mysqli_query($connection, $comment_query);

            if ($comment_query_r && mysqli_num_rows($comment_query_r) > 0) {
                while ($row = mysqli_fetch_assoc($comment_query_r)) {
                    $training_comment_id = $row['training_comment_id'];
                    $training_comment_by = htmlspecialchars($row['training_comment_by']);
                    $training_comment_data = htmlspecialchars($row['training_comment_data']);
                    $training_comment_datetime = $row['training_comment_datetime'];
            ?>
                    <div class="note-container" style="margin-bottom: 20px;">
                        <div class="d-flex justify-content-center align-items-center">
                            <p style="flex: 1; margin: 0 !important; font-size: 12px"><strong style="font-weight: 600 !important;"><?php echo $training_comment_by; ?></strong> - <?php echo $training_comment_datetime; ?></p>
                            <form action="" method="POST" style="margin-top: 0 !important;">
                                <input type="hidden" name="delete_comment_id" value="<?php echo $training_comment_id; ?>">
                                <button type="submit" name="delete-note" class="btn btn-sm btn-outline-dark" style="border: 0; font-size: 18px;">
                                    <ion-icon name="close-circle-outline"></ion-icon>
                                </button>
                            </form>
                        </div>
                        <div>
                            <p style="font-size: 16px !important; margin: 0 !important"><?php echo $training_comment_data; ?></p>
                            <!-- Read More -->
                            <div class="d-flex justify-content-center align-items-center mt-3">
                                <button class="read-more-btn">
                                    <ion-icon name="chevron-down-outline"></ion-icon>
                                </button>
                            </div>

                            <!-- Read Less -->
                            <div class="d-flex justify-content-center align-items-center mt-3">
                                <button class="read-less-btn">
                                    <ion-icon name="chevron-up-outline"></ion-icon>
                                </button>
                            </div>
                        </div>
                    </div>

            <?php
                }
            } else {
                echo "<p style='font-size: 14px;'>No comments available.</p>";
            }

            // Delete Comment
            if (isset($_POST['delete-note'])) {
                $delete_comment_id = mysqli_real_escape_string($connection, $_POST['delete_comment_id']);
                $delete_query = "DELETE FROM training_comment WHERE training_comment_id = '$delete_comment_id'";
                mysqli_query($connection, $delete_query);

                // Refresh page after deleting comment
                header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $training_id);
                exit();
            }
            ?>

            <!-- ======= ADD NOTE MODAL ======= -->
            <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <form action="" method="POST" class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Comment</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating">
                                <input type="hidden" name="training_comment_parent_id" value="<?php echo $training_id; ?>">
                                <input type="hidden" name="training_comment_by" value="<?php echo $user_name; ?>">
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

        <!-- ======================== MRB DETAILS SECTION ======================== -->
        <div class="WYSIWYG-editor-container m-1" style="width: 50% !important; max-height: fit-content;">
            <div style="margin-bottom: 15px;">
                <p style="margin: 0">Details:</p>
                <h5><?php echo htmlspecialchars($training_topic); ?></h5>
            </div>

            <?php
            if (isset($_POST['save-training-draft'])) {
                $training_parent_id = mysqli_real_escape_string($connection, $_POST['training_parent_id']);
                $training_details = mysqli_real_escape_string($connection, $_POST['training_details']);
                $training_details_status = "1";

                $update_details_q = "UPDATE `training` 
                             SET `training_details` = '$training_details', 
                                 `training_details_status` = '$training_details_status' 
                             WHERE `training_id` = '$training_parent_id'";
                mysqli_query($connection, $update_details_q);
            }

            if (isset($_POST['submit-training-draft'])) {
                $training_parent_id = mysqli_real_escape_string($connection, $_POST['training_parent_id']);
                $training_details = mysqli_real_escape_string($connection, $_POST['training_details']);
                $training_details_status = "2"; // Static value, no need to escape

                $update_details_q = "UPDATE `training` 
                             SET `training_details` = '$training_details', 
                                 `training_details_status` = '$training_details_status' 
                             WHERE `training_id` = '$training_parent_id'";
                mysqli_query($connection, $update_details_q);
            }

            // Fetch training details
            $training_data_fetched = "";
            $training_details_status = "";

            if (!empty($training_id)) {
                $fetch_q = "SELECT * FROM `training` WHERE `training_id` = '$training_id'";
                $fetch_r = mysqli_query($connection, $fetch_q);

                if ($fetch_r && mysqli_num_rows($fetch_r) > 0) {
                    $row = mysqli_fetch_assoc($fetch_r);
                    $training_data_fetched = $row['training_details'];
                    $training_details_status = $row['training_details_status'];
                }
            }
            ?>

            <form action="" method="POST">
                <input type="hidden" name="training_parent_id" value="<?php echo htmlspecialchars($training_id); ?>">
                <div class="WYSIWYG-editor" style="max-height: fit-content; overflow-y: auto;">
                    <textarea id="editorNew" name="training_details"><?php echo htmlspecialchars($training_data_fetched); ?></textarea>
                </div>
                <?php if ($training_details_status == "1") { ?>
                    <button style="font-size: 12px !important" type="submit" name="save-training-draft" class="btn btn-sm btn-outline-primary mt-3">Save Draft</button>
                    <button style="font-size: 12px !important" type="submit" name="submit-training-draft" class="btn btn-sm btn-outline-success mt-3">Submit Details</button>
                <?php } elseif ($training_details_status == "2") { ?>
                    <button style="font-size: 12px !important" type="submit" name="save-training-draft" class="btn btn-sm d-none btn-outline-primary mt-3">Save Draft</button>
                    <button style="font-size: 12px !important" type="submit" name="submit-training-draft" class="btn btn-sm d-none btn-outline-success mt-3">Submit Details</button>
                <?php } else { ?>
                    <button style="font-size: 12px !important" type="submit" name="save-training-draft" class="btn btn-outline-primary mt-3">Save Draft</button>
                    <button style="font-size: 12px !important" type="submit" name="submit-training-draft" class="btn btn-outline-success mt-3">Submit Details</button>
                <?php } ?>
            </form>
        </div>
    </div>

</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const note = document.querySelector(".main-note");
        const readMoreBtn = document.querySelector(".read-more-btn");
        const readLessBtn = document.querySelector(".read-less-btn");

        if (note) {
            let words = note.innerText.trim().split(/\s+/);
            if (words.length > 50) {
                let shortenedText = words.slice(0, 50).join(" ") + "...";
                let fullText = note.innerHTML; // Store original content

                note.innerHTML = shortenedText;
                note.parentElement.classList.add("show-read-more"); // Show Read More button

                readMoreBtn.addEventListener("click", function() {
                    note.innerHTML = fullText; // Expand text
                    note.parentElement.classList.remove("show-read-more");
                    note.parentElement.classList.add("show-read-less"); // Show Read Less button
                });

                readLessBtn.addEventListener("click", function() {
                    note.innerHTML = shortenedText; // Collapse text
                    note.parentElement.classList.remove("show-read-less");
                    note.parentElement.classList.add("show-read-more"); // Show Read More button
                });
            }
        }
    });
</script>
<?php
ob_flush();
include 'includes/footer.php';
?>