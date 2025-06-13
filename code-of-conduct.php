<?php
include('includes/header.php');
include('includes/navbar.php');
include('includes/connection.php');
include('includes/auth_check.php');

?>

<div class="dashboard-container mb-5">
    <!-- ================ MODAL BUTTON ================ -->
    <div class="d-flex justify-content-end align-items-center mb-3">
        <button type="button" style="font-size: 12px !important;" data-bs-toggle="modal" data-bs-target="#cocModal" class="btn btn-sm btn-outline-success">Add</button>
    </div>

    <?php
    $get = "SELECT * FROM coc";
    $result = mysqli_query($connection, $get);
    $count = mysqli_num_rows($result);

    if (isset($_POST['add-coc'])) {
        $coc_topic = mysqli_real_escape_string($connection, $_POST['coc_topic']);
        $coc_details = mysqli_real_escape_string($connection, $_POST['coc_details']);
        $coc_review_date = mysqli_real_escape_string($connection, $_POST['coc_review_date']);

        // Insert into database
        $query = "INSERT INTO coc (coc_topic, coc_details, coc_review_date) VALUES ('$coc_topic', '$coc_details', '$coc_review_date')";
        if (mysqli_query($connection, $query)) {
            echo "<div style='font-size: 12px !important' id='alertBox' class='alert alert-success'>Code of Conduct added successfully!</div>";
        } else {
            echo "<div style='font-size: 12px !important' id='alertBox' class='alert alert-danger'>Error: " . mysqli_error($connection) . "</div>";
        }
    }

    if (isset($_POST['delete-coc'])) {
        $coc_id = mysqli_real_escape_string($connection, $_POST['coc_id']);

        // Delete from database
        $query = "DELETE FROM coc WHERE coc_id = '$coc_id'";
        if (mysqli_query($connection, $query)) {
            echo "<div style='font-size: 12px !important' id='alertBox' class='alert alert-success'>Code of Conduct deleted successfully!</div>";
        } else {
            echo "<div style='font-size: 12px !important' id='alertBox' class='alert alert-danger'>Error: " . mysqli_error($connection) . "</div>";
        }
    }
    ?>

    <!-- ================ CODE OF CONDUCT MODAL ================ -->
    <div class="modal fade" id="cocModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form method="POST" class="modal-content">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add / Edit Code of Conduct</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="coc_id" id="edit_coc_id">
                    <div class="mb-3">
                        <label class="form-label">Code of Conduct Topic</label>
                        <input required name="coc_topic" type="text" class="form-control" id="edit_coc_topic">
                    </div>
                    <div class="WYSIWYG-editor mb-3">
                        <label class="form-label">Details</label>
                        <textarea name="coc_details" id="editorNew" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Review Date</label>
                        <input name="coc_review_date" type="date" class="form-control" id="edit_coc_review_date">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add-coc" class="btn btn-dark">Add</button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($count > 0) { ?>
        <!-- ================ DISPLAY CODE OF CONDUCT ================ -->
        <div class="card table-responsive p-3">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="font-size: 12px !important;">ID</th>
                        <th style="font-size: 12px !important;">Topic</th>
                        <th style="font-size: 12px !important;">View</th>
                        <th style="font-size: 12px !important;">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $coc_id = $row['coc_id'];
                        $coc_topic = $row['coc_topic'];
                        $coc_details = $row['coc_details'];
                        $coc_review_date = $row['coc_review_date'];
                    ?>
                        <tr>
                            <td style="font-size: 12px !important;"><?php echo $coc_id; ?></td>
                            <td style="font-size: 12px !important;"><?php echo $coc_topic; ?></td>
                            <td style="font-size: 12px !important;">
                                <input type="text" name="coc_id" value="<?php echo $coc_id; ?>" hidden>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary viewBtn"
                                    style="font-size: 12px !important;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#cocModal"
                                    data-id="<?php echo htmlspecialchars($coc_id); ?>"
                                    data-topic="<?php echo htmlspecialchars($coc_topic); ?>"
                                    data-details="<?php echo htmlspecialchars($coc_details); ?>"
                                    data-reviewdate="<?php echo htmlspecialchars($coc_review_date); ?>">View</button>

                            </td>
                            <td>
                                <form method="POST">
                                    <input type="text" name="coc_id" value="<?php echo $coc_id; ?>" hidden>
                                    <button style="font-size: 12px !important;" type="submit" name="delete-coc" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- ================ VIEW CODE OF CONDUCT MODAL ================ -->
        <div class="modal fade" id="cocModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <form method="POST" class="modal-content">

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add / Edit Code of Conduct</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="coc_id" id="edit_coc_id">
                        <div class="mb-3">
                            <label class="form-label">Code of Conduct Topic</label>
                            <input required name="coc_topic" type="text" class="form-control" id="edit_coc_topic">
                        </div>
                        <div class="WYSIWYG-editor mb-3">
                            <label class="form-label">Details</label>
                            <textarea name="coc_details" id="editorNew" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Review Date</label>
                            <input name="coc_review_date" type="date" class="form-control" id="edit_coc_review_date">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add-coc" class="btn btn-dark">Add</button>
                    </div>
                </form>
            </div>
        </div>
    <?php } else {
        echo "<div class='alert alert-warning' style='font-size: 12px !important'>No Code of Conduct found.</div>";
    }
    ?>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const viewButtons = document.querySelectorAll('.viewBtn');

        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const topic = this.getAttribute('data-topic');
                const details = this.getAttribute('data-details');
                const reviewDate = this.getAttribute('data-reviewdate');

                // Fill modal fields
                document.getElementById('edit_coc_id').value = id;
                document.getElementById('edit_coc_topic').value = topic;
                document.getElementById('editorNew').value = details;
                document.getElementById('edit_coc_review_date').value = reviewDate;

                // Make fields read-only
                document.getElementById('edit_coc_topic').readOnly = true;
                document.getElementById('editorNew').readOnly = true;
                document.getElementById('edit_coc_review_date').readOnly = true;

                // Hide Add button
                document.querySelector('button[name="add-coc"]').style.display = 'none';
            });
        });

        const modal = document.getElementById('cocModal');
        modal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('edit_coc_id').value = '';
            document.getElementById('edit_coc_topic').value = '';
            document.getElementById('editorNew').value = '';
            document.getElementById('edit_coc_review_date').value = '';

            document.getElementById('edit_coc_topic').readOnly = false;
            document.getElementById('editorNew').readOnly = false;
            document.getElementById('edit_coc_review_date').readOnly = false;

            document.querySelector('button[name="add-coc"]').style.display = 'inline-block';
        });
    });
</script>


<?php include('includes/footer.php'); ?>