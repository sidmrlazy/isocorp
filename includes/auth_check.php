<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isAuthorized = isset($_SESSION['user_session']) || isset($_COOKIE['user_session']);

if (!$isAuthorized) {
    http_response_code(403); // Forbidden
    echo "<h1>403 - You are not authorized to access this page.</h1>";
    exit();
}
