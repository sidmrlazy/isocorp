<?php
// TEST PRODUCTION
$hostname = "localhost";
$username = "u579024239_isocorp"; 
$database = "u579024239_isocorp";
$password = "Darthvader@order66";

// Development Domain : isocorp.42web.io

// DEVELOPMENT
// $hostname = "localhost";
// $username = "root";
// $database = "isms";
// $password = "";

// Validate Connection
$connection = new mysqli($hostname, $username, $password, $database);
if ($connection->connect_error) {
    die("Error 404: " . $connection->connect_error);
}