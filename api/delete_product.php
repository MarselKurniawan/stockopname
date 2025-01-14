<?php

require_once __DIR__ . '/../core/v2/config.php';
require_once __DIR__ . '/../core/v2/database.php';

$conn = db_connect();
// Ambil ID produk dari URL
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Lakukan query untuk menghapus produk berdasarkan ID
    $query = "DELETE FROM produk WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $productId);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Redirect ke halaman produk setelah berhasil menghapus
        header("Location: /stockopname/produk");
        exit;
    } else {
        echo "Terjadi kesalahan saat menghapus produk.";
    }
} else {
    echo "ID produk tidak ditemukan.";
}
