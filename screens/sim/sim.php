<?php $user_name = isset($_COOKIE['user_name']) ? $_COOKIE['user_name'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest'); ?>
<div class="dashboard-container">

    <div class="screen-name-container">
        <h1>SECURITY INCIDENT MANAGEMENT</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Security Incident Management</h2>
    </div>

    <?php
    include 'includes/connection.php';
    if (isset($_POST['create'])) {
        try {
            include 'includes/connection.php';
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $sim_topic = mysqli_real_escape_string($connection, $_POST['sim_topic']);
            $query = "INSERT INTO `sim`(`SIM_TOPIC`) VALUES ('$sim_topic')";
            $query_r = mysqli_query($connection, $query);
            if ($query_r) {
                echo '<div class="alert alert-success mt-5" role="alert">Incident created!</div>';
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger mt-5" role="alert">Error: ' . $e->getMessage() . '</div>';
        }
    }

    if (isset($_POST['record'])) {
        try {
            include 'includes/connection.php';
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            // Validate required fields
            if (
                empty($_POST['sim_id']) || empty($_POST['sim_status']) || empty($_POST['sim_severity']) ||
                empty($_POST['sim_source']) || empty($_POST['sim_type']) || empty($_POST['sim_reported_by'])
            ) {
                throw new Exception("All fields are required before recording the incident.");
            }

            // Escape user inputs
            $updated_sim_id = mysqli_real_escape_string($connection, $_POST['sim_id']);
            $updated_sim_status = mysqli_real_escape_string($connection, $_POST['sim_status']);
            $updated_sim_severity = mysqli_real_escape_string($connection, $_POST['sim_severity']);
            $updated_sim_source = mysqli_real_escape_string($connection, $_POST['sim_source']);
            $updated_sim_type = mysqli_real_escape_string($connection, $_POST['sim_type']);
            $updated_sim_reported_by = mysqli_real_escape_string($connection, $_POST['sim_reported_by']);

            // Execute update query
            $update_query = "UPDATE `sim` SET 
                `SIM_STATUS`= '$updated_sim_status',
                `SIM_SEVERITY`= '$updated_sim_severity',
                `SIM_SOURCE`= '$updated_sim_source',
                `SIM_TYPE`= '$updated_sim_type',
                `SIM_REPORTED_BY`= '$updated_sim_reported_by'
                WHERE SIM_ID = $updated_sim_id";

            $update_query_r = mysqli_query($connection, $update_query);

            if ($update_query_r) {
                echo '<div class="alert alert-success mt-5" role="alert">Incident updated!</div>';
            }
        } catch (Exception $e) {
            echo '<div class="alert alert-danger mt-5" role="alert">Error: ' . $e->getMessage() . '</div>';
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


    // $fetch = "SELECT * FROM `sim`";
    // $fetch_r = mysqli_query($connection, $fetch);
    // $fetch_count = mysqli_num_rows($fetch_r);
    if ($fetch_count > 0) {
    ?>
        <div class="table-responsive sim-table-container mb-5">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Topic</th>
                        <th scope="col">Status</th>
                        <th scope="col">Severity</th>
                        <th scope="col">Source</th>
                        <th scope="col">Type</th>
                        <th class="text-center" scope="col">Reported Date</th>
                        <th class="text-center" scope="col">Action</th>
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
                    ?>
                        <tr>
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
                                <td>
                                    <select name="sim_status" class="form-select" required <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                        <option value="" disabled>Select Status</option>
                                        <option value="1" <?= $sim_status == "1" ? 'selected' : '' ?>>To-do</option>
                                        <option value="2" <?= $sim_status == "2" ? 'selected' : '' ?>>Resolved</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="sim_severity" class="form-select" required <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                        <option value="" disabled>Select Severity</option>
                                        <option value="1" <?= $sim_severity == "1" ? 'selected' : '' ?>>Incident</option>
                                        <option value="2" <?= $sim_severity == "2" ? 'selected' : '' ?>>Event</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="sim_source" class="form-select" required <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                        <option value="" disabled>Select Source</option>
                                        <option value="1" <?= $sim_source == "1" ? 'selected' : '' ?>>External</option>
                                        <option value="2" <?= $sim_source == "2" ? 'selected' : '' ?>>Internal</option>
                                        <option value="3" <?= $sim_source == "3" ? 'selected' : '' ?>>Internal & External</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="sim_type" class="form-select" required <?= $sim_status == "2" ? 'disabled' : '' ?>>
                                        <option value="" disabled>Select Type</option>
                                        <option value="1" <?= $sim_type == "1" ? 'selected' : '' ?>>Confidentiality</option>
                                        <option value="2" <?= $sim_type == "2" ? 'selected' : '' ?>>Integrity</option>
                                        <option value="3" <?= $sim_type == "3" ? 'selected' : '' ?>>Availability</option>
                                    </select>
                                </td>
                                <td class="text-center"><?php echo $sim_reported_date ?></td>
                                <input type="hidden" value="<?php echo htmlspecialchars($user_name); ?>" name="sim_reported_by">
                                <td class="text-center">
                                    <button type="submit" name="record" class="btn btn-sm btn-outline-success"
                                        <?= $sim_status == "2" ? 'disabled' : '' ?>>Record</button>
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