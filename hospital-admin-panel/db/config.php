<?php
// Database configuration for GoogieHost
$host = "localhost";
$db = "hospit27_hospital_management";
$user = "hospit27_admin_raj";
$pass = "Raj515565";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 for proper Unicode support
$conn->set_charset("utf8mb4");
?>