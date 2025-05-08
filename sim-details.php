<?php
include('includes/header.php');
include('includes/navbar.php');
include 'includes/config.php';
include 'includes/connection.php';

// Dummy placeholders for required variables
// $user_name = "Admin"; // Replace this with actual session user value
$policy_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
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
                echo "<p style='font-size: 12px !important;' id='alertBox' class='alert alert-warning'>No SIM record found.</p>";
            }
            mysqli_stmt_close($stmt);
        }
    }

    if (isset($_POST['update-sim-detail']) && isset($sim_id)) {
        $sim_details = trim($_POST['sim_details']);
        $stmt = mysqli_prepare($connection, "UPDATE sim SET sim_details = ? WHERE sim_id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $sim_details, $sim_id);
            mysqli_stmt_execute($stmt);
            echo "<p style='font-size: 12px !important;' id='alertBox' class='alert alert-success'>Details updated successfully!</p>";
            mysqli_stmt_close($stmt);
        }
    }

    if (isset($_POST['update-sim-final']) && isset($sim_id)) {
        $sim_details = trim($_POST['sim_details']);
        $stmt = mysqli_prepare($connection, "UPDATE sim SET sim_details = ?, sim_final = '2' WHERE sim_id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $sim_details, $sim_id);
            mysqli_stmt_execute($stmt);
            echo "<p style='font-size: 12px !important;' id='alertBox' class='alert alert-success'>SIM finalized and closed successfully!</p>";
            mysqli_stmt_close($stmt);

            // Insert Old SIM Details into sim_details_history
        }
    }
    ?>
    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <!-- ========== MAIN LEFT SECTION ========== -->
        <!-- ========== SIM DETAILS SECTION ========== -->
        <div style="width: 50%; margin: 5px; box-shadow: 0 2px 4px rgba(255,255,255,0.1);">
            <div class="WYSIWYG-editor-container">
                <div class="sim-topic-container-details">
                    <p>Topic:</p>
                    <h5><?php echo $sim_id . " " . $sim_topic ?? "N/A"; ?></h5>
                </div>
                <form action="" method="POST">
                    <div class="WYSIWYG-editor">
                        <?php if ($sim_final == '2') { ?>
                            <p><?php echo !empty($sim_details) ? htmlspecialchars_decode($sim_details) : 'No details available.'; ?></p>
                        <?php } else { ?>
                            <textarea id="editorNewSim" name="sim_details"><?php echo $sim_details ?? ""; ?></textarea>
                        <?php } ?>
                    </div>
                    <?php if ($sim_final != '2') { ?>
                        <button type="submit" name="update-sim-detail" class="btn btn-primary mt-3">Save Draft</button>
                        <button type="submit" name="update-sim-final" class="btn btn-success mt-3">Submit Details</button>
                    <?php } ?>
                </form>
            </div>
        </div>

        <!-- ========== MAIN RIGHT SECTION ========== -->
        <div style="width: 50% !important;">
            <!-- ========== SIM RISKS SECTION ========== -->
            <div style="margin-top: 15px; padding: 20px; border-radius: 10px; background-color: #fff !important;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h6>Linked Risks & Treatments</h6>
                    <button class="btn btn-sm btn-outline-success" style="font-size: 12px !important; margin: 0 !important;" data-bs-toggle="modal" data-bs-target="#riskModal">+</button>
                </div>

                <!-- ========== SIM RISKS MODAL ========== -->
                <div class="modal fade" id="riskModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <?php
                        if (isset($_POST['add-sim-risk'])) {

                            $risk_ids = $_POST['risk_ids'];
                            $clause_id = intval($_POST['clause_id']);
                            $clause_type = mysqli_real_escape_string($connection, $_POST['clause_type']);

                            foreach ($risk_ids as $risk_id) {
                                $risk_id = intval($risk_id);

                                // Check if the relation already exists
                                $check_exist = "SELECT * FROM risk_policies WHERE risks_id = $risk_id AND clause_id = $clause_id AND clause_type = '$clause_type'";
                                $result = mysqli_query($connection, $check_exist);

                                if (mysqli_num_rows($result) == 0) {
                                    // Insert new relationship
                                    $insert = "INSERT INTO risk_policies (risks_id, clause_id, clause_type) VALUES ($risk_id, $clause_id, '$clause_type')";
                                    mysqli_query($connection, $insert);
                                }
                            }

                            echo "<div style='font-size: 12px !important;' id='alertBox' class='alert alert-success mt-2'>Risks successfully linked to the policy/control.</div>";
                        }
                        ?>
                        <form action="" method="POST" class="modal-content">
                            <input type="text" name="clause_id" value="<?php echo $policy_id ?>" hidden>
                            <input type="text" name="clause_type" value="sim" hidden>
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Risk & Treatments to Security Incident Management</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label style="font-size: 12px !important;" for="riskSearch" class="form-label">Search Risks</label>
                                    <input type="text" id="riskSearch" class="form-control mb-2" placeholder="Type to search risks..." style="font-size: 12px !important;">

                                    <label style="font-size: 12px !important;" for="riskSelect" class="form-label">Risks</label>
                                    <select name="risk_ids[]" id="riskSelect" style="font-size: 12px !important; height: 300px !important" multiple class="form-select">
                                        <option disabled selected>Choose Risks</option>
                                        <?php
                                        $get_risks = "SELECT * FROM risks ORDER BY risks_id ASC";
                                        $get_risks_r = mysqli_query($connection, $get_risks);
                                        while ($row = mysqli_fetch_assoc($get_risks_r)) {
                                            $risks_id = $row['risks_id'];
                                            $risks_name = $row['risks_name'];
                                            echo "<option style='border-bottom: 1px solid #e7e7e7; padding-bottom: 5px !important;' value=\"$risks_id\">$risks_id.  $risks_name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="add-sim-risk" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- ======= ASSOCIATED RISKS TABLE ======= -->
                <?php
                if(isset($_POST['del-sim'])) {
                    $risk_id = intval($_POST['risk_id']);
                    $delete_query = "DELETE FROM risk_policies WHERE risks_id = $risk_id AND clause_id = $policy_id AND clause_type = 'sim'";
                    if (mysqli_query($connection, $delete_query)) {
                        echo "<p style='font-size: 12px !important;' id='alertBox' class='alert alert-success'>Risk removed successfully.</p>";
                    } else {
                        echo "<p style='font-size: 12px !important;' id='alertBox' class='alert alert-danger'>Error removing risk.</p>";
                    }
                }

                $fetch_risks_query = " SELECT r.risks_id, r.risks_name FROM 
                risk_policies rp
                JOIN risks r ON rp.risks_id = r.risks_id
                WHERE rp.clause_id = $policy_id AND rp.clause_type = 'sim'
                    ";
                $fetch_risks_r = mysqli_query($connection, $fetch_risks_query);
                ?>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="font-size: 12px !important;">Risk Name</th>
                                <th style="font-size: 12px !important;">View</th>
                                <th style="font-size: 12px !important;">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($risk = mysqli_fetch_assoc($fetch_risks_r)) { ?>
                                <tr>
                                    <td style="font-size: 12px !important;"><?php echo htmlspecialchars($risk['risks_name']); ?></td>
                                    <td>
                                        <a href="risks-details.php?id=<?php echo $risk['risks_id']; ?>" class="btn btn-sm btn-outline-success" style="font-size: 12px !important;">
                                            View Risk
                                        </a>
                                    </td>
                                    <td>
                                        <form action="" method="POST">
                                            <input type="text" name="risk_id" value="<?php echo $risk['risks_id']; ?>" hidden>
                                            <button type="submit" name="del-risk" style="font-size: 12px !important;" class="btn btn-sm btn-outline-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ========== COMMENT SECTION ========== -->
            <div style="margin-top: 15px; padding: 20px; border-radius: 10px; background-color: #fff !important;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h6>Comments</h6>
                    <button class="btn btn-sm btn-outline-success" style="font-size: 12px !important; margin: 0 !important;" data-bs-toggle="modal" data-bs-target="#addComment">+</button>
                </div>

                <!-- ========== COMMENT MODAL ========== -->
                <div class="modal fade" id="addComment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">

                        <?php
                        // Check if the form is submitted
                        if (isset($_POST['add-sim-comment']) && isset($sim_id)) {
                            // Sanitize and fetch input data
                            $comment_data = mysqli_real_escape_string($connection, $_POST['comment_data']);
                            $comment_owner = $user_name;
                            $comment_parent_id = $sim_id;

                            // Insert the comment into the database
                            $insert_comment_query = "INSERT INTO sim_comment (comment_parent_id, comment_owner, comment_data) VALUES (?, ?, ?)";
                            $stmt = mysqli_prepare($connection, $insert_comment_query);
                            if ($stmt) {
                                mysqli_stmt_bind_param($stmt, "iss", $comment_parent_id, $comment_owner, $comment_data);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_close($stmt);

                                echo "<p style='font-size: 12px !important;' id='alertBox' class='alert alert-success'>Comment added successfully!</p>";
                            } else {
                                echo "<p style='font-size: 12px !important;' id='alertBox' class='alert alert-danger'>Error adding comment.</p>";
                            }
                        }
                        ?>
                        <form action="" method="POST" class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Comment</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px" name="comment_data"></textarea>
                                    <label for="floatingTextarea2">Comments</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="add-sim-comment" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ======= COMMENT TAB ======= -->
                <?php
                if (isset($_POST['delete_comment'])) {
                    $comment_id = intval($_POST['comment_id']);
                    $delete_query = "DELETE FROM sim_comment WHERE comment_id = $comment_id";
                    if (mysqli_query($connection, $delete_query)) {
                        echo "<p style='font-size: 12px !important;' id='alertBox' class='alert alert-success'>Comment deleted successfully.</p>";
                    } else {
                        echo "<p style='font-size: 12px !important;' id='alertBox' class='alert alert-danger'>Error deleting comment.</p>";
                    }
                }
                // Fetch comments from the database
                $fetch_comments_query = "SELECT * FROM sim_comment WHERE comment_parent_id = '$sim_id'";
                $fetch_comments_r = mysqli_query($connection, $fetch_comments_query);
                if (mysqli_num_rows($fetch_comments_r) > 0) {
                    while ($comment = mysqli_fetch_assoc($fetch_comments_r)) {
                        $comment_owner = htmlspecialchars($comment['comment_owner']);
                        $comment_data = nl2br(htmlspecialchars($comment['comment_data'])); // Display comment data
                        $comment_date = date('F j, Y, g:i a', strtotime($comment['comment_date'])); // Format date
                ?>
                        <div style="margin-top: 20px; border-bottom: 1px solid #e7e7e7">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h6 style="font-size: 10px !important; margin: 0;"><strong>Comment by:</strong> <?php echo $comment_owner; ?> - <?php echo $comment_date; ?></h6>
                                <form action="" method="POST">
                                    <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                                    <button type="submit" name="delete_comment" class="btn btn-sm btn-outline-danger" style="font-size: 10px; margin:0">
                                        <ion-icon name="close-outline"></ion-icon>
                                    </button>
                                </form>
                            </div>
                            <p style="font-size: 14px;"><?php echo $comment_data; ?></p>
                        </div>
                    <?php
                    }
                } else { ?>
                    <p style="font-size: 12px; margin: 0">No comments added.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#editorNewSim').summernote({
            height: 300,
            minHeight: 150,
            maxHeight: 500,
            focus: true
        });

        $('form').on('submit', function() {
            $('#editorNewSim').val($('#editorNewSim').summernote('code'));
        });
    });

    document.getElementById('riskSearch').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const options = document.querySelectorAll('#riskSelect option');

        options.forEach(option => {
            const text = option.textContent.toLowerCase();
            option.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>

<?php include 'includes/footer.php'; ?>