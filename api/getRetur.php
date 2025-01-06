<?php

session_start(); // Pastikan sesi dimulai

header('Content-Type: application/json');
require_once '../core/func/csrf_protection.php';
require_once '../core/v2/database.php';
require_once '../core/v2/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id_pengiriman = $_GET['id_pengiriman']; // ID pengiriman yang diterima dari frontend

    // Cek apakah id_pengiriman valid
    if (empty($id_pengiriman)) {
        echo json_encode(['status' => 'error', 'message' => 'id_pengiriman is required']);
        exit;
    }

    // Query untuk mengambil data retur berdasarkan id_pengiriman dan menggabungkan dengan tabel produk dan toko
    $conn = db_connect();
    $query = "
        SELECT r.*, p.nama_produk, p.kemasan, p.ukuran_stoples, p.ukuran_mika, p.ukuran_paket, t.nama_toko
        FROM retur r
        JOIN produk p ON r.produk_id = p.id
        JOIN toko t ON r.toko_id = t.id
        WHERE r.id_pengiriman = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_pengiriman);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $returData = [];
        while ($row = $result->fetch_assoc()) {
            // Pilih ukuran kemasan yang tersedia
            $packaging = $row['ukuran_stoples'] ?: $row['ukuran_mika'] ?: $row['ukuran_paket'];

            // Gabungkan nama produk dengan ukuran kemasan dan kemasan
            $namaProduk = "{$row['nama_produk']} {$row['kemasan']} {$packaging}";

            // Tambahkan data retur ke array
            $returData[] = [
                'id_pengiriman' => $row['id_pengiriman'],
                'produk_id' => $row['produk_id'],
                'toko_id' => $row['toko_id'],
                'nama_produk' => $namaProduk,
                'jumlah_retur' => $row['jumlah_retur'],
                'total_retur_nominal' => $row['total_retur_nominal'],
                'nama_toko' => $row['nama_toko']
            ];
        }

        echo json_encode(['status' => 'success', 'data' => $returData]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to fetch retur data']);
    }

    $stmt->close();
    $conn->close();
}
