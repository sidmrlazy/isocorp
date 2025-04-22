<?php
// PRODUCTION
$hostname = "localhost";
$username = "u998582784_isocorp"; 
$database = "u998582784_isocorp";
$password = "Darthvader@order66";

// DEVELOPMENT
// $hostname = "localhost";
// $username = "root";
// $database = "isocorp";
// $password = "";

// Validate Connection
$connection = new mysqli($hostname, $username, $password, $database);
if ($connection->connect_error) {
    die("Error 404: " . $connection->connect_error);
}