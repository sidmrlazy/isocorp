<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
?>
<div class="dashboard-container">
    <div class="row">
        <div class="col-md-4">
            <div class="card p-3">

                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
                    $isms_user_name = mysqli_real_escape_string($connection, $_POST['isms_user_name']);
                    $isms_user_email = mysqli_real_escape_string($connection, $_POST['isms_user_email']);
                    $isms_user_password = mysqli_real_escape_string($connection, $_POST['isms_user_password']);
                    $isms_user_confirm_password = mysqli_real_escape_string($connection, $_POST['isms_user_confirm_password']);
                    $isms_user_role = mysqli_real_escape_string($connection, $_POST['isms_user_role']);

                    if ($isms_user_password !== $isms_user_confirm_password) { ?>
                        <div style="font-size: 12px !important;" id="alertBox" class="alert alert-danger mb-3" role="alert">
                            Error: Passwords do not match.
                        </div>
                        <?php } else {
                        $hashed_password = password_hash($isms_user_password, PASSWORD_BCRYPT);
                        $add_user_query = "INSERT INTO `user` (`isms_user_name`, `isms_user_email`, `isms_user_password`, `isms_user_role`) 
                               VALUES ('$isms_user_name', '$isms_user_email', '$hashed_password', '$isms_user_role')";

                        $add_user_result = mysqli_query($connection, $add_user_query);
                        if ($add_user_result) { ?>
                            <div style="font-size: 12px !important;" id="alertBox" class="alert alert-success mb-3" role="alert">
                                User Added Successfully!
                            </div>
                        <?php } else { ?>
                            <div style="font-size: 12px !important;" id="alertBox" class="alert alert-danger mb-3" role="alert">
                                Error: Unable to add user. (<?php echo mysqli_error($connection); ?>)
                            </div>
                <?php }
                    }
                }
                ?>
                <form method="POST">
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="name" class="form-label">Full Name</label>
                        <input style="font-size: 12px !important;" type="text" name="isms_user_name" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="email" class="form-label">Email</label>
                        <input style="font-size: 12px !important;" type="email" name="isms_user_email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="password" class="form-label">Password</label>
                        <input style="font-size: 12px !important;" type="password" name="isms_user_password" class="form-control" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="confirm_password" class="form-label">Confirm Password</label>
                        <input style="font-size: 12px !important;" type="password" name="isms_user_confirm_password" class="form-control" id="confirm_password" required>
                    </div>
                    <div class="mb-3">
                        <label style="font-size: 12px !important;" for="role" class="form-label">User Role</label>
                        <select style="font-size: 12px !important;" name="isms_user_role" class="form-select" id="role" required>
                            <option value="" selected disabled>Select Role</option>
                            <option value="1">Admin</option>
                            <option value="2">Read Only</option>
                        </select>
                    </div>
                    <button type="submit" style="font-size: 12px !important;" name="add_user" class="btn btn-sm btn-outline-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to make alerts disappear -->
<script>
    setTimeout(function() {
        let alertBox = document.getElementById("alertBox");
        if (alertBox) {
            alertBox.style.transition = "opacity 0.5s";
            alertBox.style.opacity = "0";
            setTimeout(() => alertBox.remove(), 500);
        }
    }, 3000);
</script>

<?php include 'includes/footer.php'; ?>