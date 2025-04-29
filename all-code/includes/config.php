<?php
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Guest');
$user_name = isset($_COOKIE['user_name']) ? $_COOKIE['user_name'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest');
$user_role = isset($_COOKIE['user_role']) ? $_COOKIE['user_role'] : (isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'Guest');
?>