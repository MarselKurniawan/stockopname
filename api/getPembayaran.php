<?php
session_start(); // Pastikan sesi dimulai

header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

// Periksa CSRF token (jika diperlukan)
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

// Query untuk mengambil data pengiriman dan retur
$stmt = $conn->prepare("
    SELECT 
        pengiriman.id AS id_pengiriman,
        pengiriman.tanggal AS tanggal_pengiriman,
        pengiriman.status AS status_pengiriman,
        produk.nama_produk AS produk_pengiriman,
        toko.id AS toko_id,
        toko.nama_toko,
        kota.nama_kota,
        retur.id AS id_retur,
        retur.jumlah_retur,
        retur.total_retur_nominal,
        retur.tanggal_retur
    FROM pengiriman
    JOIN produk ON pengiriman.produk_id = produk.id
    JOIN toko ON pengiriman.toko_id = toko.id
    JOIN kota ON toko.kota_id = kota.id
    LEFT JOIN retur 
        ON retur.id_pengiriman = pengiriman.id_pengiriman 
        AND retur.produk_id = pengiriman.produk_id
    ORDER BY pengiriman.tanggal, toko.id;
");

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
$data_pengiriman = [];
while ($row = $result->fetch_assoc()) {
    $group_key = $row['toko_id'] . '-' . $row['tanggal_pengiriman'];

    // Jika grup belum ada, buat grup baru
    if (!isset($data_pengiriman[$group_key])) {
        $data_pengiriman[$group_key] = [
            'toko_id' => $row['toko_id'],
            'nama_toko' => $row['nama_toko'],
            'nama_kota' => $row['nama_kota'],
            'tanggal_pengiriman' => $row['tanggal_pengiriman'],
            'status_pengiriman' => $row['status_pengiriman'],
            'produk' => [],
            'retur' => []
        ];
    }

    // Tambahkan produk pengiriman ke grup
    $data_pengiriman[$group_key]['produk'][] = $row['produk_pengiriman'];

    // Tambahkan data retur jika ada
    if ($row['id_retur']) {
        $data_pengiriman[$group_key]['retur'][] = [
            'id_retur' => $row['id_retur'],
            'jumlah_retur' => $row['jumlah_retur'],
            'total_retur_nominal' => $row['total_retur_nominal'],
            'tanggal_retur' => $row['tanggal_retur']
        ];
    }
}

// Ubah array asosiatif ke array numerik untuk output JSON
$data_pengiriman = array_values($data_pengiriman);

// Mengembalikan hasil dalam format JSON
if (!empty($data_pengiriman)) {
    echo json_encode(['status' => 'success', 'data' => $data_pengiriman]);
} else {
    echo json_encode(['status' => 'success', 'data' => [], 'message' => 'No data found.']);
}

// Menutup statement dan koneksi
$stmt->close();
$conn->close();
