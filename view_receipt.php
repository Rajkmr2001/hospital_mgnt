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
        .bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
            opacity: 1;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .receipt-container {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: auto;
            background: rgba(255, 255, 255, 0.60); /* more transparent so bg shows clearer */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    .header { text-align: center; margin-bottom: 30px; }
    .header h2 { margin: 0; font-size: 30px; color: #111; font-weight: 800; }
    .header h3 { margin: 5px 0; font-size: 22px; color: #222; font-weight: 800; }
        .table { margin-top: 20px; }
    .table th, .table td { padding: 12px 15px; font-weight: 700; font-size: 16px; color: #111; }
    .table th { background-color: rgba(242,242,242,0.9); width: 30%; }
        .footer { text-align: center; margin-top: 30px; }
        .footer-note {
            margin-top: 30px;
            padding: 10px 5px;
            background: transparent; /* remove extra background */
            border-left: none;
            font-size: 14px;
            color: #222;
            text-align: center;
        }
        .creator-link {
            color: #0b8f3b; /* green */
            text-decoration: none;
            font-weight: 700;
        }
        .creator-link:hover {
            text-decoration: underline;
        }
        .github-link {
            color: #0b8f3b;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .github-icon {
            width: 18px;
            height: 18px;
            fill: #0b8f3b;
            vertical-align: middle;
        }
        a.creator-link, a.github-link { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        @media print {
            body {
                margin: 0;
                background-color: #fff;
                background-image: none !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .receipt-container {
                background: white !important;
                box-shadow: none;
                border-radius: 0;
            }
            .no-print { display: none; }
            .bg-image { display: block; opacity: 1; }
        }
    </style>
</head>
<body>
    <img src="Images/college_image.jpg" alt="background" class="bg-image">
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
            <p>Created and managed by <a href="https://rajkmr2001.github.io/hospital_mgnt/creator.html" class="creator-link" target="_blank" rel="noopener">Raj Kumar</a></p>
            <p>For more visit - <a href="https://github.com/Rajkmr2001" class="github-link" target="_blank" rel="noopener">
                <!-- GitHub SVG icon -->
                <svg class="github-icon" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.19 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
                </svg>
                GitHub Profile
            </a></p>
        </div>
        <div class="footer no-print">
            <div style="margin-bottom:8px;font-size:14px;color:#333;">Before saving as PDF: enable "Background graphics" or "Background colors and images" in the browser print dialog for the image to appear.</div>
            <button onclick="window.print()" class="btn btn-primary">Print or Save as PDF</button>
            <a href="index.html" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>
</body>
</html>
