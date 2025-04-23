<?php
include 'includes/header.php';
include 'includes/navbar.php'; 
include 'includes/connection.php'; 
include 'functions/audit-program-functions/add-program-function.php'; 
include 'functions/audit-program-functions/update-activity-function.php'; 
include 'functions/audit-program-functions/update-details-functions.php'; 
?>
<div class="dashboard-container">
    <!-- ============ SCREEN NAME ============ -->
    <div class="screen-name-container">
        <h1>AUDIT PROGRAMME SETUP</h1>
        <h2><a href="dashboard.php">Dashboard</a> > AUDIT PROGRAMME SETUP</h2>
    </div>

    <div class="mb-5">
        <!-- ============ PROGRAMME NAME ============ -->
        <form action="" method="POST" class="form-container mt-3" style="width: 50% !important;">
            <div class="mb-3">
                <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Programme Name</label>
                <input type="text" name="ap_name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <button style="font-size: 12px !important;" type="submit" name="add-pr" class="btn btn-primary">Add Program</button>
        </form>

        <!-- ============ ACTIVITY NAME ============ -->
        <form action="" method="POST" class="form-container mt-3" style="width: 50% !important;">
            <div class="mb-3">
                <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Select Programme</label>
                <select name="ap_name" class="form-select" aria-label="Default select example">
                    <option style="font-size: 12px !important;" selected>Open this select menu</option>
                    <?php 
                    $get_pr_query = "SELECT * FROM `audit_program`";
                    $get_pr_res = mysqli_query($connection, $get_pr_query);
                    while($row = mysqli_fetch_assoc($get_pr_res)) {
                        $f_ap_id = $row['ap_id'];
                        $f_ap_name = $row['ap_name']; ?>
                    <option style="font-size: 12px !important;" value="<?php echo $f_ap_id ?>"><?php echo $f_ap_name ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Activity Name</label>
                <input type="text" name="ap_act_name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <button style="font-size: 12px !important;" type="submit" name="add-act" class="btn btn-primary">Add Activity</button>
        </form>

        <!-- ============ ACTIVITY NAME ============ -->
        <form action="" method="POST" class="form-container mt-3" style="width: 50% !important;">
            <div class="mb-3">
                <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Select Activity</label>
                <select name="ap_act_id" class="form-select" aria-label="Default select example">
                    <option selected>Open this select menu</option>
                    <?php 
                    $get_pr_query = "SELECT * FROM `audit_program`";
                    $get_pr_res = mysqli_query($connection, $get_pr_query);
                    while($row = mysqli_fetch_assoc($get_pr_res)) {
                        $f_ap_id = $row['ap_id'];
                        $f_ap_act_name = $row['ap_act_name']; ?>
                    <option style="font-size: 12px !important;" value="<?php echo $f_ap_id ?>"><?php echo $f_ap_act_name ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Details</label>
                <input type="text" name="ap_details" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <button style="font-size: 12px !important;" name="add-det" type="submit" class="btn btn-primary">Add Details</button>
        </form>
    </div>
</div>
<?php include('includes/footer.php'); ?>