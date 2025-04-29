<div class="dashboard-container">
    <?php
    if (isset($_POST['upload'])) {
        $policy_id = intval($_POST['policy_id']);
        $policy_table = isset($_POST['policy_table_for_document']) ? $_POST['policy_table_for_document'] : null;

        if (is_null($policy_table)) {
            die("<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Policy table for document is missing!</div>");
        }

        if (!isset($_FILES['document']) || $_FILES['document']['error'] != UPLOAD_ERR_OK) {
            die("<div id='alertBox' class='alert alert-danger mt-3 mb-3'>File upload error!</div>");
        }

        $file_name = basename($_FILES['document']['name']);
        $file_tmp = $_FILES['document']['tmp_name'];
        $upload_dir = "uploads/";
        $file_path = $upload_dir . time() . "_" . $file_name;

        $document_version = isset($_POST['document_version']) ? $_POST['document_version'] : null;

        if (move_uploaded_file($file_tmp, $file_path)) {
            // Insert document details into policy_documents table
            $query = "INSERT INTO policy_documents (policy_id, policy_table_for_document, document_name, document_path, document_version) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "issss", $policy_id, $policy_table, $file_name, $file_path, $document_version);

            if (mysqli_stmt_execute($stmt)) {
                echo "<div id='alertBox' class='alert alert-success mt-3 mb-3'>Document uploaded successfully.</div>";

                // ✅ Insert into activity table
                date_default_timezone_set('Asia/Kolkata');
                $activity_name = "Document added";
                $activity_done_on_id = $policy_id;
                $activity_done_on = $policy_table;
                $activity_done_by = $user_name; // Assuming user_name is set in session
                $activity_date = date('m-d-Y H:i:s');

                $activity_sql = "INSERT INTO activity (
                activity_done_on, 
                activity_done_on_id,
                activity_name, 
                activity_by, 
                activity_date
            ) VALUES (
                '$activity_done_on',
                '$activity_done_on_id',
                '$activity_name',
                '$activity_done_by',
                '$activity_date'
            )";

                mysqli_query($connection, $activity_sql);
            } else {
                echo "<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Error uploading document: " . mysqli_error($connection) . "</div>";
            }
        } else {
            echo "<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Failed to move uploaded file.</div>";
        }
    }
    ?>
</div>