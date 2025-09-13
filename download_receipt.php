<?php
// Database credentials
$servername = "localhost";
$username = "hospit27_rajskmr";
$password = "Rajneha7070";
$dbname = "hospit27_hospital_db";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$contact = isset($_GET['contact']) ? $_GET['contact'] : '';
$stmt = $conn->prepare("SELECT * FROM patient_data2 WHERE contact = ?");
$stmt->bind_param('s', $contact);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows < 1) {
    echo "No data found for the given contact number.";
    exit();
}
$row = $result->fetch_assoc();

$stmt->close();
$conn->close();

// Appointment details
$today = date("Y-m-d");
$appointment_date = date('Y-m-d', strtotime("$today +" . rand(3, 7) . " days"));
$appointment_time_24 = rand(9, 17) . ":" . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
$appointment_time = DateTime::createFromFormat('H:i', $appointment_time_24)->format('g:i A');

$autoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    echo "Dompdf not installed. Run <code>composer require dompdf/dompdf</code>.";
    exit();
}
require_once $autoload;

use Dompdf\Dompdf;
use Dompdf\Options;

// Prepare background image
$imgPath = __DIR__ . '/Images/college_image.jpg';
$bgBase64 = "";
if (file_exists($imgPath)) {
    $mime = mime_content_type($imgPath);
    $data = base64_encode(file_get_contents($imgPath));
    $bgBase64 = "data:" . $mime . ";base64," . $data;
}

// HTML for PDF
$receipt = "
<!doctype html>
<html>
<head>
  <meta charset='utf-8'>
  <title>Appointment Receipt</title>
  <style>
    @page { margin: 15mm; }
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
        line-height: 1.6;
        font-size: 14px;
    }
    .container {
        padding: 30px;
        border-radius: 12px;
        max-width: 850px;
        margin: auto;
        border: 2px solid transparent;
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(45deg, #0b5fa4, #007bff) border-box;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .overlay {
        background: rgba(255,255,255,0.95);
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .header { text-align:center; margin-bottom:25px; }
    .header h2 { margin: 0; color: #0b5fa4; }
    .header h3 { margin: 5px 0; color: #555; }
    table {
        width:100%;
        border-collapse:collapse;
        margin-top:20px;
        font-size:14px;
        background: url('{$bgBase64}') no-repeat center;
        background-size: cover;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    th, td {
        padding:12px;
        border:1px solid #ddd;
        background-color: rgba(255, 255, 255, 0.73);
    }
    th { background-color:#f4f6f8; text-align:left; width:35%; }
    tr:nth-child(even) { background-color:#fafafa; }
    .footer-note {
        margin-top:30px;
        padding:20px 15px;
        border-top:2px dashed #ddd;
        font-size:13px;
        text-align:center;
        color:#444;
        background: rgba(248,249,250,0.8);
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .creator-link {
        color:#0b5fa4;
        font-weight:600;
        text-decoration:none;
        display: inline-block;
        padding: 8px 16px;
        margin: 4px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 2px 6px rgba(11,95,164,0.2);
        border: 1px solid rgba(11,95,164,0.3);
        transition: all 0.3s ease;
    }
    
    .creator-link:hover {
        box-shadow: 0 4px 12px rgba(11,95,164,0.3);
        transform: translateY(-1px);
        color: #007bff;
    }
    .github-link {
        display:inline-flex;
        align-items:center;
        color:#0b8f3b;
        font-weight:600;
        text-decoration:none;
        display: inline-block;
        padding: 8px 16px;
        margin: 4px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 2px 6px rgba(11,143,59,0.2);
        border: 1px solid rgba(11,143,59,0.3);
        transition: all 0.3s ease;
    }
    
    .github-link:hover {
        box-shadow: 0 4px 12px rgba(11,143,59,0.3);
        transform: translateY(-1px);
        color: #28a745;
    }
    .github-icon { width:16px; height:16px; margin-right:6px; fill:#0b8f3b; }
  </style>
</head>
<body>
  <div class='container'>
    <div class='overlay'>
      <div class='header'>
        <h2>Maa Kalawati Hospital Ranchi</h2>
        <h3>Appointment Receipt</h3>
      </div>
      <table>
        <tr><th>Hospital Name</th><td>Maa Kalawati Hospital Ranchi</td></tr>
        <tr><th>Full Name</th><td>" . htmlspecialchars($row['name']) . "</td></tr>
        <tr><th>Age</th><td>" . htmlspecialchars($row['age']) . "</td></tr>
        <tr><th>Gender</th><td>" . htmlspecialchars($row['gender']) . "</td></tr>
        <tr><th>Contact Number</th><td>" . htmlspecialchars($row['contact']) . "</td></tr>
        <tr><th>Submission Time</th><td>" . htmlspecialchars($row['submission_time']) . "</td></tr>
        <tr><th>Submission Date</th><td>" . htmlspecialchars($row['submission_date']) . "</td></tr>
        <tr><th>Appointment Date</th><td>" . $appointment_date . "</td></tr>
        <tr><th>Appointment Time</th><td>" . $appointment_time . "</td></tr>
      </table>
      <div class='footer-note'>
        <p><strong style="color: #dc3545; font-weight: 700;">Note:</strong> This website has been developed solely for educational purposes. It is a personal project and does not represent a real-world hospital management system.</p>
        <p>Created and managed by 
           <a href='https://rajkmr2001.github.io/hospital_mgnt/creator.html' class='creator-link' target='_blank' rel='noopener'>Raj Kumar</a>
        </p>
        <p>For more visit - 
           <a href='https://github.com/Rajkmr2001' class='github-link' target='_blank' rel='noopener'>
              <svg class='github-icon' viewBox='0 0 16 16' xmlns='http://www.w3.org/2000/svg'>
                <path d='M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38
                0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13
                -.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87
                2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95
                0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12
                0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 
                1.36.09 2 .27 1.53-1.04 2.2-.82 
                2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 
                1.27.82 2.15 0 3.07-1.87 3.75-3.65 
                3.95.29.25.54.73.54 1.48 0 1.07-.01 
                1.93-.01 2.19 0 .21.15.46.55.38A8.013 
                8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z'/>
              </svg>
              GitHub Profile
           </a>
        </p>
      </div>
    </div>
  </div>
</body>
</html>
";

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($receipt);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="appointment_receipt.pdf"');
echo $dompdf->output();
exit();
?>
