<?php
header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

$conn = db_connect(); // Connect to the database

try {
    $stmt = $conn->prepare("SELECT id, nama_kota FROM kota");
    $stmt->execute();
    $result = $stmt->get_result(); // Get the result set

    $cities = [];
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row; // Append each row to the cities array
    }

    echo json_encode($cities);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch cities']);
}
?>