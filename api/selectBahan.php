<?php
header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

$conn = db_connect(); // Connect to the database

try {
    $stmt = $conn->prepare("SELECT id_bahan, nama_bahan FROM bahan");
    $stmt->execute();
    $result = $stmt->get_result(); // Get the result set

    $bahan = [];
    while ($row = $result->fetch_assoc()) {
        $bahan[] = $row; // Append each row to the bahan array
    }

    echo json_encode($bahan);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch bahan']);
}
?>