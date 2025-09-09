<?php
// Database credentials
$servername = "localhost";  // XAMPP MySQL host
$username = "hospit27_rajskmr";      // Database username
$password = "Rajneha7070"; // Database password
$dbname = "hospit27_hospital_db";    // Your database name
$port = 3306; // MySQL default port

// Set the default timezone
date_default_timezone_set("Asia/Kolkata");

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
    $stmt = $conn->prepare("INSERT INTO patient_data (contact, name, age, gender, address, submission_time, submission_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
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
?>
