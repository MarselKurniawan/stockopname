<?php
session_start(); // Pastikan sesi dimulai

header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

// Periksa metode permintaan
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Ambil data JSON dari php://input
$input = json_decode(file_get_contents('php://input'), true);

// Periksa CSRF token
$csrf_token = $input['csrf_token'] ?? '';
if (!verify_csrf_token($csrf_token)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']);
    exit;
}

// Koneksi database
$conn = db_connect();
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Pastikan data berhasil didekode
if ($input === null) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
    exit;
}

// Ambil data dari JSON
$city_id = $input['city_id'] ?? null;
$store_id = $input['store_id'] ?? null;
$product_id = $input['product_id'] ?? null;
$harga = $input['harga'] ?? null;
$tanggal = $input['tanggal'] ?? null;
$jumlah = $input['jumlah'] ?? null;

// Validasi data
if (empty($city_id) || empty($store_id) || empty($product_id) || empty($harga) || empty($tanggal) || empty($jumlah)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

// Siapkan query untuk menambahkan data jumlah baru
$query = "INSERT INTO pengiriman (toko_id, produk_id, harga, tanggal, jumlah ) VALUES ( ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Query preparation failed: ' . $conn->error]);
    exit;
}

// Bind parameter dan eksekusi query
$stmt->bind_param('iissi', $store_id, $product_id, $harga, $tanggal, $jumlah);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Stock added successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add stock: ' . $stmt->error]);
}

// Menutup statement dan koneksi
$stmt->close();
$conn->close();
?>
