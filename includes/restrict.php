<?php
// Secure session settings
ini_set('session.cookie_lifetime', 0);
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

if (!isset($_SESSION['user_session']) || !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
