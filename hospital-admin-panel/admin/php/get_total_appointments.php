<?php
header('Content-Type: application/json');
include '../../db/config.php';

try {
    $sql = "SELECT COUNT(*) as total FROM patient_data2";
    $result = $conn->query($sql);
    
    if ($result) {
        $row = $result->fetch_assoc();
        echo json_encode(['total' => (int)$row['total']]);
    } else {
        echo json_encode(['total' => 0]);
    }
} catch (Exception $e) {
    echo json_encode(['total' => 0]);
}

$conn->close();
?>