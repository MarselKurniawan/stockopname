<?php
header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';


if (isset($_GET['city_id'])) {
    $city_id = $_GET['city_id'];
    $conn = db_connect(); // Connect to the database

    try {
        $stmt = $conn->prepare("SELECT id, nama_toko FROM toko WHERE kota_id = ?");
        $stmt->bind_param("i", $city_id); // Bind city_id as an integer
        $stmt->execute();
        $result = $stmt->get_result(); // Get the result set

        $stores = [];
        while ($row = $result->fetch_assoc()) {
            $stores[] = $row; // Append each row to the stores array
        }

        echo json_encode($stores);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Failed to fetch stores']);
    }
} else {
    echo json_encode(['error' => 'City ID not provided']);
}
?>
