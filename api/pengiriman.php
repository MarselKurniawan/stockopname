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

// Query untuk mengambil data pengiriman
$stmt = $conn->prepare("SELECT pengiriman.*, produk.nama_produk, produk.ukuran_stoples, produk.ukuran_mika, produk.ukuran_paket, produk.kemasan, toko.nama_toko, kota.nama_kota 
    FROM pengiriman 
    JOIN produk ON pengiriman.produk_id = produk.id 
    JOIN toko ON pengiriman.toko_id = toko.id 
    JOIN kota ON toko.kota_id = kota.id  ORDER BY status != 'done' DESC;");
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
$pengiriman = [];
while ($row = $result->fetch_assoc()) {
    // Tentukan display_produk
    $packaging = $row['ukuran_stoples'] ?: ($row['ukuran_mika'] ?: $row['ukuran_paket']);
    $display_produk = trim("{$row['nama_produk']} {$row['kemasan']} {$packaging}");

    // Tambahkan ke data pengiriman
    $pengiriman[] = array_merge($row, ['display_produk' => $display_produk]);
}

// Mengembalikan hasil dalam format JSON
if (!empty($pengiriman)) {
    echo json_encode(['status' => 'success', 'data' => $pengiriman]);
} else {
    echo json_encode(['status' => 'success', 'data' => [], 'message' => 'No stock found.']);
}

// Menutup statement dan koneksi
$stmt->close();
$conn->close();
