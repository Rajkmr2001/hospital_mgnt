<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = "localhost";
$db = "hospit27_hospital_db";
$user = "hospit27_rajskmr";
$password = "Rajneha7070";
$port = 3306;

// Function to check if a table exists
function tableExists($conn, $tableName) {
    $result = $conn->query("SHOW TABLES LIKE '$tableName'");
    return $result && $result->num_rows > 0;
}

// Function to check table structure
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

// Start output buffering to capture all output
ob_start();

// Test database connection
echo "<h2>Testing Database Connection</h2>";
echo "<p>Attempting to connect to database: $db as user: $user on host: $host</p>";

try {
    $conn = new mysqli($host, $user, $password, $db, $port);
    
    if ($conn->connect_error) {
        echo "<p style='color:red'>Connection failed: " . $conn->connect_error . "</p>";
    } else {
        echo "<p style='color:green'>Database connection successful!</p>";
        
        // Test patient_register table
        echo "<h2>Testing Patient Registration Table</h2>";
        if (tableExists($conn, "patient_register")) {
            echo "<p style='color:green'>patient_register table exists</p>";
            
            $requiredColumns = ["mobile_no", "name", "gender", "password", "register_date", "register_time"];
            list($structureValid, $structureMessage) = checkTableStructure($conn, "patient_register", $requiredColumns);
            
            if ($structureValid) {
                echo "<p style='color:green'>Table structure is valid</p>";
            } else {
                echo "<p style='color:red'>Table structure issue: $structureMessage</p>";
            }
            
            // Count records
            $result = $conn->query("SELECT COUNT(*) as count FROM patient_register");
            if ($result) {
                $row = $result->fetch_assoc();
                echo "<p>Total records: " . $row['count'] . "</p>";
            }
        } else {
            echo "<p style='color:red'>patient_register table does not exist!</p>";
        }
        
        // Test feedback table
        echo "<h2>Testing Feedback Table</h2>";
        if (tableExists($conn, "feedback")) {
            echo "<p style='color:green'>feedback table exists</p>";
            
            $requiredColumns = ["id", "name", "number", "comment", "likes"];
            list($structureValid, $structureMessage) = checkTableStructure($conn, "feedback", $requiredColumns);
            
            if ($structureValid) {
                echo "<p style='color:green'>Table structure is valid</p>";
            } else {
                echo "<p style='color:red'>Table structure issue: $structureMessage</p>";
            }
            
            // Count records
            $result = $conn->query("SELECT COUNT(*) as count FROM feedback");
            if ($result) {
                $row = $result->fetch_assoc();
                echo "<p>Total records: " . $row['count'] . "</p>";
            }
        } else {
            echo "<p style='color:red'>feedback table does not exist!</p>";
        }
        
        // Test admins table
        echo "<h2>Testing Admins Table</h2>";
        if (tableExists($conn, "admins")) {
            echo "<p style='color:green'>admins table exists</p>";
            
            $requiredColumns = ["id", "mobile_number", "password"];
            list($structureValid, $structureMessage) = checkTableStructure($conn, "admins", $requiredColumns);
            
            if ($structureValid) {
                echo "<p style='color:green'>Table structure is valid</p>";
            } else {
                echo "<p style='color:red'>Table structure issue: $structureMessage</p>";
            }
            
            // Count records
            $result = $conn->query("SELECT COUNT(*) as count FROM admins");
            if ($result) {
                $row = $result->fetch_assoc();
                echo "<p>Total records: " . $row['count'] . "</p>";
            }
        } else {
            echo "<p style='color:red'>admins table does not exist!</p>";
        }
        
        // Test messages table
        echo "<h2>Testing Messages Table</h2>";
        if (tableExists($conn, "messages")) {
            echo "<p style='color:green'>messages table exists</p>";
            
            // Count records
            $result = $conn->query("SELECT COUNT(*) as count FROM messages");
            if ($result) {
                $row = $result->fetch_assoc();
                echo "<p>Total records: " . $row['count'] . "</p>";
            }
        } else {
            echo "<p style='color:red'>messages table does not exist!</p>";
        }
        
        // Close connection
        $conn->close();
    }
} catch (Exception $e) {
    echo "<p style='color:red'>Connection error: " . $e->getMessage() . "</p>";
}

// Get server information
echo "<h2>Server Information</h2>";
echo "<ul>";
echo "<li>PHP Version: " . phpversion() . "</li>";
echo "<li>Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</li>";
echo "<li>Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</li>";
echo "<li>Server Name: " . ($_SERVER['SERVER_NAME'] ?? 'Unknown') . "</li>";
echo "<li>HTTP Host: " . ($_SERVER['HTTP_HOST'] ?? 'Unknown') . "</li>";
echo "<li>Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Unknown') . "</li>";
echo "</ul>";

// Check file permissions
echo "<h2>File Permissions</h2>";
$files_to_check = [
    'register.php',
    'db/config.php',
    'patient_registration.html',
    'feedback.html',
    'db/submit_feedback.php'
];

echo "<ul>";
foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        $perms = fileperms($file);
        $perms_octal = substr(sprintf('%o', $perms), -4);
        echo "<li>$file: Exists, Permissions: $perms_octal</li>";
    } else {
        echo "<li style='color:red'>$file: Does not exist</li>";
    }
}
echo "</ul>";

// Get the buffered content
$content = ob_get_clean();

// Output HTML page with styling
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoogieHost Database Test</title>
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
        h1, h2 {
            color: #2563eb;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        h2 {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
            margin-top: 30px;
        }
        p {
            margin: 10px 0;
        }
        ul {
            padding-left: 20px;
        }
        li {
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9em;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>GoogieHost Database Test</h1>
        <?php echo $content; ?>
        <div class="footer">
            <p>This test script helps diagnose issues with your hospital management system on GoogieHost.</p>
            <p><strong>Note:</strong> For security reasons, remove this file after troubleshooting.</p>
        </div>
    </div>
</body>
</html>