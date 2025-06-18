<?php
if (!isset($_SESSION['user_session']) && !isset($_COOKIE['user_session'])) {
    // Send a 404 Not Found response
    http_response_code(404);
    exit();
}
include('includes/header.php');
include('includes/navbar.php');
include 'includes/connection.php'; ?>

<div class="dashboard-container">
    <?php
    if (isset($_POST['delete'])) {
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $sim_id = mysqli_real_escape_string($connection, $_POST['sim_id']);

            // Ensure the ID is valid before deletion
            $delete_query = "DELETE FROM `sim` WHERE `SIM_ID` = '$sim_id'";
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



    if (isset($_POST['record'])) {
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            if (
                empty($_POST['sim_id']) || empty($_POST['sim_status']) || empty($_POST['sim_severity']) ||
                empty($_POST['sim_source']) || empty($_POST['sim_type']) || empty($_POST['sim_reported_by'])
            ) {
                throw new Exception("All required fields must be filled.");
            }

            $updated_sim_id = mysqli_real_escape_string($connection, $_POST['sim_id']);
            $updated_sim_status = mysqli_real_escape_string($connection, $_POST['sim_status']);
            $updated_sim_severity = mysqli_real_escape_string($connection, $_POST['sim_severity']);
            $updated_sim_source = mysqli_real_escape_string($connection, $_POST['sim_source']);
            $updated_sim_type = mysqli_real_escape_string($connection, $_POST['sim_type']);
            $updated_sim_reported_by = mysqli_real_escape_string($connection, $_POST['sim_reported_by']);
            $updated_sim_assigned_to = mysqli_real_escape_string($connection, $_POST['sim_assigned_to']);
            $updated_sim_start_date = mysqli_real_escape_string($connection, $_POST['sim_start_date']);
            $updated_sim_end_date = mysqli_real_escape_string($connection, $_POST['sim_end_date']);
            $updated_sim_due_date = mysqli_real_escape_string($connection, $_POST['sim_due_date']);

            // Fetch sim_final and sim_details from the database
            $check_final_query = "SELECT sim_final, sim_details FROM `sim` WHERE sim_id = '$updated_sim_id'";
            $check_final_result = mysqli_query($connection, $check_final_query);

            if (!$check_final_result || mysqli_num_rows($check_final_result) == 0) {
                throw new Exception("Incident not found.");
            }

            $row = mysqli_fetch_assoc($check_final_result);
            $existing_sim_final = $row['sim_final'];
            $existing_sim_details = $row['sim_details'];

            // Prevent changing status to resolved unless sim_final is 2
            if ($updated_sim_status == '2' && $existing_sim_final != '2') {
                throw new Exception("Incident cannot be marked as resolved unless details are finalized.");
            }

            // Use existing sim_details if nothing new is provided
            $updated_sim_details = isset($_POST['sim_details']) && trim($_POST['sim_details']) !== ''
                ? mysqli_real_escape_string($connection, $_POST['sim_details'])
                : $existing_sim_details;

            $update_query = "UPDATE `sim` SET
    `sim_status` = '$updated_sim_status',
    `sim_severity` = '$updated_sim_severity',
    `sim_source` = '$updated_sim_source',
    `sim_type` = '$updated_sim_type',
    `sim_reported_by` = '$updated_sim_reported_by',
    `sim_assigned_to` = '$updated_sim_assigned_to',
    `sim_start_date` = '$updated_sim_start_date',
    `sim_end_date` = '$updated_sim_end_date',
    `sim_due_date` = '$updated_sim_due_date',
    `sim_details` = '$updated_sim_details'
    WHERE `sim_id` = '$updated_sim_id'"; // Escape $updated_sim_id

            $update_query_r = mysqli_query($connection, $update_query);

            if ($update_query_r) {
                echo '<div id="alertBox" class="alert alert-success mt-5" role="alert">Incident updated!</div>';
            }
        } catch (Exception $e) {
            echo '<div id="alertBox" class="alert alert-danger mt-5" role="alert">Error: ' . $e->getMessage() . '</div>';
        }
    }

    ?>
    <div class="screen-name-container">
        <h1>SECURITY INCIDENT MANAGEMENT</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Security Incident Management</h2>
    </div>
    <?php

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
    ?>
    <!-- =========== CREATE TOPIC SECTION =========== -->
    <div class="card p-3 mt-3">
        <form action="" method="POST">
            <div class="mb-3">
                <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Create Topic</label>
                <input style="font-size: 12px !important;" type="text" name="sim_topic" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required placeholder="Enter topic">
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" name="create" class="btn btn-sm btn-outline-success">Create</button>
            </div>
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
        <div class="table-responsive card mb-5 p-3 mt-3">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr class="sim-th">
                        <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">ID</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Name</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Status</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Severity</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Source</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Type</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Assigned to</th>
                        <!-- <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Reported By</th> -->
                        <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Start Date</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">End Date</th>
                        <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Due Date</th>
                        <!-- <th style="font-size: 12px !important; font-weight: 600 !important;" scope="col">Reported Date</th> -->

                        <?php if ($user_role === '1') { ?>
                            <th style="font-size: 12px !important; font-weight: 600 !important;" class="text-center" scope="col">Action</th>
                        <?php } ?>
                        <th style="font-size: 12px !important; font-weight: 600 !important;" class="text-center" scope="col">Delete</th>
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
                        $sim_start_date = $row['sim_start_date'];
                        $sim_end_date = $row['sim_end_date'];
                        $sim_due_date = $row['sim_due_date'];
                        $sim_assigned_to = $row['sim_assigned_to'];
                    ?>
                        <tr class="sim-th">
                            <form action="" method="POST">
                                <!-- ================ SERIAL NUMBER ================ -->
                                <th scope="row" style="font-size: 12px !important;">
                                    <input type="hidden" name="sim_id" value="<?php echo $sim_id ?>">
                                    <?php echo $sim_id ?>
                                </th>

                                <!-- ================ TOPIC ================ -->
                                <td>
                                    <a href="sim-details.php?id=<?php echo $sim_id; ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($sim_topic); ?>
                                    </a>
                                </td>

                                <!-- ================ STATUS ================ -->
                                <td>
                                    <select style="font-size: 12px;" name="sim_status" class="form-select" required <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                        <option value="" disabled>Select Status</option>
                                        <option value="1" <?= $sim_status == "1" ? 'selected' : '' ?>>To-do</option>
                                        <option value="2" <?= $sim_status == "2" ? 'selected' : '' ?>>Resolved</option>
                                    </select>
                                </td>

                                <!-- ================ SEVERITY ================ -->
                                <td>
                                    <select style="font-size: 12px;" name="sim_severity" class="form-select" required <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                        <option value="" disabled>Select Status</option>
                                        <option value="1" <?= $sim_severity == "1" ? 'selected' : '' ?>>Incident</option>
                                        <option value="2" <?= $sim_severity == "2" ? 'selected' : '' ?>>Event</option>
                                        <option value="2" <?= $sim_severity == "3" ? 'selected' : '' ?>>Weakness</option>
                                    </select>
                                </td>

                                <!-- ================ SOURCE ================ -->
                                <td>
                                    <select style="font-size: 12px;" name="sim_source" class="form-select" required <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                        <option value="" disabled>Select Severity</option>
                                        <option value="1" <?= $sim_source == "1" ? 'selected' : '' ?>>External</option>
                                        <option value="2" <?= $sim_source == "2" ? 'selected' : '' ?>>Internal</option>
                                        <option value="3" <?= $sim_source == "3" ? 'selected' : '' ?>>Internal & External</option>
                                    </select>
                                </td>

                                <!-- ================ TYPE ================ -->
                                <td>
                                    <select style="font-size: 12px;" name="sim_type" class="form-select" required <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                        <option value="" disabled>Select Source</option>
                                        <option value="1" <?= $sim_type == "1" ? 'selected' : '' ?>>Confidentiality</option>
                                        <option value="2" <?= $sim_type == "2" ? 'selected' : '' ?>>Integrity</option>
                                        <option value="3" <?= $sim_type == "3" ? 'selected' : '' ?>>Availability</option>
                                        <option value="4" <?= $sim_type == "4" ? 'selected' : '' ?>> </option>
                                    </select>
                                </td>

                                <!-- ================ ASSIGNED TO ================ -->
                                <td>
                                    <select style="font-size: 12px;" name="sim_assigned_to" class="form-select" required <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                        <option value="" disabled <?= empty($sim_assigned_to) ? 'selected' : '' ?>>Select User</option>
                                        <?php
                                        $get_user = "SELECT * FROM `user`";
                                        $get_user_r = mysqli_query($connection, $get_user);
                                        while ($row = mysqli_fetch_assoc($get_user_r)) {
                                            $fetched_user_name = $row['isms_user_name'];
                                            $selected = ($fetched_user_name == $sim_assigned_to) ? 'selected' : '';
                                        ?>
                                            <option value="<?= htmlspecialchars($fetched_user_name) ?>" <?= $selected ?>>
                                                <?= htmlspecialchars($fetched_user_name) ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </td>

                                <!-- ================ REPORTED BY ================ -->
                                <!-- <td>
                                    <p><?php echo $sim_reported_by ?></p>
                                </td> -->

                                <!-- ================ REPORTED DATE ================ -->
                                <!-- <td class="text-center small-text">
                                    <?php
                                    $formatted_date = (!empty($sim_reported_date) && $sim_reported_date !== '0000-00-00')
                                        ? date('Y-m-d', strtotime($sim_reported_date))
                                        : date('Y-m-d');
                                    $disabled = ($sim_status == '1' || $sim_status == NULL) ? '' : 'disabled';
                                    ?>

                                    <input
                                        type="date"
                                        class="form-control"
                                        style="font-size: 12px;"
                                        name="sim_reported_date"
                                        value="<?php echo htmlspecialchars($formatted_date); ?>" <?php echo $disabled; ?>>
                                </td> -->

                                <!-- ================ START DATE ================ -->
                                <td>
                                    <div class="mb-3">
                                        <input
                                            type="date"
                                            name="sim_start_date"
                                            style="font-size: 12px;"
                                            class="form-control"
                                            value="<?= htmlspecialchars($sim_start_date) ?>"
                                            <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                    </div>
                                </td>

                                <!-- ================ END DATE ================ -->
                                <td>
                                    <div class="mb-3">
                                        <input
                                            type="date"
                                            name="sim_end_date"
                                            style="font-size: 12px;"
                                            class="form-control"
                                            value="<?= htmlspecialchars($sim_end_date) ?>"
                                            <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                    </div>
                                </td>

                                <!-- ================ DUE DATE ================ -->
                                <td>
                                    <div class="mb-3">
                                        <input
                                            type="date"
                                            name="sim_due_date"
                                            style="font-size: 12px;"
                                            class="form-control"
                                            value="<?= htmlspecialchars($sim_due_date) ?>"
                                            <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                    </div>
                                </td>

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
                                        <button type="submit" style="font-size: 12px !important;" name="delete" class="btn btn-sm btn-outline-danger" onclick="return confirmDelete()">Delete</button>
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
<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this incident?");
    }
</script>

<?php include('includes/footer.php');
