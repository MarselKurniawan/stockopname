<?php
header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

$conn = db_connect();

try {
    $stmt = $conn->prepare("
        SELECT pengiriman.id, pengiriman.toko_id, toko.nama_toko, produk.nama_produk, pengiriman.jumlah, pengiriman.harga, pengiriman.tanggal
        FROM pengiriman 
        JOIN toko ON pengiriman.toko_id = toko.id 
        JOIN produk ON pengiriman.produk_id = produk.id
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    $pengirimanData = [];
    while ($row = $result->fetch_assoc()) {
        $pengirimanData[] = $row;
    }

    echo json_encode($pengirimanData);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch data']);
}
?>
