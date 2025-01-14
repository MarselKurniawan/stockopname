<?php
session_start();

header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

// Periksa metode request
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Ambil data dari request body
parse_str(file_get_contents("php://input"), $data);

// Periksa keberadaan ID pengiriman
if (!isset($data['id_pengiriman'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID pengiriman is required']);
    exit;
}

$id_pengiriman = $data['id_pengiriman'];

// Koneksi database
$conn = db_connect();
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

try {
    // Cek status di tabel pengiriman
    $stmtCheck = $conn->prepare("SELECT status FROM pengiriman WHERE id_pengiriman = ?");
    if (!$stmtCheck) {
    }
    $stmtCheck->bind_param('i', $id_pengiriman);
    if (!$stmtCheck->execute()) {
    }

    $resultCheck = $stmtCheck->get_result();
    if ($resultCheck->num_rows === 0) {
    }

    $row = $resultCheck->fetch_assoc();
    $status = $row['status'];
    $stmtCheck->close();

    // Jika status bukan "done", batalkan penghapusan
    if ($status !== 'done') {
        echo json_encode(['status' => 'error', 'message' => 'Data Tidak Bisa Dihapus karena status "Belum Terbayar.']);
        exit;
    }

    // Mulai transaksi
    $conn->begin_transaction();

    // Hapus data dari tabel retur
    $stmtRetur = $conn->prepare("DELETE FROM retur WHERE id_pengiriman = ?");
    if (!$stmtRetur) {
    }
    $stmtRetur->bind_param('i', $id_pengiriman);
    if (!$stmtRetur->execute()) {
    }
    $stmtRetur->close();

    // Hapus data dari tabel pengiriman
    $stmtPengiriman = $conn->prepare("DELETE FROM pengiriman WHERE id_pengiriman = ?");
    if (!$stmtPengiriman) {
    }
    $stmtPengiriman->bind_param('i', $id_pengiriman);
    if (!$stmtPengiriman->execute()) {
    }
    $stmtPengiriman->close();

    // Commit transaksi
    $conn->commit();

    echo json_encode(['status' => 'success', 'message' => 'Pengiriman and related retur deleted successfully']);
} catch (Exception $e) {
    // Rollback transaksi jika ada kesalahan
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

// Tutup koneksi
$conn->close();
?>