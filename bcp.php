<?php
include('includes/header.php');
include('includes/navbar.php');
include('includes/connection.php'); // Make sure this connects to your DB

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Guest');
$user_name = isset($_COOKIE['user_name']) ? $_COOKIE['user_name'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest');
$user_role = isset($_COOKIE['user_role']) ? $_COOKIE['user_role'] : (isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Guest');

// Submit new BCP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_bcp'])) {
    $topic = mysqli_real_escape_string($connection, $_POST['bcp_topic'] ?? '');
    $details = mysqli_real_escape_string($connection, $_POST['bcp_details'] ?? '');
    $upload_date = mysqli_real_escape_string($connection, $_POST['bcp_upload_date'] ?? '');
    $review_date = mysqli_real_escape_string($connection, $_POST['bcp_review_date'] ?? '');
    $uploaded_by = $user_name ?? 'admin';

    $query = "INSERT INTO bcp (bcp_topic, bcp_details, bcp_upload_date, bcp_review_date, bcp_uploaded_by) 
              VALUES ('$topic', '$details', '$upload_date', '$review_date', '$uploaded_by')";
    mysqli_query($connection, $query);
}

?>
<div class="dashboard-container">
    <!-- ============ SCREEN NAME ============ -->
    <div class="screen-name-container">
        <h1>BUSINESS CONTINUITY PLAN</h1>
        <h2><a href="dashboard.php">Dashboard</a> > Business Continuity Plan</h2>
    </div>

    <!-- ============ BUTTON TO ADD BCP ============ -->
    <?php if ($user_role == "2") { ?>
        <div class="d-none justify-content-end mb-3" data-bs-toggle="modal" data-bs-target="#bcpModal">
            <button type="button" style="font-size: 12px !important;" class="btn btn-sm btn-outline-success">Create New BCP</button>
        </div>
    <?php } else { ?>
        <div class="d-flex justify-content-end mb-3" data-bs-toggle="modal" data-bs-target="#bcpModal">
            <button type="button" style="font-size: 12px !important;" class="btn btn-sm btn-outline-success">Create New BCP</button>
        </div>
    <?php } ?>

    <!-- ============ BCP MODAL ============ -->
    <div class="modal fade" id="bcpModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="POST" class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Add Business Continuity Plan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Upload Date</label>
                        <input style="font-size: 12px !important;" name="bcp_upload_date" type="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Review Date</label>
                        <input style="font-size: 12px !important;" name="bcp_review_date" type="date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Topic</label>
                        <input style="font-size: 12px !important;" name="bcp_topic" type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" class="form-label">Details</label>
                        <textarea name="bcp_details" class="form-control" id="editorNew" rows="6"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button style="font-size: 12px !important;" type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button style="font-size: 12px !important;" type="submit" name="save_bcp" class="btn btn-sm btn-outline-success">Save changes</button>
                </div>
            </div>
        </form>

    </div>

    <!-- ============ BCP TABLE ============ -->
    <div class="table-responsive card mt-3 p-3">
        <?php
        if (isset($_POST['delete_bcp']) && isset($_POST['delete_id'])) {
            $id = intval($_POST['delete_id']);
            mysqli_query($connection, "DELETE FROM bcp WHERE bcp_id = $id");
            echo "<div style='font-size: 12px !important;' class='alert alert-success mb-3' id='alertBox' role='alert'>Deleted</div>";
        }
        ?>

        <table class="table table-striped table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th style="font-size: 12px !important;" scope="col">Topic</th>
                    <th style="font-size: 12px !important;" scope="col">Assigned to</th>
                    <th style="font-size: 12px !important;" scope="col">Status</th>
                    <th style="font-size: 12px !important;" scope="col">Review Date</th>
                    <th style="font-size: 12px !important;" scope="col">View</th>
                    <?php if ($user_role == "2") { ?>
                        <th style="font-size: 12px !important;" class="d-none" scope="col">Delete</th>
                    <?php } else { ?>
                        <th style="font-size: 12px !important;" scope="col">Delete</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM bcp ORDER BY bcp_id DESC";
                $result = mysqli_query($connection, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td style='font-size: 12px;'>{$row['bcp_topic']}</td>";
                    echo "<td style='font-size: 12px;'>{$row['bcp_assigned_to']}</td>";
                    echo "<td style='font-size: 12px;'>{$row['bcp_status']}</td>";
                    echo "<td style='font-size: 12px;'>{$row['bcp_review_date']}</td>";
                    echo "<td>
            <a href='bcp-details.php?id={$row['bcp_id']}' class='btn btn-sm btn-outline-success' style='font-size: 12px;'>View</a>
          </td>";
          if($user_role == "2") {
                        echo "<td class='d-none'>
            <form method='POST' onsubmit='return confirm(\"Delete this BCP?\");'>
                <input type='hidden' name='delete_id' value='{$row['bcp_id']}'>
                <button style='font-size: 12px;' type='submit' name='delete_bcp' class='btn btn-sm btn-outline-danger'>Delete</button>
            </form>
          </td>"; } else {
                    echo "<td>
            <form method='POST' onsubmit='return confirm(\"Delete this BCP?\");'>
                <input type='hidden' name='delete_id' value='{$row['bcp_id']}'>
                <button style='font-size: 12px;' type='submit' name='delete_bcp' class='btn btn-sm btn-outline-danger'>Delete</button>
            </form>
          </td>";
          }
                    echo "</tr>";
                }
                ?>
            </tbody>

        </table>
    </div>
</div>
<?php include('includes/footer.php'); ?>