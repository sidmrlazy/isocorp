<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/connection.php'; // Use the same connection file from the main project

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = $_POST['password'];

    // Look up user by email
    $sql = "SELECT * FROM user WHERE isms_user_email = '$email'";
    $result = mysqli_query($connection, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $row['isms_user_password'])) {
            // Session is already started in main project, so set variables directly
            $_SESSION['user_id'] = $row['isms_user_id'];
            $_SESSION['user_name'] = $row['isms_user_name'];
            $_SESSION['user_email'] = $row['isms_user_email'];
            $_SESSION['user_role'] = $row['isms_user_role'];

            // Update last login time
            $update = "UPDATE user SET isms_user_last_login = NOW() WHERE isms_user_id = {$row['isms_user_id']}";
            mysqli_query($connection, $update);

            // Redirect to remote-user-details.php on success
            header("Location: remote-user-details.php");
            exit;
        }
    }

    // On failure, redirect to index.php
    header("Location: index.php");
    exit;
}
?>

<!-- Login Form UI -->
<div class="container mt-5 login-form-holder">
    <form class="form-container-custom" method="POST" action="">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input name="email" type="email" class="form-control" id="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input name="password" type="password" class="form-control" id="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
