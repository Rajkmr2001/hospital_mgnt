<?php
// Database credentials for GoogieHost
$servername = "localhost";
$username = "hospit27_rajskmr";
$password = "Rajneha7070";
$dbname = "hospit27_hospital_db";
$port = 3306;

// Set the default timezone
date_default_timezone_set("Asia/Kolkata");

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . ". Please ensure your GoogieHost database is accessible.");
}

// Create patient_data table if it doesn't exist
$table_sql = "CREATE TABLE IF NOT EXISTS patient_data2 (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    contact VARCHAR(15) NOT NULL,
    name VARCHAR(100) NOT NULL,
    age INT(3) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    address TEXT NOT NULL,
    submission_time VARCHAR(20) NOT NULL,
    submission_date DATE NOT NULL
)";
if (!$conn->query($table_sql)) {
    die("Error creating table: " . $conn->error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $submission_time = date("h:i A"); // Current time with AM/PM
    $submission_date = date("Y-m-d"); // Current date

    // Prepare SQL query to insert data into the database
    $stmt = $conn->prepare("INSERT INTO patient_data2 (contact, name, age, gender, address, submission_time, submission_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissss", $contact, $name, $age, $gender, $address, $submission_time, $submission_date);

    // Execute query and check if data is inserted
    if ($stmt->execute()) {
        // Data inserted successfully, redirect to the page to view the receipt
        header("Location: view_receipt.php?contact=" . urlencode($contact));
        exit(); // Make sure to exit after header redirect
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
$conn->close();
?>
