<?php
require_once '../core/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $jumlah = $_POST['jumlah'];
    $tanggal = $_POST['tanggal'];

    $mysqli = db_connect();

    // Update query
    $stmt = $mysqli->prepare("UPDATE stock_opname SET jumlah = ?, tanggal = ? WHERE id = ?");
    $stmt->bind_param("isi", $jumlah, $tanggal, $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }

    $stmt->close();
    $mysqli->close();
}
