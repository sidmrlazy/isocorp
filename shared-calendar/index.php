<?php
session_start();
include 'includes/connection.php';
include 'includes/header.php';

if (isset($_SESSION['isms_user_id'])) {
    // Already logged in? Redirect to calendar page
    header("Location: calendar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $connection->prepare("SELECT isms_user_id, isms_user_name, isms_user_email, isms_user_password FROM user WHERE isms_user_email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['isms_user_password'])) {
            $_SESSION['isms_user_id'] = $user['isms_user_id'];
            $_SESSION['isms_user_name'] = $user['isms_user_name'];

            $update = $connection->prepare("UPDATE user SET isms_user_last_login = NOW() WHERE isms_user_id = ?");
            $update->bind_param('i', $user['isms_user_id']);
            $update->execute();

            // Redirect to calendar.php on successful login
            header("Location: calendar.php");
            exit;
        } else {
            $login_error = "Invalid email or password.";
        }
    } else {
        $login_error = "Invalid email or password.";
    }
}
?>

<div class="container">
    <form action="" method="POST" class="card p-4 mt-5">
        <div class="mb-3">
            <label for="emailInput" class="form-label">Login ID</label>
            <input type="email" name="email" class="form-control" id="emailInput" placeholder="" required>
        </div>
        <div class="mb-3">
            <label for="passwordInput" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="passwordInput" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-sm btn-outline-success">LOGIN</button>
        <?php if (!empty($login_error)) echo "<p style='color:red; margin-top: 10px;'>$login_error</p>"; ?>
    </form>
</div>

<?php include 'includes/footer.php'; ?>