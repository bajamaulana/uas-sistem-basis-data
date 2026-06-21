<?php
/**
 * Database Connection - Ngopidea Coffee
 * Connects to MySQL database using mysqli
 */

$host     = '172.22.148.251';
$dbname   = 'coffeeidea';
$username = 'root';
$password = 'bajamaulana73*';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");
