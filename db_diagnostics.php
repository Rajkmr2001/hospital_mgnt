<?php
// Database configuration
$host = "localhost";
$db = "hospit27_hospital_db";
$user = "hospit27_rajskmr";
$password = "Rajneha7070";
$port = 3306;

// Initialize variables
$db_status = false;
$db_message = "";
$patient_reg_status = false;
$patient_reg_message = "";
$admin_login_status = false;
$admin_login_message = "";
$feedback_status = false;
$feedback_message = "";
$messages_status = false;
$messages_message = "";

// Function to check if a table exists
function tableExists($conn, $tableName) {
    $result = $conn->query("SHOW TABLES LIKE '$tableName'")->num_rows > 0;
    return $result;
}

// Function to check if a file exists
function fileExists($path) {
    return file_exists($path);
}

// Function to test table structure
function checkTableStructure($conn, $tableName, $requiredColumns) {
    $result = $conn->query("DESCRIBE $tableName");
    if (!$result) {
        return [false, "Could not check table structure: " . $conn->error];
    }
    
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    
    $missingColumns = [];
    foreach ($requiredColumns as $column) {
        if (!in_array($column, $columns)) {
            $missingColumns[] = $column;
        }
    }
    
    if (count($missingColumns) > 0) {
        return [false, "Missing columns: " . implode(", ", $missingColumns)];
    }
    
    return [true, "Table structure is valid"];
}

// Check database connection
try {
    $conn = new mysqli($host, $user, $password, $db, $port);
    
    if ($conn->connect_error) {
        $db_message = "Connection failed: " . $conn->connect_error;
    } else {
        $db_status = true;
        $db_message = "Database connection successful";
        
        // Check patient registration table
        if (tableExists($conn, "patient_register")) {
            $requiredColumns = ["mobile_no", "name", "gender", "password"];
            list($structureValid, $structureMessage) = checkTableStructure($conn, "patient_register", $requiredColumns);
            
            if ($structureValid) {
                $patient_reg_status = true;
                $patient_reg_message = "Patient registration table exists and has valid structure";
            } else {
                $patient_reg_message = "Patient registration table exists but has issues: $structureMessage";
            }
        } else {
            $patient_reg_message = "Patient registration table does not exist";
        }
        
        // Check admin table
        if (tableExists($conn, "admins")) {
            $requiredColumns = ["id", "mobile_number", "password"];
            list($structureValid, $structureMessage) = checkTableStructure($conn, "admins", $requiredColumns);
            
            if ($structureValid) {
                $admin_login_status = true;
                $admin_login_message = "Admin table exists and has valid structure";
            } else {
                $admin_login_message = "Admin table exists but has issues: $structureMessage";
            }
        } else {
            $admin_login_message = "Admin table does not exist";
        }
        
        // Check feedback table
        if (tableExists($conn, "feedback")) {
            $feedback_status = true;
            $feedback_message = "Feedback table exists";
        } else {
            $feedback_message = "Feedback table does not exist";
        }
        
        // Check messages table
        if (tableExists($conn, "messages")) {
            $messages_status = true;
            $messages_message = "Messages table exists";
        } else {
            $messages_message = "Messages table does not exist";
        }
    }
} catch (Exception $e) {
    $db_message = "Connection error: " . $e->getMessage();
}

// Check if patient_registration.html exists
$patient_reg_file_exists = fileExists("patient_registration.html");

// Check if admin login file exists
$admin_login_file_exists = fileExists("hospital-admin-panel/admin/login.php");

// Function to test if a URL is accessible
function testUrlAccess($url) {
    $headers = @get_headers($url);
    return $headers && strpos($headers[0], '200');
}

// Get server information
$server_info = [
    'PHP Version' => phpversion(),
    'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    'Document Root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
    'Server Name' => $_SERVER['SERVER_NAME'] ?? 'Unknown',
    'HTTP Host' => $_SERVER['HTTP_HOST'] ?? 'Unknown'
];

// Get current URL base
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base_url = $protocol . $domain;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital System Diagnostics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2563eb;
            text-align: center;
            margin-bottom: 30px;
        }
        .status-card {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
            border-left: 5px solid #ccc;
        }
        .success {
            background-color: #ecfdf5;
            border-left-color: #10b981;
        }
        .error {
            background-color: #fef2f2;
            border-left-color: #ef4444;
        }
        .warning {
            background-color: #fffbeb;
            border-left-color: #f59e0b;
        }
        .status-title {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .status-icon {
            margin-right: 10px;
            font-size: 24px;
        }
        .success .status-icon:after {
            content: '✓';
            color: #10b981;
        }
        .error .status-icon:after {
            content: '✗';
            color: #ef4444;
        }
        .warning .status-icon:after {
            content: '!';
            color: #f59e0b;
        }
        .status-message {
            margin-top: 5px;
            font-size: 14px;
        }
        .instructions {
            margin-top: 30px;
            padding: 15px;
            background-color: #f0f9ff;
            border-radius: 5px;
            border-left: 5px solid #0ea5e9;
        }
        .instructions h2 {
            margin-top: 0;
            color: #0369a1;
        }
        code {
            background-color: #f1f5f9;
            padding: 2px 5px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hospital System Diagnostics</h1>
        
        <!-- Server Information -->
        <div class="status-card">
            <div class="status-title">
                <h2>Server Information</h2>
            </div>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Setting</th>
                    <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Value</th>
                </tr>
                <?php foreach ($server_info as $key => $value): ?>
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($key); ?></td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($value); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        
        <!-- Database Connection Status -->
        <div class="status-card <?php echo $db_status ? 'success' : 'error'; ?>">
            <div class="status-title">
                <div class="status-icon"></div>
                <h2>Database Connection</h2>
            </div>
            <p><strong>Status:</strong> <?php echo $db_status ? 'Connected' : 'Failed'; ?></p>
            <p class="status-message"><?php echo $db_message; ?></p>
        </div>
        
        <!-- Patient Registration Status -->
        <div class="status-card <?php echo $patient_reg_status ? 'success' : 'warning'; ?>">
            <div class="status-title">
                <div class="status-icon"></div>
                <h2>Patient Registration System</h2>
            </div>
            <p><strong>Database Table:</strong> <?php echo $patient_reg_status ? 'Available' : 'Not Available'; ?></p>
            <p class="status-message"><?php echo $patient_reg_message; ?></p>
            <p><strong>Registration File:</strong> <?php echo $patient_reg_file_exists ? 'Found' : 'Not Found'; ?></p>
            <?php if (!$patient_reg_file_exists): ?>
                <p class="status-message">The patient_registration.html file could not be found.</p>
            <?php endif; ?>
        </div>
        
        <!-- Admin Login Status -->
        <div class="status-card <?php echo $admin_login_status ? 'success' : 'warning'; ?>">
            <div class="status-title">
                <div class="status-icon"></div>
                <h2>Admin Login System</h2>
            </div>
            <p><strong>Database Table:</strong> <?php echo $admin_login_status ? 'Available' : 'Not Available'; ?></p>
            <p class="status-message"><?php echo $admin_login_message; ?></p>
            <p><strong>Admin Login File:</strong> <?php echo $admin_login_file_exists ? 'Found' : 'Not Found'; ?></p>
            <?php if (!$admin_login_file_exists): ?>
                <p class="status-message">The admin login file could not be found.</p>
            <?php endif; ?>
        </div>
        
        <!-- Feedback System Status -->
        <div class="status-card <?php echo $feedback_status ? 'success' : 'warning'; ?>">
            <div class="status-title">
                <div class="status-icon"></div>
                <h2>Feedback System</h2>
            </div>
            <p><strong>Database Table:</strong> <?php echo $feedback_status ? 'Available' : 'Not Available'; ?></p>
            <p class="status-message"><?php echo $feedback_message; ?></p>
            <p><strong>Feedback File:</strong> <?php echo fileExists('feedback.html') ? 'Found' : 'Not Found'; ?></p>
        </div>
        
        <!-- Messages System Status -->
        <div class="status-card <?php echo $messages_status ? 'success' : 'warning'; ?>">
            <div class="status-title">
                <div class="status-icon"></div>
                <h2>Messages System</h2>
            </div>
            <p><strong>Database Table:</strong> <?php echo $messages_status ? 'Available' : 'Not Available'; ?></p>
            <p class="status-message"><?php echo $messages_message; ?></p>
            <p><strong>Messages File:</strong> <?php echo fileExists('messages.php') ? 'Found' : 'Not Found'; ?></p>
        </div>
        
        <!-- Test Connection Form -->
        <div class="status-card">
            <div class="status-title">
                <h2>Test Database Connection</h2>
            </div>
            <form method="post" action="">
                <p>Click the button below to test the database connection with your current settings:</p>
                <input type="submit" name="test_connection" value="Test Connection" style="background-color: #2563eb; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">
                
                <?php if (isset($_POST['test_connection'])): ?>
                <div style="margin-top: 15px; padding: 10px; background-color: <?php echo $db_status ? '#ecfdf5' : '#fef2f2'; ?>; border-radius: 5px;">
                    <p><strong>Test Result:</strong> <?php echo $db_status ? 'Connection Successful!' : 'Connection Failed!'; ?></p>
                    <p><?php echo $db_message; ?></p>
                </div>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Test Key Features -->
        <div class="status-card">
            <div class="status-title">
                <h2>Test Key Features</h2>
            </div>
            <p>Click on the links below to test specific features of your hospital system:</p>
            
            <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px;">
                <a href="patient_registration.html" target="_blank" style="background-color: #10b981; color: white; text-decoration: none; padding: 10px 15px; border-radius: 5px; display: inline-block;">
                    Test Patient Registration
                </a>
                
                <a href="hospital-admin-panel/admin/login.php" target="_blank" style="background-color: #2563eb; color: white; text-decoration: none; padding: 10px 15px; border-radius: 5px; display: inline-block;">
                    Test Admin Login
                </a>
                
                <a href="feedback.html" target="_blank" style="background-color: #8b5cf6; color: white; text-decoration: none; padding: 10px 15px; border-radius: 5px; display: inline-block;">
                    Test Feedback System
                </a>
                
                <a href="messages.php" target="_blank" style="background-color: #f59e0b; color: white; text-decoration: none; padding: 10px 15px; border-radius: 5px; display: inline-block;">
                    Test Messages System
                </a>
            </div>
            
            <div style="margin-top: 15px;">
                <p><strong>Note:</strong> Clicking these links will open the respective pages in a new tab. If the page loads successfully, it means the file exists and is accessible. However, you'll need to test the actual functionality by using the forms on those pages.</p>
            </div>
        </div>
        
        <!-- Instructions -->
        <div class="instructions">
            <h2>How to Use This Diagnostic Tool</h2>
            <ol>
                <li>Upload this file to your GoogieHost server in the same directory as your hospital system files.</li>
                <li>Access the file in your browser using: <code>https://your-domain.com/db_diagnostics.php</code></li>
                <li>Review the status of each component to identify any issues.</li>
                <li>Use the "Test Connection" button to verify your database connection in real-time.</li>
                <li>If you see any errors, use the error messages to troubleshoot the problem.</li>
            </ol>
            <h3>Common Issues and Solutions:</h3>
            <ul>
                <li><strong>Database Connection Failed:</strong> Verify your database credentials in the configuration files. On GoogieHost, make sure you're using the correct username, password, and database name.</li>
                <li><strong>Tables Not Found:</strong> Make sure you've imported the database schema correctly. You may need to run the setup scripts or import the SQL file.</li>
                <li><strong>Files Not Found:</strong> Check that all required files have been uploaded to the correct locations. Ensure file permissions are set correctly (usually 644 for files and 755 for directories).</li>
                <li><strong>Patient Registration Issues:</strong> If the patient registration system isn't working, check both the database table and the HTML form file.</li>
                <li><strong>Admin Login Issues:</strong> Verify the admin table exists and has the correct structure. Also check that the login.php file is in the correct location.</li>
            </ul>
            <h3>GoogieHost Specific Instructions:</h3>
            <ol>
                <li>Upload all your hospital system files to your GoogieHost account using FTP or the File Manager in cPanel.</li>
                <li>Create a MySQL database through your GoogieHost cPanel if you haven't already.</li>
                <li>Import your database schema using phpMyAdmin in cPanel.</li>
                <li>Update all database configuration files with your GoogieHost database credentials.</li>
                <li>Access this diagnostic file by navigating to <code>https://your-domain.com/db_diagnostics.php</code> in your browser.</li>
            </ol>
            <p><strong>Note:</strong> For security reasons, remove this diagnostic file from your server after troubleshooting.</p>
        </div>
    </div>
</body>
</html>