<?php
include('includes/header.php');
include('includes/navbar.php');
include('includes/connection.php');
// include('includes/auth_check.php');

// normalize DB handle: support either $mysqli or $connection from your include
if (!isset($mysqli) && isset($connection)) {
    $mysqli = $connection;
}

$errors = [];
$success = '';

/* =================== HANDLE DELETE (POST) BEFORE OUTPUT =================== */
if (isset($_POST['delete-opp'])) {
    $opp_id = (int)($_POST['delete_opp_id'] ?? 0);
    if ($opp_id <= 0) {
        $errors[] = "Invalid delete request.";
    } else {
        if ($stmt = $mysqli->prepare("DELETE FROM opportunities WHERE opp_id = ?")) {
            $stmt->bind_param("i", $opp_id);
            if ($stmt->execute()) {
                $success = "Opportunity deleted (ID: {$opp_id}).";
            } else {
                $errors[] = "Delete failed: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors[] = "Prepare failed: " . $mysqli->error;
        }
    }
}

/* =================== HANDLE CREATE (POST) BEFORE OUTPUT =================== */
if (isset($_POST['add-opp'])) {
    // Read POST safely
    $title            = trim($_POST['title'] ?? '');
    $description      = trim($_POST['description'] ?? '');
    $category         = trim($_POST['category'] ?? '');
    $expected_benefit = trim($_POST['expected_benefit'] ?? '');
    $owner            = trim($_POST['owner'] ?? '');
    $status           = trim($_POST['status'] ?? '');
    $date_identified  = trim($_POST['date_identified'] ?? '');
    $review_date      = trim($_POST['review_date'] ?? '');

    // Basic validation
    if ($title === '')            $errors[] = "Title is required.";
    if ($description === '')      $errors[] = "Description is required.";
    if ($category === '')         $errors[] = "Category is required.";
    if ($expected_benefit === '') $errors[] = "Expected Benefit is required.";
    if ($owner === '')            $errors[] = "Owner is required.";
    if ($status === '')           $errors[] = "Status is required.";
    if ($date_identified === '')  $errors[] = "Date Identified is required.";
    if ($review_date === '')      $errors[] = "Review Date is required.";

    if (empty($errors)) {
        // INSERT
        $sql = "INSERT INTO opportunities
                (opp_title, opp_desc, opp_category, opp_ben, opp_owner, opp_status, opp_created_on, opp_review_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $mysqli->prepare($sql)) {
            // opp_desc is BLOB in DB; binding as string is fine for typical editor HTML
            $stmt->bind_param(
                "ssssssss",
                $title,
                $description,
                $category,
                $expected_benefit,
                $owner,
                $status,
                $date_identified,
                $review_date
            );
            if ($stmt->execute()) {
                $success = "Opportunity created (ID: {$stmt->insert_id}).";
                // Clear modal inputs on success
                $_POST = [];
            } else {
                $errors[] = "Insert failed: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errors[] = "Prepare failed: " . $mysqli->error;
        }
    }
}
?>
<div class="dashboard-container">
    <div class="screen-name-container mb-3">
        <h1>Opportunities & Treatments </h1>
        <h2><a href="index.php">Home</a> > Opportunities & Treatments </h2>
    </div>

    <!-- flash messages -->
    <?php if (!empty($success)) { ?>
        <div class="alert alert-success py-2" role="alert" style="font-size:12px;"><?php echo htmlspecialchars($success); ?></div>
    <?php } ?>
    <?php if (!empty($errors)) { ?>
        <div class="alert alert-danger py-2" role="alert" style="font-size:12px;">
            <?php foreach ($errors as $e) {
                echo '<div>' . htmlspecialchars($e) . '</div>';
            } ?>
        </div>
    <?php } ?>

    <button class="btn btn-sm btn-outline-success mb-3" data-bs-toggle="modal" data-bs-target="#createOpp">Create Opportunity</button>

    <!-- =========== CREATE OPPORTUNITY MODAL START =========== -->
    <div class="modal fade" id="createOpp" tabindex="-1" aria-labelledby="createOppLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="" method="POST" class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createOppLabel">Create Opportunity</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="opp_title" class="form-label">Title</label>
                        <input style="font-size: 12px !important;" type="text" class="form-control" id="opp_title" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label style="font-size: 12px !important;">Description</label>
                        <textarea id="editorNew" name="description"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="opp_category" class="form-label">Category</label>
                        <select style="font-size: 12px !important;" class="form-select" id="opp_category" name="category">
                            <option value="" <?php echo empty($_POST['category']) ? 'selected' : ''; ?>>Select a category</option>
                            <option value="Process" <?php echo (($_POST['category'] ?? '') === 'Process') ? 'selected' : ''; ?>>Process</option>
                            <option value="Product" <?php echo (($_POST['category'] ?? '') === 'Product') ? 'selected' : ''; ?>>Product</option>
                            <option value="Compliance" <?php echo (($_POST['category'] ?? '') === 'Compliance') ? 'selected' : ''; ?>>Compliance</option>
                            <option value="Cost" <?php echo (($_POST['category'] ?? '') === 'Cost') ? 'selected' : ''; ?>>Cost</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="opp_benefit" class="form-label">Expected Benefit</label>
                        <select style="font-size: 12px !important;" class="form-select" id="opp_benefit" name="expected_benefit">
                            <option value="" <?php echo empty($_POST['expected_benefit']) ? 'selected' : ''; ?>>Select benefit</option>
                            <option value="High" <?php echo (($_POST['expected_benefit'] ?? '') === 'High') ? 'selected' : ''; ?>>High</option>
                            <option value="Medium" <?php echo (($_POST['expected_benefit'] ?? '') === 'Medium') ? 'selected' : ''; ?>>Medium</option>
                            <option value="Low" <?php echo (($_POST['expected_benefit'] ?? '') === 'Low') ? 'selected' : ''; ?>>Low</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="opp_owner" class="form-label">Owner</label>
                        <select style="font-size: 12px !important;" class="form-select" id="opp_owner" name="owner">
                            <option value="" <?php echo empty($_POST['owner']) ? 'selected' : ''; ?>>Select owner</option>
                            <?php
                            // dynamic owners from `user` table
                            $userSql = "SELECT isms_user_id, isms_user_name FROM user ORDER BY isms_user_name ASC";
                            if ($res = $mysqli->query($userSql)) {
                                while ($rowU = $res->fetch_assoc()) {
                                    $selected = (($_POST['owner'] ?? '') == $rowU['isms_user_name']) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($rowU['isms_user_name']) . '" ' . $selected . '>'
                                        . htmlspecialchars($rowU['isms_user_name']) . '</option>';
                                }
                                $res->free();
                            } else {
                                echo '<option disabled>Error loading users</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="opp_status" class="form-label">Status</label>
                        <select style="font-size: 12px !important;" class="form-select" id="opp_status" name="status">
                            <option value="" <?php echo empty($_POST['status']) ? 'selected' : ''; ?>>Select status</option>
                            <option value="Open" <?php echo (($_POST['status'] ?? '') === 'Open') ? 'selected' : ''; ?>>Open</option>
                            <option value="In Progress" <?php echo (($_POST['status'] ?? '') === 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Closed" <?php echo (($_POST['status'] ?? '') === 'Closed') ? 'selected' : ''; ?>>Closed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="opp_date_identified" class="form-label">Date Identified</label>
                        <input style="font-size: 12px !important;" type="date" class="form-control" id="opp_date_identified" name="date_identified" value="<?php echo htmlspecialchars($_POST['date_identified'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="opp_review_date" class="form-label">Review Date</label>
                        <input style="font-size: 12px !important;" type="date" class="form-control" id="opp_review_date" name="review_date" value="<?php echo htmlspecialchars($_POST['review_date'] ?? ''); ?>">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="add-opp" class="btn btn-sm btn-outline-success" style="font-size: 12px !important;">Add</button>
                </div>
            </form>
        </div>
    </div>
    <!-- =========== CREATE OPPORTUNITY MODAL END =========== -->

    <!-- =========== DELETE CONFIRM MODAL (POSTS TO SAME PAGE) =========== -->
    <div class="modal fade" id="deleteOppModal" tabindex="-1" aria-labelledby="deleteOppLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" class="modal-content" id="deleteForm">
                <input type="hidden" name="delete_opp_id" id="delete_opp_id" value="">
                <div class="modal-header">
                    <h1 class="modal-title fs-6" id="deleteOppLabel">Delete Opportunity</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="font-size:13px;">
                    Are you sure you want to delete this opportunity?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="delete-opp" class="btn btn-sm btn-danger">Yes, delete</button>
                </div>
            </form>
        </div>
    </div>

    <!-- =========== OPPORTUNITY TABLE START =========== -->
    <div class="card p-3">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr class="risk-details-headers">
                    <th style="font-size:12px !important;">ID</th>
                    <th style="font-size:12px !important;">Title</th>
                    <th style="font-size:12px !important;">Category</th>
                    <th style="font-size:12px !important;">Benefit</th>
                    <th style="font-size:12px !important;">Owner</th>
                    <th style="font-size:12px !important;">Status</th>
                    <th style="font-size:12px !important;">Date Identified</th>
                    <th style="font-size:12px !important;">Review Date</th>
                    <?php if (($user_role ?? '') === '1') { ?>
                        <th style="font-size:12px !important;">Delete</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT opp_id, opp_title, opp_category, opp_ben, opp_owner, opp_status, opp_created_on, opp_review_date
                        FROM opportunities
                        ORDER BY opp_id DESC";
                if ($result = $mysqli->query($sql)) {
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td style="font-size:12px;"><?php echo htmlspecialchars($row['opp_id']); ?></td>
                                <td style="font-size:12px;">
                                    <a href="opp-details.php?id=<?php echo urlencode($row['opp_id']); ?>">
                                        <?php echo htmlspecialchars($row['opp_title']); ?>
                                    </a>
                                </td>
                                <td style="font-size:12px;"><?php echo htmlspecialchars($row['opp_category']); ?></td>
                                <td style="font-size:12px;"><?php echo htmlspecialchars($row['opp_ben']); ?></td>
                                <td style="font-size:12px;"><?php echo htmlspecialchars($row['opp_owner']); ?></td>
                                <td style="font-size:12px;"><?php echo htmlspecialchars($row['opp_status']); ?></td>
                                <td style="font-size:12px;"><?php echo htmlspecialchars($row['opp_created_on']); ?></td>
                                <td style="font-size:12px;"><?php echo htmlspecialchars($row['opp_review_date']); ?></td>
                                <?php if (($user_role ?? '') === '1') { ?>
                                    <td style="font-size:12px;">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-danger delete-btn"
                                            style="font-size:12px;"
                                            data-id="<?php echo htmlspecialchars($row['opp_id']); ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteOppModal">
                                            Delete
                                        </button>
                                    </td>
                                <?php } ?>
                            </tr>
                <?php }
                        $result->free();
                    } else {
                        echo '<tr><td colspan="9" class="text-center" style="font-size:12px;">No opportunities found.</td></tr>';
                    }
                } else {
                    echo '<tr><td colspan="9" class="text-danger" style="font-size:12px;">Error: ' . htmlspecialchars($mysqli->error) . '</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script>
    // Wire Delete buttons: pass row id into hidden input in the confirm modal
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            document.getElementById('delete_opp_id').value = id;
        });
    });
</script>