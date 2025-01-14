<?php
// updateToko.php
require_once __DIR__ . '/../core/v2/database.php';
require_once __DIR__ . '/../core/func/csrf_protection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validate_csrf_token($_POST['csrf_token'])) {
    $id = $_POST['id'];
    $nama_toko = $_POST['nama_toko'];
    $kota_id = $_POST['kota'];

    $query = "UPDATE toko SET nama_toko = ?, kota_id = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $nama_toko, $kota_id, $id);
    $stmt->execute();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
