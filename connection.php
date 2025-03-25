<?php
// db_connection.php

// Database connection credentials
$server = "localhost";  // Hostname (usually localhost)
$username = "root";   // Your MySQL username
$password = "";       // Your MySQL password
$database = "travel_db";  // Your database name

// Create a connection to the database
$mysqli = new mysqli($server, $username, $password, $database);

// Check if the connection was successful
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Set the character set to UTF-8 for better character encoding handling
$mysqli->set_charset("utf8");
?>
