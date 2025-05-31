<?php
session_start();

// Clear session data
session_unset();
session_destroy();

// Clear cookies related to the session
if (isset($_COOKIE['user_session'])) {
    setcookie('user_session', '', time() - 3600, '/'); // Expire the cookie
}
if (isset($_COOKIE['user_name'])) {
    setcookie('user_name', '', time() - 3600, '/'); // Expire the cookie
}
if (isset($_COOKIE['user_email'])) {
    setcookie('user_email', '', time() - 3600, '/'); // Expire the cookie
}

// Redirect to the login page
header("Location: index.php");
exit();
