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

// Start output buffering
ob_start();

// Function to check if a table exists
function tableExists($conn, $tableName) {
    $result = $conn->query("SHOW TABLES LIKE '$tableName'");
    return $result && $result->num_rows > 0;
}

// Connect to database
echo "<h2>Connecting to Database</h2>";
try {
    $conn = new mysqli($host, $user, $password, $db, $port);
    
    if ($conn->connect_error) {
        echo "<p style='color:red'>Connection failed: " . $conn->connect_error . "</p>";
        exit;
    }
    
    echo "<p style='color:green'>Database connection successful!</p>";
    
    // Fix patient_register table if needed
    echo "<h2>Checking/Fixing patient_register Table</h2>";
    if (!tableExists($conn, "patient_register")) {
        echo "<p>patient_register table does not exist. Creating it...</p>";
        
        $sql = "CREATE TABLE patient_register (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            mobile_no VARCHAR(15) NOT NULL UNIQUE,
            name VARCHAR(100) NOT NULL,
            gender VARCHAR(10) NOT NULL,
            password VARCHAR(255) NOT NULL,
            register_date DATE,
            register_time VARCHAR(20)
        )";
        
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green'>patient_register table created successfully!</p>";
        } else {
            echo "<p style='color:red'>Error creating patient_register table: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:green'>patient_register table already exists.</p>";
        
        // Check if columns exist and add them if they don't
        $columns = ["mobile_no", "name", "gender", "password", "register_date", "register_time"];
        $result = $conn->query("DESCRIBE patient_register");
        $existing_columns = [];
        
        while ($row = $result->fetch_assoc()) {
            $existing_columns[] = $row['Field'];
        }
        
        foreach ($columns as $column) {
            if (!in_array($column, $existing_columns)) {
                echo "<p>Adding missing column: $column</p>";
                
                $sql = "";
                switch ($column) {
                    case "mobile_no":
                        $sql = "ALTER TABLE patient_register ADD COLUMN mobile_no VARCHAR(15) NOT NULL";
                        break;
                    case "name":
                        $sql = "ALTER TABLE patient_register ADD COLUMN name VARCHAR(100) NOT NULL";
                        break;
                    case "gender":
                        $sql = "ALTER TABLE patient_register ADD COLUMN gender VARCHAR(10) NOT NULL";
                        break;
                    case "password":
                        $sql = "ALTER TABLE patient_register ADD COLUMN password VARCHAR(255) NOT NULL";
                        break;
                    case "register_date":
                        $sql = "ALTER TABLE patient_register ADD COLUMN register_date DATE";
                        break;
                    case "register_time":
                        $sql = "ALTER TABLE patient_register ADD COLUMN register_time VARCHAR(20)";
                        break;
                }
                
                if ($sql && $conn->query($sql) === TRUE) {
                    echo "<p style='color:green'>Column $column added successfully!</p>";
                } else {
                    echo "<p style='color:red'>Error adding column $column: " . $conn->error . "</p>";
                }
            }
        }
    }
    
    // Fix feedback table if needed
    echo "<h2>Checking/Fixing feedback Table</h2>";
    if (!tableExists($conn, "feedback")) {
        echo "<p>feedback table does not exist. Creating it...</p>";
        
        $sql = "CREATE TABLE feedback (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            number VARCHAR(15) NOT NULL,
            comment TEXT NOT NULL,
            likes INT(11) DEFAULT 0,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green'>feedback table created successfully!</p>";
        } else {
            echo "<p style='color:red'>Error creating feedback table: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:green'>feedback table already exists.</p>";
        
        // Check if columns exist and add them if they don't
        $columns = ["id", "name", "number", "comment", "likes", "timestamp"];
        $result = $conn->query("DESCRIBE feedback");
        $existing_columns = [];
        
        while ($row = $result->fetch_assoc()) {
            $existing_columns[] = $row['Field'];
        }
        
        foreach ($columns as $column) {
            if (!in_array($column, $existing_columns)) {
                echo "<p>Adding missing column: $column</p>";
                
                $sql = "";
                switch ($column) {
                    case "id":
                        $sql = "ALTER TABLE feedback ADD COLUMN id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY";
                        break;
                    case "name":
                        $sql = "ALTER TABLE feedback ADD COLUMN name VARCHAR(100) NOT NULL";
                        break;
                    case "number":
                        $sql = "ALTER TABLE feedback ADD COLUMN number VARCHAR(15) NOT NULL";
                        break;
                    case "comment":
                        $sql = "ALTER TABLE feedback ADD COLUMN comment TEXT NOT NULL";
                        break;
                    case "likes":
                        $sql = "ALTER TABLE feedback ADD COLUMN likes INT(11) DEFAULT 0";
                        break;
                    case "timestamp":
                        $sql = "ALTER TABLE feedback ADD COLUMN timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
                        break;
                }
                
                if ($sql && $conn->query($sql) === TRUE) {
                    echo "<p style='color:green'>Column $column added successfully!</p>";
                } else {
                    echo "<p style='color:red'>Error adding column $column: " . $conn->error . "</p>";
                }
            }
        }
    }
    
    // Fix admins table if needed
    echo "<h2>Checking/Fixing admins Table</h2>";
    if (!tableExists($conn, "admins")) {
        echo "<p>admins table does not exist. Creating it...</p>";
        
        $sql = "CREATE TABLE admins (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            mobile_number VARCHAR(15) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        )";
        
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green'>admins table created successfully!</p>";
            
            // Add default admin user
            $default_mobile = "9876543210";
            $default_password = password_hash("Admin@123", PASSWORD_BCRYPT);
            
            $sql = "INSERT INTO admins (mobile_number, password) VALUES ('$default_mobile', '$default_password')";
            
            if ($conn->query($sql) === TRUE) {
                echo "<p style='color:green'>Default admin user created successfully!</p>";
                echo "<p>Mobile: 9876543210</p>";
                echo "<p>Password: Admin@123</p>";
            } else {
                echo "<p style='color:red'>Error creating default admin user: " . $conn->error . "</p>";
            }
        } else {
            echo "<p style='color:red'>Error creating admins table: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:green'>admins table already exists.</p>";
        
        // Check if columns exist and add them if they don't
        $columns = ["id", "mobile_number", "password"];
        $result = $conn->query("DESCRIBE admins");
        $existing_columns = [];
        
        while ($row = $result->fetch_assoc()) {
            $existing_columns[] = $row['Field'];
        }
        
        foreach ($columns as $column) {
            if (!in_array($column, $existing_columns)) {
                echo "<p>Adding missing column: $column</p>";
                
                $sql = "";
                switch ($column) {
                    case "id":
                        $sql = "ALTER TABLE admins ADD COLUMN id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY";
                        break;
                    case "mobile_number":
                        $sql = "ALTER TABLE admins ADD COLUMN mobile_number VARCHAR(15) NOT NULL UNIQUE";
                        break;
                    case "password":
                        $sql = "ALTER TABLE admins ADD COLUMN password VARCHAR(255) NOT NULL";
                        break;
                }
                
                if ($sql && $conn->query($sql) === TRUE) {
                    echo "<p style='color:green'>Column $column added successfully!</p>";
                } else {
                    echo "<p style='color:red'>Error adding column $column: " . $conn->error . "</p>";
                }
            }
        }
        
        // Check if there are any admin users, if not add a default one
        $result = $conn->query("SELECT COUNT(*) as count FROM admins");
        $row = $result->fetch_assoc();
        
        if ($row['count'] == 0) {
            echo "<p>No admin users found. Adding default admin user...</p>";
            
            $default_mobile = "9876543210";
            $default_password = password_hash("Admin@123", PASSWORD_BCRYPT);
            
            $sql = "INSERT INTO admins (mobile_number, password) VALUES ('$default_mobile', '$default_password')";
            
            if ($conn->query($sql) === TRUE) {
                echo "<p style='color:green'>Default admin user created successfully!</p>";
                echo "<p>Mobile: 9876543210</p>";
                echo "<p>Password: Admin@123</p>";
            } else {
                echo "<p style='color:red'>Error creating default admin user: " . $conn->error . "</p>";
            }
        } else {
            echo "<p style='color:green'>Admin users already exist.</p>";
        }
    }
    
    // Fix messages table if needed
    echo "<h2>Checking/Fixing messages Table</h2>";
    if (!tableExists($conn, "messages")) {
        echo "<p>messages table does not exist. Creating it...</p>";
        
        $sql = "CREATE TABLE messages (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            subject VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green'>messages table created successfully!</p>";
        } else {
            echo "<p style='color:red'>Error creating messages table: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:green'>messages table already exists.</p>";
    }
    
    // Close connection
    $conn->close();
    echo "<p>Database connection closed.</p>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}

// Get the buffered content
$content = ob_get_clean();

// Output HTML page with styling
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Database Tables</title>
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
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9em;
            color: #6b7280;
        }
        .actions {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .button.secondary {
            background-color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Fix Database Tables</h1>
        <?php echo $content; ?>
        <div class="actions">
            <a href="db_diagnostics.php" class="button">Run Diagnostics</a>
            <a href="googiehost_test.php" class="button secondary">Run Tests</a>
        </div>
        <div class="footer">
            <p>This script helps fix database tables for your hospital management system on GoogieHost.</p>
            <p><strong>Note:</strong> For security reasons, remove this file after troubleshooting.</p>
        </div>
    </div>
</body>
</html>