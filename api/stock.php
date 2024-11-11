<?php
session_start(); // Pastikan sesi dimulai

header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

// Periksa CSRF token
if (!isset($_GET['csrf_token']) || !verify_csrf_token($_GET['csrf_token'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']);
    exit;
}

// Koneksi database
$conn = db_connect();
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Query untuk mengambil data stock
$stmt = $conn->prepare("SELECT stock.*, produk.nama_produk, toko.nama_toko, kota.nama_kota 
    FROM stock 
    JOIN produk ON stock.produk_id = produk.id 
    JOIN toko ON stock.toko_id = toko.id 
    JOIN kota ON toko.kota_id = kota.id;");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Query preparation failed: ' . $conn->error]);
    exit;
}

// Eksekusi query
if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Query execution failed: ' . $stmt->error]);
    exit;
}

// Mendapatkan hasil
$result = $stmt->get_result();
if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Fetching result failed: ' . $stmt->error]);
    exit;
}

// Memproses hasil
$stock = [];
while ($row = $result->fetch_assoc()) {
    $stock[] = $row;
}

// Mengembalikan hasil dalam format JSON
if (!empty($stock)) {
    echo json_encode(['status' => 'success', 'data' => $stock]);
} else {
    echo json_encode(['status' => 'success', 'data' => [], 'message' => 'No stock found.']);
}

// Menutup statement dan koneksi
$stmt->close();
$conn->close();
    