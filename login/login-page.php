<?php
include('includes/connection.php');
session_start(); // Ensure session is started

// Redirect to dashboard if user is already logged in
if (isset($_COOKIE['user_session']) || isset($_SESSION['user_session'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['user_email']) ? $_POST['user_email'] : '';
    $password = isset($_POST['user_password']) ? $_POST['user_password'] : '';

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // Query to fetch user details, including user role
    $stmt = $connection->prepare("SELECT isms_user_id, isms_user_name, isms_user_email, isms_user_password, isms_user_role FROM user WHERE isms_user_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['isms_user_password'])) {
            // Generate a session token (alternative to random_bytes)
            $user_session = bin2hex(openssl_random_pseudo_bytes(16));
            $_SESSION['user_session'] = $user_session;
            setcookie('user_session', $user_session, time() + (86400 * 30), '/'); // Store session token in cookie for 30 days

            // Store user details in cookies and session
            $_SESSION['user_name'] = $user['isms_user_name'];
            $_SESSION['user_email'] = $user['isms_user_email'];
            $_SESSION['user_role'] = $user['isms_user_role']; // Store role in session

            setcookie('user_name', $user['isms_user_name'], time() + (86400 * 30), '/');
            setcookie('user_email', $user['isms_user_email'], time() + (86400 * 30), '/');
            setcookie('user_role', $user['isms_user_role'], time() + (86400 * 30), '/'); // Store user role in cookie

            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Incorrect password.";
        }
    } else {
        $error_message = "User not found.";
    }

    $stmt->close();
    $connection->close();
}
?>
<div class="login-page-container">
    <div class="login-form">
        <form method="POST" action="">
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="user_email" class="form-label">Email address</label>
                <input type="email" name="user_email" class="form-control" id="user_email" required>
            </div>
            <div class="mb-3">
                <label for="user_password" class="form-label">Password</label>
                <input type="password" name="user_password" class="form-control" id="user_password" required>
            </div>
            <button type="submit" class="w-100 btn btn-primary in3-btn">LOGIN</button>
        </form>
        <p>&#169; Copyright in3corp.com</p>
    </div>
</div>
