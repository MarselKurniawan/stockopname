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

// Ambil id_pengiriman dari input
$id_pengiriman = $input['id_pengiriman'] ?? null;
$laku = $input['laku'] ?? null;
$tanggal = $input['tanggal'] ?? null;

// Validasi data
if (empty($id_pengiriman) || empty($laku) || empty($tanggal)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

// Ambil data dari tabel pengiriman berdasarkan id_pengiriman
$query = "SELECT toko_id, produk_id, harga, jumlah FROM pengiriman WHERE id_pengiriman = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id_pengiriman);
$stmt->execute();
$result = $stmt->get_result();

// Cek apakah data ditemukan
if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Pengiriman not found']);
    exit;
}

$dataPengiriman = $result->fetch_assoc();
$toko_id = $dataPengiriman['toko_id'];
$produk_id = $dataPengiriman['produk_id'];
$harga = $dataPengiriman['harga'];
$stok = $dataPengiriman['jumlah']; // Jumlah stok sebelum dikurangi

// Hitung laku_nominal dan sisa
$laku_nominal = $harga * $laku;
$sisa = $stok - $laku; // Jumlah yang tersisa setelah dikurangi laku

// Siapkan query untuk menambahkan data ke tabel stock
$queryInsert = "INSERT INTO stock (toko_id, produk_id, harga, stok, laku, laku_nominal, sisa, tanggal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmtInsert = $conn->prepare($queryInsert);

if (!$stmtInsert) {
    echo json_encode(['status' => 'error', 'message' => 'Query preparation failed: ' . $conn->error]);
    exit;
}

// Bind parameter dan eksekusi query
$stmtInsert->bind_param('iisdiids', $toko_id, $produk_id, $harga, $stok, $laku, $laku_nominal, $sisa, $tanggal);

if ($stmtInsert->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Stock added successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add stock: ' . $stmtInsert->error]);
}

// Menutup statement dan koneksi
$stmt->close();
$stmtInsert->close();
$conn->close();
?>
