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

// SQL query to get patient data using the contact number
$sql = "SELECT * FROM patient_data2 WHERE contact = '$contact'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch data for the given contact
    $row = $result->fetch_assoc();
} else {
    echo "No data found for the given contact number.";
    exit();
}

$conn->close();

// Generate Random Appointment Date (3-7 days ahead)
$today = date("Y-m-d");
$appointment_days_ahead = rand(3, 7); // Random number between 3 and 7
$appointment_date = date('Y-m-d', strtotime("$today +$appointment_days_ahead days"));

// Generate Random Appointment Time (between 9 AM to 5 PM)
$hour = rand(9, 17); // Random hour between 9 and 17 (9 AM to 5 PM)
$minute = str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT); // Random minute (00 to 59)
$appointment_time_24 = "$hour:$minute"; // 24-hour format

// Convert to 12-hour AM/PM format
function convertTo12Hour($time24) {
    $time = DateTime::createFromFormat('H:i', $time24);
    return $time ? $time->format('g:i A') : $time24;
}
$appointment_time = convertTo12Hour($appointment_time_24);

// Prepare the receipt content in an HTML table
$receipt = "
    <html>
    <head>
        <title>Appointment Receipt</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
                background-image: url('Images/college_image.jpg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                min-height: 100vh;
            }
            .container {
                background-color: rgba(255, 255, 255, 0.95);
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 15px rgba(0,0,0,0.1);
                max-width: 800px;
                margin: 0 auto;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            table, th, td {
                border: 1px solid black;
            }
            th, td {
                padding: 12px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
                width: 30%;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
            }
            .header h2 {
                margin: 0;
                font-size: 28px;
                color: #333;
            }
            .header h3 {
                margin: 5px 0;
                font-size: 22px;
                color: #555;
            }
            .footer-note {
                margin-top: 30px;
                padding: 15px;
                background-color: #f8f9fa;
                border-left: 4px solid #007bff;
                font-size: 14px;
                color: #6c757d;
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
                    background-image: none !important;
                    background-color: white !important;
                }
                .container {
                    background-color: white !important;
                    box-shadow: none !important;
                }
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Maa Kalawati Hospital Ranchi</h2>
                <h3>Appointment Receipt</h3>
            </div>
            <table>
                <tr>
                    <th>Field</th>
                    <th>Details</th>
                </tr>
                <tr>
                    <td>Hospital Name</td>
                    <td>Maa Kalawati Hospital Ranchi</td>
                </tr>
                <tr>
                    <td>Full Name</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                </tr>
                <tr>
                    <td>Age</td>
                    <td>" . htmlspecialchars($row['age']) . "</td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>" . htmlspecialchars($row['gender']) . "</td>
                </tr>
                <tr>
                    <td>Contact Number</td>
                    <td>" . htmlspecialchars($row['contact']) . "</td>
                </tr>
                <tr>
                    <td>Submission Time</td>
                    <td>" . htmlspecialchars($row['submission_time']) . "</td>
                </tr>
                <tr>
                    <td>Submission Date</td>
                    <td>" . htmlspecialchars($row['submission_date']) . "</td>
                </tr>
                <tr>
                    <td>Appointment Date</td>
                    <td>" . $appointment_date . "</td>
                </tr>
                <tr>
                    <td>Appointment Time</td>
                    <td>" . $appointment_time . "</td>
                </tr>
            </table>
            <div class='footer-note'>
                <p><strong>Note:</strong> This website has been developed solely for educational purposes. It is a personal project and does not represent a real-world hospital management system.</p>
                <p>Created and managed by <a href='https://rajkmr2001.github.io/hospital_mgnt/creator.html' class='creator-link' target='_blank'>Raj Kumar</a></p>
                <p>For more visit - <a href='https://github.com/Rajkmr2001' class='creator-link' target='_blank'>GitHub Profile</a></p>
            </div>
        </div>
    </body>
    </html>
";

// Set headers to force download the file as HTML
header('Content-Type: text/html');
header('Content-Disposition: attachment; filename="appointment_receipt.html"');

// Output the receipt content as HTML
echo $receipt;
?>
