<?php
session_start(); // Pastikan sesi dimulai

header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

// Koneksi database
$conn = db_connect();
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Query untuk mengambil data stok
$stmt = $conn->prepare("SELECT * FROM produk;");
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
$stok = [];
while ($row = $result->fetch_assoc()) {
    $stok[] = $row;
}

// Mengembalikan hasil dalam format JSON
if (!empty($stok)) {
    echo json_encode(['status' => 'success', 'data' => $stok]);
} else {
    echo json_encode(['status' => 'success', 'data' => [], 'message' => 'No stock found.']);
}

// Menutup statement dan koneksi
$stmt->close();
$conn->close();
    