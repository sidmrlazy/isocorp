<?php
include('includes/header.php');
include('includes/navbar.php');
include 'includes/config.php';
include 'includes/connection.php';
?>
<div class="dashboard-container">
    <div class="screen-name-container">
        <h1>SECURITY INCIDENT MANAGEMENT</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Security Incident Management</h2>
    </div>

    <?php

    if (isset($_POST['delete'])) {
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $sim_id = mysqli_real_escape_string($connection, $_POST['sim_id']);

            // Ensure the ID is valid before deletion
            $delete_query = "DELETE FROM `sim` WHERE `SIM_ID` = $sim_id";
            $delete_query_r = mysqli_query($connection, $delete_query);

            if ($delete_query_r) {
                echo '<div id="alertBox" class="alert alert-success mt-5" role="alert">Incident deleted successfully!</div>';
            } else {
                throw new Exception("Failed to delete the incident.");
            }
        } catch (Exception $e) {
            echo '<div id="alertBox" class="alert alert-danger mt-5" role="alert">Error: ' . $e->getMessage() . '</div>';
        }
    }
    if (isset($_POST['create'])) {
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $sim_topic = mysqli_real_escape_string($connection, $_POST['sim_topic']);
            $sim_reported_by = $user_name;
            $query = "INSERT INTO `sim`(`SIM_TOPIC`, `SIM_REPORTED_BY`) VALUES ('$sim_topic', '$sim_reported_by')";
            $query_r = mysqli_query($connection, $query);
            if ($query_r) {
                echo '<div id="alertBox" class="alert alert-success mt-5" role="alert">Incident created!</div>';
            }
        } catch (Exception $e) {
            echo '<div id="alertBox" class="alert alert-danger mt-5" role="alert">Error: ' . $e->getMessage() . '</div>';
        }
    }

    if (isset($_POST['record'])) {
        try {

            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            if (
                empty($_POST['sim_id']) || empty($_POST['sim_status']) || empty($_POST['sim_severity']) ||
                empty($_POST['sim_source']) || empty($_POST['sim_type']) || empty($_POST['sim_reported_by'])
            ) {
                throw new Exception("All fields are required before recording the incident.");
            }

            $updated_sim_id = mysqli_real_escape_string($connection, $_POST['sim_id']);
            $updated_sim_status = mysqli_real_escape_string($connection, $_POST['sim_status']);
            $updated_sim_severity = mysqli_real_escape_string($connection, $_POST['sim_severity']);
            $updated_sim_source = mysqli_real_escape_string($connection, $_POST['sim_source']);
            $updated_sim_type = mysqli_real_escape_string($connection, $_POST['sim_type']);
            $updated_sim_reported_date = mysqli_real_escape_string($connection, $_POST['sim_reported_date']);
            $updated_sim_reported_by = mysqli_real_escape_string($connection, $_POST['sim_reported_by']);

            $update_query = "UPDATE `sim` SET 
                `SIM_STATUS`= '$updated_sim_status',
                `SIM_SEVERITY`= '$updated_sim_severity',
                `SIM_SOURCE`= '$updated_sim_source',
                `SIM_TYPE`= '$updated_sim_type',
                `SIM_REPORTED_DATE`= '$updated_sim_reported_date',
                `SIM_REPORTED_BY`= '$updated_sim_reported_by'
                WHERE SIM_ID = $updated_sim_id";

            $update_query_r = mysqli_query($connection, $update_query);

            if ($update_query_r) {
                echo '<div id="alertBox" class="alert alert-success mt-5" role="alert">Incident updated!</div>';
            }
        } catch (Exception $e) {
            echo '<div id="alertBox" class="alert alert-danger mt-5" role="alert">Error: ' . $e->getMessage() . '</div>';
        }
    }

    ?>
    <div class="sim-topic-container">
        <form action="" method="POST">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Create Topic</label>
                <input type="text" name="sim_topic" class="form-control" id="exampleInputEmail1"
                    aria-describedby="emailHelp" required>
            </div>
            <button type="submit" name="create" class="btn btn-primary">Create</button>
        </form>
    </div>
    <?php
    $records_per_page = 10;
    $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $records_per_page;
    $total_query = "SELECT COUNT(*) AS total FROM `sim`";
    $total_result = mysqli_query($connection, $total_query);
    $total_row = mysqli_fetch_assoc($total_result);
    $total_records = $total_row['total'];
    $total_pages = ceil($total_records / $records_per_page);
    $fetch = "SELECT * FROM `sim` LIMIT $records_per_page OFFSET $offset";
    $fetch_r = mysqli_query($connection, $fetch);
    $fetch_count = mysqli_num_rows($fetch_r);
    if ($fetch_count > 0) {
    ?>
        <div class="table-responsive sim-table-container mb-5">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="sim-th">
                        <th scope="col">ID</th>
                        <th scope="col">Topic</th>
                        <th scope="col">Type</th>
                        <th scope="col">Severity</th>
                        <th scope="col">Source</th>
                        <th scope="col">Status</th>
                        <th scope="col">Reported By</th>
                        <th scope="col">Reported Date</th>
                        <?php if ($user_role === '1') { ?>
                            <th class="text-center" scope="col">Action</th>
                        <?php } ?>
                        <th class="text-center" scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($fetch_r)) {
                        $sim_id = $row['sim_id'];
                        $sim_topic = $row['sim_topic'];
                        $sim_status = $row['sim_status'];
                        $sim_severity = $row['sim_severity'];
                        $sim_source = $row['sim_source'];
                        $sim_type = $row['sim_type'];
                        $sim_reported_date = $row['sim_reported_date'];
                        $sim_reported_by = $row['sim_reported_by'];
                    ?>
                        <tr class="sim-th">
                            <form action="" method="POST">
                                <th scope="row">
                                    <input type="hidden" name="sim_id" value="<?php echo $sim_id ?>">
                                    <?php echo $sim_id ?>
                                </th>
                                <td>
                                    <a href="sim-details.php?id=<?php echo $sim_id; ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($sim_topic); ?>
                                    </a>
                                </td>



                                <!-- ================ SEVERITY ================ -->
                                <?php if ($user_role == '1') { ?>
                                    <td>
                                        <select style="font-size: 12px;" name="sim_severity" class="form-select" required <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                            <option value="" disabled>Select Severity</option>
                                            <option value="1" <?= $sim_severity == "1" ? 'selected' : '' ?>>Incident</option>
                                            <option value="2" <?= $sim_severity == "2" ? 'selected' : '' ?>>Event</option>
                                        </select>
                                    </td>
                                <?php } elseif ($user_role == '2') { ?>
                                    <td>
                                        <?php
                                        if ($sim_severity == '1') {
                                            $new_sim_severity = "Incident";
                                        } elseif ($sim_severity == '2') {
                                            $new_sim_severity = "Event";
                                        } else {
                                            $new_sim_severity = "Action Pending";
                                        }
                                        ?>
                                        <input type="text" style="font-size: 12px;" value="<?php echo $new_sim_severity ?>" disabled>
                                    </td>
                                <?php } ?>

                                <!-- ================ SOURCE ================ -->
                                <?php if ($user_role == '1') { ?>
                                    <td>
                                        <select style="font-size: 12px;" name="sim_source" class="form-select" required <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                            <option value="" disabled>Select Source</option>
                                            <option value="1" <?= $sim_source == "1" ? 'selected' : '' ?>>External</option>
                                            <option value="2" <?= $sim_source == "2" ? 'selected' : '' ?>>Internal</option>
                                            <option value="3" <?= $sim_source == "3" ? 'selected' : '' ?>>Internal & External</option>
                                        </select>
                                    </td>
                                <?php } elseif ($user_role == '2') { ?>
                                    <td>
                                        <?php
                                        if ($sim_source == '1') {
                                            $new_sim_source = "External";
                                        } elseif ($sim_source == '2') {
                                            $new_sim_source = "Internal";
                                        } elseif ($sim_source == '3') {
                                            $new_sim_source = "Internal & External";
                                        } else {
                                            $new_sim_source = "Action Pending";
                                        }
                                        ?>
                                        <input style="font-size: 12px;" type="text" value="<?php echo $new_sim_source ?>" disabled>
                                    </td>
                                <?php } ?>

                                <!-- ================ INCIDENT TYPE ================ -->
                                <?php if ($user_role == '1') { ?>
                                    <td>
                                        <select style="font-size: 12px;" name="sim_type" class="form-select" required <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                            <option value="" disabled>Select Type</option>
                                            <option value="1" <?= $sim_type == "1" ? 'selected' : '' ?>>Confidentiality</option>
                                            <option value="2" <?= $sim_type == "2" ? 'selected' : '' ?>>Integrity</option>
                                            <option value="3" <?= $sim_type == "3" ? 'selected' : '' ?>>Availability</option>
                                        </select>
                                    </td>
                                <?php } elseif ($user_role == '2') { ?>
                                    <td>
                                        <?php
                                        if ($sim_type == '1') {
                                            $new_sim_type = "Confidentiality";
                                        } elseif ($sim_type == '2') {
                                            $new_sim_type = "Integrity";
                                        } elseif ($sim_type == '3') {
                                            $new_sim_type = "Availability";
                                        } else {
                                            $new_sim_type = "Action Pending";
                                        }
                                        ?>
                                        <input style="font-size: 12px;" type="text" value="<?php echo $new_sim_type ?>" disabled>
                                    </td>
                                <?php } ?>

                                <!-- ================ STATUS ================ -->
                                <?php if ($user_role == '1') { ?>
                                    <!-- Add feature to throw error if user tries to reolve the issue if the sim_final column is not 2 -->
                                    <td>
                                        <select style="font-size: 12px;" name="sim_status" class="form-select" required <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                            <option value="" disabled>Select Status</option>
                                            <option value="1" <?= $sim_status == "1" ? 'selected' : '' ?>>To-do</option>
                                            <option value="2" <?= $sim_status == "2" ? 'selected' : '' ?>>Resolved</option>
                                        </select>
                                    </td>
                                <?php } elseif ($user_role == '2') { ?>
                                    <?php
                                    if ($sim_status == '1') {
                                        $new_sim_status =  "To-Do";
                                    } elseif ($sim_status == '2') {
                                        $new_sim_status = "Resolved";
                                    } else {
                                        $new_sim_status = "Action Pending";
                                    }
                                    ?>
                                    <td>
                                        <input style="font-size: 12px;" type="text" value="<?php echo $new_sim_status ?>" disabled>
                                    </td>
                                <?php } ?>

                                <td>
                                    <p><?php echo $sim_reported_by ?></p>
                                </td>

                                <!-- <td class="text-center" style="font-size: 12px;">
                                    <?php
                                    $formatted_date = (!empty($sim_reported_date) && $sim_reported_date !== '0000-00-00')
                                        ? date('m-d-Y', strtotime($sim_reported_date))
                                        : date('m-d-Y');
                                    ?>
                                    <input type="date" style="font-size: 12px;" name="sim_reported_date" value="<?php echo htmlspecialchars($formatted_date); ?>">
                                </td> -->

                                <?php if ($sim_status == '1') { ?>
                                    <td class="text-center small-text">
                                        <?php
                                        $formatted_date = (!empty($sim_reported_date) && $sim_reported_date !== '0000-00-00')
                                            ? date('Y-m-d', strtotime($sim_reported_date)) // Ensuring correct format for <input type="date">
                                            : date('Y-m-d');
                                        ?>
                                        <input type="date" class="small-text" name="sim_reported_date" value="<?php echo htmlspecialchars($formatted_date); ?>">
                                    </td>
                                <?php } elseif ($sim_status == '2') { ?>
                                    <td class="text-center small-text">

                                        <input type="text" class="small-text" name="sim_reported_date" value="<?php echo htmlspecialchars($formatted_date); ?>" disabled>
                                    </td>
                                <?php } ?>

                                <?php if ($user_role === '1') { ?>
                                    <input type="hidden" value="<?php echo htmlspecialchars($user_name); ?>" name="sim_reported_by">
                                    <td class="text-center">
                                        <button type="submit" style="font-size: 12px;" name="record" class="btn btn-sm btn-outline-success"
                                            <?= $sim_status == "2" ? 'disabled' : '' ?>>Record</button>
                                    </td>
                                <?php } ?>

                                <td class="text-center">
                                    <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this incident?');">
                                        <input type="hidden" name="sim_id" value="<?php echo $sim_id ?>">
                                        <button type="submit" style="font-size: 12px;" name="delete" class="btn btn-sm btn-outline-danger">
                                            <ion-icon name="trash-outline"></ion-icon>
                                        </button>
                                    </form>
                                </td>
                            </form>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <nav aria-label="Page navigation" class="mt-3 d-flex justify-content-center">
                <ul class="pagination">
                    <!-- Previous Page -->
                    <li class="page-item <?php if ($current_page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo max(1, $current_page - 1); ?>">Previous</a>
                    </li>

                    <!-- Page Numbers -->
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                        <li class="page-item <?php if ($i == $current_page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php } ?>

                    <!-- Next Page -->
                    <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo min($total_pages, $current_page + 1); ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php } else { ?>
        <div class="alert alert-danger mt-5" role="alert">No security incidents recorded!</div>
    <?php } ?>
</div>
<?php include('includes/footer.php');
