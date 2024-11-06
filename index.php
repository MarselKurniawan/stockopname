<?php
// Mengarahkan request ke file yang sesuai
require_once 'core/v2/config.php';
require_once 'core/v2/database.php';
// require_once 'core/func/functions.php';

// Mendapatkan URI yang benar tanpa BASE_URL
$request = str_replace('/stockopname', '', rtrim($_SERVER['REQUEST_URI'], '/'));

// Mengecek apakah permintaan berasal dari folder 'api'
if (strpos($request, '/api') === 0) {
    // Jika berasal dari folder 'api', biarkan berjalan tanpa routing (langsung ke file API)
    return; // Jangan lakukan routing, biarkan default
}

// Routing ke halaman login atau dashboard
if ($request == '' || $request == '/login') {
    require_once 'views/login.php'; 
} elseif ($request == '/dashboard') {
    require_once 'views/dashboard.php'; 
} elseif ($request == '/pengiriman') {
    require_once 'views/pengiriman.php'; 
} elseif ($request == '/retur') {
    require_once 'views/retur.php'; 
} elseif ($request == '/produk') {
    require_once 'views/produk.php'; 
} elseif ($request == '/pabrik') {
    require_once 'views/pabrik.php';
} elseif ($request == '/bahan-baku') {
    require_once 'views/bahanbaku.php';
} elseif ($request == '/track-bahan') {
    require_once 'views/trackbahan.php';
} else {
    http_response_code(404);
    echo "404 - Page Not Found";
}
