<?php
session_start();
require_once '../core/functions.php';

// Cek apakah user sudah login
is_logged_in();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto mt-5">
        <h1 class="text-3xl font-bold">Dashboard</h1>
        <p>Selamat datang di dashboard. Pilih menu di atas untuk melanjutkan.</p>
    </div>
</body>
</html>
