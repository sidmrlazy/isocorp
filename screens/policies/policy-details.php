<div class="container mt-3 mb-3 policy-det-heading-section">
    <?php
    include 'includes/connection.php';

    if (!$connection) {
        die("<div class='alert alert-danger mt-3 mb-3'>Database connection failed: " . mysqli_connect_error() . "</div>");
    }
    
    if (isset($_POST['save'])) {
        // Collect the policy-related values from hidden fields
        $policy_id = isset($_POST['policy_id']) ? $_POST['policy_id'] : null;
        $linked_policy_id = isset($_POST['linked_policy_id']) ? $_POST['linked_policy_id'] : null;
        $inner_policy_id = isset($_POST['inner_policy_id']) ? $_POST['inner_policy_id'] : null;
        $policy_table = isset($_POST['policy_table']) ? $_POST['policy_table'] : null;
        $editorContent = isset($_POST['editorContent']) ? $_POST['editorContent'] : null;

        // Convert text to blob format
        $editorBlob = !empty($editorContent) ? addslashes($editorContent) : NULL;

        if (!empty($policy_table)) {
            if (!empty($policy_id) || !empty($linked_policy_id) || !empty($inner_policy_id)) {
                // Determine which ID to use based on the inputs
                $current_policy_id = !empty($policy_id) ? $policy_id : (!empty($linked_policy_id) ? $linked_policy_id : $inner_policy_id);

                // Check if an entry exists for this policy
                $query = "SELECT 1 FROM policy_details WHERE policy_id = ? AND policy_table = ?";
                $stmt = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($stmt, "is", $current_policy_id, $policy_table);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt); // Store result to use mysqli_num_rows
                $rowCount = mysqli_stmt_num_rows($stmt);

                if ($rowCount > 0) {
                    // Update existing record
                    $update_query = "UPDATE policy_details SET policy_details = ? WHERE policy_id = ? AND policy_table = ?";
                    $stmt = mysqli_prepare($connection, $update_query);
                    mysqli_stmt_bind_param($stmt, "sis", $editorBlob, $current_policy_id, $policy_table);
                } else {
                    // Insert new record and set policy_document to NULL
                    $insert_query = "INSERT INTO policy_details (policy_id, policy_table, policy_details, policy_document) VALUES (?, ?, ?, NULL)";
                    $stmt = mysqli_prepare($connection, $insert_query);
                    mysqli_stmt_bind_param($stmt, "iss", $current_policy_id, $policy_table, $editorBlob);
                }

                if (mysqli_stmt_execute($stmt)) {
                    echo '<div class="alert alert-success mt-3 mb-3">Policy details saved successfully.</div>';
                } else {
                    echo '<div class="alert alert-danger mt-3 mb-3">Error saving policy details: ' . mysqli_error($connection) . '</div>';
                }
            }
        }
    }


    $policy_id = null;
    $policy_table = "";
    $policy_column = "";

    if (isset($_GET['policy_id'])) {
        $policy_id = intval($_GET['policy_id']);
        $policy_table = "sub_control_policy";
        $policy_column = "sub_control_policy_id";
    } elseif (isset($_GET['linked_policy_id'])) {
        $policy_id = intval($_GET['linked_policy_id']);
        $policy_table = "linked_control_policy";
        $policy_column = "linked_control_policy_id";
    } elseif (isset($_GET['inner_policy_id'])) {
        $policy_id = intval($_GET['inner_policy_id']);
        $policy_table = "inner_linked_control_policy";
        $policy_column = "inner_linked_control_policy_id";
    }

    // Validate table name
    $allowed_tables = ['sub_control_policy', 'linked_control_policy', 'inner_linked_control_policy'];
    if (!in_array($policy_table, $allowed_tables)) {
        die("<div class='alert alert-danger mt-3 mb-3'>Invalid policy table specified.</div>");
    }

    if ($policy_id && $policy_table && $policy_column) {
        // Fetch policy details using the correct column
        $query = "SELECT * FROM $policy_table WHERE $policy_column = ?";
        $stmt = mysqli_prepare($connection, $query);
        if (!$stmt) {
            die("<div class='alert alert-danger mt-3 mb-3'>Prepare Error: " . mysqli_error($connection) . "</div>");
        }
        mysqli_stmt_bind_param($stmt, "i", $policy_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $policy = mysqli_fetch_assoc($result);

            // Determine the correct column names based on the table
            if ($policy_table === "sub_control_policy") {
                $policy_number = $policy["sub_control_policy_number"];
                $policy_heading = $policy["sub_control_policy_heading"];
                $policy_content = $policy["sub_control_policy_det"];
            } elseif ($policy_table === "linked_control_policy") {
                $policy_number = $policy["linked_control_policy_number"];
                $policy_heading = $policy["linked_control_policy_heading"];
                $policy_content = $policy["linked_control_policy_det"];
            } elseif ($policy_table === "inner_linked_control_policy") {
                $policy_number = $policy["inner_linked_control_policy_number"];
                $policy_heading = $policy["inner_linked_control_policy_heading"];
                $policy_content = $policy["inner_linked_control_policy_det"];
            } else {
                $policy_number = '';
                $policy_heading = '';
                $policy_content = '';
            }
            ?>

            <h1>Policy Details</h1>
            <div class="details-container">
                <h2><?= $policy_number . " " . $policy_heading ?></h2>
                <p><?= $policy_content ?></p>
            </div>

            <?php
        } else {
            echo "<p>Policy details not found.</p>";
        }
    } else {
        echo "<p>No policy ID provided.</p>";
    }

    $policy_content = "";
    if ($policy_id && $policy_table) {
        $query = "SELECT policy_details FROM policy_details WHERE policy_id = ? AND policy_table = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "is", $policy_id, $policy_table); // Correct binding
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $policy = mysqli_fetch_assoc($result);
            $policy_content = stripslashes($policy["policy_details"]); // Retrieve and decode blob content ?>
            <div class="clause-container">
                <?= htmlspecialchars_decode($policy_content) ?>
            </div>
            <?php
        } else {
            //echo "<div class='alert alert-warning mt-3'>No policy details found for this policy.</div>";
        }
    } else {
        echo "<div class='alert alert-danger mt-3'>Invalid Policy ID or Table.</div>";
    }

    if (isset($_POST['upload'])) {
        $policy_id = intval($_POST['policy_id']);
        $policy_table = isset($_POST['policy_table_for_document']) ? $_POST['policy_table_for_document'] : null;

        if (is_null($policy_table)) {
            die("<div class='alert alert-danger mt-3 mb-3'>Policy table for document is missing!</div>");
        }

        if (!isset($_FILES['document']) || $_FILES['document']['error'] != UPLOAD_ERR_OK) {
            die("<div class='alert alert-danger mt-3 mb-3'>File upload error!</div>");
        }

        $file_name = basename($_FILES['document']['name']);
        $file_tmp = $_FILES['document']['tmp_name'];
        $upload_dir = "uploads/";
        $file_path = $upload_dir . time() . "_" . $file_name;

        if (move_uploaded_file($file_tmp, $file_path)) {
            $query = "INSERT INTO policy_documents (policy_id, policy_table_for_document, document_name, document_path) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "isss", $policy_id, $policy_table, $file_name, $file_path);

            if (mysqli_stmt_execute($stmt)) {
                echo "<div class='alert alert-success mt-3 mb-3'>Document uploaded successfully.</div>";
            } else {
                echo "<div class='alert alert-danger mt-3 mb-3'>Error uploading document: " . mysqli_error($connection) . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger mt-3 mb-3'>Failed to move uploaded file.</div>";
        }
    }
    ?>

    <div class="section-divider mb-5">
        <!-- ========== UPLOAD CONTENT ========== -->
        <form action="" method="POST" class="WYSIWYG-editor-container ">
            <input type="hidden" name="policy_id"
                value="<?php echo isset($_GET['policy_id']) ? $_GET['policy_id'] : ''; ?>">
            <input type="hidden" name="linked_policy_id"
                value="<?php echo isset($_GET['linked_policy_id']) ? $_GET['linked_policy_id'] : ''; ?>">
            <input type="hidden" name="inner_policy_id"
                value="<?php echo isset($_GET['inner_policy_id']) ? $_GET['inner_policy_id'] : ''; ?>">
            <input type="hidden" name="policy_table" value="<?php echo isset($policy_table) ? $policy_table : ''; ?>">

            <div class="WYSIWYG-editor">
                <textarea id="editorNew"></textarea>
            </div>
            <input type="hidden" name="editorContent" id="editorContent">

            <button type="submit" name="save" class="btn btn-sm btn-success mt-3">Update</button>
        </form>


        <!-- ========== SUPPORTING DOCUMENTS ========== -->
        <div class="document-container">
            <?php
            $query = "SELECT document_name, document_path FROM policy_documents WHERE policy_id = ? AND policy_table_for_document = ?";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "is", $policy_id, $policy_table);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<ul class='document-list'>";
                while ($doc = mysqli_fetch_assoc($result)) {
                    echo "<li>
                    <a href='" . htmlspecialchars($doc['document_path']) . "' target='_blank'>" . htmlspecialchars($doc['document_name']) . "</a>
                  </li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No documents uploaded for this policy.</p>";
            }
            ?>
            <p class="mt-5">Upload Supporting Document</p>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="policy_id" value="<?php echo $policy_id; ?>">
                <input type="hidden" name="policy_table" value="<?php echo $policy_table; ?>">
                <input type="hidden" name="policy_table_for_document" value="<?php echo $policy_table; ?>">

                <label for="document">Choose file:</label>
                <input type="file" name="document" id="document" required>

                <button type="submit" name="upload" class="btn btn-sm btn-primary mt-3">Upload</button>
            </form>
        </div>
    </div>
</div>