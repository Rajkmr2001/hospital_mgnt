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

// Fetch contact from URL safely
$contact = isset($_GET['contact']) ? $_GET['contact'] : '';

// Prepare and execute query to avoid injection
$stmt = $conn->prepare("SELECT * FROM patient_data2 WHERE contact = ?");
$stmt->bind_param('s', $contact);
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

function convertTo12Hour($time24) {
    $time = DateTime::createFromFormat('H:i', $time24);
    return $time ? $time->format('g:i A') : $time24;
}
$appointment_time = convertTo12Hour($appointment_time_24);

// Try to include Dompdf (installed via Composer). If not available, fallback to HTML download.
$autoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    // Fallback: send HTML file (as before) but with base64 background so that if user opens HTML it shows image
    $imgPath = __DIR__ . '/Images/college_image.jpg';
    $bgCss = '';
    if (file_exists($imgPath)) {
        $mime = mime_content_type($imgPath);
        $data = base64_encode(file_get_contents($imgPath));
        $bgCss = "background-image: url('data:" . $mime . ";base64," . $data . "'); background-size: cover; background-position: center; background-repeat: no-repeat;";
    } else {
        $bgCss = "background-color: #ffffff;";
    }

    $receipt = "
    <html>
    <head>
        <title>Appointment Receipt</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 0; padding: 20px; {$bgCss} min-height:100vh; }
            .container { background-color: rgba(255,255,255,0.95); padding:30px; border-radius:10px; box-shadow:0 0 15px rgba(0,0,0,0.1); max-width:800px; margin:0 auto; }
            table { width:100%; border-collapse:collapse; margin-top:20px; }
            table, th, td { border:1px solid black; }
            th, td { padding:12px; text-align:left; }
            th { background-color:#f2f2f2; width:30%; }
            .header { text-align:center; margin-bottom:30px; }
            .footer-note { margin-top:30px; padding:15px; background-color:#f8f9fa; border-left:4px solid #007bff; font-size:14px; color:#6c757d; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'><h2>Maa Kalawati Hospital Ranchi</h2><h3>Appointment Receipt</h3></div>
            <table>
                <tr><th>Field</th><th>Details</th></tr>
                <tr><td>Hospital Name</td><td>Maa Kalawati Hospital Ranchi</td></tr>
                <tr><td>Full Name</td><td>" . htmlspecialchars($row['name']) . "</td></tr>
                <tr><td>Age</td><td>" . htmlspecialchars($row['age']) . "</td></tr>
                <tr><td>Gender</td><td>" . htmlspecialchars($row['gender']) . "</td></tr>
                <tr><td>Contact Number</td><td>" . htmlspecialchars($row['contact']) . "</td></tr>
                <tr><td>Submission Time</td><td>" . htmlspecialchars($row['submission_time']) . "</td></tr>
                <tr><td>Submission Date</td><td>" . htmlspecialchars($row['submission_date']) . "</td></tr>
                <tr><td>Appointment Date</td><td>" . $appointment_date . "</td></tr>
                <tr><td>Appointment Time</td><td>" . $appointment_time . "</td></tr>
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

    header('Content-Type: text/html');
    header('Content-Disposition: attachment; filename="appointment_receipt.html"');
    echo $receipt;
    exit();
}

require_once $autoload;

use Dompdf\Dompdf;
use Dompdf\Options;

// Prepare base64 background for embedding in PDF (ensures image appears in generated PDF)
$imgPath = __DIR__ . '/Images/college_image.jpg';
$bgCss = '';
if (file_exists($imgPath)) {
    $mime = mime_content_type($imgPath);
    $data = base64_encode(file_get_contents($imgPath));
    $bgCss = "background-image: url('data:" . $mime . ";base64," . $data . "'); background-size: cover; background-position: center; background-repeat: no-repeat;";
} else {
    $bgCss = "background-color: #ffffff;";
}

$receipt = "
<!doctype html>
<html>
<head>
  <meta charset='utf-8'>
  <title>Appointment Receipt</title>
  <style>
    @page { margin: 20mm; }
    body { font-family: Arial, sans-serif; margin: 0; padding: 20px; {$bgCss} min-height:100vh; }
    .container { background-color: rgba(255,255,255,0.95); padding:30px; border-radius:10px; box-shadow:0 0 15px rgba(0,0,0,0.1); max-width:800px; margin:0 auto; }
    table { width:100%; border-collapse:collapse; margin-top:20px; }
    table, th, td { border:1px solid black; }
    th, td { padding:12px; text-align:left; }
    th { background-color:#f2f2f2; width:30%; }
    .header { text-align:center; margin-bottom:30px; }
    .footer-note { margin-top:30px; padding:15px; background-color:#f8f9fa; border-left:4px solid #007bff; font-size:14px; color:#6c757d; }
  </style>
</head>
<body>
  <div class='container'>
    <div class='header'><h2>Maa Kalawati Hospital Ranchi</h2><h3>Appointment Receipt</h3></div>
    <table>
      <tr><th>Field</th><th>Details</th></tr>
      <tr><td>Hospital Name</td><td>Maa Kalawati Hospital Ranchi</td></tr>
      <tr><td>Full Name</td><td>" . htmlspecialchars($row['name']) . "</td></tr>
      <tr><td>Age</td><td>" . htmlspecialchars($row['age']) . "</td></tr>
      <tr><td>Gender</td><td>" . htmlspecialchars($row['gender']) . "</td></tr>
      <tr><td>Contact Number</td><td>" . htmlspecialchars($row['contact']) . "</td></tr>
      <tr><td>Submission Time</td><td>" . htmlspecialchars($row['submission_time']) . "</td></tr>
      <tr><td>Submission Date</td><td>" . htmlspecialchars($row['submission_date']) . "</td></tr>
      <tr><td>Appointment Date</td><td>" . $appointment_date . "</td></tr>
      <tr><td>Appointment Time</td><td>" . $appointment_time . "</td></tr>
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

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($receipt);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$pdfOutput = $dompdf->output();
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="appointment_receipt.pdf"');
echo $pdfOutput;
exit();
?>
