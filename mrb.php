<?php
include('includes/header.php');
include('includes/navbar.php');
include('includes/connection.php'); ?>

<div class="dashboard-container">
    <!-- ============ SCREEN NAME ============ -->
    <div class="screen-name-container">
        <h1>MANAGEMENT REVIEW BOARD</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Management Review Board</h2>
    </div>

    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addTopicModal">Add Topic</button>
    <?php
    if(isset($_POST['delete-topic'])) {
        $training_id = $_POST['training_id'];
        $delete_query = "DELETE FROM training WHERE training_id = $training_id";
        $delete_result = mysqli_query($connection, $delete_query);

    }
    if (isset($_POST['add-topic'])) {
        $mrb_topic = mysqli_real_escape_string($connection, $_POST['mrb_topic']);

        $insert_query = "INSERT INTO `training`(
        `training_topic`, 
        `training_created_by`) VALUES (
        '$mrb_topic',
        '$user_name')";
        $insert_res = mysqli_query($connection, $insert_query);
    }
    $fetch_data = "SELECT * FROM training";
    $fetch_data_r = mysqli_query($connection, $fetch_data);
    $insert_res_count = mysqli_num_rows($fetch_data_r);
    if ($insert_res_count > 0) {
    ?>
        <div class="table-container table-responsive mt-3">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="font-size: 12px;" scope="col">#</th>
                        <th style="font-size: 12px;" scope="col">Topic</th>
                        <th style="font-size: 12px;" scope="col">Created On</th>
                        <th class="text-center" style="font-size: 12px;" scope="col">Edit</th>
                        <th class="text-center" style="font-size: 12px;" scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($fetch_data_r)) {
                        $training_id = $row['training_id'];
                        $training_topic = $row['training_topic'];
                        $training_date = $row['training_date'];
                    ?>
                        <tr>
                            <th style="font-size: 12px" scope="row"><?php echo $training_id ?></th>
                            <td style="font-size: 12px">
                                <a href="mrb-details.php?id=<?php echo $training_id; ?>"><?php echo $training_topic ?></a>
                            </td>
                            <td style="font-size: 12px"><?php echo $training_date ?></td>
                            <td style="font-size: 12px" class="text-center">
                                <form action="" method="post">
                                    <input type="text" name="training_id" value="<?php echo $training_id; ?>" hidden>
                                    <button style="font-size: 12px;" class="btn btn-sm btn-warning">
                                        <ion-icon name="create-outline"></ion-icon>
                                    </button>
                                </form>
                            </td>
                            <td style="font-size: 12px" class="text-center">
                                <form action="" method="post">
                                    <input type="text" name="training_id" value="<?php echo $training_id; ?>" hidden>
                                    <button type="submit" name="delete-topic" style="font-size: 12px;" class="btn btn-sm btn-danger">
                                        <ion-icon name="trash-outline"></ion-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    <?php } else { ?>
        <div class="alert alert-danger mt-3 w-100" role="alert">
            No topic found!
        </div>

    <?php
    } ?>

    <!-- ============ CREATE TOPIC MODAL ============ -->
    <div class="modal fade" id="addTopicModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="" method="POST" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Topic</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating">
                        <textarea name="mrb_topic" class="form-control" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                        <label for="floatingTextarea">Topic</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add-topic" class="btn btn-sm btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>

</div>

<?php include('includes/footer.php'); ?>