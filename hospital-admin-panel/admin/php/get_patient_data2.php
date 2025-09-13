<?php
header('Content-Type: application/json');
include '../../db/config.php';

try {
    $sql = "SELECT * FROM patient_data2 ORDER BY submission_date DESC, submission_time DESC";
    $result = $conn->query($sql);
    
    $patients = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $patients[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'age' => $row['age'],
                'gender' => $row['gender'],
                'contact' => $row['contact'],
                'submission_time' => $row['submission_time'],
                'submission_date' => $row['submission_date']
            ];
        }
    }
    
    echo json_encode($patients);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

$conn->close();
?>