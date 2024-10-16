<?php
// Mengarahkan request ke file yang sesuai
require_once 'core/v2/config.php';
require_once 'core/func/functions.php';

// Menghilangkan trailing slash dari URL jika ada
$request = rtrim($_SERVER['REQUEST_URI'], '/');

// Routing ke halaman login atau dashboard
if ($request == '' || $request == '/login') {
    require_once BASE_URL . 'stockopname/views/login.php'; // Perbaikan concatenation
} elseif ($request == '/dashboard') {
    require_once 'views/dashboard.php';
} else {
    http_response_code(404);
    echo "404 - Page Not Found";
}
?>
