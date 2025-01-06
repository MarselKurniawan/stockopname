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

// Pastikan data yang diterima valid
if (!$input) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid or missing data']);
    exit;
}

// Koneksi database
$conn = db_connect();
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Loop untuk proses setiap entry dalam array input
foreach ($input as $entry) {
    $id_pengiriman = $entry['id_pengiriman'] ?? null;
    $toko_id = $entry['toko_id'] ?? null;
    $produk_id = $entry['produk_id'] ?? null;
    $jumlah = $entry['jumlah'] ?? null;
    $total_retur_nominal = $entry['total_retur_nominal'] ?? null;

   
    // Siapkan query untuk menambahkan data ke tabel retur
    $queryInsertRetur = "INSERT INTO retur (id_pengiriman,toko_id, produk_id, jumlah_retur, total_retur_nominal) 
                         VALUES (?, ?, ?, ?, ?)";
    $stmtInsertRetur = $conn->prepare($queryInsertRetur);

    // Verifikasi keberhasilan persiapan query
    if (!$stmtInsertRetur) {
        echo json_encode(['status' => 'error', 'message' => 'Query preparation failed: ' . $conn->error]);
        exit;
    }

    // Bind parameter dan eksekusi query
    $stmtInsertRetur->bind_param('iiiid', $id_pengiriman, $toko_id, $produk_id, $jumlah, $total_retur_nominal);

    if (!$stmtInsertRetur->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add retur: ' . $stmtInsertRetur->error]);
        exit;
    }
}

// Menutup statement dan koneksi
$stmtInsertRetur->close();
$conn->close();

echo json_encode(['status' => 'success', 'message' => 'Retur added successfully']);
?>