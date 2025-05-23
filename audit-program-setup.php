<?php
include 'includes/connection.php';
include 'includes/header.php';
include 'includes/navbar.php';
if (isset($_GET['program_id'])) {
    $program_id = $_GET['program_id'];
    $query = "SELECT * FROM ap_act WHERE ap_act_main_id = $program_id";
    $result = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='{$row['ap_act_id']}'>{$row['ap_act_name']}</option>";
    }
}
?>
<div class="dashboard-container">
    <!-- ============ SCREEN NAME ============ -->
    <div class="screen-name-container">
        <h1>AUDIT PROGRAMME SETUP</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Audit Programme Setup</h2>
    </div>

    <div class="row mt-5 mb-5">
        <!-- ============================== CREATE PROGRAM SECTION ============================== -->
        <div class="col-md-6">
            <!-- ========= PROGRAM NAME ========= -->
            <?php
            if (isset($_POST['add-program'])) {
                $ap_name = mysqli_real_escape_string($connection, $_POST['ap_name']);
                $query = "INSERT INTO `ap_main` (ap_name) VALUES ('$ap_name')";
                $result = mysqli_query($connection, $query);
                if ($result) {
                    echo "<div id='alertBox' style='font-size: 12px !important' class='alert alert-success'>Program added successfully</div>";
                } else {
                    echo "<div id='alertBox' style='font-size: 12px !important' class='alert alert-danger'>Failed to add program</div>";
                }
            }
            ?>
            <form action="" method="POST" class="card p-3 mb-3">
                <div class="mb-3">
                    <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Program Name</label>
                    <input type="text" name="ap_name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <button type="submit" name="add-program" style="width: 20%; font-size: 12px !important" class="btn btn-sm btn-outline-success">Add Program</button>
            </form>

            <!-- ========= ACTIVITY NAME ========= -->
            <?php
            if (isset($_POST['add-activity'])) {
                $ap_act_main_id = mysqli_real_escape_string($connection, $_POST['ap_act_main_id']);
                $ap_act_name = mysqli_real_escape_string($connection, $_POST['ap_act_name']);
                $query = "INSERT INTO `ap_act` (ap_act_main_id, ap_act_name) VALUES ('$ap_act_main_id', '$ap_act_name')";
                $result = mysqli_query($connection, $query);
                if ($result) {
                    echo "<div id='alertBox' style='font-size: 12px !important' class='alert alert-success'>Activity added successfully</div>";
                } else {
                    echo "<div id='alertBox' style='font-size: 12px !important' class='alert alert-danger'>Failed to add activity</div>";
                }
            }
            ?>
            <form action="" method="POST" class="card p-3 mb-3">
                <div class="mb-3">
                    <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Select Audit Program</label>
                    <select style="font-size: 12px !important;" name="ap_act_main_id" class="form-select" aria-label="Default select example">
                        <option selected>Open this select menu</option>
                        <?php
                        $get_program_query = "SELECT * FROM ap_main";
                        $get_program_result = mysqli_query($connection, $get_program_query);
                        while ($row = mysqli_fetch_assoc($get_program_result)) {
                            $ap_id = $row['ap_id'];
                            $ap_name = $row['ap_name'];
                            echo "<option value='$ap_id'>$ap_name</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Phase Name</label>
                    <input type="text" name="ap_act_name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>

                <button type="submit" name="add-activity" style="width: 20%; font-size: 12px !important" class="btn btn-sm btn-outline-success">Add Phase</button>
            </form>

            <!-- ========= PHASE NAME ========= -->
            <?php
            if (isset($_POST['add-phase'])) {
                $ap_ph_main_id = mysqli_real_escape_string($connection, $_POST['ap_ph_main_id']);
                $ap_ph_act_id = mysqli_real_escape_string($connection, $_POST['ap_ph_act_id']);
                $ap_ph_name = mysqli_real_escape_string($connection, $_POST['ap_ph_name']);
                $query = "INSERT INTO `ap_phase` (ap_ph_main_id, ap_ph_act_id, ap_ph_name) VALUES ('$ap_ph_main_id', '$ap_ph_act_id', '$ap_ph_name')";
                $result = mysqli_query($connection, $query);
                if ($result) {
                    echo "<div id='alertBox' style='font-size: 12px !important' class='alert alert-success'>Phase added successfully</div>";
                } else {
                    echo "<div id='alertBox' style='font-size: 12px !important' class='alert alert-danger'>Failed to add phase</div>";
                }
            }
            ?>
            <form action="" method="POST" class="card p-3 mb-3">
                <div class="mb-3">
                    <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Select Program</label>
                    <select style="font-size: 12px !important;" name="ap_ph_main_id" id="ap_ph_main_id" class="form-select" aria-label="Default select example">
                        <option selected>Open this select menu</option>
                        <?php
                        $get_program_query = "SELECT * FROM ap_main";
                        $get_program_result = mysqli_query($connection, $get_program_query);
                        while ($row = mysqli_fetch_assoc($get_program_result)) {
                            $ap_id = $row['ap_id'];
                            $ap_name = $row['ap_name'];
                            echo "<option value='$ap_id'>$ap_name</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Select Phase</label>
                    <select style="font-size: 12px !important;" name="ap_ph_act_id" id="ap_ph_act_id" class="form-select" aria-label="Default select example">
                        <option selected>Select Activity</option>
                        <?php
                        $get_program_query = "SELECT * FROM ap_act";
                        $get_program_result = mysqli_query($connection, $get_program_query);
                        while ($row = mysqli_fetch_assoc($get_program_result)) {
                            $ap_act_id = $row['ap_act_id'];
                            $ap_act_name = $row['ap_act_name'];
                            echo "<option value='$ap_act_id'>$ap_act_name</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Activity Name</label>
                    <input type="text" name="ap_ph_name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>

                <button type="submit" name="add-phase" style="width: 20%; font-size: 12px !important" class="btn btn-sm btn-outline-success">Add Activity</button>
            </form>
            <script>
                $(document).ready(function() {
                    // Phase Form: Update Activity Dropdown when Program changes
                    $('#ap_ph_main_id').on('change', function() {
                        let programId = $(this).val();
                        if (programId) {
                            $.ajax({
                                type: "GET",
                                data: {
                                    program_id: programId
                                },
                                success: function(data) {
                                    $('#ap_ph_act_id').html('<option selected disabled>Select Activity</option>' + data);
                                }
                            });
                        } else {
                            $('#ap_ph_act_id').html('<option selected disabled>Select Activity</option>');
                        }
                    });
                });
            </script>
        </div>


        <!-- ============================== DATABASE STRUCTURE SECTION ============================== -->
        <div class="col-md-6">
            <div class="table-responsive card p-4">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th style="font-size: 12px !important;" scope="col">#</th>
                            <th style="font-size: 12px !important;" scope="col">Program</th>
                            
                            <th style="font-size: 12px !important;" scope="col">Phase</th>
                            <th style="font-size: 12px !important;" scope="col">Activity</th>
                            <th style="font-size: 12px !important;" scope="col">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // DELETE PHASE
                        if (isset($_POST['delete_phase'])) {
                            $delete_id = mysqli_real_escape_string($connection, $_POST['delete_id']);
                            $delete_query = "DELETE FROM ap_phase WHERE ap_phase_id = '$delete_id'";
                            mysqli_query($connection, $delete_query);
                        }

                        // FETCH ALL PHASES JOINED WITH PROGRAM AND ACTIVITY NAMES
                        $fetch_query = "
                    SELECT 
                        ap_phase.ap_phase_id, 
                        ap_phase.ap_ph_main_id, 
                        ap_main.ap_name, 
                        ap_act.ap_act_name, 
                        ap_phase.ap_ph_name 
                    FROM ap_phase 
                    INNER JOIN ap_main ON ap_phase.ap_ph_main_id = ap_main.ap_id 
                    INNER JOIN ap_act ON ap_phase.ap_ph_act_id = ap_act.ap_act_id 
                    ORDER BY ap_phase.ap_ph_main_id DESC
                ";
                        $result = mysqli_query($connection, $fetch_query);
                        $serial = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $ph_id = $row['ap_phase_id']; // fixed
                            $program = $row['ap_name'];
                            $activity = $row['ap_act_name'];
                            $phase = $row['ap_ph_name'];
                        ?>
                            <tr>
                                <td style="font-size: 12px;"><?php echo $serial++; ?></td>
                                <td style="font-size: 12px;"><?php echo htmlspecialchars($program); ?></td>
                                <td style="font-size: 12px;"><?php echo htmlspecialchars($activity); ?></td>
                                <td style="font-size: 12px;"><?php echo htmlspecialchars($phase); ?></td>
                                <td>
                                    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this phase?');">
                                        <input type="hidden" name="delete_id" value="<?php echo $ph_id; ?>">
                                        <button type="submit" name="delete_phase" style="font-size: 12px !important;" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>



    </div>
</div>

<?php include 'includes/footer.php' ?>