<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
?>

<div class="dashboard-container">
    <?php
    if (isset($_POST['update-doc'])) {
        $policy_document_id = $_POST['policy_document_id'];
        $new_document_version = $_POST['document_version'];

        // Fetch current document details
        $get_document = "SELECT * FROM policy_documents WHERE policy_document_id = '$policy_document_id'";
        $get_document_r = mysqli_query($connection, $get_document);
        $row = mysqli_fetch_assoc($get_document_r);

        $current_version = $row['document_version'];
        $document_path = $row['document_path'];

        $update_query = ""; // Variable to hold the update query
        $upload_success = false;

        // Handle file upload
        if (!empty($_FILES['document']['name'])) {
            $file_name = $_FILES['document']['name'];
            $file_tmp = $_FILES['document']['tmp_name'];
            $upload_directory = "uploads/" . $file_name; // Adjust path as needed

            if (move_uploaded_file($file_tmp, $upload_directory)) {
                $upload_success = true;
            }
        }

        if ($upload_success) {
            // Update document and version if version changed
            if ($new_document_version !== $current_version) {
                $update_query = "UPDATE policy_documents SET document_path = '$upload_directory', document_version = '$new_document_version' WHERE policy_document_id = '$policy_document_id'";
            } else {
                // Only update the document path
                $update_query = "UPDATE policy_documents SET document_path = '$upload_directory' WHERE policy_document_id = '$policy_document_id'";
            }
        } elseif ($new_document_version !== $current_version) {
            // Only update the version if document is not changed
            $update_query = "UPDATE policy_documents SET document_version = '$new_document_version' WHERE policy_document_id = '$policy_document_id'";
        }

        if (!empty($update_query)) {
            if (mysqli_query($connection, $update_query)) {
                echo "<div class='alert alert-success mb-3' role='alert'>
                    Document updated successfully!
                </div>";
            } else {
                echo "<div class='alert alert-danger mb-3' role='alert'>
                    Failed to update document!
                </div>";
            }
        }
    }


    if (isset($_POST['doc-edit-btn'])) {
        $policy_document_id = $_POST['policy_document_id'];

        $get_document = "SELECT * FROM policy_documents WHERE policy_document_id = '$policy_document_id'";
        $get_document_r = mysqli_query($connection, $get_document);

        if ($row = mysqli_fetch_assoc($get_document_r)) {
            $document_id = $row['policy_document_id'];
            $document_path = $row['document_path'];
            $document_name = $row['document_name'];
            $document_version = $row['document_version']; ?>

            <div class="update-document-container">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="policy_document_id" value="<?php echo $document_id; ?>">
                    <div class="mb-3">
                        <label class="form-label">Upload Document</label>
                        <input type="file" name="document" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Document Version</label>
                        <input type="text" name="document_version" value="<?php echo $document_version; ?>" class="form-control">
                    </div>
                    <button type="submit" name="update-doc" class="btn btn-success w-100">Update Document</button>
                </form>
            </div>
    <?php
        }
    }

    ?>
</div>

<?php include 'includes/footer.php'; ?>