<?php
$host = "localhost";
$user = "root";
$pass = ""; // Use your DB password if any
$db = "studio_proposals";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
