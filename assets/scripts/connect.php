<?php 

// Database connection variables
$host = 'localhost';
$username = 'collins';
$password = '1234';
$database = 'mzehemen_db';
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>