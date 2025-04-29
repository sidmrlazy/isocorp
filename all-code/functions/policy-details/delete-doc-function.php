<div class="dashboard-container">
    <?php
    if (isset($_POST['delete-doc'])) {
        $document_id = isset($_POST['document_id']) ? intval($_POST['document_id']) : null;
        if ($document_id) {
            $query = "SELECT document_path FROM policy_documents WHERE policy_document_id = ?";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "i", $document_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($result && mysqli_num_rows($result) > 0) {
                $doc = mysqli_fetch_assoc($result);
                $document_path = $doc['document_path'];
                // Delete file from server
                if (file_exists($document_path)) {
                    unlink($document_path);
                }
                // Delete from database
                $delete_query = "DELETE FROM policy_documents WHERE policy_document_id = ?";
                $stmt = mysqli_prepare($connection, $delete_query);
                mysqli_stmt_bind_param($stmt, "i", $document_id);

                if (mysqli_stmt_execute($stmt)) {
                    echo "<div id='alertBox' class='alert alert-success mt-3 mb-3'>Document deleted successfully.</div>";
                } else {
                    echo "<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Error deleting document: " . mysqli_error($connection) . "</div>";
                }
            } else {
                echo "<div id='alertBox' class='alert alert-warning mt-3 mb-3'>Document not found.</div>";
            }
        } else {
            echo "<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Invalid document ID.</div>";
        }
    }
    ?>
</div>