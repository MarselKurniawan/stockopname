<?php
require_once '../core/database.php';

header('Content-Type: application/json');

// Koneksi ke database
$mysqli = db_connect();

// Query untuk mengambil stock opname
$query = "SELECT id, nama_produk, jumlah, tanggal FROM stock_opname";
$result = $mysqli->query($query);

$stock = [];

while ($row = $result->fetch_assoc()) {
    $stock[] = $row;
}

$mysqli->close();

// Mengirim response dalam format JSON
echo json_encode($stock);
