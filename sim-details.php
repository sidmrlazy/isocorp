<?php
ob_start();
include('includes/header.php');
include('includes/navbar.php');
include 'includes/config.php';
include 'includes/connection.php';
?>
<div class="dashboard-container">
    <?php
    if (isset($_POST['add-note'])) {
        // Retrieve and sanitize user input
        $comment_parent_id = mysqli_real_escape_string($connection, $_POST['sim_id']);
        $comment_owner = mysqli_real_escape_string($connection, $_POST['comment_owner']);
        $comment_data = mysqli_real_escape_string($connection, $_POST['comment_data']);

        // Prepare SQL statement
        $insert_comment = "INSERT INTO `sim_comment` (`comment_parent_id`, `comment_owner`, `comment_data`) 
                       VALUES ('$comment_parent_id', '$comment_owner', '$comment_data')";

        if (mysqli_query($connection, $insert_comment)) {
            // Redirect to the same page with the correct ID after successful insertion
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $comment_parent_id);
            exit();
        } else {
            echo "<p class='alert alert-danger'>Error: " . mysqli_error($connection) . "</p>";
        }
    }



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
    ?>
    <div class="section-divider">

        <div class="notes-section mt-1">
            <div class="heading-row">
                <p style="font-size: 18px !important;">Comments</p>
                <!-- ========== ADD ========== -->
                <button style="font-size: 12px;" type="button" data-bs-toggle="modal" data-bs-target="#commentModal" class="btn btn-sm btn-outline-dark">Add Comment</button>
            </div>
            <!-- ======= ADD NOTE MODAL ======= -->
            <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Create Note</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-floating">
                                <input type="text" name="sim_id" value="<?php echo $sim_id ?>" hidden>
                                <input type="text" name="comment_owner" value="<?php echo $user_name ?>" hidden>
                                <textarea class="form-control" name="comment_data" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                                <label for="floatingTextarea2">Comments</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="add-note" class="btn btn-primary">Add Note</button>
                        </div>
                    </div>
                </form>
            </div>
            <?php
            if (isset($_POST['delete-note'])) {
                $fetched_comment_id = $_POST['fetched_comment_id'];
                $delete_q = "DELETE FROM `sim_comment` WHERE comment_id = $fetched_comment_id";
                $delete_r = mysqli_query($connection, $delete_q);
            }

            $fetch_comment = "SELECT * FROM sim_comment WHERE comment_parent_id = $sim_id";
            $fetch_comment_r = mysqli_query($connection, $fetch_comment);
            $fetch_comment_count = mysqli_num_rows($fetch_comment_r);

            if ($fetch_comment_count > 0) {
                $fetched_comment_id = "";
                while ($row = mysqli_fetch_assoc($fetch_comment_r)) {
                    $fetched_comment_id = $row['comment_id'];
                    $fetched_comment_owner = $row['comment_owner'];
                    $fetched_comment_date = $row['comment_date'];
                    $fetched_comment_data = $row['comment_data'];
            ?>

                    <div class="note-container">
                        <p class="note-owner"><strong><?php echo $fetched_comment_owner ?></strong> - <?php echo $fetched_comment_date ?></p>

                        <div>
                            <p class="main-note"><?php echo $fetched_comment_data ?></p>
                            <!-- Read More -->
                            <div class="d-flex justify-content-center align-items-center">
                                <button class="read-more-btn">
                                    <ion-icon name="chevron-down-outline"></ion-icon>
                                </button>
                            </div>

                            <!-- Read Less -->
                            <div class="d-flex justify-content-center align-items-center">
                                <button class="read-less-btn">
                                    <ion-icon name="chevron-up-outline"></ion-icon>
                                </button>
                            </div>
                        </div>
                        <form action="" method="POST" class="button-row">
                            <input type="text" name="fetched_comment_id" value="<?php echo $fetched_comment_id ?>" hidden>
                            <input type="text" value="<?php echo $sim_id ?>" hidden>
                            <input type="text" value="<?php echo $user_name ?>" hidden>
                            <button type="submit" name="delete-note" class="btn btn-sm btn-outline-dark">
                                <ion-icon name="close-circle-outline"></ion-icon>
                            </button>
                        </form>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>

        <div class="WYSIWYG-editor-container m-1" style="flex: 2">
            <div class="sim-topic-container-details">
                <p>Topic:</p>
                <h5><?php echo $sim_topic ?></h5>
            </div>
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
                    <button type="submit" name="update-sim-detail" class="btn btn-primary mt-3">Save Draft</button>
                    <button type="submit" name="update-sim-final" class="btn btn-success mt-3">Submit Details</button>
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
include('includes/footer.php');
