<?php
include 'includes/connection.php';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="dashboard-container">
    <!-- ============ SCREEN NAME ============ -->
    <div class="screen-name-container">
        <h1>AUDIT PROGRAMME</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Audit Programme</h2>
    </div>

    <div class="d-flex justify-content-end align-items-center mt-3 mb-3">
        <button type="button" style="font-size: 12px !important;" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#exampleModal">Add</button>
    </div>

    <?php


    if (isset($_POST['add-ap'])) {
        $ap_name = mysqli_real_escape_string($connection, $_POST['ap_name']);
        $ap_assigned = mysqli_real_escape_string($connection, $_POST['assigned_to']);
        $ap_created_date = mysqli_real_escape_string($connection, $_POST['ap_created_date']);

        $insert_sql = "INSERT INTO audit_program (ap_name, ap_assigned, ap_created_date) 
                       VALUES ('$ap_name', '$ap_assigned', '$ap_created_date')";

        if (mysqli_query($connection, $insert_sql)) {
            echo "<div id='alertBox' class='alert alert-success' style='font-size: 12px !important;'>Audit programme added successfully.</div>";
        } else {
            echo "<div class='alert alert-danger' style='font-size: 12px !important;'>Error: " . mysqli_error($connection) . "</div>";
        }
    }
    ?>

    <!-- ============ ADD MODAL ============ -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="" method="POST" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Audit Programme</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 12px !important;">Topic</label>
                        <input name="ap_name" type="text" class="form-control" style="font-size: 12px !important;" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-size: 12px !important;">Upload Date</label>
                        <input name="ap_created_date" type="date" class="form-control" style="font-size: 12px !important;" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-size: 12px !important;">Assigned to</label>
                        <select name="assigned_to" class="form-select" style="font-size: 12px !important;" required>
                            <option selected disabled>Select a user</option>
                            <?php
                            $get_users = "SELECT * FROM user";
                            $get_user_result = mysqli_query($connection, $get_users);
                            if ($get_user_result) {
                                while ($row = mysqli_fetch_assoc($get_user_result)) {
                                    $user_name = htmlspecialchars($row['isms_user_name']);
                                    echo "<option value=\"$user_name\">$user_name</option>";
                                }
                            } else {
                                echo "<option disabled>Error loading users</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="add-ap" class="btn btn-sm btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
    <?php
    if (isset($_POST['delete'])) {
        $ap_id = mysqli_real_escape_string($connection, $_POST['ap_id']);
        $delete_sql = "DELETE FROM audit_program WHERE ap_id = '$ap_id'";

        if (mysqli_query($connection, $delete_sql)) {
            echo "<div id='alertBox' class='alert alert-success' style='font-size: 12px !important;'>Audit programme deleted successfully.</div>";
        } else {
            echo "<div class='alert alert-danger' style='font-size: 12px !important;'>Error: " . mysqli_error($connection) . "</div>";
        }
    }
    $query = "SELECT * FROM audit_program";
    $result = mysqli_query($connection, $query);
    $count = mysqli_num_rows($result);


    ?>
    <!-- ============ AUDIT TABLE ============ -->
    <?php if ($count == 0) { ?>
        <div class="alert alert-info" style="font-size: 12px !important;">No audit programmes found.</div>
    <?php } else { ?>
        <div class="table-responsive card p-3 mb-5">
            <table class="table table-hover table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th style="font-size: 12px !important;">ID</th>
                        <th style="font-size: 12px !important;">Topic</th>
                        <th style="font-size: 12px !important;">Assigned to</th>
                        <th style="font-size: 12px !important;">Review Date</th>
                        <th style="font-size: 12px !important;">View Details</th>
                        <th style="font-size: 12px !important;">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $ap_id = htmlspecialchars($row['ap_id']);
                        $ap_name = htmlspecialchars($row['ap_name']);
                        $ap_assigned = htmlspecialchars($row['ap_assigned']);
                        $ap_created_date = htmlspecialchars($row['ap_created_date']);
                    ?>
                        <tr>
                            <td style="font-size: 12px !important;"><?php echo $ap_id ?></td>
                            <td style="font-size: 12px !important;"><?php echo $ap_name ?></td>
                            <td style="font-size: 12px !important;"><?php echo $ap_assigned ?></td>
                            <td style="font-size: 12px !important;"><?php echo $ap_created_date ?></td>
                            <td style="font-size: 12px !important;">
                                <a href="audit-program-display.php?id=<?php echo $ap_id; ?>" style="font-size: 12px !important;" class="btn btn-sm btn-outline-success">View</a>
                            </td>
                            <td style="font-size: 12px !important;">
                                <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                    <input type="text" name="ap_id" value="<?php echo $ap_id ?>" hidden>
                                    <button type="submit" name="delete" style="font-size: 12px !important;" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>


<?php include 'includes/footer.php'; ?>