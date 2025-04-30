<!-- ============== DOCUMENT SECTION ============== -->
<?php
if (isset($_POST['upload-doc'])) {
    $control_linked_policies_id = mysqli_real_escape_string($connection, $_POST['control_doc_parent_id']);
    $control_doc_updated_by = mysqli_real_escape_string($connection, $_POST['control_doc_updated_by']);
    $control_doc_version = mysqli_real_escape_string($connection, $_POST['control_doc_version']);

    date_default_timezone_set('Asia/Kolkata');
    $control_doc_update_date = date('m-d-Y H:i:s');

    if (isset($_FILES['control_doc_name']) && $_FILES['control_doc_name']['error'] == 0) {
        $filename = $_FILES['control_doc_name']['name'];
        $tempname = $_FILES['control_doc_name']['tmp_name'];
        $uploadDir = 'uploads/';
        $filepath = $uploadDir . basename($filename);

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($tempname, $filepath)) {
            $query = "INSERT INTO control_document (
                        control_doc_parent_id, 
                        control_doc_name, 
                        control_doc_path, 
                        control_doc_updated_by, 
                        control_doc_version, 
                        control_doc_update_date
                      ) VALUES (
                        '$control_linked_policies_id', 
                        '$filename',
                        '$filepath',
                        '$control_doc_updated_by',
                        '$control_doc_version',
                        '$control_doc_update_date'
                      )";

            $result = mysqli_query($connection, $query);
        }
    }
}


if (isset($_POST['update-document'])) {
    $edit_control_doc_id = mysqli_real_escape_string($connection, $_POST['edit_control_doc_id']);
    $control_doc_updated_by = mysqli_real_escape_string($connection, $_POST['control_doc_updated_by']);
    $control_doc_version = mysqli_real_escape_string($connection, $_POST['control_doc_version']);

    date_default_timezone_set('Asia/Kolkata');
    $control_doc_update_date = date('m-d-Y H:i:s');

    // Check if a new file was uploaded
    if (isset($_FILES['control_doc_name']) && $_FILES['control_doc_name']['error'] == 0) {
        $filename = $_FILES['control_doc_name']['name'];
        $tempname = $_FILES['control_doc_name']['tmp_name'];
        $uploadDir = 'uploads/';
        $filepath = $uploadDir . basename($filename);

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($tempname, $filepath)) {
            // Update with new file
            $query = "UPDATE control_document SET 
                        control_doc_name = '$filename',
                        control_doc_path = '$filepath',
                        control_doc_updated_by = '$control_doc_updated_by',
                        control_doc_version = '$control_doc_version',
                        control_doc_update_date = '$control_doc_update_date'
                      WHERE control_doc_id = '$edit_control_doc_id'";
        }
    } else {
        // Update without changing file
        $query = "UPDATE control_document SET 
                    control_doc_updated_by = '$control_doc_updated_by',
                    control_doc_version = '$control_doc_version',
                    control_doc_update_date = '$control_doc_update_date'
                  WHERE control_doc_id = '$edit_control_doc_id'";
    }

    $result = mysqli_query($connection, $query);
}


if (isset($_POST['delete-document'])) {
    $control_doc_id = mysqli_real_escape_string($connection, $_POST['control_doc_id']);
    $delete_query = "DELETE FROM control_document WHERE control_doc_id = '$control_doc_id'";
    $delete_result = mysqli_query($connection, $delete_query);
}
?>

<!-- ============== ADD DOCUMENT MODAL ============== -->
<div class="modal fade" id="addDocModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Upload Document</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="control_doc_parent_id" value="<?php echo htmlspecialchars($fetched_control_id); ?>" hidden>
                <input type="text" name="control_doc_updated_by" value="<?php echo htmlspecialchars($user_name); ?>" hidden>
                <div class="mb-3">
                    <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Upload File</label>
                    <input type="file" name="control_doc_name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>

                <div class="mb-3">
                    <label style="font-size: 12px !important;" for="exampleInputEmail1" class="form-label">Version</label>
                    <input type="text" name="control_doc_version" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="upload-doc" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</div>

<!-- ============== SHOW DOCUMENTS ============== -->
<div class="mt-3">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <h6>Documents</h6>
        <button data-bs-toggle="modal" data-bs-target="#addDocModal" type="button" class="btn btn-sm btn-outline-success">+</button>
    </div>

    <?php
    $fetch_doc = "SELECT * FROM control_document WHERE control_doc_parent_id = '$fetched_control_id'";
    $fetch_doc_r = mysqli_query($connection, $fetch_doc);
    $fetch_doc_count = mysqli_num_rows($fetch_doc_r);

    if ($fetch_doc_count > 0) {
    ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="font-size: 12px !important;">Name</th>
                        <th style="font-size: 12px !important;">Version</th>
                        <th style="font-size: 12px !important;">Updated By</th>
                        <th style="font-size: 12px !important;">Updated On</th>
                        <th style="font-size: 12px !important;">Download</th>
                        <th style="font-size: 12px !important;">Edit</th>
                        <th style="font-size: 12px !important;">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($fetch_doc_r)) {
                        $control_doc_id = $row['control_doc_id'];
                        $doc_name = $row['control_doc_name'];
                        $doc_path = $row['control_doc_path'];
                        $doc_version = $row['control_doc_version'];
                        $doc_updated_by = $row['control_doc_updated_by'];
                        $doc_update_date = $row['control_doc_update_date'];
                    ?>
                        <tr>
                            <td style="font-size: 12px;"><?php echo htmlspecialchars($doc_name); ?></td>
                            <td style="font-size: 12px;"><?php echo htmlspecialchars($doc_version); ?></td>
                            <td style="font-size: 12px;"><?php echo htmlspecialchars($doc_updated_by); ?></td>
                            <td style="font-size: 12px;"><?php echo htmlspecialchars($doc_update_date); ?></td>
                            <td style="font-size: 12px;">
                                <a href="<?php echo htmlspecialchars($doc_path); ?>" download style="font-size: 10px !important;" class="btn btn-sm btn-outline-primary">Download</a>
                            </td>
                            <td style="font-size: 12px;">
                                <form action="" method="POST">
                                    <input type="hidden" name="control_doc_id" value="<?php echo $control_doc_id; ?>">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-info"
                                        style="font-size: 10px !important;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editDocModal"
                                        data-doc-id="<?php echo $control_doc_id; ?>"
                                        data-doc-version="<?php echo htmlspecialchars($doc_version); ?>">
                                        Edit
                                    </button>
                                </form>
                            </td>
                            <td style="font-size: 12px;">
                                <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                    <input type="hidden" name="control_doc_id" value="<?php echo $control_doc_id; ?>">
                                    <button type="submit" name="delete-document" class="btn btn-sm btn-outline-danger" style="font-size: 10px !important;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php
    } else {
        echo "<p style='font-size:12px;'>No documents available.</p>";
    }
    ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var editModal = document.getElementById('editDocModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var docId = button.getAttribute('data-doc-id');
            var docVersion = button.getAttribute('data-doc-version');

            document.getElementById('edit_control_doc_id').value = docId;
            document.getElementById('edit_control_doc_version').value = docVersion;
        });
    });
</script>