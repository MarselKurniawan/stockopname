<?php

// Mengarahkan request ke file yang sesuai
require_once 'core/v2/config.php';
require_once 'core/v2/database.php';

// Mendapatkan URI yang benar tanpa BASE_URL
$request = str_replace('/stockopname', '', rtrim($_SERVER['REQUEST_URI'], '/'));

// Mengecek apakah permintaan berasal dari folder 'api'
if (strpos($request, '/api') === 0) {
    // Jika berasal dari folder 'api', biarkan berjalan tanpa routing (langsung ke file API)
    return; // Jangan lakukan routing, biarkan default
}

// Routing untuk operasi delete produk
if (preg_match('/^\/delete_product\.php\?id=(\d+)$/', $request, $matches)) {
    $productId = $matches[1]; // Ambil ID produk dari URL
    require_once 'api/delete_product.php'; // Panggil file delete_product.php
}

// Routing untuk halaman-halaman lainnya
elseif ($request == '' || $request == '/login') {
    require_once 'views/login.php';
} elseif ($request == '/dashboard') {
    require_once 'views/dashboard.php';
} elseif ($request == '/penjualan') {
    require_once 'views/penjualan.php';
} elseif ($request == '/pembayaran') {
    require_once 'views/pembayaran.php';
} elseif ($request == '/retur') {
    require_once 'views/retur.php';
} elseif ($request == '/produk') {
    require_once 'views/produk.php';
} elseif ($request == '/pabrik') {
    require_once 'views/pabrik.php';
} elseif (preg_match('/^\/pabrik(\?edit=\d+|\?delete=\d+)$/', $request, $matches)) {
    // Handle the edit or delete logic
    require_once 'views/pabrik.php';  // This will include the pabrik.php page, with any query params
} elseif ($request == '/bahan-baku') {
    require_once 'views/bahanbaku.php';
} elseif ($request == '/track-bahan') {
    require_once 'views/trackbahan.php';
} elseif ($request == '/pembukuan') {
    require_once 'views/pembukuan.php';
} elseif ($request == '/toko') {
    require_once 'views/toko.php';
} elseif ($request == '/catatan') {
    require_once 'views/notes.php';
} else {
    http_response_code(404);
    echo "404 - Page Not Found";
}
