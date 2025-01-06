<?php
header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

// Inisialisasi koneksi database
$conn = db_connect(); // Pastikan fungsi db_connect() ada di database.php

// Ambil data dari body request
$data = json_decode(file_get_contents('php://input'), true);

$id_pengiriman = $data['id_pengiriman'] ?? null;
$status = $data['status'] ?? null;

if ($id_pengiriman && $status) {
    $query = "UPDATE pengiriman SET status = ? WHERE id_pengiriman = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $status, $id_pengiriman);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status']);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
}

$conn->close();
?>
