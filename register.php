<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration - auto-detect environment
if ($_SERVER['HTTP_HOST'] == 'hospitalmgnt.whf.bz') {
    // GoogieHost production environment
    $servername = "localhost";
    $username = "hospit27_rajskmr";
    $password = "Rajneha7070";
    $dbname = "hospit27_hospital_db";
    $port = 3306;
} else {
    // Local XAMPP development environment
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hospital_management";
    $port = 3306;
}

// Log connection attempt
file_put_contents('register_log.txt', date('Y-m-d H:i:s') . " - Attempting connection\n", FILE_APPEND);

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    $error_msg = "Database connection failed: " . $conn->connect_error;
    file_put_contents('register_log.txt', date('Y-m-d H:i:s') . " - " . $error_msg . "\n", FILE_APPEND);
    die($error_msg);
}

$name = $_POST['name'];
$mobile = $_POST['mobile'];
$gender = $_POST['gender'];
$password = $_POST['password'];

// Validate mobile number
if (!preg_match("/^\d{10}$/", $mobile)) {
    echo "Error: Mobile number must be exactly 10 digits.";
    exit();
}

// Validate password
if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
    echo "Error: Password must be at least 8 characters with uppercase, lowercase, number, and special character.";
    exit();
}

// Check if mobile is already registered
$check_sql = "SELECT * FROM patient_register WHERE mobile_no = '$mobile'";
$result = $conn->query($check_sql);

if ($result->num_rows > 0) {
    echo "You have already registered!";
    exit();
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Get current date and time
$register_date = date("Y-m-d");
$register_time = date("H:i:s A");

// Log the query attempt
file_put_contents('register_log.txt', date('Y-m-d H:i:s') . " - Attempting to insert new user: {$mobile}\n", FILE_APPEND);

// Insert data with register_date and register_time
$sql = "INSERT INTO patient_register (mobile_no, name, gender, password, register_date, register_time) 
        VALUES ('$mobile', '$name', '$gender', '$hashed_password', '$register_date', '$register_time')";

if ($conn->query($sql) === TRUE) {
    file_put_contents('register_log.txt', date('Y-m-d H:i:s') . " - Registration successful for: {$mobile}\n", FILE_APPEND);
    echo "Registration successful!";
} else {
    $error = "Error: Could not register. MySQL Error: " . $conn->error;
    file_put_contents('register_log.txt', date('Y-m-d H:i:s') . " - " . $error . "\n", FILE_APPEND);
    echo $error;
}

$conn->close();
file_put_contents('register_log.txt', date('Y-m-d H:i:s') . " - Connection closed\n", FILE_APPEND);
?>
