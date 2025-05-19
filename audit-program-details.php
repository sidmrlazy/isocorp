<?php
include 'includes/connection.php';
include 'includes/header.php';
include 'includes/navbar.php';

$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Guest');
$user_name = isset($_COOKIE['user_name']) ? $_COOKIE['user_name'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest');
$user_role = isset($_COOKIE['user_role']) ? $_COOKIE['user_role'] : (isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Guest');

$ap_phase_name = "";
$ap_phase_id = "";
$ap_phase_name = "";
$ap_phase_desc = "";
$ap_phase_assigned_to = "";
$ap_phase_due_date = "";
$ap_ph_status = "";

if (isset($_GET['id'])) {
    $ph_id = mysqli_real_escape_string($connection, $_GET['id']);
    $fetch_query = "SELECT * FROM ap_phase WHERE ap_phase_id = '$ph_id'";
    $result = mysqli_query($connection, $fetch_query);
    while ($row = mysqli_fetch_assoc($result)) {
        $ap_phase_id = $row['ap_phase_id'];
        $ap_phase_name = $row['ap_ph_name'];
        $ap_phase_desc = $row['ap_ph_desc'];
        $ap_phase_assigned_to = $row['ap_ph_assigned_to'];
        $ap_phase_due_date = $row['ap_ph_due_date'];
        $ap_ph_status = $row['ap_ph_status'];
    }
}
?>
<div class="dashboard-container">
    <div class="card p-3">
        <p style="font-weight: 600; margin: 0"><?php echo htmlspecialchars($ap_phase_name); ?></p>
    </div>

    <div class="row mt-3">
        <!-- ============ LEFT SECTION ============ -->
        <div class="col-md-6">
            <?php
            if (isset($_POST['update-desc'])) {
                $ap_phase_desc = mysqli_real_escape_string($connection, $_POST['ap_ph_desc']);
                $insert_des = "UPDATE ap_phase SET ap_ph_desc = '$ap_phase_desc' WHERE ap_phase_id = '$ap_phase_id'";

                $insert_des_r = mysqli_query($connection, $insert_des);
                if ($insert_des_r) {
                    echo '<div id="alertBox" style="font-size: 12px" class="alert alert-success mb-2" role="alert"> Description Updated Successfully! </div>';
                } else {
                    echo '<div id="alertBox" style="font-size: 12px" class="alert alert-danger mb-2" role="alert"> Failed to Update Description! </div>';
                }
            }
            ?>
            <form method="POST" class="card p-3">
                <div class="mb-3">
                    <label style="font-size: 12px !important;" for="exampleFormControlInput1" class="form-label">Description</label>
                    <div class="WYSIWYG-editor">
                        <textarea name="ap_ph_desc" id="editorNew"><?php echo htmlspecialchars($ap_phase_desc); ?></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" name="update-desc" style="width: 30%;" class="btn btn-sm btn-outline-success">Update</button>
                </div>
            </form>
        </div>
        <!-- ============ RIGHT SECTION ============ -->
        <div class="col-md-6">
            <!-- ============ ASSIGNMENT SECTION ============ -->
            <?php
            if (isset($_POST['assign'])) {
                $ap_ph_assigned_to = mysqli_real_escape_string($connection, $_POST['ap_ph_assigned_to']);
                $ap_ph_due_date = mysqli_real_escape_string($connection, $_POST['ap_ph_due_date']);
                $ap_ph_status = mysqli_real_escape_string($connection, $_POST['ap_ph_status']);

                $update_assign = "UPDATE ap_phase SET 
                ap_ph_assigned_to = '$ap_ph_assigned_to', 
                ap_ph_due_date = '$ap_ph_due_date',
                ap_ph_status = '$ap_ph_status'  WHERE ap_phase_id = '$ap_phase_id'";
                $update_assign_r = mysqli_query($connection, $update_assign);

                if ($update_assign_r) {
                    echo '<div id="alertBox" style="font-size: 12px" class="alert alert-success mb-2" role="alert"> Assigned Successfully! </div>';
                } else {
                    echo '<div id="alertBox" style="font-size: 12px" class="alert alert-danger mb-2" role="alert"> Failed to Assign! </div>';
                }

                // Update local variables for prefill
                $ap_phase_assigned_to = $ap_ph_assigned_to;
                $ap_phase_due_date = $ap_ph_due_date;
            }
            ?>

            <form method="POST" class="card p-3">
                <div class="mb-3">
                    <label style="font-size: 12px !important;" class="form-label">Assigned to</label>
                    <select name="ap_ph_assigned_to" style="font-size: 12px !important;" class="form-select" aria-label="Default select example">
                        <option disabled <?php if (!$ap_phase_assigned_to) echo 'selected'; ?>>Open this select menu</option>
                        <?php
                        $get_user = "SELECT * FROM user";
                        $get_user_r = mysqli_query($connection, $get_user);
                        while ($row = mysqli_fetch_assoc($get_user_r)) {
                            $fetched_user_name = $row['isms_user_name'];
                            $selected = ($fetched_user_name == $ap_phase_assigned_to) ? "selected" : "";
                            echo "<option value='" . htmlspecialchars($fetched_user_name) . "' $selected>" . htmlspecialchars($fetched_user_name) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label style="font-size: 12px !important;" class="form-label">Review Date</label>
                    <input
                        name="ap_ph_due_date"
                        style="font-size: 12px !important;"
                        type="date"
                        class="form-control"
                        value="<?php echo htmlspecialchars($ap_phase_due_date); ?>">
                </div>

                <div class="mb-3">
                    <label style="font-size: 12px !important;" class="form-label">Assigned to</label>
                    <select name="ap_ph_status" style="font-size: 12px !important;" class="form-select" aria-label="Default select example">
                        <option disabled <?php if (!$ap_ph_status) echo 'selected'; ?>>Open this select menu</option>
                        <option value="Open" <?php if ($ap_ph_status == "Open") echo 'selected'; ?>>Open</option>
                        <option value="Completed" <?php if ($ap_ph_status == "Completed") echo 'selected'; ?>>Completed</option>
                        <option value="In-Progress" <?php if ($ap_ph_status == "In-Progress") echo 'selected'; ?>>In-Progress</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" name="assign" style="width: 30%;" class="btn btn-sm btn-outline-success">Assign</button>
                </div>
            </form>

            <!-- ============ COMMENT SECTION ============ -->
            <div class="card p-3 mt-3">
                <?php
                if (isset($_POST['add-comm'])) {
                    $ap_comm_phase_id = mysqli_real_escape_string($connection, $_POST['ap_comm_phase_id']);
                    $ap_comm_by = mysqli_real_escape_string($connection, $_POST['ap_comm_by']);
                    $ap_comm_date = mysqli_real_escape_string($connection, date('m-d-Y'));
                    $ap_comm = mysqli_real_escape_string($connection, $_POST['ap_comm']);


                    $insert_comment = "INSERT INTO ap_comment (ap_comm_phase_id, ap_comm_by, ap_comm_date, ap_comm) VALUES ('$ap_comm_phase_id', '$ap_comm_by', '$ap_comm_date', '$ap_comm')";
                    $insert_comment_r = mysqli_query($connection, $insert_comment);
                    if ($insert_comment_r) {
                        echo '<div id="alertBox" style="font-size: 12px" class="alert alert-success" role="alert"> Comment Added Successfully! </div>';
                    } else {
                        echo '<div id="alertBox" style="font-size: 12px" class="alert alert-danger" role="alert"> Failed to Add Comment! </div>';
                    }
                }
                ?>
                <div class="d-flex justify-content-space-around mb-3">
                    <p style="font-size: 12px !important; flex:1;">Comments</p>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" name="add-comment" class="btn btn-sm btn-outline-success" style="font-size: 12px;">
                        <ion-icon name="add-outline"></ion-icon>
                    </button>
                </div>

                <!-- ============ FETCH COMMENT SECTION ============ -->
                <?php
                if (isset($_POST['del-comm'])) {
                    $ap_comm_id = mysqli_real_escape_string($connection, $_POST['ap_comm_id']);
                    $delete_comm = "DELETE FROM ap_comment WHERE ap_comm_id = '$ap_comm_id'";
                    $delete_comm_r = mysqli_query($connection, $delete_comm);
                }

                $get_comm = "SELECT * FROM ap_comment WHERE ap_comm_phase_id = '$ap_phase_id' ORDER BY ap_comm_date DESC";
                $get_comm_r = mysqli_query($connection, $get_comm);
                $count = mysqli_num_rows($get_comm_r);
                if ($count == 0) {
                    echo '<div style="font-size: 12px" class="alert alert-danger mt-3 mb-5" role="alert"> No Comments Found! </div>';
                } else {
                    while ($row = mysqli_fetch_assoc($get_comm_r)) {
                        $ap_comm_by = $row['ap_comm_by'];
                        $ap_comm_date = $row['ap_comm_date'];
                        $ap_comm = $row['ap_comm'];
                ?>
                        <div class="mb-3" style="border-bottom: 1px solid #ccc;">
                            <form action="" method="POST" style="display: flex; justify-content: space-between; align-items: center;">
                                <input type="text" name="ap_comm_id" value="<?php echo $row['ap_comm_id'] ?>" hidden>
                                <p style="margin: 0; font-weight: 600; font-size: 10px"><?php echo $ap_comm_by ?> - <?php echo $ap_comm_date ?></p>
                                <button type="submit" name="del-comm" class="btn btn-sm btn-outline-danger" style="font-size: 10px !important;"><ion-icon name="close-outline"></ion-icon></button>
                            </form>
                            <p style="margin: 0 !important; font-size: 12px !important;"><?php echo $ap_comm ?></p>
                        </div>
                <?php }
                } ?>

                <!-- ============ COMMENT MODAL ============ -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <form action="" method="POST" class="modal-content">

                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Comment</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="ap_comm_phase_id" value="<?php echo $ap_phase_id ?>">
                                <input type="hidden" name="ap_comm_by" value="<?php echo $user_name ?>">
                                <div class="mb-3">
                                    <div class="WYSIWYG-editor">
                                        <textarea name="ap_comm" id="auditEditor"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="add-comm" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#auditEditor').summernote({
            height: 300,
            minHeight: 150,
            maxHeight: 500,
            focus: true
        });

        $('form').on('submit', function() {
            $('#editorContent').val($('#auditEditor').summernote('code'));
        });
    });
</script>
<?php include 'includes/footer.php'; ?>