<?php
header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

$conn = db_connect();

try {
    // Query untuk mendapatkan semua data pengiriman kecuali yang memiliki id_pengiriman_checker yang sama dengan id_pengiriman
    $stmt = $conn->prepare("
        SELECT 
            pengiriman.id, 
            pengiriman.id_pengiriman, 
            pengiriman.toko_id,
            pengiriman.produk_id,
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
        LEFT JOIN 
            stock ON pengiriman.id = stock.id_pengiriman_checker
        WHERE 
            (stock.id_pengiriman_checker IS NULL OR stock.id_pengiriman_checker != pengiriman.id)
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    $pengirimanData = [];

    while ($row = $result->fetch_assoc()) {
        // Pilih ukuran kemasan yang tersedia
        $packaging = $row['ukuran_stoples'] ?: $row['ukuran_mika'] ?: $row['ukuran_paket'];

        // Hanya tambahkan produk jika ada ukuran kemasan
        if ($packaging) {
            $pengirimanData[] = [
                'id' => $row['id'],

                'id_pengiriman' => $row['id_pengiriman'],
                'nama_toko' => $row['nama_toko'],
                'nama_produk' => "{$row['nama_produk']} ({$row['kemasan']} - {$packaging})",
                'harga' => $row['harga'],
                'jumlah' => $row['jumlah'],
                'tanggal' => $row['tanggal'],
                'produk_id' => $row['produk_id'],
                'toko_id' => $row['toko_id'],
            ];
        }
    }

    // Output hasil sebagai JSON
    echo json_encode($pengirimanData);
} catch (Exception $e) {
    echo json_encode(['error' => 'Gagal mengambil data']);
}
?>