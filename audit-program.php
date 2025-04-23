<?php
include('includes/header.php');
include('includes/navbar.php');
include('includes/connection.php'); ?>

<div class="dashboard-container">
    <!-- ============ SCREEN NAME ============ -->
    <div class="screen-name-container">
        <h1>AUDIT PROGRAMME</h1>
        <h2><a href="dashboard.php">Dashboard</a> > AUDIT PROGRAMME</h2>
    </div>

    <div class="audit-program-table-container mt-3">
        <div class="table-responsive">
            <?php

            if (isset($_POST['add'])) {
                $ap_id = mysqli_real_escape_string($connection, $_POST['ap_id']);
                $ap_blob = mysqli_real_escape_string($connection, $_POST['ap_blob']);
                $ap_assigned_to = mysqli_real_escape_string($connection, $_POST['ap_assigned_to']);
                $ap_status = mysqli_real_escape_string($connection, $_POST['ap_status']);
                $ap_due_date = mysqli_real_escape_string($connection, $_POST['ap_due_date']);

                $update_query = "UPDATE
                `audit_program`
                SET
                `ap_blob` = '$ap_blob',
                `ap_assigned_to` = '$ap_assigned_to',
                `ap_status` = '$ap_status',
                `ap_due_date` = '$ap_due_date'
                WHERE
                `ap_id` = '$ap_id'";
                $update_res = mysqli_query($connection, $update_query);
                if ($update_res) { ?>
                    <div class="alert alert-success mb-3" id="alertBox" role="alert">
                        Details updated successfully!
                    </div>
                <?php } else {
                    mysqli_error($connection);
                }
            }

            $get_data = "SELECT * FROM audit_program";
            $get_res = mysqli_query($connection, $get_data);
            $get_count = mysqli_num_rows($get_res);
            if ($get_count > 0) {
                ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Programme Name</th>
                            <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Activity</th>
                            <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Details</th>
                            <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Status</th>
                            <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Due Date</th>
                            <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($get_res)) {
                            $ap_id = $row['ap_id'];
                            $ap_name = $row['ap_name'];
                            $ap_act_name = $row['ap_act_name'];
                            $ap_details = $row['ap_details'];
                            $ap_status = $row['ap_status'];
                            $ap_due_date = $row['ap_due_date'];
                        ?>
                            <tr>
                                <td style="font-size: 12px !important;"><?php echo $ap_name ?></td>
                                <td style="font-size: 12px !important;"><?php echo $ap_act_name ?></td>
                                <td style="font-size: 12px !important;"><?php echo $ap_details ?></td>
                                <td style="font-size: 12px !important;"><?php echo $ap_status ?></td>
                                <td style="font-size: 12px !important;"><?php echo date('m-d-Y', strtotime($ap_due_date)); ?></td>
                                <td style="font-size: 12px !important;">
                                    <form action="audit-program-edit.php" method="POST">
                                        <input type="text" name="ap_id" value="<?php echo $ap_id ?>" hidden>
                                        <button style="font-size: 12px !important;" type="submit" name="edit-program" class="btn btn-sm btn-dark">Edit</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="alert alert-danger" role="alert">
                    No data Found!
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>