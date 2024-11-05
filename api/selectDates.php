<?php
header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

$conn = db_connect();

try {
    $stmt = $conn->prepare("SELECT DISTINCT tanggal FROM pabrik ORDER BY tanggal DESC");
    $stmt->execute();
    $result = $stmt->get_result();

    $dates = [];
    while ($row = $result->fetch_assoc()) {
        // Format the date to YYYY-MM-DD
        $row['tanggal'] = date('Y-m-d', strtotime($row['tanggal']));
        $dates[] = $row;
    }

    echo json_encode($dates);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch dates']);
}
?>
