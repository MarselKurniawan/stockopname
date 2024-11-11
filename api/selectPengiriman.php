<?php
header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

$conn = db_connect();

try {
    // Query to get combined data from pengiriman, toko, and produk
    $stmt = $conn->prepare("
        SELECT 
            pengiriman.id, 
            pengiriman.id_pengiriman, 
            pengiriman.toko_id,
            toko.nama_toko,
            produk.nama_produk,
            pengiriman.harga,
            produk.kemasan,
            produk.ukuran_stoples,
            produk.ukuran_mika,
            produk.ukuran_paket,
            pengiriman.tanggal,
            pengiriman.jumlah
        FROM 
            pengiriman 
        JOIN 
            toko ON pengiriman.toko_id = toko.id 
        JOIN 
            produk ON pengiriman.produk_id = produk.id
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    $pengirimanData = [];

    while ($row = $result->fetch_assoc()) {
        // Choose the available packaging size
        $packaging = $row['ukuran_stoples'] ?: $row['ukuran_mika'] ?: $row['ukuran_paket'];

        // Only add the product if there's a packaging size
        if ($packaging) {
            $pengirimanData[] = [
                'id_pengiriman' => $row['id_pengiriman'],
                'nama_toko' => $row['nama_toko'],
                'nama_produk' => "{$row['nama_produk']} ({$row['kemasan']} - {$packaging})",
                'harga' => $row['harga'],
                'jumlah' => $row['jumlah'],
                'tanggal' => $row['tanggal']
            ];
        }
    }

    // Output the result as JSON
    echo json_encode($pengirimanData);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch data']);
}
