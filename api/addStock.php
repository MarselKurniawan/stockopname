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
if ($input === null || !isset($input['entries']) || empty($input['entries'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid or missing entries']);
    exit;
}

// Ambil data entries dari input
$entries = $input['entries'];

// Loop untuk proses setiap entry dalam array entries
foreach ($entries as $entry) {
    $id_pengiriman = $entry['id_pengiriman'] ?? null;
    $laku_nominal = $entry['laku_nominal'] ?? null;
    $tanggal_masuk = $entry['tanggal_masuk'] ?? null;
    $toko_id = $entry['toko_id'] ?? null;
    $produk_id = $entry['produk_id'] ?? null;
    $harga = $entry['harga'] ?? null;

    // Validasi data
    if (empty($id_pengiriman) || empty($laku_nominal) || empty($tanggal_masuk)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    // Periksa apakah tanggal_masuk ada dan memiliki format yang valid
    if ($tanggal_masuk && !DateTime::createFromFormat('Y-m-d', $tanggal_masuk)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid date format for tanggal_masuk. Expected format: YYYY-MM-DD']);
        exit;
    }

    // Jika tanggal valid, ubah menjadi format yang benar
    $tanggal_masuk = date('Y-m-d', strtotime($tanggal_masuk));

    // Ambil data dari tabel pengiriman berdasarkan id_pengiriman
    $query = "SELECT toko_id, produk_id, harga, jumlah, id FROM pengiriman WHERE id_pengiriman = ?";
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
    $stok = $dataPengiriman['jumlah']; // Jumlah stok sebelum dikurangi
    $id_pengiriman_checker = $dataPengiriman['id']; // Ambil id_pengiriman_checker

    // Siapkan query untuk menambahkan data ke tabel stock
    $queryInsert = "INSERT INTO stock (toko_id, produk_id, harga, stok, laku_nominal, tanggal, id_pengiriman_checker, id_pengiriman) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($queryInsert);

    // Verifikasi keberhasilan persiapan query
    if (!$stmtInsert) {
        echo json_encode(['status' => 'error', 'message' => 'Query preparation failed: ' . $conn->error]);
        exit;
    }

    // Bind parameter dan eksekusi query
    $stmtInsert->bind_param('iisdiisi', $toko_id, $produk_id, $harga, $stok, $laku_nominal, $tanggal_masuk, $id_pengiriman_checker, $id_pengiriman);

    if (!$stmtInsert->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add stock: ' . $stmtInsert->error]);
        exit;
    }
}

// Menutup statement dan koneksi
$stmt->close();
$stmtInsert->close();
$conn->close();

echo json_encode(['status' => 'success', 'message' => 'Stock added successfully']);
?>