<?php
header('Content-Type: application/json');
include '../../db/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'] ?? null;
    $name = $_POST['name'] ?? '';
    $age = $_POST['age'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $contact = $_POST['contact'] ?? '';
    
    if ($patient_id && !empty($name) && !empty($age) && !empty($gender) && !empty($contact)) {
        try {
            $stmt = $conn->prepare("UPDATE patient_data2 SET name = ?, age = ?, gender = ?, contact = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $age, $gender, $contact, $patient_id);
            
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode(['success' => true, 'message' => 'Patient record updated successfully']);
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
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>