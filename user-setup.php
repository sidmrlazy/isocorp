<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/connection.php';
?>
<div class="dashboard-container">
    <a href="add-user.php" class="btn btn-sm btn-outline-success mb-3 mt-3">Add User</a>

    <?php
    if (isset($_POST['del-user'])) {
        $isms_user_id = $_POST['isms_user_id'];

        $del_query = "DELETE FROM `user` WHERE isms_user_id = $isms_user_id";
        $del_query_r = mysqli_query($connection, $del_query);
        if ($del_query_r) { ?>
            <div id="alertBox" class="alert alert-success mb-3 mt-3" role="alert">
                User deleted!
            </div>
            <?php
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
        // Correctly fetching the user ID and role from the POST request
        $isms_user_id_new = mysqli_real_escape_string($connection, $_POST['isms_user_id_new']);
        $isms_user_role_new = mysqli_real_escape_string($connection, $_POST['isms_user_role_new']);

        // Ensure variables are not empty before proceeding
        if (!empty($isms_user_id_new) && !empty($isms_user_role_new)) {
            $update_query = "UPDATE `user` SET `isms_user_role` = '$isms_user_role_new' WHERE `isms_user_id` = '$isms_user_id_new'";
            $update_query_result = mysqli_query($connection, $update_query);

            if ($update_query_result) { ?>
                <div id="alertBox" class="alert alert-success mb-3" role="alert">
                    User Role Updated!
                </div>
            <?php } else { ?>
                <div id="alertBox" class="alert alert-danger mb-3" role="alert">
                    Error updating user role: <?php echo mysqli_error($connection); ?>
                </div>
            <?php }
        } else { ?>
            <div id="alertBox" class="alert alert-warning mb-3" role="alert">
                Invalid user data. Please try again.
            </div>
        <?php }
    }

    // Fetch and display users
    $get_users = "SELECT * FROM user";
    $get_users_result = mysqli_query($connection, $get_users);
    $get_user_count = mysqli_num_rows($get_users_result);

    if ($get_user_count > 0) { ?>
        <div class="table-responsive card p-3">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th style="font-size: 12px !important;" scope="col">ID</th>
                        <th style="font-size: 12px !important;" scope="col">NAME</th>
                        <th style="font-size: 12px !important;" scope="col">EMAIL</th>
                        <th style="font-size: 12px !important;" scope="col">USER ROLE</th>
                        <th style="font-size: 12px !important;" scope="col">CREATED ON</th>
                        <th style="font-size: 12px !important;" scope="col" class="text-center">ROLE</th>
                        <th style="font-size: 12px !important;" scope="col" class="text-center">DELETE</th>
                        <th style="font-size: 12px !important;" scope="col" class="text-center">UPDATE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($get_users_result)) {
                        $isms_user_id = $row['isms_user_id'];
                        $isms_user_name = $row['isms_user_name'];
                        $isms_user_email = $row['isms_user_email'];
                        $isms_user_role = $row['isms_user_role'];
                        $isms_user_creation_dt = $row['isms_user_creation_dt'];
                    ?>
                        <tr>
                            <td style="font-size: 12px !important;" scope="row"><?php echo $isms_user_id ?></td>
                            <td style="font-size: 12px !important;"><?php echo $isms_user_name ?></td>
                            <td style="font-size: 12px !important;"><?php echo $isms_user_email ?></td>
                            <td style="font-size: 12px !important;"><?php echo ($isms_user_role == '1') ? "Admin" : "Read Only"; ?></td>
                            <td style="font-size: 12px !important;"><?php echo $isms_user_creation_dt ?></td>
                            <td style="font-size: 12px !important;" class="text-center">
                                <form action="edit-user-role.php" method="POST">
                                    <input type="hidden" name="isms_user_id" value="<?php echo $isms_user_id ?>">
                                    <button style="font-size: 12px !important;" type="submit" name="edit_user_role" class="btn btn-sm btn-outline-primary">
                                        <ion-icon name="build-outline"></ion-icon>
                                    </button>
                                </form>
                            </td>
                            <td style="font-size: 12px !important;" class="text-center">
                                <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    <input type="hidden" name="isms_user_id" value="<?php echo $isms_user_id ?>">
                                    <button style="font-size: 12px !important;" type="submit" name="del-user" class="btn btn-sm btn-outline-danger">
                                        <ion-icon name="trash-outline"></ion-icon>
                                    </button>
                                </form>
                            </td>
                            <td style="font-size: 12px !important;" class="text-center">
                                <form action="" method="POST">
                                    <input type="hidden" name="isms_user_id" value="<?php echo $isms_user_id ?>">
                                    <button style="font-size: 12px !important;" class="btn btn-sm btn-outline-warning">
                                        <ion-icon name="create-outline"></ion-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php
    }
    ?>
</div>
<?php include 'includes/footer.php'; ?>