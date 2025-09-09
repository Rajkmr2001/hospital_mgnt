<?php
include __DIR__ . '/../../db/config.php';
header('Content-Type: application/json');

// Helper function to get excluded IPs
function getExcludedIps(mysqli $conn): array {
    $excludeIps = [];
    $sql = "SELECT ip_address FROM analytics_ip_exclusions";
    if ($res = $conn->query($sql)) {
        while ($row = $res->fetch_assoc()) {
            $excludeIps[] = $conn->real_escape_string($row['ip_address']);
        }
        $res->close();
    }
    return $excludeIps;
}

$excludeIps = getExcludedIps($conn);
$excludeCondition = '';
if (!empty($excludeIps)) {
    $excludeCondition = " AND ip_address NOT IN ('" . implode("','", $excludeIps) . "')";
}

$date = $_GET['date'] ?? date('Y-m-d');
$selectedDate = $conn->real_escape_string($date);

// --- Main Stats for the Selected Date ---
// Total visits for the selected date
$sql_total = "SELECT COUNT(*) as count FROM visitor_logs WHERE DATE(visit_timestamp) = '{$selectedDate}'" . $excludeCondition;
$total_visits = $conn->query($sql_total)->fetch_assoc()['count'] ?? 0;

// Unique visits for the selected date
$sql_unique = "SELECT COUNT(DISTINCT ip_address) as count FROM visitor_logs WHERE DATE(visit_timestamp) = '{$selectedDate}'" . $excludeCondition;
$unique_visits = $conn->query($sql_unique)->fetch_assoc()['count'] ?? 0;


// --- Trend Data (Last 30 Days) ---
$trend_data = [];
$sql_trend = "SELECT DATE(visit_timestamp) as day, COUNT(DISTINCT ip_address) as count 
              FROM visitor_logs 
              WHERE visit_timestamp >= DATE_SUB('{$selectedDate}', INTERVAL 30 DAY) AND visit_timestamp <= '{$selectedDate}'" . $excludeCondition . "
              GROUP BY day ORDER BY day ASC";

if ($res = $conn->query($sql_trend)) {
    while ($row = $res->fetch_assoc()) {
        $trend_data[] = $row;
    }
    $res->close();
}

// --- Visitor Log (Last 100 Unique IPs) ---
$visitor_log = [];
$sql_log = "SELECT ip_address, MIN(visit_timestamp) as first_visit, MAX(visit_timestamp) as last_visit, COUNT(*) as total_visits
            FROM visitor_logs 
            WHERE 1=1" . $excludeCondition . "
            GROUP BY ip_address 
            ORDER BY last_visit DESC 
            LIMIT 100";

if ($res = $conn->query($sql_log)) {
    while ($row = $res->fetch_assoc()) {
        $visitor_log[] = $row;
    }
    $res->close();
}


echo json_encode([
    'selected_date' => $date,
    'stats' => [
        'total_visits' => (int)$total_visits,
        'unique_visits' => (int)$unique_visits,
    ],
    'trend' => $trend_data,
    'log' => $visitor_log
]);

$conn->close();
?>
