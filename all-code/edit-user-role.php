<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
?>
<div class="dashboard-container">
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user_role'])) {
        $isms_user_id = mysqli_real_escape_string($connection, $_POST['isms_user_id']);

        $fetch_user = "SELECT * FROM `user` WHERE `isms_user_id` = '$isms_user_id'";
        $fetch_user_r = mysqli_query($connection, $fetch_user);

        if ($row = mysqli_fetch_assoc($fetch_user_r)) {
            $isms_user_id_new = $row['isms_user_id'];
            $isms_user_name = $row['isms_user_name'];
            $isms_user_role = $row['isms_user_role'];
        }
    }
    ?>
    <form class="form-container" action="user-setup.php" method="POST">
        <input type="hidden" name="isms_user_id_new" value="<?php echo $isms_user_id_new; ?>" />
        
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" value="<?php echo htmlspecialchars($isms_user_name); ?>" class="form-control" id="name" disabled>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">User Role</label>
            <select name="isms_user_role_new" class="form-select" id="role" required>
                <option value="1" <?php echo ($isms_user_role == '1') ? 'selected' : ''; ?>>Admin</option>
                <option value="2" <?php echo ($isms_user_role == '2') ? 'selected' : ''; ?>>Read Only</option>
            </select>
        </div>

        <button type="submit" name="update_user" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
