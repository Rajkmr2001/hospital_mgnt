<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db/config.php'; // adjust path as needed
header('Content-Type: application/json');

// Log access to this file
file_put_contents('../feedback_log.txt', date('Y-m-d H:i:s') . " - Feedback submission attempt\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $number = $_POST['number'] ?? '';
    $comment = $_POST['comment'] ?? '';
    $likes = 0;

    if ($name && $number && $comment) {
        file_put_contents('../feedback_log.txt', date('Y-m-d H:i:s') . " - Preparing to insert feedback from: {$name}\n", FILE_APPEND);
        
        // Check if table exists
        $table_check = $conn->query("SHOW TABLES LIKE 'feedback'");
        if ($table_check->num_rows == 0) {
            $error_msg = "Feedback table does not exist";
            file_put_contents('../feedback_log.txt', date('Y-m-d H:i:s') . " - Error: {$error_msg}\n", FILE_APPEND);
            echo json_encode(['success' => false, 'message' => $error_msg]);
            exit;
        }
        
        $stmt = $conn->prepare("INSERT INTO feedback (name, number, comment, likes) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            $error_msg = "Prepare failed: " . $conn->error;
            file_put_contents('../feedback_log.txt', date('Y-m-d H:i:s') . " - Error: {$error_msg}\n", FILE_APPEND);
            echo json_encode(['success' => false, 'message' => $error_msg]);
            exit;
        }
        
        $stmt->bind_param("sssi", $name, $number, $comment, $likes);
        if ($stmt->execute()) {
            file_put_contents('../feedback_log.txt', date('Y-m-d H:i:s') . " - Feedback successfully inserted\n", FILE_APPEND);
            echo json_encode(['success' => true]);
        } else {
            $error_msg = "Execute failed: " . $stmt->error;
            file_put_contents('../feedback_log.txt', date('Y-m-d H:i:s') . " - Error: {$error_msg}\n", FILE_APPEND);
            echo json_encode(['success' => false, 'message' => $error_msg]);
        }
        $stmt->close();
    } else {
        $error_msg = "Missing fields: " . 
                     (empty($name) ? "name " : "") . 
                     (empty($number) ? "number " : "") . 
                     (empty($comment) ? "comment" : "");
        file_put_contents('../feedback_log.txt', date('Y-m-d H:i:s') . " - Error: {$error_msg}\n", FILE_APPEND);
        echo json_encode(['success' => false, 'message' => 'Missing fields: ' . $error_msg]);
    }
    file_put_contents('../feedback_log.txt', date('Y-m-d H:i:s') . " - Connection closed\n", FILE_APPEND);
    $conn->close();
} else {
    file_put_contents('../feedback_log.txt', date('Y-m-d H:i:s') . " - Invalid request method: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
