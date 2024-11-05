<?php
header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

$conn = db_connect(); // Connect to the database

try {
    $stmt = $conn->prepare("SELECT id, nama FROM customer WHERE level = 'supplier'");
    $stmt->execute();
    $result = $stmt->get_result(); // Get the result set

    $supplier = [];
    while ($row = $result->fetch_assoc()) {
        $supplier[] = $row; // Append each row to the supplier array
    }

    echo json_encode($supplier);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch supplier']);
}
?>