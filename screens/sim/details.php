<?php
$user_name = isset($_COOKIE['user_name']) ? $_COOKIE['user_name'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest');
$user_role = isset($_COOKIE['user_role']) ? $_COOKIE['user_role'] : (isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Guest');
?>
<div class="dashboard-container">
    <?php
    include 'includes/connection.php';

    if (isset($_GET['id'])) {
        $sim_id = intval($_GET['id']); // Ensuring it's an integer to prevent SQL injection
        $query = "SELECT * FROM sim WHERE sim_id = ?";
        $stmt = mysqli_prepare($connection, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $sim_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                $sim_topic = htmlspecialchars($row['sim_topic']);
                $sim_details = htmlspecialchars($row['sim_details']);
                $sim_status = htmlspecialchars($row['sim_status']);
                $sim_severity = htmlspecialchars($row['sim_severity']);
                $sim_source = htmlspecialchars($row['sim_source']);
                $sim_type = htmlspecialchars($row['sim_type']);
                $sim_final = htmlspecialchars($row['sim_final']);
                $sim_reported_date = htmlspecialchars($row['sim_reported_date']);
            } else {
                echo "<p id='alertBox' class='alert alert-warning'>No record found.</p>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<p id='alertBox' class='alert alert-danger'>Database error: " . mysqli_error($connection) . "</p>";
        }
    }

    if (isset($_POST['update-sim-detail']) && isset($sim_id)) {
        $sim_details = trim($_POST['sim_details']); // Trim to remove unwanted spaces
        $update_query = "UPDATE sim SET sim_details = ? WHERE sim_id = ?";
        $stmt = mysqli_prepare($connection, $update_query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $sim_details, $sim_id);
            if (mysqli_stmt_execute($stmt)) {
                echo "<p id='alertBox' class='alert alert-success'>Details updated successfully!</p>";
            } else {
                echo "<p id='alertBox' class='alert alert-danger'>Error updating details: " . mysqli_error($connection) . "</p>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<p id='alertBox' class='alert alert-danger'>Failed to prepare statement.</p>";
        }
    }

    if (isset($_POST['update-sim-final']) && isset($sim_id)) {
        $sim_details = trim($_POST['sim_details']);
        $close_sim_query = "UPDATE sim SET sim_details = ?, sim_final = '2' WHERE sim_id = ?";
        $stmt = mysqli_prepare($connection, $close_sim_query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $sim_details, $sim_id);
            if (mysqli_stmt_execute($stmt)) {
                echo "<p id='alertBox' class='alert alert-success'>SIM finalized and closed successfully!</p>";
            } else {
                echo "<p id='alertBox' class='alert alert-danger'>Error finalizing SIM: " . mysqli_error($connection) . "</p>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "<p id='alertBox' class='alert alert-danger'>Failed to prepare statement.</p>";
        }
    }
    ?>
    <div class="WYSIWYG-editor-container">
        <form action="" method="POST">
            <div class="WYSIWYG-editor">
                <?php if ($sim_final == '2') { ?>
                    <p><?php echo !empty($sim_details) ? htmlspecialchars_decode($sim_details)  : 'No details available.'; ?></p>
                <?php } else { ?>
                    <textarea id="editorNew" name="sim_details"><?php echo $sim_details; ?></textarea>
                <?php } ?>
            </div>
            
            <?php if ($sim_final == "2") { ?>
                <button type="submit" style="display: none" name="update-sim-detail" class="btn btn-primary mt-3">Save Details</button>
                <button type="submit" style="display: none" name="update-sim-final" class="btn btn-success mt-3">Submit Details</button>
            <?php } else { ?>
                <button type="submit" name="update-sim-detail" class="btn btn-primary mt-3">Save Details</button>
                <button type="submit" name="update-sim-final" class="btn btn-success mt-3">Submit Details</button>
            <?php } ?>
        </form>
    </div>
</div>
