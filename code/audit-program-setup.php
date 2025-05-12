<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
include 'includes/config.php';

$phases = mysqli_query($connection, "SELECT * FROM ap_phases");
?>
<div class="dashboard-container">
    <div class="screen-name-container mb-3">
        <h1 style="text-transform: uppercase;">Audit Programme Setup</h1>
        <h2><a href="index.php">Dashboard</a> > Audit Programme Setup</h2>
    </div>

    <div style="display: flex; gap: 20px;">
        <!-- ======== LEFT SECTION ======== -->
        <div style="background-color: #fff; width: 50%; padding: 20px; border-radius: 10px;">
            <?php
            if (isset($_POST['submit_all'])) {
                // Create Phase
                if (!empty($_POST['ap_phases_name'])) {
                    $name = mysqli_real_escape_string($conn, $_POST['ap_phases_name']);
                    mysqli_query($conn, "INSERT INTO ap_phases (ap_phases_name) VALUES ('$name')");
                }

                // Create Deliverable
                if (!empty($_POST['ap_del_name']) && !empty($_POST['ap_del_phase_id'])) {
                    $name = mysqli_real_escape_string($conn, $_POST['ap_del_name']);
                    $phase_id = intval($_POST['ap_del_phase_id']);
                    mysqli_query($conn, "INSERT INTO ap_deliverables (ap_del_name, ap_del_phase_id) VALUES ('$name', $phase_id)");
                }

                // Create Activity
                if (!empty($_POST['ap_act_name']) && !empty($_POST['ap_act_del_id'])) {
                    $name = mysqli_real_escape_string($conn, $_POST['ap_act_name']);
                    $del_id = intval($_POST['ap_act_del_id']);
                    mysqli_query($conn, "INSERT INTO ap_activities (ap_act_name, ap_act_del_id) VALUES ('$name', $del_id)");
                }

                header("Location: audit_setup.php");
                exit();
            }
            ?>
            <form action="" method="POST">
                <!-- ======== PHASE SECTION ======== -->
                <div class="mb-3">
                    <label style="font-size: 12px;" for="exampleInputEmail1" class="form-label">Create Phase</label>
                    <input type="text" name="ap_phases_name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>


                <!-- ======== DELIVERABLE SECTION ======== -->
                <div class="mb-3">
                    <label style="font-size: 12px;" for="exampleInputEmail1" class="form-label">Create Deliverable</label>
                    <select style="font-size: 12px;" class="form-select" aria-label="Default select example" name="ap_del_phase_id">
                        <option value="">Select Phase</option>
                        <?php while ($p = mysqli_fetch_assoc($phases)) { ?>
                            <option value="<?= $p['ap_phases_id'] ?>"><?= $p['ap_phases_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label style="font-size: 12px;" for="exampleFormControlInput1" class="form-label">Deliverable Name</label>
                    <input type="text" name="ap_del_name" class="form-control" id="exampleFormControlInput1" placeholder="">
                </div>

                <!-- ======== ACTIVITY SECTION ======== -->
                <div class="mb-3">
                    <label style="font-size: 12px;" for="exampleInputEmail1" class="form-label">Create Activity</label>
                    <select style="font-size: 12px;" class="form-select" aria-label="Default select example" name="ap_act_del_id">
                        <option value="">Select Deliverable</option>
                        <?php
                        $deliverables = mysqli_query($connection, "SELECT * FROM ap_deliverables");
                        while ($d = mysqli_fetch_assoc($deliverables)) {
                            echo "<option value='{$d['ap_del_id']}'>{$d['ap_del_name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label style="font-size: 12px;" for="exampleFormControlInput1" class="form-label">Activity Name</label>
                    <input type="text" name="ap_act_name" class="form-control" id="exampleFormControlInput1" placeholder="">
                </div>


                <button type="submit" name="submit_all" class="btn btn-primary">Submit</button>
            </form>
        </div>

        <!-- ======== RIGHT SECTION  ======== -->
        <div style="background-color: #fff; width: 50%; padding: 20px; border-radius: 10px;" class="table-responsive">


        </div>



    </div>
</div>
<?php include 'includes/footer.php'; ?>