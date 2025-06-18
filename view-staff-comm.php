<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
?>

<div class="dashboard-container mb-5">
    <?php
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if (isset($_POST['del-comm'])) {
        $sf_comment_id = intval($_POST['sf_comment_id']); // safe conversion
        $delete_comment = mysqli_query($connection, "DELETE FROM sf_comments WHERE sf_comment_id='$sf_comment_id'") or die(mysqli_error($connection));
        if ($delete_comment) {
            echo "<p id='alertBox' style='font-size: 12px !important' class='alert alert-danger'>Comment deleted successfully!</p>";
        }
    }

    if (isset($_POST['save-det'])) {
        $comm_details = mysqli_real_escape_string($connection, $_POST['comm_details']);
        $update_query = "UPDATE staff_comm SET comm_details='$comm_details' WHERE comm_id=$id";
        $update_result = mysqli_query($connection, $update_query) or die(mysqli_error($connection));
        if ($update_result) {
            echo "<p id='alertBox' style='font-size: 12px !important' class='alert alert-success'>Details updated successfully!</p>";
        } else {
            echo "<p id='alertBox' style='font-size: 12px !important' class='alert alert-danger'>Failed to update details.</p>";
        }
    }


    // Fetch main communication data
    $query = mysqli_query($connection, "SELECT * FROM staff_comm WHERE comm_id = $id") or die(mysqli_error($connection));
    $data = mysqli_fetch_assoc($query);
    ?>
    <div class="row">
        <!-- ============ LEFT SECTION ============ -->
        <div class="col-md-6">
            <form action="" method="POST" class="card p-3">
                <p style="margin: 0; font-size: 12px !important;"><strong><?php echo $data['comm_by']; ?> -</strong> <?php echo $data['comm_date']; ?></p>
                <p style="margin: 0; font-size: 16px !important;"><strong>Topic:</strong> <?php echo $data['comm_data']; ?></p>

                <div class="mb-3 mt-3">
                    <label style="font-size: 12px;" for="edit">Details</label>
                    <div class="WYSIWYG-editor">
                        <!-- <textarea id="editorNew" name="comm_details"><?php echo $data['comm_details']; ?></textarea> -->
                        <textarea id="editorNew" name="comm_details"><?php echo htmlspecialchars($data['comm_details']); ?></textarea>

                    </div>
                </div>
                <button type="submit" name="save-det" class="btn btn-sm btn-outline-success" style="font-size: 12px !important;">Update</button>
            </form>
        </div>

        <!-- ============ RIGHT SECTION ============ -->
        <div class="col-md-6">
            <!-- =========== DOCUMENTS SECTION =========== -->
            <div class="card p-3 mb-3">
                <!-- =========== ADD DOCUMENT =========== -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label style="font-size: 12px !important;" for="">Documents</label>
                    <button type="button" style="font-size: 12px !important;" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-sm btn-outline-success">Add</button>
                </div>

                <?php
                $main_id = $_GET['id'] ?? ''; // This is the communication ID

                // Handle document upload
                if (isset($_POST['upload_document'])) {
                    $main_id_post = $_POST['stfc_doc_main_id'] ?? '';
                    $uploadDate = date('Y-m-d');

                    if (!empty($main_id_post) && isset($_FILES['document_file']) && $_FILES['document_file']['error'] == 0) {
                        $filename = basename($_FILES['document_file']['name']);
                        $targetPath = 'uploads/staff_comm_documents/' . $filename;

                        move_uploaded_file($_FILES['document_file']['tmp_name'], $targetPath);

                        $stmt = $connection->prepare("INSERT INTO staff_comm_doc (stfc_doc_filepath, stfc_doc_upload_date, stfc_doc_main_id) VALUES (?, ?, ?)");
                        $stmt->bind_param("sss", $targetPath, $uploadDate, $main_id_post);
                        $stmt->execute();
                    }
                }

                // Handle document deletion
                if (isset($_POST['delete_document'])) {
                    $doc_id = $_POST['doc_id'];
                    $query = $connection->query("SELECT stfc_doc_filepath FROM staff_comm_doc WHERE stfc_doc_id = $doc_id");
                    $row = $query->fetch_assoc();

                    if (file_exists($row['stfc_doc_filepath'])) {
                        unlink($row['stfc_doc_filepath']);
                    }

                    $connection->query("DELETE FROM staff_comm_doc WHERE stfc_doc_id = $doc_id");
                }
                ?>

                <!-- =========== SHOW DOCUMENT TABLE =========== -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th style="font-size: 12px !important;" scope="col">Document</th>
                                <th style="font-size: 12px !important;" scope="col">Download</th>
                                <th style="font-size: 12px !important;" scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $connection->query("SELECT * FROM staff_comm_doc WHERE stfc_doc_main_id = '$main_id'");
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                            ?>
                                    <tr>
                                        <td style="font-size: 12px !important;"><?php echo basename($row['stfc_doc_filepath']); ?></td>
                                        <td style="font-size: 12px !important;">
                                            <a href="<?php echo $row['stfc_doc_filepath']; ?>" style="font-size: 12px !important;" target="_blank" class="btn btn-sm btn-outline-primary">Download</a>
                                        </td>
                                        <td style="font-size: 12px !important;">
                                            <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                <input type="hidden" name="doc_id" value="<?php echo $row['stfc_doc_id']; ?>">
                                                <button type="submit" style="font-size: 12px !important;" name="delete_document" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="3" class="text-muted" style="font-size: 12px !important;">No Document Uploaded</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- =========== UPLOAD DOCUMENT MODAL =========== -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <form action="" method="POST" enctype="multipart/form-data" class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Upload Document</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Hidden field to carry communication ID -->
                                <input type="hidden" name="stfc_doc_main_id" value="<?php echo $main_id; ?>">

                                <div class="mb-3">
                                    <label style="font-size: 12px !important;" for="document_file" class="form-label">Select Document</label>
                                    <input style="font-size: 12px !important;" class="form-control" type="file" id="document_file" name="document_file" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button style="font-size: 12px !important;" type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                <button style="font-size: 12px !important;" type="submit" name="upload_document" class="btn btn-sm btn-outline-success">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- =========== COMMENTS SECTION =========== -->
            <div class="card p-3">
                <?php
                // Handle comment submission first
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-comment'])) {
                    $sf_comment_parent_id = mysqli_real_escape_string($connection, $_POST['sf_comment_parent_id']);
                    $sf_comment_by = mysqli_real_escape_string($connection, $_POST['sf_comment_by']);
                    $sf_comment_details = mysqli_real_escape_string($connection, $_POST['sf_comment_details']);
                    $sf_comment_date = date('Y-m-d H:i:s');

                    $insert_comment = mysqli_query($connection, "INSERT INTO sf_comments (sf_comment_parent_id, sf_comment_by, sf_comment_details, sf_comment_date) VALUES ('$sf_comment_parent_id', '$sf_comment_by', '$sf_comment_details', '$sf_comment_date')") or die(mysqli_error($connection));
                    if ($insert_comment) {
                        echo "<p id='alertBox' style='font-size: 12px !important' style='font-size: 12px !important;' class='alert alert-success'>Comment added successfully!</p>";
                    }
                }
                ?>
                <div style="margin-top: 10px; display: flex; justify-content: flex-end; margin-bottom: 20px">
                    <button type="button" data-bs-toggle="modal" style="font-size: 12px !important;" data-bs-target="#addStaffComment" class="btn btn-sm btn-outline-success">Add Comment</button>
                </div>

                <?php
                $fetch_comments = "SELECT * FROM sf_comments WHERE sf_comment_parent_id = $id ORDER BY sf_comment_date DESC";
                $fetch_comments_r = mysqli_query($connection, $fetch_comments) or die(mysqli_error($connection));
                if (mysqli_num_rows($fetch_comments_r) > 0) {
                    while ($row = mysqli_fetch_assoc($fetch_comments_r)) {
                        $sf_comment_by = $row['sf_comment_by'];
                        $sf_comment_details = $row['sf_comment_details'];
                        $sf_comment_date = $row['sf_comment_date'];
                ?>
                        <div class="card p-2 mb-2">
                            <div style="display: flex; justify-content: space-between;">
                                <p style="font-size: 12px !important; margin: 0;"><strong><?php echo htmlspecialchars($sf_comment_by); ?></strong> - <?php echo $sf_comment_date; ?></p>
                                <form action="" method="POST">
                                    <input type="hidden" name="sf_comment_id" value="<?php echo $row['sf_comment_id']; ?>">
                                    <button type="submit" style="font-size: 12px;" name="del-comm" class="btn btn-sm btn-outline-danger"><ion-icon name="close-outline"></ion-icon></button>
                                </form>
                            </div>
                            <p style="margin: 0 !important"><?php echo $sf_comment_details; ?></p>
                        </div>
                <?php
                    }
                } else {
                    echo "<p class='text-muted mt-2'>No comments yet.</p>";
                }
                ?>
            </div>
        </div>

        <!-- ============= ADD COMMENT MODAL ============= -->
        <div class="modal fade" id="addStaffComment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <form action="" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" style="font-size: 14px !important;">Add Comment</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="sf_comment_by" value="<?php echo htmlspecialchars($user_name); ?>">
                        <input type="hidden" name="sf_comment_parent_id" value="<?php echo $id; ?>">
                        <div class="mb-3">
                            <label style="font-size: 12px;">Details</label>
                            <textarea class="form-control" name="sf_comment_details" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add-comment" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Modal -->
    </div>

</div>



<?php include 'includes/footer.php'; ?>