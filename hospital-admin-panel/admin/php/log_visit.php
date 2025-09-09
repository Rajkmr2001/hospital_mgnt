<?php
include __DIR__ . '/../../db/config.php';

// Function to log a user visit
function log_user_visit(mysqli $conn) {
    // Do not log visits from localhost if desired
    // if ($_SERVER['REMOTE_ADDR'] === '::1' || $_SERVER['REMOTE_ADDR'] === '127.0.0.1') {
    //     return;
    // }

    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $page_url = $_SERVER['REQUEST_URI'];

    $stmt = $conn->prepare("INSERT INTO visitor_logs (ip_address, user_agent, page_url) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ip_address, $user_agent, $page_url);
    $stmt->execute();
    $stmt->close();
}

log_user_visit($conn);
?>
