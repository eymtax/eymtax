<?php
// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'eymtax_user');
define('DB_PASSWORD', 'Eymtax@2024#Secure');
define('DB_NAME', 'eymtax_db');

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($conn, "utf8");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
?> 