<?php
header('Content-Type: application/json');
include '../../db/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'] ?? null;
    
    if ($patient_id) {
        try {
            $stmt = $conn->prepare("DELETE FROM patient_data2 WHERE id = ?");
            $stmt->bind_param("i", $patient_id);
            
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode(['success' => true, 'message' => 'Patient record deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No record found with this ID']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
            }
            
            $stmt->close();
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Patient ID is required']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>