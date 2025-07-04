<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
include('includes/header.php');
include('includes/connection.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_COOKIE['user_session']) || isset($_SESSION['user_session'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user input
    $email = isset($_POST['user_email']) ? trim($_POST['user_email']) : '';
    $password = isset($_POST['user_password']) ? trim($_POST['user_password']) : '';

    if (empty($email) || empty($password)) {
        $error_message = "Please enter both email and password.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else {
        if (!isset($connection) || $connection->connect_error) {
            die("Database connection error: " . $connection->connect_error);
        }

        // Prepare SQL statement
        $stmt = $connection->prepare("SELECT isms_user_id, isms_user_name, isms_user_email, isms_user_password, isms_user_role FROM user WHERE isms_user_email = ?");

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['isms_user_password'])) {
                    session_regenerate_id(true);
                    $user_session = bin2hex(random_bytes(16));
                    $_SESSION['user_session'] = $user_session;

                    $_SESSION['user_id'] = $user['isms_user_id'];
                    $_SESSION['user_name'] = $user['isms_user_name'];
                    $_SESSION['user_email'] = $user['isms_user_email'];
                    $_SESSION['user_role'] = $user['isms_user_role'];

                    // Set secure cookies correctly
                    $expiry_time = time() + (86400 * 30); // 30 days
                    setcookie('user_session', $user_session, $expiry_time, '/', '', isset($_SERVER['HTTPS']), true);
                    setcookie('user_id', $user['isms_user_id'], $expiry_time, '/', '', isset($_SERVER['HTTPS']), true);
                    setcookie('user_name', htmlspecialchars($user['isms_user_name']), $expiry_time, '/', '', isset($_SERVER['HTTPS']), true);
                    setcookie('user_email', htmlspecialchars($user['isms_user_email']), $expiry_time, '/', '', isset($_SERVER['HTTPS']), true);
                    setcookie('user_role', htmlspecialchars($user['isms_user_role']), $expiry_time, '/', '', isset($_SERVER['HTTPS']), true);

                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error_message = "Incorrect password.";
                }
            } else {
                $error_message = "User not found.";
            }
            $stmt->close();
        } else {
            $error_message = "Database error. Please try again later.";
        }
        $connection->close();
    }
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

<?php
include('includes/footer.php');
ob_end_flush();
?>