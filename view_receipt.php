<?php
// Database credentials for GoogieHost
$servername = "localhost";
$username = "hospit27_rajskmr";
$password = "Rajneha7070";
$dbname = "hospit27_hospital_db";
$port = 3306;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . ". Please ensure your GoogieHost database is accessible.");
}

// Fetch contact from URL
$contact = $_GET['contact'];

// Prepare and execute the query to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM patient_data2 WHERE contact = ?");
$stmt->bind_param("s", $contact);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "No data found for the given contact number.";
    exit();
}

$stmt->close();
$conn->close();

// Generate Random Appointment Date (3-7 days ahead)
$today = date("Y-m-d");
$appointment_days_ahead = rand(3, 7);
$appointment_date = date('Y-m-d', strtotime("$today +$appointment_days_ahead days"));

// Generate Random Appointment Time (between 9 AM to 5 PM)
$hour = rand(9, 17);
$minute = str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
$appointment_time_24 = "$hour:$minute";

// Convert to 12-hour AM/PM format
function convertTo12Hour($time24) {
    $time = DateTime::createFromFormat('H:i', $time24);
    return $time ? $time->format('g:i A') : $time24;
}
$appointment_time = convertTo12Hour($appointment_time_24);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-image: url('Images/college_image.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }
        .receipt-container {
            max-width: 800px;
            margin: auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 0; font-size: 28px; color: #333; }
        .header h3 { margin: 5px 0; font-size: 22px; color: #555; }
        .table { margin-top: 20px; }
        .table th, .table td { padding: 12px 15px; }
        .table th { background-color: #f2f2f2; width: 30%; }
        .footer { text-align: center; margin-top: 30px; }
        .footer-note {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            font-size: 14px;
            color: #6c757d;
            text-align: center;
        }
        .creator-link {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .creator-link:hover {
            text-decoration: underline;
        }
        @media print {
            body {
                margin: 0;
                background-color: #fff;
                background-image: none !important;
            }
            .receipt-container {
                background: white !important;
                box-shadow: none;
                border-radius: 0;
            }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h2>Maa Kalawati Hospital Ranchi</h2>
            <h3>Appointment Receipt</h3>
        </div>
        <table class="table table-bordered">
            <tbody>
                <tr><th>Hospital Name</th><td>Maa Kalawati Hospital Ranchi</td></tr>
                <tr><th>Full Name</th><td><?php echo htmlspecialchars($row['name']); ?></td></tr>
                <tr><th>Age</th><td><?php echo htmlspecialchars($row['age']); ?></td></tr>
                <tr><th>Gender</th><td><?php echo htmlspecialchars($row['gender']); ?></td></tr>
                <tr><th>Contact Number</th><td><?php echo htmlspecialchars($row['contact']); ?></td></tr>
                <tr><th>Submission Time</th><td><?php echo htmlspecialchars($row['submission_time']); ?></td></tr>
                <tr><th>Submission Date</th><td><?php echo htmlspecialchars($row['submission_date']); ?></td></tr>
                <tr><th>Appointment Date</th><td><?php echo $appointment_date; ?></td></tr>
                <tr><th>Appointment Time</th><td><?php echo $appointment_time; ?></td></tr>
            </tbody>
        </table>
        <div class="footer-note">
            <p><strong>Note:</strong> This website has been developed solely for educational purposes. It is a personal project and does not represent a real-world hospital management system.</p>
            <p>Created and managed by <a href="https://rajkmr2001.github.io/hospital_mgnt/creator.html" class="creator-link" target="_blank">Raj Kumar</a></p>
        </div>
        <div class="footer no-print">
            <button onclick="window.print()" class="btn btn-primary">Print or Save as PDF</button>
            <a href="index.html" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>
</body>
</html>
