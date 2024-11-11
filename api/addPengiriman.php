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
$product_id = $input['product_id'] ?? null;
$harga = $input['harga'] ?? null;
$tanggal = $input['tanggal'] ?? null;
$jumlah = $input['jumlah'] ?? null;

// Validate data
if (empty($city_id) || empty($store_id) || empty($product_id) || empty($harga) || empty($tanggal) || empty($jumlah)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

// Function to generate a unique 4-digit id_pengiriman
function generateUniquePengirimanId($conn) {
    do {
        $id_pengiriman = random_int(1000, 9999);
        $query = "SELECT COUNT(*) FROM pengiriman WHERE id_pengiriman = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id_pengiriman);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    } while ($count > 0);
    return $id_pengiriman;
}

// Function to insert data into pengiriman table
function insertPengirimanData($conn, $store_id, $product_id, $harga, $tanggal, $jumlah) {
    $id_pengiriman = generateUniquePengirimanId($conn); // Generate unique ID

    $query = "INSERT INTO pengiriman (id_pengiriman, toko_id, produk_id, harga, tanggal, jumlah) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        return ['status' => 'error', 'message' => 'Query preparation failed: ' . $conn->error];
    }

    $stmt->bind_param('iiissi', $id_pengiriman, $store_id, $product_id, $harga, $tanggal, $jumlah);

    if ($stmt->execute()) {
        $stmt->close();
        return ['status' => 'success', 'message' => 'Stock added successfully'];
    } else {
        $stmt->close();
        return ['status' => 'error', 'message' => 'Failed to add stock: ' . $stmt->error];
    }
}

// Insert data into the pengiriman table and return response
$response = insertPengirimanData($conn, $store_id, $product_id, $harga, $tanggal, $jumlah);
echo json_encode($response);

// Close database connection
$conn->close();
?>
