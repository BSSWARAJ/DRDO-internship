<?php
// Database configuration
$host = "localhost"; // The host name for your database (usually "localhost" if the database is on the same server)
$username = "root"; // Your database username
$password = ""; // Your database password
$database = "cabs"; // Your database name

// Create a connection to the database
$conn = mysqli_connect($host, $username, $password, $database);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>