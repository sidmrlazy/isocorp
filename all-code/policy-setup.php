<?php
include('includes/header.php');
include('includes/navbar.php'); ?>
<div class="dashboard-container">
    <!-- ============= POLICY NAME ============= -->
    <div class="form-container w-50">
        <?php
        if (isset($_POST['add-control'])) {
            date_default_timezone_set('Asia/Kolkata');
            $control_name = mysqli_real_escape_string($connection, $_POST['control_name']);
            $control_added_date = date('m-d-Y H:i:s');

            $insert_q = "INSERT
            INTO
            `controls`(
                `control_name`,
                `control_added_date`
            )
            VALUES(
            '$control_name',
            '$control_added_date'
            )";
            $insert_r = mysqli_query($connection, $insert_q);
            if ($insert_r) { ?>
                <div class="alert alert-success mb-3" id="alertBox" role="alert">
                    Policy added!
                </div>
        <?php
            }
        }
        ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Policy Name</label>
                <input type="text" name="control_name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <button type="submit" name="add-control" class="btn btn-primary">Add</button>
        </form>
    </div>

    <!-- ============= LINKED 1 ============= -->
    <div class="form-container w-50">
        <?php
        if (isset($_POST['update-1'])) {
            $control_id = $_POST['control_id'];
            $control_linked_1 = $_POST['control_linked_1'];

            $udpate_control_1 = "UPDATE controls SET control_linked_1 = '$control_linked_1' WHERE control_id = '$control_id'";
            $udpate_control_1_r = mysqli_query($connection, $udpate_control_1);
            if ($udpate_control_1_r) { ?>
                <div class="alert alert-success mb-3" id="alertBox" role="alert">
                    Policy added!
                </div>
        <?php
            }
        }
        ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Select Main Policy</label>
                <select name="control_id" class="form-select" aria-label="Default select example">
                    <option selected>Open this select menu</option>
                    <?php
                    $get_control = "SELECT * FROM controls";
                    $get_control_r = mysqli_query($connection, $get_control);
                    while ($row = mysqli_fetch_assoc($get_control_r)) {
                        $fetched_control_id = $row['control_id'];
                        $fetched_control_name = $row['control_name'];
                        if (!empty($fetched_control_name)) {
                    ?>
                            <option value="<?php echo $fetched_control_id ?>"><?php echo $fetched_control_name ?></option>
                    <?php }
                    } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Linked Policy Name</label>
                <input type="text" name="control_linked_1" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <button type="submit" name="update-1" class="btn btn-primary">Add</button>
        </form>
    </div>

    <!-- ============= LINKED 2 ============= -->
    <div class="form-container w-50">
        <?php
        if (isset($_POST['update-2'])) {
            $control_id = $_POST['control_id'];
            $control_linked_2 = $_POST['control_linked_2'];

            $udpate_control_1 = "UPDATE controls SET control_linked_2 = '$control_linked_2' WHERE control_id = '$control_id'";
            $udpate_control_1_r = mysqli_query($connection, $udpate_control_1);
            if ($udpate_control_1_r) { ?>
                <div class="alert alert-success mb-3" id="alertBox" role="alert">
                    Policy added!
                </div>
        <?php
            }
        }
        ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Select Linked Policy 1</label>
                <select name="control_id" class="form-select" aria-label="Default select example">
                    <option selected>Open this select menu</option>
                    <?php
                    $get_control = "SELECT * FROM controls";
                    $get_control_r = mysqli_query($connection, $get_control);
                    while ($row = mysqli_fetch_assoc($get_control_r)) {
                        $fetched_control_id = $row['control_id'];
                        $fetched_control_name = $row['control_linked_1'];
                        if (!empty($fetched_control_name)) {
                    ?>
                            <option value="<?php echo $fetched_control_id ?>"><?php echo $fetched_control_name ?></option>
                    <?php }
                    } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Linked Policy 2</label>
                <input type="text" name="control_linked_2" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <button type="submit" name="update-2" class="btn btn-primary">Add</button>
        </form>
    </div>

    <!-- ============= LINKED 3 ============= -->
    <div class="form-container w-50">
        <?php
        if (isset($_POST['update-3'])) {
            $control_id = $_POST['control_id'];
            $control_linked_3 = $_POST['control_linked_3'];

            $udpate_control_1 = "UPDATE controls SET control_linked_3 = '$control_linked_3' WHERE control_id = '$control_id'";
            $udpate_control_1_r = mysqli_query($connection, $udpate_control_1);
            if ($udpate_control_1_r) { ?>
                <div class="alert alert-success mb-3" id="alertBox" role="alert">
                    Policy added!
                </div>
        <?php
            }
        }
        ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Select Linked Policy 2</label>
                <select name="control_id" class="form-select" aria-label="Default select example">
                    <option selected>Open this select menu</option>
                    <?php
                    $get_control = "SELECT * FROM controls";
                    $get_control_r = mysqli_query($connection, $get_control);
                    while ($row = mysqli_fetch_assoc($get_control_r)) {
                        $fetched_control_id = $row['control_id'];
                        $fetched_control_name = $row['control_linked_2'];
                        if (!empty($fetched_control_name)) {
                    ?>
                            <option value="<?php echo $fetched_control_id ?>"><?php echo $fetched_control_name ?></option>
                    <?php }
                    } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Linked Policy 3</label>
                <input type="text" name="control_linked_3" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <button type="submit" name="update-3" class="btn btn-primary">Add</button>
        </form>
    </div>

    <!-- ============= DETAILS ============= -->
    <div class="form-container w-50 mb-5">
        <?php
        if (isset($_POST['update-details'])) {
            $control_id = $_POST['control_id'];
            $control_details = $_POST['control_details'];

            $udpate_control_1 = "UPDATE controls SET control_details = '$control_details' WHERE control_id = '$control_id'";
            $udpate_control_1_r = mysqli_query($connection, $udpate_control_1);
            if ($udpate_control_1_r) { ?>
                <div class="alert alert-success mb-3" id="alertBox" role="alert">
                    Policy added!
                </div>
        <?php
            }
        }
        ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Select Main Policy</label>
                <select name="control_id" class="form-select" aria-label="Default select example">
                    <option selected>Open this select menu</option>
                    <?php
                    $get_control = "SELECT * FROM controls";
                    $get_control_r = mysqli_query($connection, $get_control);
                    while ($row = mysqli_fetch_assoc($get_control_r)) {
                        $fetched_control_id = $row['control_id'];
                        $fetched_control_name = $row['control_name'];
                        if (!empty($fetched_control_name)) {
                    ?>
                            <option value="<?php echo $fetched_control_id ?>"><?php echo $fetched_control_name ?></option>
                    <?php }
                    } ?>
                </select>
            </div>

            <div class="WYSIWYG-editor mb-3">
                <label for="editorNew" class="form-label">Policy Details</label>
                <textarea id="editorNew" name="control_details"></textarea>
            </div>
            <button type="submit" name="update-details" class="btn btn-primary">Add</button>
        </form>
    </div>
</div>
<?php include('includes/footer.php'); ?>

