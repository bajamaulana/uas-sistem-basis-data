<<?php

$host     = getenv('DB_HOST');
$dbname   = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$port     = getenv('DB_PORT');

$conn = new mysqli(
    $host,
    $username,
    $password,
    $dbname,
    (int)$port
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");