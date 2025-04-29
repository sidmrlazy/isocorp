<?php
include('includes/header.php');
include('includes/navbar.php');
include('includes/connection.php'); ?>
<div class="dashboard-container">
    <?php
    if (isset($_GET['id'])) {
        $training_id = mysqli_real_escape_string($connection, $_GET['id']);
        $fetch = "SELECT * FROM `training` WHERE `training_id` = '$training_id'";
        $fetch_r = mysqli_query($connection, $fetch);
        $fetched_training_id = "";
        $fetched_training_topic = "";
        while ($row = mysqli_fetch_assoc($fetch_r)) {
            $fetched_training_id = $row['training_id'];
            $fetched_training_topic = $row['training_topic'];
        }
    }
    ?>

    <form action="mrb.php" method="POST" class="form-container">
        <input type="text" value="<?php echo $fetched_training_id ?>" name="fetched_training_id" hidden>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Topic</label>
            <input type="text" name="new_training_topic" class="form-control" id="exampleInputEmail1" value="<?php echo $fetched_training_topic ?>" aria-describedby="emailHelp">
        </div>

        <button type="submit" name="edit-training-topic" class="btn btn-sm btn-success">Submit</button>
    </form>
</div>
<?php include('includes/footer.php'); ?>