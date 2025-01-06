<?php
header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

$conn = db_connect(); // Connect to the database

// Ambil id_pengiriman dari parameter GET
$id_pengiriman = isset($_GET['id_pengiriman']) ? $_GET['id_pengiriman'] : null;

if (!$id_pengiriman) {
    echo json_encode(['error' => 'id_pengiriman is required']);
    exit;
}

try {
    // Query untuk mengambil produk dan jumlah berdasarkan id_pengiriman
    $stmt = $conn->prepare("
        SELECT 
            p.id,
            p.nama_produk,
            p.kemasan,
            p.ukuran_stoples,
            p.ukuran_mika,
            p.ukuran_paket,
            p.harga,
            ip.produk_id,
            ip.toko_id
        FROM produk p
        JOIN pengiriman ip ON ip.produk_id = p.id
        WHERE ip.id_pengiriman = ?
    ");

    // Bind parameter id_pengiriman ke query
    $stmt->bind_param("i", $id_pengiriman);
    $stmt->execute();
    $result = $stmt->get_result(); // Get the result set

    $products = [];
    while ($row = $result->fetch_assoc()) {
        // Tentukan kemasan produk yang tersedia
        $packaging = '';
        if (!empty($row['ukuran_stoples'])) {
            $packaging = $row['ukuran_stoples'];
        } elseif (!empty($row['ukuran_mika'])) {
            $packaging = $row['ukuran_mika'];
        } elseif (!empty($row['ukuran_paket'])) {
            $packaging = $row['ukuran_paket'];
        }

        // Hanya tambahkan produk dengan kemasan yang tersedia
        if ($packaging) {
            $products[] = [
                'id' => $row['id'],
                'display_name' => $row['nama_produk'] . ' ' . $row['kemasan'] . ' ' . $packaging,
                'harga' => $row['harga'],
                'produk_id' => $row['produk_id'],
                'toko_id' => $row['toko_id']
            ];
        }
    }

    echo json_encode($products);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch products']);
}
?>