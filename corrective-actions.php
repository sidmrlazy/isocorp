<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
include 'includes/config.php';
?>
<div class="dashboard-container mb-5">
    <div class="screen-name-container">
        <h1>CORRECTIVE ACTIONS & IMPROVEMENTS</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Corrective Actions & Improvements</h2>
    </div>

    <?php
    if (isset($_POST['delete'])) {
        $ca_id = mysqli_real_escape_string($connection, $_POST['ca_id']);
        $delete = "DELETE FROM `tblca` WHERE ca_id = '$ca_id'";
        $delete_r = mysqli_query($connection, $delete);
    }

    if (isset($_POST['add-topic'])) {
        date_default_timezone_set('Asia/Kolkata');
        $ca_topic = mysqli_real_escape_string($connection, $_POST['ca_topic']);
        $ca_created_date = date('m-d-Y H:i:s');
        $create_topic_query = "INSERT INTO `tblca`(
            `ca_topic`, 
            `ca_created_by`, 
            `ca_created_date`) VALUES (
            '$ca_topic',
            '$user_name', 
            '$ca_created_date')";
        $create_topic_result = mysqli_query($connection, $create_topic_query);

        if ($create_topic_result) { ?>
            <div class="alert alert-success mt-3 mb-3" id="alertBox" role="alert">
                Topic added successfully!
            </div>
        <?php
        } else { ?>
            <div class="alert alert-danger mt-3 mb-3" id="alertBox" role="alert">
                <?php echo die("Query Failed: " . mysqli_error($connection)); ?>
            </div>
    <?php }
    }
    ?>


    <!-- ============ MODAL ============ -->
    <div class="modal fade" id="caModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="" method="POST" class="modal-content">
                <div class="modal-header">
                    <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Add Topic</h1> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" name="ca_topic" class="form-control" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Topic</label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" name="add-topic" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($user_role == "2") { ?>
        <div class="d-none justify-content-end align-items-center mt-3 mb-3">
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#caModal">
                <ion-icon name="add-outline"></ion-icon> Add
            </button>
        </div>
    <?php } else { ?>
        <div class="d-flex justify-content-end align-items-center mt-3 mb-3">
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#caModal">
                <ion-icon name="add-outline"></ion-icon> Add
            </button>
        </div>
    <?php } ?>

    <?php
    $get_query = "SELECT * FROM tblca ORDER BY `ca_created_date` ASC";
    $get_res = mysqli_query($connection, $get_query);
    $get_count = mysqli_num_rows($get_res);
    if ($get_count > 0) {
    ?>
        <div class="card p-3 mt-5 mb-5">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">ID</th>
                            <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Name</th>
                            <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Status</th>
                            <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Value</th>
                            <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Source</th>
                            <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Severity</th>
                            <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Assigned to</th>
                            <!-- <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Created by</th> -->
                            <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Created date</th>
                            <!-- <th style="font-size: 12px !important; font-weight: 600 !important; text-align: center;" scope="col">EDIT TOPIC</th> -->
                            <?php if ($user_role == "2") { ?>
                                <th style="font-size: 12px !important; font-weight: 600 !important; text-align: center;" class="d-none" scope="col">Delete</th>
                            <?php } else { ?>
                                <th style="font-size: 12px !important; font-weight: 600 !important; text-align: center;" scope="col">Delete</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $serial = 1;
                        while ($row = mysqli_fetch_assoc($get_res)) {
                            $ca_id = $row['ca_id'];
                            $ca_topic = $row['ca_topic'];
                            $ca_status = $row['ca_status'];
                            $ca_financial_value = $row['ca_financial_value'];
                            $ca_source = $row['ca_source'];
                            $ca_severity = $row['ca_severity'];
                            $ca_assigned_to = $row['ca_assigned_to'];
                            $ca_created_by = $row['ca_created_by'];
                            $ca_created_date = $row['ca_created_date'];
                        ?>
                            <tr>
                                <td style="font-size: 12px !important;">
                                    <?php echo $serial++;
                                    //echo $ca_id 
                                    ?>
                                </td>
                                <td style="font-size: 12px !important;">
                                    <a href="corrective-actions-details.php?id=<?php echo $ca_id ?>"><?php echo $ca_topic ?></a>
                                </td>
                                <td style="font-size: 12px !important;"><?php echo $ca_status ?></td>
                                <td style="font-size: 12px !important;"><?php echo $ca_financial_value ?></td>
                                <td style="font-size: 12px !important;"><?php echo $ca_source ?></td>
                                <td style="font-size: 12px !important;"><?php echo $ca_severity ?></td>
                                <td style="font-size: 12px !important;"><?php echo $ca_assigned_to ?></td>
                                <!-- <td style="font-size: 12px !important;"><?php echo $ca_created_by ?></td> -->
                                <td style="font-size: 12px !important;"><?php echo date('m-d-Y', strtotime($ca_created_date)); ?></td>

                                <!-- <td style="font-size: 12px !important;" class="text-center">
                                    <form action="" method="POST">
                                        <input type="text" name="ca_id" value="<?php echo $ca_id ?>" hidden>
                                        <button type="submit" name="edit-topic" class="btn btn-sm btn-warning">
                                            <ion-icon name="create-outline"></ion-icon>
                                        </button>
                                    </form>
                                </td> -->
                                <?php if ($user_role == "2") { ?>
                                    <td style="font-size: 12px !important;" class="d-none text-center">
                                        <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                            <input type="text" name="ca_id" value="<?php echo $ca_id ?>" hidden>
                                            <button type="submit" name="delete" class="btn btn-sm btn-outline-danger" style="font-size: 12px;">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                <?php } else { ?>
                                    <td style="font-size: 12px !important;" class="text-center">
                                        <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                            <input type="text" name="ca_id" value="<?php echo $ca_id ?>" hidden>
                                            <button type="submit" name="delete" class="btn btn-sm btn-outline-danger" style="font-size: 12px;">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } else { ?>
        <div class="alert alert-danger mt-3 mb-3 w-100" role="alert">
            No topic found.
        </div>

    <?php } ?>
</div>
<?php include 'includes/footer.php'; ?>