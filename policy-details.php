<?php
include('includes/header.php');
include('includes/navbar.php');
include 'includes/connection.php';
include 'includes/config.php'; ?>
<div class="container mt-3 mb-3 policy-det-heading-section">
    <?php
    if (!$connection) {
        die("<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Database connection failed: " . mysqli_connect_error() . "</div>");
    }

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

    if (isset($_POST['save'])) {

        $policy_id = isset($_POST['policy_id']) ? $_POST['policy_id'] : null;
        $linked_policy_id = isset($_POST['linked_policy_id']) ? $_POST['linked_policy_id'] : null;
        $inner_policy_id = isset($_POST['inner_policy_id']) ? $_POST['inner_policy_id'] : null;
        $policy_table = isset($_POST['policy_table']) ? $_POST['policy_table'] : null;
        $editorContent = isset($_POST['editorContent']) ? $_POST['editorContent'] : null;


        $editorBlob = !empty($editorContent) ? addslashes($editorContent) : NULL;

        if (!empty($policy_table)) {
            if (!empty($policy_id) || !empty($linked_policy_id) || !empty($inner_policy_id)) {

                $current_policy_id = !empty($policy_id) ? $policy_id : (!empty($linked_policy_id) ? $linked_policy_id : $inner_policy_id);


                $query = "SELECT 1 FROM policy_details WHERE policy_id = ? AND policy_table = ?";
                $stmt = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($stmt, "is", $current_policy_id, $policy_table);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $rowCount = mysqli_stmt_num_rows($stmt);

                if ($rowCount > 0) {

                    $update_query = "UPDATE policy_details SET policy_details = ? WHERE policy_id = ? AND policy_table = ?";
                    $stmt = mysqli_prepare($connection, $update_query);
                    mysqli_stmt_bind_param($stmt, "sis", $editorBlob, $current_policy_id, $policy_table);
                } else {

                    $insert_query = "INSERT INTO policy_details (policy_id, policy_table, policy_details, policy_document) VALUES (?, ?, ?, NULL)";
                    $stmt = mysqli_prepare($connection, $insert_query);
                    mysqli_stmt_bind_param($stmt, "iss", $current_policy_id, $policy_table, $editorBlob);
                }

                if (mysqli_stmt_execute($stmt)) {
                    echo '<div id="alertBox" class="alert alert-success mt-3 mb-3">Policy details saved successfully.</div>';
                } else {
                    echo '<div id="alertBox" class="alert alert-danger mt-3 mb-3">Error saving policy details: ' . mysqli_error($connection) . '</div>';
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


    $allowed_tables = ['sub_control_policy', 'linked_control_policy', 'inner_linked_control_policy'];
    if (!in_array($policy_table, $allowed_tables)) {
        die("<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Invalid policy table specified.</div>");
    }

    if ($policy_id && $policy_table && $policy_column) {

        $query = "SELECT * FROM $policy_table WHERE $policy_column = ?";
        $stmt = mysqli_prepare($connection, $query);
        if (!$stmt) {
            die("<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Prepare Error: " . mysqli_error($connection) . "</div>");
        }
        mysqli_stmt_bind_param($stmt, "i", $policy_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $policy = mysqli_fetch_assoc($result);


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

            <h1 style="font-size: 24px; font-weight: 500;">Policy Details</h1>
            <div class="details-container">
                <h2 style="font-size: 20px !important;"><?= $policy_number . " " . $policy_heading ?></h2>
                <p style="font-size: 20px; margin: 0;"><?= $policy_content ?></p>
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
        mysqli_stmt_bind_param($stmt, "is", $policy_id, $policy_table);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $policy = mysqli_fetch_assoc($result);
            $policy_content = stripslashes($policy["policy_details"]);
        ?>
            <div class="clause-container" style="font-size: 16px !important; width: 100%">
                <style>

                </style>
                <div class="clause-content" style="font-size: 16px !important; font-weight: 400 !important;">
                    <?= htmlspecialchars_decode($policy_content) ?>
                </div>
                <div style="display:flex; justify-content: center; align-items: center;">
                    <button class="read-more-btn btn btn-outline-dark" style="display: none; margin-top: 10px; cursor: pointer; padding: 5px 10px; font-size: 24px; border: 0; background: none;">
                        <ion-icon name="caret-down-circle-outline"></ion-icon>
                    </button>
                </div>
            </div>
    <?php
        } else {
        }
    } else {
        echo "<div id='alertBox' class='alert alert-danger mt-3'>Invalid Policy ID or Table.</div>";
    }

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
            $query = "INSERT INTO policy_documents (policy_id, policy_table_for_document, document_name, document_path, document_version) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "issss", $policy_id, $policy_table, $file_name, $file_path, $document_version);

            if (mysqli_stmt_execute($stmt)) {
                echo "<div id='alertBox' class='alert alert-success mt-3 mb-3'>Document uploaded successfully.</div>";
            } else {
                echo "<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Error uploading document: " . mysqli_error($connection) . "</div>";
            }
        } else {
            echo "<div id='alertBox' class='alert alert-danger mt-3 mb-3'>Failed to move uploaded file.</div>";
        }
    }
    ?>

    <div class="section-divider mb-5">
        <?php if ($user_role == '1') { ?>
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
        <?php } elseif ($user_role == '2') { ?>
            <form action="" method="POST" class="WYSIWYG-editor-container d-none">
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
        <?php } ?>

        <!-- ========== SUPPORTING DOCUMENTS ========== -->
        <?php if ($user_role == '1') { ?>
            <div class="document-container" style="margin-left: 10px;">
            <?php } elseif ($user_role == '2') { ?>
                <div class="document-container">
                <?php } ?>
                <?php
                $query = "SELECT policy_document_id, document_name, document_path, document_version FROM policy_documents WHERE policy_id = ? AND policy_table_for_document = ?";
                $stmt = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($stmt, "is", $policy_id, $policy_table);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <?php while ($doc = mysqli_fetch_assoc($result)) {
                                    $document_id = htmlspecialchars($doc['policy_document_id']);
                                    $document_path = htmlspecialchars($doc['document_path']);
                                    $document_name = htmlspecialchars($doc['document_name']);
                                    $document_version = htmlspecialchars($doc['document_version']);
                                ?>
                                    <tr>
                                        <td style="font-size: 12px;">
                                            <a href="<?php echo $document_path; ?>" target="_blank">
                                                <?php echo $document_name; ?>
                                            </a>
                                        </td>
                                        <td style="font-size: 12px;"><?php echo $document_version; ?></td>
                                        <?php
                                        if ($user_role === '1') { ?>
                                            <td class="text-center">
                                                <form action="update-document.php" method="POST">
                                                    <input type="text" name="policy_document_id" value="<?php echo $document_id; ?>" hidden>
                                                    <button type="submit" name="doc-edit-btn" class="doc-edit-btn">
                                                        <ion-icon name="create-outline"></ion-icon>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <form action="" method="POST">
                                                    <input type="hidden" name="policy_document_id" value="<?php echo $document_id; ?>">
                                                    <button type="submit" name="delete-doc" class="doc-edit-btn">
                                                        <ion-icon name="trash-outline"></ion-icon>
                                                    </button>
                                                </form>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php
                } else {
                    echo "<p>No documents uploaded for this policy.</p>";
                }
                ?>
                <div class="document-upload-container">
                    <?php if ($user_role === '1') { ?>
                        <!-- <p class="mt-5">Upload Supporting Document</p> -->
                        <form action="" method="POST" enctype="multipart/form-data" class="mt-3 w-100 ml-3">
                            <input type="hidden" name="policy_id" value="<?php echo $policy_id; ?>">
                            <input type="hidden" name="policy_table" value="<?php echo $policy_table; ?>">
                            <input type="hidden" name="policy_table_for_document" value="<?php echo $policy_table; ?>">

                            <div class="mb-4 w-100">
                                <label for="document" class="form-label">Upload</label>
                                <input type="file" name="document" class="form-control w-100" id="document" placeholder="name@example.com">
                            </div>


                            <div class="mb-3 w-100">
                                <label for="documentVersion" class="form-label">Version</label>
                                <input type="text" name="document_version" class="form-control w-100" id="documentVersion" aria-describedby="emailHelp">
                            </div>


                            <button type="submit" name="upload" class="btn btn-sm btn-primary mt-3">Upload</button>
                        </form>
                    <?php }  ?>
                </div>
                </div>
            </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.querySelector(".clause-content");
            const button = document.querySelector(".read-more-btn");
            const readMoreIcon = "caret-down-circle-outline";
            const readLessIcon = "caret-up-circle-outline";

            if (container && button) {
                let words = container.innerText.trim().split(/\s+/);
                if (words.length > 200) {
                    let shortenedText = words.slice(0, 200).join(" ") + "...";
                    let fullText = container.innerHTML; // Store original content to preserve formatting

                    container.innerHTML = shortenedText;
                    button.style.display = "block";

                    button.addEventListener("click", function() {
                        let icon = button.querySelector("ion-icon");
                        if (!icon) return; // Prevent errors if the icon is missing

                        if (container.innerText.trim().endsWith("...")) {
                            container.innerHTML = fullText; // Restore full text
                            icon.setAttribute("name", readLessIcon);
                        } else {
                            container.innerHTML = shortenedText; // Collapse text
                            icon.setAttribute("name", readMoreIcon);
                        }
                    });
                }
            }
        });
    </script>
    <?php include('includes/footer.php'); ?>