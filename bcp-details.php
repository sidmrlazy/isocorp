<?php
include('includes/header.php');
include('includes/navbar.php');
include('includes/connection.php');

$bcp_id = intval($_GET['id'] ?? 0);

// Update BCP record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_bcp'])) {
    $review_date = mysqli_real_escape_string($connection, $_POST['bcp_review_date']);
    $assigned_to = mysqli_real_escape_string($connection, $_POST['bcp_assigned_to']);
    $details = mysqli_real_escape_string($connection, $_POST['bcp_details']);
    $status = mysqli_real_escape_string($connection, $_POST['bcp_status']);

    $query = "UPDATE bcp SET 
                bcp_review_date = '$review_date', 
                bcp_assigned_to = '$assigned_to', 
                bcp_details = '$details',
                bcp_status = '$status' 
              WHERE bcp_id = $bcp_id";
    mysqli_query($connection, $query);
}

// Fetch BCP record
$query = "SELECT * FROM bcp WHERE bcp_id = $bcp_id LIMIT 1";
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($result);

// Fetch Users
$users_result = mysqli_query($connection, "SELECT isms_user_name FROM user");
?>

<div class="dashboard-container mb-5">
    <div class="row">
        <!-- LEFT SECTION -->
        <div class="col-md-6">
            <?php if ($row): ?>
                <div class="card p-3">
                    <form method="POST">
                        <p><strong>Topic:</strong> <?php echo htmlspecialchars($row['bcp_topic']); ?></p>

                        <?php if ($user_role == "2") { ?>
                            <div class="mb-3">
                                <label style="font-size: 12px !important;" class="form-label">Review Date</label>
                                <input disabled style="font-size: 12px !important;" type="date" class="form-control" name="bcp_review_date" value="<?php echo htmlspecialchars($row['bcp_review_date']); ?>">
                            </div>
                        <?php } else { ?>
                            <div class="mb-3">
                                <label style="font-size: 12px !important;" class="form-label">Review Date</label>
                                <input style="font-size: 12px !important;" type="date" class="form-control" name="bcp_review_date" value="<?php echo htmlspecialchars($row['bcp_review_date']); ?>">
                            </div>
                        <?php } ?>

                        <?php if ($user_role == "2") { ?>
                            <div class="mb-3">
                                <label style="font-size: 12px !important;" class="form-label">Assigned To</label>
                                <select disabled style="font-size: 12px !important;" class="form-select" name="bcp_assigned_to" required>
                                    <option disabled <?php if (empty($row['bcp_assigned_to'])) echo "selected"; ?>>Select user</option>
                                    <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                                        <option value="<?php echo $user['isms_user_name']; ?>" <?php echo ($row['bcp_assigned_to'] == $user['isms_user_name']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($user['isms_user_name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <div class="mb-3">
                                <label style="font-size: 12px !important;" class="form-label">Assigned To</label>
                                <select style="font-size: 12px !important;" class="form-select" name="bcp_assigned_to" required>
                                    <option disabled <?php if (empty($row['bcp_assigned_to'])) echo "selected"; ?>>Select user</option>
                                    <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                                        <option value="<?php echo $user['isms_user_name']; ?>" <?php echo ($row['bcp_assigned_to'] == $user['isms_user_name']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($user['isms_user_name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>div>
                        <?php } ?>

                        <?php if ($user_role == "2") { ?>
                            <div class="mb-3">
                                <label style="font-size: 12px !important;" class="form-label">Status</label>
                                <select disabled style="font-size: 12px !important;" class="form-select" name="bcp_status" required>
                                    <option disabled <?php if (empty($row['bcp_status'])) echo "selected"; ?>>Select status</option>
                                    <option value="Complete" <?php if ($row['bcp_status'] == 'Complete') echo "selected"; ?>>Complete</option>
                                    <option value="In-progress" <?php if ($row['bcp_status'] == 'In-progress') echo "selected"; ?>>In-progress</option>
                                    <option value="Not Approved" <?php if ($row['bcp_status'] == 'Not Approved') echo "selected"; ?>>Not Approved</option>
                                </select>
                            </div>
                        <?php } else { ?>
                            <div class="mb-3">
                                <label style="font-size: 12px !important;" class="form-label">Status</label>
                                <select style="font-size: 12px !important;" class="form-select" name="bcp_status" required>
                                    <option disabled <?php if (empty($row['bcp_status'])) echo "selected"; ?>>Select status</option>
                                    <option value="Complete" <?php if ($row['bcp_status'] == 'Complete') echo "selected"; ?>>Complete</option>
                                    <option value="In-progress" <?php if ($row['bcp_status'] == 'In-progress') echo "selected"; ?>>In-progress</option>
                                    <option value="Not Approved" <?php if ($row['bcp_status'] == 'Not Approved') echo "selected"; ?>>Not Approved</option>
                                </select>
                            </div>
                        <?php } ?>

                        <div class="mb-3">
                            <label style="font-size: 12px !important;" class="form-label"><strong>Details</strong></label>
                            <textarea class="form-control" name="bcp_details" id="editorNew" rows="12"><?php echo htmlspecialchars($row['bcp_details']); ?></textarea>
                        </div>

                        <?php if ($user_role == "2") { ?>
                            <div class="d-none justify-content-end">
                                <button style="font-size: 12px !important;" type="submit" name="update_bcp" class="btn btn-sm btn-outline-success">Update</button>
                            </div>
                        <?php } else { ?>
                            <div class="d-flex justify-content-end">
                                <button style="font-size: 12px !important;" type="submit" name="update_bcp" class="btn btn-sm btn-outline-success">Update</button>
                            </div>
                        <?php } ?>
                    </form>
                </div>
            <?php else: ?>
                <div class="alert alert-danger mt-3">BCP not found.</div>
            <?php endif; ?>
        </div>

        <!-- RIGHT SECTION -->
        <div class="col-md-6">
            <div class="card p-3">
                <!-- ADD DOC BUTTON -->

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label style="font-size: 12px !important;">Documents</label>
                    <?php if ($user_role == "2") { ?>
                        <button style="font-size: 12px !important;" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="d-none btn btn-sm btn-outline-success"><ion-icon name="add-outline"></ion-icon></button>
                    <?php } else { ?>
                        <button style="font-size: 12px !important;" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-sm btn-outline-success"><ion-icon name="add-outline"></ion-icon></button>
                    <?php } ?>
                </div>


                <?php
                // Handle upload
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_doc'])) {
                    if (isset($_FILES['bcp_document']) && $_FILES['bcp_document']['error'] === UPLOAD_ERR_OK) {
                        $fileTmpPath = $_FILES['bcp_document']['tmp_name'];
                        $originalFileName = basename($_FILES['bcp_document']['name']);

                        // Sanitize file name
                        $sanitizedFileName = preg_replace("/[^A-Za-z0-9\-_\.]/", "_", $originalFileName);
                        $newFileName = time() . '_' . $sanitizedFileName;

                        $uploadDir = 'uploads/bcp_docs/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        $destPath = $uploadDir . $newFileName;
                        if (move_uploaded_file($fileTmpPath, $destPath)) {
                            $insertDoc = "INSERT INTO bcp_doc (bcp_id, doc_filename) VALUES ($bcp_id, '$newFileName')";
                            mysqli_query($connection, $insertDoc);
                        }
                    }
                }

                // Handle deletion
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_bcp_doc'])) {
                    $docId = intval($_POST['delete_doc_id']);
                    $docFile = basename($_POST['delete_doc_file']);
                    $filePath = 'uploads/bcp_docs/' . $docFile;

                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }

                    mysqli_query($connection, "DELETE FROM bcp_doc WHERE bcp_doc_id = $docId");
                }

                // Fetch documents
                $docs_query = "SELECT * FROM bcp_doc WHERE bcp_id = $bcp_id ORDER BY uploaded_at DESC";
                $docs_result = mysqli_query($connection, $docs_query);
                ?>

                <!-- DOC TABLE -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th style="font-size: 12px !important;">#</th>
                                <th style="font-size: 12px !important;">Filename</th>
                                <th style="font-size: 12px !important;">Uploaded</th>
                                <th style="font-size: 12px !important;">Download</th>
                                <?php if ($user_role == "2") { ?>
                                    <th style="font-size: 12px !important;" class="d-none">Delete</th>
                                <?php } else { ?>
                                    <th style="font-size: 12px !important;">Delete</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1;
                            while ($doc = mysqli_fetch_assoc($docs_result)): ?>
                                <tr>
                                    <td style="font-size: 12px !important;"><?php echo $count++; ?></td>
                                    <td style="font-size: 12px !important;"><?php echo htmlspecialchars($doc['doc_filename']); ?></td>
                                    <td style="font-size: 12px !important;"><?php echo $doc['uploaded_at']; ?></td>
                                    <td style="font-size: 12px !important;">
                                        <a href="uploads/bcp_docs/<?php echo rawurlencode($doc['doc_filename']); ?>" class="btn btn-sm btn-outline-primary" style="font-size: 12px !important;" download>Download</a>
                                    </td>
                                    <?php if ($user_role == "2") { ?>
                                        <td class="d-none" style="font-size: 12px !important;">
                                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                                <input style="font-size: 12px !important;" type="hidden" name="delete_doc_id" value="<?php echo $doc['bcp_doc_id']; ?>">
                                                <input style="font-size: 12px !important;" type="hidden" name="delete_doc_file" value="<?php echo htmlspecialchars($doc['doc_filename']); ?>">
                                                <button style="font-size: 12px !important;" type="submit" name="delete_bcp_doc" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </td>
                                    <?php } else { ?>
                                        <td style="font-size: 12px !important;">
                                            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                                <input style="font-size: 12px !important;" type="hidden" name="delete_doc_id" value="<?php echo $doc['bcp_doc_id']; ?>">
                                                <input style="font-size: 12px !important;" type="hidden" name="delete_doc_file" value="<?php echo htmlspecialchars($doc['doc_filename']); ?>">
                                                <button style="font-size: 12px !important;" type="submit" name="delete_bcp_doc" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- ADD DOC MODAL -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <form action="" method="POST" enctype="multipart/form-data" class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Add document</h1>
                                <button style="font-size: 12px !important;" type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label style="font-size: 12px !important;" class="form-label">Select Document</label>
                                    <input style="font-size: 12px !important;" type="file" class="form-control" name="bcp_document" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button style="font-size: 12px !important;" type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                <button style="font-size: 12px !important;" type="submit" name="upload_doc" class="btn btn-sm btn-outline-success">Upload</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>