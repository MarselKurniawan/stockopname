<?php
header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']);
        exit;
    }

    $toko_id = $_POST['toko_id'];
    $produk_id = $_POST['produk_id'];
    $jumlah_stok = $_POST['jumlah_stok'];

    // Koneksi database
    $conn = connect_db();
    $stmt = $conn->prepare("INSERT INTO stok (toko_id, produk_id, jumlah_stok) VALUES (?, ?, ?)");
    $stmt->bind_param('iii', $toko_id, $produk_id, $jumlah_stok);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Stok berhasil ditambahkan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan stok']);
    }

    $stmt->close();
    $conn->close();
}
?>
