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
$harga_beli = $input['harga_beli'] ?? null;
$harga_jual = $input['harga_jual'] ?? null; // Periksa penamaan yang konsisten
$tanggal_masuk = $input['tanggal_masuk'] ?? null;
$stok = $input['stok'] ?? null;
$laku = $input['laku'] ?? null;

// Validasi data
if (empty($city_id) || empty($store_id) || empty($product_id) || empty($harga_beli) || empty($harga_jual) || empty($tanggal_masuk) || empty($stok) || empty($laku)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

// Hitung laku_nominal
$laku_nominal = $harga_jual * $laku;

// Siapkan query untuk menambahkan data stok baru
$query = "INSERT INTO stok (toko_id, produk_id, harga_beli, harga_jual, tanggal_masuk, jumlah_stok, laku, laku_nominal) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Query preparation failed: ' . $conn->error]);
    exit;
}

// Bind parameter dan eksekusi query
$stmt->bind_param('iiddsiid', $store_id, $product_id, $harga_beli, $harga_jual, $tanggal_masuk, $stok, $laku, $laku_nominal);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Stock added successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add stock: ' . $stmt->error]);
}

// Menutup statement dan koneksi
$stmt->close();
$conn->close();
?>
