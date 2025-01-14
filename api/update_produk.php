<?php
session_start(); // Pastikan sesi dimulai

header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

// Get data from POST request
$id_produk = $_POST['id_produk'];
$nama_produk = $_POST['nama_produk'];
$kemasan = $_POST['kemasan'];
$ukuran_stoples = $_POST['ukuran_stoples'];
$ukuran_mika = $_POST['ukuran_mika'];
$ukuran_paket = $_POST['ukuran_paket'];
$harga = $_POST['harga'];

// Update the product data in the database
$sql = "UPDATE produk 
        SET nama_produk = '$nama_produk', kemasan = '$kemasan', ukuran_stoples = '$ukuran_stoples', 
            ukuran_mika = '$ukuran_mika', ukuran_paket = '$ukuran_paket', harga = '$harga' 
        WHERE id_produk = $id_produk";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['status' => 'success', 'message' => 'Product updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update product']);
}

$conn->close();
?>