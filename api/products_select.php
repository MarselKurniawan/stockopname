<?php
header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

$conn = db_connect(); // Connect to the database


try {
    $stmt = $conn->prepare("SELECT id, nama_produk, kemasan, ukuran_stoples, ukuran_mika, ukuran_paket, harga FROM produk");
    $stmt->execute();
    $result = $stmt->get_result(); // Get the result set

    $products = [];
    while ($row = $result->fetch_assoc()) {
        // Determine available packaging
        $packaging = '';
        if (!empty($row['ukuran_stoples'])) {
            $packaging = $row['ukuran_stoples'];
        } elseif (!empty($row['ukuran_mika'])) {
            $packaging = $row['ukuran_mika'];
        } elseif (!empty($row['ukuran_paket'])) {
            $packaging = $row['ukuran_paket'];
        }

        // Only add products with an available packaging
        if ($packaging) {
            $products[] = [
                'id' => $row['id'],
                'display_name' => $row['nama_produk'] .' '.  $row['kemasan'] . ' '. $packaging,
                'harga' => $row['harga']
            ];
        }
    }

    echo json_encode($products);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch products']);
}
?>
