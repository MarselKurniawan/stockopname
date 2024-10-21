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
    require_once 'views/login.php'; // Perbaikan concatenation
} elseif ($request == '/dashboard') {
    require_once 'views/dashboard.php'; // Perbaikan concatenation
} elseif ($request == '/stock') {
    require_once 'views/stok.php'; // Perbaikan concatenation
} else {
    http_response_code(404);
    echo "404 - Page Not Found";
}
