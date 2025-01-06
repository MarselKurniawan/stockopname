<?php
require_once '../core/v2/database.php'; // Pastikan file koneksi sudah benar

// Array data toko
$data_toko = [
    'PAMELA 1',
    'PAMELA 2',
    'PAMELA 3',
    'PAMELA 4',
    'PAMELA 6',
    'PAMELA 7',
    'PAMELA 8',
    'PAMELA 9',
    'GARDENA JOGJA',
    'GARDENA MAGELANG',
    'LEZAT',
    'PURNAMA',
    'PENIAYU',
    'MIROTA GEJAYAN',
    'MIROTA KALIURANG',
    'MIROTA BABARSARI',
    'RAMAI JOGJA',
    'TRIO MAGELANG',
    'LARIS MUNTILAN',
    'TOSERBA',
    'IUNDOWARUNG',
    'WASERDA',
    'INDOTOKO',
    'INDOASRI',
    'SIDOADI',
    'INDOKULAK'
];

// Koneksi ke database
$conn = db_connect();
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Persiapan query insert
$stmt = $conn->prepare("INSERT INTO  (nama_toko, kota_id) VALUES (?, ?)");
if (!$stmt) {
    die('Query preparation failed: ' . $conn->error);
}

// Kota ID yang ingin ditambahkan
$kota_id = 5;

// Insert data ke database
foreach ($data_toko as $toko) {
    $stmt->bind_param('si', $toko, $kota_id); // Bind parameter (string, integer)
    if (!$stmt->execute()) {
        echo "Failed to insert: $toko. Error: " . $stmt->error . "\n";
    } else {
        echo "Successfully inserted: $toko\n";
    }
}

// Tutup statement dan koneksi
$stmt->close();
$conn->close();
?>