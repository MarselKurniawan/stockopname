<?php
session_start();

header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Get JSON data from php://input
$input = json_decode(file_get_contents('php://input'), true);

// Check CSRF token
$csrf_token = $input['csrf_token'] ?? '';
if (!verify_csrf_token($csrf_token)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']);
    exit;
}

// Database connection
$conn = db_connect();
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Ensure data was decoded successfully
if ($input === null) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
    exit;
}

// Retrieve data from JSON
$city_id = $input['city_id'] ?? null;
$store_id = $input['store_id'] ?? null;
$tanggal = $input['tanggal'] ?? null;
$products = $input['products'] ?? [];

// Validate data
if (empty($city_id) || empty($store_id) || empty($tanggal) || empty($products)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

function generateUniquePengirimanId($conn)
{
    do {
        $id_pengiriman = random_int(1000, 9999);
        $query = "SELECT COUNT(*) FROM pengiriman WHERE id_pengiriman = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception('Query preparation failed: ' . $conn->error);
        }

        $stmt->bind_param('i', $id_pengiriman);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

    } while ($count > 0);

    return $id_pengiriman;
}

// Start transaction
$conn->begin_transaction();

try {
    // Generate a unique ID pengiriman for the whole batch
    $id_pengiriman = generateUniquePengirimanId($conn);

    foreach ($products as $product) {
        $product_id = $product['product_id'] ?? null;
        $harga = $product['harga'] ?? null;
        $jumlah = $product['jumlah'] ?? null;

        // Validate product data
        if (empty($product_id) || empty($harga) || empty($jumlah)) {
            throw new Exception('Product data is incomplete');
        }

        // Insert each product into the pengiriman table
        $query = "INSERT INTO pengiriman (id_pengiriman, toko_id, produk_id, harga, tanggal, jumlah) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception('Query preparation failed: ' . $conn->error);
        }

        $stmt->bind_param('iiidsi', $id_pengiriman, $store_id, $product_id, $harga, $tanggal, $jumlah);

        if (!$stmt->execute()) {
            throw new Exception('Failed to execute query: ' . $stmt->error);
        }

        $stmt->close();
    }

    // Commit transaction
    $conn->commit();
    echo json_encode(['status' => 'success', 'message' => 'Pengiriman added successfully']);

} catch (Exception $e) {
    // Rollback transaction in case of error
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

// Close database connection
$conn->close();
?>