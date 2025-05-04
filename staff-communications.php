<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php'; ?>
<div class="dashboard-container">
    <div class="screen-name-container">
        <h1>STAFF COMMUNICATIONS</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Staff Communications</h2>
    </div>

    <div style="border-bottom: 1px solid #c5c5c5c5; margin-bottom: 20px; margin-top: 40px">
        <h1 style="font-size: 20px !important; margin: 0">Overview</h1>
        <p style="font-size: 12px; margin: 0; padding-bottom: 20px">A communication group for our staff and truisted associates to discuss, task and
            develop greater understanding of this platform, Information Security Management System,
            policies processes & controls, including thos relating to data protection for GDPR</p>
    </div>


    <div class="table-responsive" style="background-color: #fff; padding: 20px; border-radius: 10px;">
        <div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 20px">
            <button type="button" data-bs-toggle="modal" data-bs-target="#insertComm" class="btn btn-sm btn-outline-success"><ion-icon name="add-outline"></ion-icon> Add </button>
        </div>
        <!-- =========== INSERT COM =========== -->
        <div class="modal fade" id="insertComm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <?php
                if (isset($_POST['update-comm'])) {
                    $comm_id = mysqli_real_escape_string($connection, $_POST['edit_comm_id']);
                    $comm_data = mysqli_real_escape_string($connection, $_POST['edit_comm_data']);
                    $comm_by = mysqli_real_escape_string($connection, $_POST['edit_comm_by']);
                    $comm_date = mysqli_real_escape_string($connection, $_POST['edit_comm_date']);

                    $update_comm = mysqli_query($connection, "UPDATE staff_comm SET comm_data='$comm_data', comm_by='$comm_by', comm_date='$comm_date' WHERE comm_id='$comm_id'") or die(mysqli_error($connection));

                    if ($update_comm) {
                        echo "<p style='font-size: 12px' id='alertBox' class='alert alert-success' role='alert'>Staff communication updated successfully!</p>";
                    }
                }


                if (isset($_POST['insert-comm'])) {
                    $comm_data = mysqli_real_escape_string($connection, $_POST['comm_data']);
                    $comm_by = mysqli_real_escape_string($connection, $_POST['comm_by']);
                    $comm_date = mysqli_real_escape_string($connection, $_POST['comm_date']);

                    $insert_comm = mysqli_query($connection, "INSERT INTO staff_comm (comm_data, comm_by, comm_date) VALUES ('$comm_data', '$comm_by', '$comm_date')") or die(mysqli_error($connection));
                    if ($insert_comm) {
                        echo "<p style='font-size: 12px' id='alertBox' class='alert alert-primary' role='alert'>Staff communication added successfully!</p>";
                    }
                }
                ?>
                <form action="" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add staff communication</h1>
                    </div>
                    <div class="modal-body">
                        <input type="text" value="<?php echo $user_name ?>" name="comm_by" hidden>
                        <td>
                            <div class="mb-3">
                                <label style="font-size: 12px;" for="exampleInputEmail1" class="form-label">Upload Date</label>
                                <input type="date" name="comm_date" style="font-size: 12px;" class="form-control" value="">
                            </div>
                        </td>
                        <div class="form-floating">
                            <textarea name="comm_data" class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                            <label style="font-size: 12px;" for="floatingTextarea2">Comments</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="insert-comm" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- =========== EDIT COM =========== -->
        <div class="modal fade" id="editCommModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <form action="" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editModalLabel">Edit Staff Communication</h1>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="edit_comm_id" id="edit_comm_id">
                        <input type="text" value="<?php echo $user_name ?>" name="edit_comm_by" id="edit_comm_by" hidden>
                        <div class="mb-3">
                            <label style="font-size: 12px;" class="form-label">Upload Date</label>
                            <input type="date" name="edit_comm_date" id="edit_comm_date" style="font-size: 12px;" class="form-control">
                        </div>
                        <div class="form-floating">
                            <textarea name="edit_comm_data" id="edit_comm_data" class="form-control" style="height: 100px"></textarea>
                            <label style="font-size: 12px;" for="edit_comm_data">Comments</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="update-comm" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover">
            <?php
            if (isset($_POST['delete-comm'])) {
                $comm_id = mysqli_real_escape_string($connection, $_POST['comm_id']);
                $delete_comm = mysqli_query($connection, "DELETE FROM staff_comm WHERE comm_id='$comm_id'") or die(mysqli_error($connection));
                if ($delete_comm) {
                    echo "<p style='font-size: 12px' id='alertBox' class='alert alert-danger' role='alert'>Staff communication deleted successfully!</p>";
                }
            }

            $get_comm = "SELECT * FROM staff_comm ORDER BY comm_date DESC";
            $result = mysqli_query($connection, $get_comm) or die(mysqli_error($connection));
            $count = mysqli_num_rows($result);
            if ($count == 0) {
                echo "<p style='font-size: 12px' id='alertBox' class='alert alert-danger' role='alert'>No staff communications found!</p>";
            } else {
            ?>
                <thead>
                    <tr>
                        <th style="font-size: 12px !important;" scope="col">Content</th>
                        <th style="font-size: 12px !important;" scope="col">Uploaded Date</th>
                        <th style="font-size: 12px !important;" scope="col">Uploaded By</th>
                        <th style="font-size: 12px !important;" scope="col">Edit</th>
                        <th style="font-size: 12px !important;" scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) {
                        $comm_id = $row['comm_id'];
                        $comm_data = $row['comm_data'];
                        $comm_by = $row['comm_by'];
                        $comm_date = $row['comm_date'];
                    ?>
                        <tr>
                            <th style="font-size: 12px !important;"><?php echo $comm_data ?></th>
                            <td style="font-size: 12px !important;"><?php echo $comm_date ?></td>
                            <td style="font-size: 12px !important;"><?php echo $comm_by ?></td>
                            <td style="font-size: 12px !important;">
                                <form action="" method="POST">
                                    <input type="text" value="<?php echo $comm_id ?>" name="comm_id" hidden>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-warning"
                                        style="font-size: 12px !important;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCommModal"
                                        onclick="populateEditModal('<?php echo $comm_id; ?>', '<?php echo htmlspecialchars(addslashes($comm_data)); ?>', '<?php echo $comm_by; ?>', '<?php echo $comm_date; ?>')">
                                        Edit
                                    </button>
                                </form>
                            </td>
                            <td style="font-size: 12px !important;">
                                <form action="" method="POST">
                                    <input type="text" value="<?php echo $comm_id ?>" name="comm_id" hidden>
                                    <button style="font-size: 12px !important;" type="submit" name="delete-comm" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            <?php } ?>
        </table>
    </div>
</div>
<script>
    function populateEditModal(id, data, by, date) {
        document.getElementById('edit_comm_id').value = id;
        document.getElementById('edit_comm_data').value = data;
        document.getElementById('edit_comm_by').value = by;
        document.getElementById('edit_comm_date').value = date;
    }
</script>
<?php include 'includes/footer.php'; ?>