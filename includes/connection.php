<?php
// TEST PRODUCTION
// $hostname = "sql203.infinityfree.com";
// $username = "if0_38336161"; 
// $database = "if0_38336161_isms";
// $password = "sid12asthana";

// Development Domain : isocorp.42web.io

// DEVELOPMENT
$hostname = "localhost";
$username = "root";
$database = "isms";
$password = "";

// Validate Connection
$connection = new mysqli($hostname, $username, $password, $database);
if ($connection->connect_error) {
    die("Error 404: " . $connection->connect_error);
}