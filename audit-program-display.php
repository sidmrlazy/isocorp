<?php
if (!isset($_SESSION['user_session']) && !isset($_COOKIE['user_session'])) {
    // Send a 404 Not Found response
    http_response_code(404);
    exit();
}
include 'includes/connection.php';
include 'includes/header.php';
include 'includes/navbar.php';

?>

<div class="dashboard-container">
    <?php

    $user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Guest');
    $user_name = isset($_COOKIE['user_name']) ? $_COOKIE['user_name'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest');
    $user_role = isset($_COOKIE['user_role']) ? $_COOKIE['user_role'] : (isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Guest');

    $ap_id = "";
    $ap_name = "";
    $ap_assigned = "";
    $ap_created_date = "";
    $ap_details = "";
    $ap_comments = [];

    // Handle comment deletion
    if (isset($_GET['delete_comment'])) {
        $delete_id = mysqli_real_escape_string($connection, $_GET['delete_comment']);
        mysqli_query($connection, "DELETE FROM audit_program_comments WHERE ap_c_id = '$delete_id'");
    }

    // Handle comment submission
    if (isset($_POST['submit-comment'])) {
        $ap_c_main_id = mysqli_real_escape_string($connection, $_POST['ap_id']);
        $ap_c_by = $user_name; // Replace this with $_SESSION['username'] or appropriate logged-in user variable
        $ap_c_date = date("Y-m-d H:i:s");
        $ap_c_comment = mysqli_real_escape_string($connection, $_POST['comment']);

        $insert_comment = "INSERT INTO audit_program_comments (ap_c_main_id, ap_c_by, ap_c_date, ap_c_comment)
                       VALUES ('$ap_c_main_id', '$ap_c_by', '$ap_c_date', '$ap_c_comment')";
        mysqli_query($connection, $insert_comment);
    }

    // Update audit program
    if (isset($_POST['update-ap'])) {
        $ap_id = mysqli_real_escape_string($connection, $_POST['ap_id']);
        $ap_details = mysqli_real_escape_string($connection, $_POST['editorNew']);

        $update_query = "UPDATE audit_program SET ap_details = '$ap_details' WHERE ap_id = '$ap_id'";
        if (mysqli_query($connection, $update_query)) {
            echo "<div id='alertBox' class='alert alert-success' style='font-size: 12px;'>Audit Program updated successfully.</div>";
        } else {
            echo "<div id='alertBox' class='alert alert-danger' style='font-size: 12px;'>Error updating Audit Program: " . mysqli_error($connection) . "</div>";
        }
    }

    // Load audit program details
    if (isset($_GET['id'])) {
        $ap_id = mysqli_real_escape_string($connection, $_GET['id']);
        $fetch_audit_details = "SELECT * FROM audit_program WHERE ap_id = '$ap_id'";
        $result = mysqli_query($connection, $fetch_audit_details);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $ap_name = htmlspecialchars($row['ap_name']);
            $ap_assigned = htmlspecialchars($row['ap_assigned']);
            $ap_created_date = htmlspecialchars($row['ap_created_date']);
            $ap_details = htmlspecialchars($row['ap_details']);
        } else {
            echo "<div id='alertBox' class='alert alert-warning'>Audit Programme not found.</div>";
        }

        // Load comments
        $comments_query = "SELECT * FROM audit_program_comments WHERE ap_c_main_id = '$ap_id' ORDER BY ap_c_date DESC";
        $comments_result = mysqli_query($connection, $comments_query);
        if ($comments_result) {
            while ($comment = mysqli_fetch_assoc($comments_result)) {
                $ap_comments[] = $comment;
            }
        }
    } else {
        echo "<div id='alertBox' class='alert alert-danger'>Invalid request. No Audit Programme ID provided.</div>";
        exit;
    }
    ?>
    <div class="row">
        <!-- =========== LEFT SECTION =========== -->
        <div class="col-md-6">
            <div class="card p-3 mb-3">
                <p style="margin: 0;"><?php echo $ap_name ?></p>
            </div>

            <form action="" method="POST" class="card p-3">
                <input type="hidden" name="ap_id" value="<?php echo $ap_id ?>">

                <div class="WYSIWYG-editor">
                    <textarea id="editorNew" name="editorNew"><?php echo $ap_details ?></textarea>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" name="update-ap" style="font-size: 12px;" class="btn btn-sm btn-outline-success">Update</button>
                </div>
            </form>
        </div>

        <!-- =========== RIGHT SECTION =========== -->
        <div class="col-md-6">
            <div class="card p-3 mb-3">
                <?php
                if (isset($_POST['upload_doc'])) {
                    $main_id = mysqli_real_escape_string($connection, $_POST['ap_d_main_id']);
                    $ver = mysqli_real_escape_string($connection, $_POST['ap_d_ver']);
                    $upload_date = date("Y-m-d H:i:s");

                    if (!is_dir("ap_doc")) {
                        mkdir("ap_doc", 0777, true);
                    }

                    $file = $_FILES['ap_doc_file'];
                    $filename = basename($file['name']);
                    $target_path = "ap_doc/" . $filename;

                    if (move_uploaded_file($file['tmp_name'], $target_path)) {
                        $insert_doc = "INSERT INTO audit_program_doc (ap_d_main_id, ap_d_doc_name, ap_d_ver, ap_d_upload_date, ap_d_update_date)
                       VALUES ('$main_id', '$filename', '$ver', '$upload_date', '$upload_date')";
                        mysqli_query($connection, $insert_doc);
                        echo "<div style='font-size: 12px !important' id='alertBox' class='alert alert-success mb-3'>Document uploaded successfully.</div>";
                    } else {
                        echo "<div style='font-size: 12px !important' id='alertBox' class='alert alert-danger mb-3'>Failed to upload document.</div>";
                    }
                }

                if (isset($_GET['delete_doc'])) {
                    $doc_id = mysqli_real_escape_string($connection, $_GET['delete_doc']);
                    $result = mysqli_query($connection, "SELECT ap_d_doc_name FROM audit_program_doc WHERE ap_d_id = '$doc_id'");
                    if ($row = mysqli_fetch_assoc($result)) {
                        $file_path = "ap_doc/" . $row['ap_d_doc_name'];
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                    }
                    mysqli_query($connection, "DELETE FROM audit_program_doc WHERE ap_d_id = '$doc_id'");
                    echo "<div style='font-size: 12px !important' id='alertBox' class='alert alert-success mb-3'>Document deleted.</div>";
                }


                ?>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <form action="" method="POST" enctype="multipart/form-data" class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Document</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="ap_d_main_id" value="<?php echo $ap_id; ?>">
                                <div class="mb-3">
                                    <label style='font-size: 12px !important' class="form-label">Upload Document</label>
                                    <input style='font-size: 12px !important' type="file" class="form-control" name="ap_doc_file" required>
                                </div>
                                <div class="mb-3">
                                    <label style='font-size: 12px !important' class="form-label">Version</label>
                                    <input style='font-size: 12px !important' type="text" class="form-control" name="ap_d_ver" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="upload_doc" class="btn btn-sm btn-outline-success">Upload</button>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <p>Documents</p>
                    <button type="button" name="update-ap" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-sm btn-outline-success"><ion-icon name="add-outline"></ion-icon></button>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th style="font-size: 12px !important;" scope="col">Document</th>
                                <th style="font-size: 12px !important;" scope="col">Version</th>
                                <th style="font-size: 12px !important;" scope="col">Download</th>
                                <th style="font-size: 12px !important;" scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $docs = mysqli_query($connection, "SELECT * FROM audit_program_doc WHERE ap_d_main_id = '$ap_id' ORDER BY ap_d_upload_date DESC");
                            if ($docs && mysqli_num_rows($docs) > 0):
                                while ($doc = mysqli_fetch_assoc($docs)):
                            ?>
                                    <tr>
                                        <td style="font-size: 12px;"><?php echo htmlspecialchars($doc['ap_d_doc_name']); ?></td>
                                        <td style="font-size: 12px;"><?php echo htmlspecialchars($doc['ap_d_ver']); ?></td>
                                        <td style="font-size: 12px;">
                                            <a href="ap_doc/<?php echo urlencode($doc['ap_d_doc_name']); ?>" target="_blank" style='font-size: 12px !important' class="btn btn-sm btn-outline-success">Download</a>
                                        </td>
                                        <td style="font-size: 12px;">
                                            <a href="?id=<?php echo $ap_id ?>&delete_doc=<?php echo $doc['ap_d_id']; ?>" style='font-size: 12px !important' class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this document?')">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                            <?php
                                endwhile;
                            else:
                                echo "<tr><td colspan='5' style='font-size: 12px;'>No documents uploaded.</td></tr>";
                            endif;
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>
            <!-- Comment section -->
            <div class="card p-3">
                <p style="margin: 0; font-weight: 600">Comments</p>
                <form method="POST" action="" class="mt-3">
                    <input type="hidden" name="ap_id" value="<?php echo $ap_id ?>">
                    <textarea name="comment" class="form-control mb-2" rows="2" placeholder="Add a comment..." required></textarea>
                    <div class="d-flex justify-content-end">
                        <button type="submit" style="font-size: 12px !important;" name="submit-comment" class="btn btn-sm btn-outline-success">Add comment</button>
                    </div>
                </form>
                <?php if (!empty($ap_comments)): ?>
                    <?php foreach ($ap_comments as $comment): ?>
                        <div class="d-flex justify-content-between align-items-start mt-3 mb-3">
                            <p style="margin: 0; margin-right: 20px !important">
                                <span style="font-weight: 500; font-size: 12px;">
                                    <?php echo htmlspecialchars($comment['ap_c_by']); ?> (<?php echo htmlspecialchars($comment['ap_c_date']); ?>) -
                                </span>
                                <?php echo htmlspecialchars($comment['ap_c_comment']); ?>
                            </p>
                            <a href="?id=<?php echo $ap_id; ?>&delete_comment=<?php echo $comment['ap_c_id']; ?>" onclick="return confirm('Delete this comment?')" class="btn btn-sm btn-outline-danger" style="font-size: 10px;">
                                <ion-icon name="close-circle-outline"></ion-icon>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted mt-3" style="font-size: 13px;">No comments yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>